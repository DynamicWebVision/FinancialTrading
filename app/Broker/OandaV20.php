<?php namespace App\Broker;
use \Log;

class OandaV20 extends \App\Broker\Base  {
    public $frequency;
    public $exchange;

    public $takeProfit;
    public $takeProfitPipAmount;

    public $stopLoss;
    public $stopLossPipAmount;

    public $trailingStop;

    public $startDate;

    public $runId;
    public $accountId;

    public $positionAmount;

    public $rateCount = 5000;

    public $addCurrentPriceToRates;

    public $strategyLogger;

    public function __construct() {
        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, ['Authorization: Bearer '.env('OANDA_AUTHORIZATION_TOKEN'),'Content-Type: application/json', 'Accept-Datetime-Format: UNIX']);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
    }

    public function getRates() {
        $this->getVariables['granularity'] = $this->frequency;
        $this->getVariables['count'] = $this->rateCount;
        $this->getVariables['dailyAlignment'] = 17;

        if (isset($this->startDate)) {
            $this->getVariables['from'] = $this->startDate;
        }

        $this->apiUrl = env('OANDA_API_URL')."instruments/".$this->exchange."/candles";

        $response = $this->apiGetRequest();

       if (isset($response->candles)) {
            return $response->candles;
        }
        else {
            return false;
        }
    }

    public function simpleRates($removeUnfinished = true) {
        $rates = $this->getRates();
        if ($rates) {
            if ($removeUnfinished) {
                $lastRate = end($rates);
                if (!$lastRate->complete) {
                    array_pop($rates);
                }
            }
            $rates = array_map(function($rate) {
                return (float) $rate->mid->c;
            }, $rates);

            if ($this->addCurrentPriceToRates) {
                $currentPriceData = $this->currentPrice();
                $rates[] = $currentPriceData->mid;
            }
            return $rates;
        }
        else {
            return false;
        }
    }

    public function fullRates($removeUnfinished = true) {
        $rates = $this->getRates();
        if ($rates) {
            if ($removeUnfinished) {
                $lastRate = end($rates);
                if (!$lastRate->complete) {
                    array_pop($rates);
                }
            }

            $rates = array_map(function($rate) {
                $stdRate = $rate;

                $stdRate->highMid = (float) $rate->mid->h;
                $stdRate->closeMid = (float) $rate->mid->c;
                $stdRate->lowMid = (float) $rate->mid->l;
                $stdRate->openMid = (float) $rate->mid->o;
                $stdRate->dateTime = date("Y-m-d H:i:s",(int) $rate->time);
                $stdRate->volume = (float) $rate->volume;

                return $stdRate;

            }, $rates);

            return $rates;
        }
        else {
            return false;
        }
    }

    public function fullAndSimpleRates($removeUnfinished = false) {
        $rates = $this->getRates();
        if ($rates) {
            if ($removeUnfinished) {
                $lastRate = end($rates);
                if (!$lastRate->complete) {
                    array_pop($rates);
                }
            }

            $bothRates = [];
            $bothRates['full'] = array_map(function($rate) {
                $stdRate = new \StdClass();

                $stdRate->highMid = (float) $rate->mid->h;
                $stdRate->closeMid = (float) $rate->mid->c;
                $stdRate->lowMid = (float) $rate->mid->l;
                $stdRate->openMid = (float) $rate->mid->o;
                $stdRate->dateTime = date("Y-m-d H:i:s",(int) $rate->time);
                $stdRate->volume = (float) $rate->volume;
                return $stdRate;
            }, $rates);

            $bothRates['simple'] = array_map(function($rate) {
                return $rate->mid->c;
            }, $rates);

            //Add Current Price if Set
            if ($this->addCurrentPriceToRates) {
                $currentPriceData = $this->currentPrice();
                $bothRates['simple'][] = $currentPriceData->mid;
            }


            return $bothRates;
        }
        else {
            return false;
        }
    }

    public function newOrder($params) {
        //Make Units Negative for Short
        if ($params['side'] == 'sell') {
            $this->positionAmount = $this->positionAmount*-1;
        }

        $postStructure = new \StdClass();

        $postStructure->order = new \StdClass();

        $postStructure->order->instrument = $this->exchange;
        $postStructure->order->units = $this->positionAmount;

        if ($params['type'] == 'MARKET') {
            $postStructure->order->type = 'MARKET';
            $postStructure->order->timeInForce = 'FOK';
            $postStructure->order->positionFill = 'DEFAULT';
        }
        elseif ($params['type'] == 'LIMIT') {
            $postStructure->order->type = 'LIMIT';
            $postStructure->order->timeInForce = 'GTD';
            $postStructure->order->positionFill = 'DEFAULT';
            $postStructure->order->price = (string) $params['limitPrice'];
            $postStructure->order->gtdTime = $this->convertOandaDate($params['gtdTime']);
        }
        elseif ($params['type'] == 'MARKET_IF_TOUCHED') {
            $postStructure->order->type = 'MARKET_IF_TOUCHED';
            $postStructure->order->timeInForce = 'GTD';
            $postStructure->order->positionFill = 'DEFAULT';
            $postStructure->order->price = (string) $params['marketIfTouchedOrderPrice'];
            $postStructure->order->priceBound = (string) $params['marketIfTouchedOrderPrice'];
            $postStructure->order->gtdTime = $this->convertOandaDate($params['gtdTime']);
        }

        if (isset($this->takeProfit)) {
            $postStructure->order->takeProfitOnFill = new \StdClass();
            $postStructure->order->takeProfitOnFill->price = (string) $this->takeProfit;
        }

        if (isset($this->stopLoss)) {
            $postStructure->order->stopLossOnFill = new \StdClass();

            $postStructure->order->stopLossOnFill->price = (string) $this->stopLoss;
        }

        if (isset($this->trailingStop)) {
            $postStructure->order->trailingStopLossOnFill = $this->trailingStop;
        }

        $this->apiUrl = env('OANDA_API_URL').'accounts/'.$this->accountId.'/orders';

        $this->strategyLogger->logApiRequestStart($this->apiUrl, $postStructure, 'new_order');

        $response = $this->apiPostRequest($postStructure);

        $this->strategyLogger->logApiRequestResponse($response);

        return $response;
    }

    public function currentPrice() {
        $this->getVariables['instruments'] = $this->exchange;

        $this->apiUrl = env('OANDA_API_URL')."accounts/".$this->accountId."/pricing";

        $response = $this->apiGetRequest();

        $bidAskObject = new \StdClass();

        $bidAskObject->bid =  (float) $response->prices[0]->bids[0]->price;
        $bidAskObject->ask =  (float) $response->prices[0]->asks[0]->price;

        $bidAskObject->mid =  ($bidAskObject->bid + $bidAskObject->ask)/2;

        return $bidAskObject;
    }

    public function checkOpenPosition() {
        $this->strategyLogger->logApiRequestStart($this->apiUrl, '', 'check_position');

        $this->apiUrl = env('OANDA_API_URL')."accounts/".$this->accountId."/positions/".$this->exchange;
        $response = $this->apiGetRequest();

        $this->strategyLogger->logApiRequestResponse($response);

        if (!isset($response->position)) {
            return false;
        }

        $openPosition = [];
        $openPosition['side'] = false;

        if ($response->position->long->units > 0) {
            $openPosition['side'] = 'long';
            $openPosition['gl'] = $response->position->long->unrealizedPL;

            if (isset($response->position->long->tradeIDs)) {
                foreach ($response->position->long->tradeIDs as $tradeId) {
                    $openPosition['tradeId'] = $tradeId;

                    $this->apiUrl = env('OANDA_API_URL')."accounts/".$this->accountId."/trades/".$tradeId;
                    $tradeResponse = $this->apiGetRequest();

                    //Handle Stop Loss
                    if (isset($tradeResponse->trade->stopLossOrder)) {
                        $openPosition['stopLossPrice'] = $tradeResponse->trade->stopLossOrder->price;
                        $openPosition['stopLossId'] = $tradeId;
                    }
                }
            }
        }
        elseif ($response->position->short->units < 0) {
            $openPosition['side'] = 'short';
            $openPosition['gl'] = $response->position->short->unrealizedPL;

            if (isset($response->position->short->tradeIDs)) {
                foreach ($response->position->short->tradeIDs as $tradeId) {
                    $openPosition['tradeId'] = $tradeId;

                    $this->apiUrl = env('OANDA_API_URL')."accounts/".$this->accountId."/trades/".$tradeId;
                    $tradeResponse = $this->apiGetRequest();

                    //Handle Stop Loss
                    if (isset($tradeResponse->trade->stopLossOrder)) {
                        $openPosition['stopLossPrice'] = $tradeResponse->trade->stopLossOrder->price;
                        $openPosition['stopLossId'] = $tradeId;
                    }
                }
            }
        }
        else {
            return false;
        }
        return $openPosition;
    }


//    public function addTrailingStop() {
//        $this->apiUrl = env('OANDA_API_URL')."accounts/".$this->accountId."/openPositions";
//        $response = $this->apiGetRequest();
//        return sizeof($response->positions);
//    }


    public function checkTotalOpenPositions() {
        $this->apiUrl = env('OANDA_API_URL')."accounts/".$this->accountId."/openPositions";
        $response = $this->apiGetRequest();
        return sizeof($response->positions);
    }

    public function transactions() {
        if (!isset($this->getVariables['pageSize'])) {
            $this->getVariables['pageSize'] = 1000;
        }

        $this->apiUrl = env('OANDA_API_URL')."accounts/".$this->accountId."/transactions";

        $response =  $this->apiGetRequest();

        foreach ($response->pages as $pageRequest) {
            $this->apiUrl = $pageRequest;
            $pageResult = $this->apiGetRequest(false);
        }
    }

    public function accountInfo() {
        $this->apiUrl = env('OANDA_API_URL')."accounts/".$this->accountId;
        $response = $this->apiGetRequest();
        return $response->account;
    }

    public function modifyStopLoss($tradeId) {
        $stopLossObject = new \StdClass();
        $stopLossObject->price = (string) $this->stopLoss;

        $fieldObject = new \StdClass();
        $fieldObject->stopLoss = $stopLossObject;

        $this->modifyTrade($tradeId, $fieldObject);
    }

    public function addStopLoss($tradeId) {
        $stopLossObject = new \StdClass();
        $stopLossObject->price = (string) $this->stopLoss;

        $fieldObject = new \StdClass();
        $fieldObject->stopLoss = $stopLossObject;

        $this->modifyTrade($tradeId, $fieldObject);
    }

    public function modifyTrade($tradeId, $fields) {
        $this->apiUrl = env('OANDA_API_URL')."accounts/".$this->accountId."/trades/".$tradeId."/orders";

        $this->strategyLogger->logApiRequestStart($this->apiUrl, $fields, 'modify_trade');

        $response = $this->apiPatchRequest($fields);

        $this->strategyLogger->logApiRequestResponse($response);
    }

    public function closePosition() {

        $openPosition = $this->checkOpenPosition();

        if ($openPosition) {
            $data = new \StdClass();

            if ($openPosition['side'] == 'long') {
                $data->longUnits = 'ALL';
            }
            else {
                $data->shortUnits = 'ALL';
            }
            $this->apiUrl = env('OANDA_API_URL')."accounts/".$this->accountId."/positions/".$this->exchange."/close";

            $this->strategyLogger->logApiRequestStart($this->apiUrl, $data, 'close_position');
            $response = $this->apiPatchRequest($data);
            $this->strategyLogger->logApiRequestResponse($response);
        }
    }

    public function getTransactionsFrom($date) {
        $this->getVariables['from'] = 1523010000;
        $this->apiUrl = env('OANDA_API_URL')."accounts/".$this->accountId."/transactions";

        $response = $this->apiGetRequest();

//        foreach ($response->pages as $page) {
//            $this->apiUrl = $page;
//            $pageResponse = $this->apiGetRequest(false);
//            foreach ($pageResponse->transactions as $transaction) {
//                if ($transaction->type == 'MARKET_ORDER') {
//
//                }
//            }
//        }
        echo 'asdf';
    }

    public function getTransactionsSince($minId) {
        $this->getVariables['id'] = $minId;

        $this->apiUrl = env('OANDA_API_URL')."accounts/".$this->accountId."/transactions/sinceid";
        $response = $this->apiGetRequest();

        if (isset($response->transactions)) {
            return $response->transactions;
        }
        else {
            Log::critical('Empty Transactions Response'.$response);
            return [];
        }
    }

    public function getSpecificTransaction($id) {
        $this->apiUrl = env('OANDA_API_URL')."accounts/".$this->accountId."/transactions/".$id;
        $response = $this->apiGetRequest();
    }

    public function getTransactionHistory() {
        $this->apiUrl = env('OANDA_API_URL')."accounts/".$this->accountId."/transactions";
        $response = $this->apiGetRequest();

        $pages = $response->pages;

        $transactions = [];

        foreach ($pages as $page) {
            $this->apiUrl = $page;
            $response = $this->apiGetRequest(false);
            $transactions = array_merge($transactions, $response->transactions);
        }
        return $transactions;
    }

    public function price() {
        $this->apiUrl = env('OANDA_API_URL')."accounts/".$this->accountId."/pricing";

        $this->getVariables['instruments'] = $this->exchange;

        $response = $this->apiGetRequest();
        return $response;
    }

    public function allAccounts() {
        $this->apiUrl = env('OANDA_API_URL')."accounts";

        $accounts = [];

        $response = $this->apiGetRequest();

        foreach ($response->accounts as $account) {
            $this->apiUrl = env('OANDA_API_URL')."accounts/".$account->id."/summary";

            $accountResponse = $this->apiGetRequest();
            $accounts[] = $accountResponse->account;
        }
        return $accounts;
    }

    public function convertOandaDate($unixTime) {
        //2018-07-02T04:00:00.000000Z
        return date('Y-m-d', $unixTime).'T'.date('H:i:s', $unixTime).'.000000Z';
    }

    public function getOandaPrecisionPrice($price, $pip) {
        $exchangedStripExtraZeros = substr($pip, 0, strpos($pip, "1") + 1);
        $decimalPipPlaces = strlen(substr(strrchr($exchangedStripExtraZeros, "."), 1));
        return round($price, $decimalPipPlaces);
    }
}
