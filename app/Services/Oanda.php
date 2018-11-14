<?php namespace App\Services;

use \App\Model\Exchange;
use \Log;

class Oanda  {

    public $exchange;
    public $timePeriod;
    public $apiUrl;

    public $takeProfit;
    public $takeProfitPipAmount;

    public $stopLoss;
    public $stopLossPipAmount;

    public $trailingStop;

    public $accountId;

    public $historicalCount;

    public $runId;

    public $positionAmount;


    public function __construct() {
        $this->curl = curl_init();
//        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , 'Authorization: Bearer 1d95d8dd88b59a1f7c53e7cb2886df89-3f40f99e79545ae6539aabd8b718cbb0' ));
       // curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer 1d95d8dd88b59a1f7c53e7cb2886df89-3f40f99e79545ae6539aabd8b718cbb0' ));
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.env('OANDA_AUTHORIZATION_TOKEN') ));
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
    }

    public function apiGetRequest() {

        curl_setopt($this->curl, CURLOPT_URL, $this->apiUrl);
        $resp = curl_exec($this->curl);

        return $resp;
    }

    public function apiPostRequest($fields) {

        $fields_string = "";

        foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
        rtrim($fields_string, '&');

        $fields_string = substr($fields_string, 0, strlen($fields_string)-1);

        curl_setopt($this->curl, CURLOPT_URL, $this->apiUrl);
        curl_setopt($this->curl,CURLOPT_POST, count($fields));
        curl_setopt($this->curl,CURLOPT_POSTFIELDS, $fields_string);

        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, NULL);

        //Added to Get Curl Info
        //curl_setopt($this->curl, CURLINFO_HEADER_OUT, true);

        $resp = curl_exec($this->curl);

       // $headers = curl_getinfo($this->curl, CURLINFO_HEADER_OUT);

        return $resp;
    }

    public function apiDeleteRequest() {
        curl_setopt($this->curl, CURLOPT_URL, $this->apiUrl);
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, "DELETE");
        $resp = curl_exec($this->curl);

        return $resp;
    }

    public function apiPatchRequest($fields) {
        $fields_string = "";

        foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
        rtrim($fields_string, '&');

        $fields_string = substr($fields_string, 0, strlen($fields_string)-1);
        curl_setopt($this->curl, CURLOPT_URL, $this->apiUrl);
        curl_setopt($this->curl,CURLOPT_POST, count($fields));
        curl_setopt($this->curl,CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, "PATCH");
        $resp = curl_exec($this->curl);

        return $resp;
    }

    public function getHistoricalData() {

        $this->apiUrl = 'https://api-fxpractice.oanda.com/v1/candles?instrument='.$this->exchange.'&count='.$this->historicalCount.'&candleFormat=midpoint&granularity='.$this->timePeriod.'&dailyAlignment=17&alignmentTimezone=America%2FNew_York&accountId='.$this->accountId;


        return json_decode($this->apiGetRequest());
    }

    public function getHistoricalDataStart($start, $rateCount = 5000) {
        //2007-06-19T15%3A47%3A40Z
        $this->apiUrl = 'https://api-fxpractice.oanda.com/v1/candles?instrument='.$this->exchange.'&count='.$rateCount.'&start='.$start.'&candleFormat=midpoint&granularity='.$this->timePeriod.'&dailyAlignment=0&alignmentTimezone=America%2FNew_York&accountId='.$this->accountId;

        return json_decode($this->apiGetRequest());
    }

    public function getInstruments() {
        $this->apiUrl = 'https://api-fxpractice.oanda.com/v1/candles?instrument='.$this->exchange.'&count=5000&candleFormat=midpoint&granularity='.$this->timePeriod.'&dailyAlignment=0&alignmentTimezone=America%2FNew_York&accountId='.$this->accountId;

        return $this->apiGetRequest();
    }

    public function newOrder($orderDirection, $exchange) {
//        Input Data Parameters (inside body)
//
//instrument:* Required Instrument to open the order on.
//
//        units: Required The number of units to open order for.
//
//side: Required Direction of the order, either ‘buy’ or ‘sell’.
//
//type: Required The type of the order ‘limit’, ‘stop’, ‘marketIfTouched’ or ‘market’.
//
//expiry: Required If order type is ‘limit’, ‘stop’, or ‘marketIfTouched’. The order expiration time in UTC. The value specified must be in a valid datetime format.
//
//        price: Required If order type is ‘limit’, ‘stop’, or ‘marketIfTouched’. The price where the order is set to trigger at.
//
//        lowerBound: Optional The minimum execution price.
//
//        upperBound: Optional The maximum execution price.
//
//        stopLoss: Optional The stop loss price.
//
//        takeProfit: Optional The take profit price.
//
//        trailingStop: Optional The trailing stop distance in pips, up to one decimal place.

        $fields = [
            'instrument' => urlencode($exchange),
            'units' => urlencode($this->positionAmount),
            'side' => urlencode($orderDirection),
            'type' => urlencode('market')
        ];

        if (isset($this->takeProfit)) {
            $fields['takeProfit'] = $this->takeProfit;
        }

        if (isset($this->stopLoss)) {
            $fields['stopLoss'] = $this->stopLoss;
        }

        if (isset($this->trailingStop)) {
            $fields['trailingStop'] = $this->trailingStop;
        }

        $this->apiUrl = 'https://api-fxpractice.oanda.com/v1/accounts/'.$this->accountId.'/orders';

        Log::notice($this->runId.': Oanda Order Attempt Side '.$orderDirection.' url: \n '.json_encode($this->apiUrl));
        Log::notice($this->runId.': Oanda Order Attempt Side '.$orderDirection.' fields: \n '.json_encode($fields));
        $response = $this->apiPostRequest($fields);

        Log::notice($this->runId.': Oanda Order Attempt Side '.$orderDirection.' response: '.$response);
        return $response;
    }

    public function closePosition($exchange) {
        $this->apiUrl = self::ROOT_URL."/accounts/".$this->accountId."/positions/".$exchange;

        return $this->apiGetRequest();
    }

    public function getAccounts() {
        $this->apiUrl = 'https://api-fxpractice.oanda.com//v1/accounts';

        return $this->apiGetRequest();
    }

    public function getPositions() {
        $this->apiUrl = self::ROOT_URL."/accounts/".$this->accountId."/positions";
        $response = json_decode($this->apiGetRequest());
        return $response;
    }

    public function getTrades() {
        $this->apiUrl = self::ROOT_URL."/accounts/".$this->accountId."/positions";
        $response = json_decode($this->apiGetRequest());
        return $response;
    }

    public function getAccountInfo() {
        $this->apiUrl = self::ROOT_URL."/accounts/".$this->accountId;
        $response = json_decode($this->apiGetRequest());
        return $response;
    }

    public function getTransactionHistory() {
        $this->apiUrl = self::ROOT_URL."/accounts/".$this->accountId."/transactions?count=500";
        $response = json_decode($this->apiGetRequest());
        return $response;
    }

    public function getTransactionsSince($minId) {
        $this->apiUrl = self::ROOT_URL."/accounts/".$this->accountId."/transactions?count=500&minId=".$minId;
        $response = json_decode($this->apiGetRequest());
        return $response;
    }

    public function closeAllPositions() {
        $exchanges = Exchange::get();

        Log::notice($this->runId.': Close All Positions Start');

        foreach ($exchanges as $exchange) {
            $this->apiUrl = self::ROOT_URL."/accounts/".$this->accountId."/positions/".$exchange->exchange;
            $response = $this->apiDeleteRequest();
        }

        Log::notice($this->runId.': Close All Positions End -- response: '.$response);
    }

    public function getInstrumentPosition() {
            $this->apiUrl = self::ROOT_URL."/accounts/".$this->accountId."/positions/".$this->exchange;
            $response = json_decode($this->apiGetRequest());

            Log::info($this->runId.": Get Instrument Position response: ".json_encode($response));

            return $response;
    }

    public function addTrailingStopToTrade($tradeId, $trailingStopPips) {
        Log::notice($this->runId.': Add Trailing Stop to trade '.$tradeId.' with pip value '.$trailingStopPips.'Start');
        $this->apiUrl = self::ROOT_URL."/accounts/".$this->accountId."/trades/".$tradeId;
        $fields = [
            'trailingStop'=> $trailingStopPips
        ];
        $response = $this->apiPatchRequest($fields);
        Log::notice($this->runId.': Add Trailing Stop to trade '.$tradeId.' End response '.$response);
        return $response;
    }

    public function getInstrumentTrade() {
        $this->apiUrl = self::ROOT_URL."/accounts/".$this->accountId."/trades?instrument=".$this->exchange;
        $response = json_decode($this->apiGetRequest());
        return $response;
    }

    public function closeExchangePositions() {
        Log::notice($this->runId.': Close All Positions Start for exchange '.$this->exchange);
        $this->apiUrl = self::ROOT_URL."/accounts/".$this->accountId."/positions/".$this->exchange;
        $response = $this->apiDeleteRequest();
        Log::notice($this->runId.': Close All Positions End for exchange '.$this->exchange.' response: '.$response);
    }

    public function simpleRates($removeUnfinished = true) {
        $rates = $this->getHistoricalData();

        if (isset($rates->candles)) {
            $rates = $rates->candles;
            if ($removeUnfinished) {
                $lastRate = end($rates);
                if (!$lastRate->complete) {
                    array_pop($rates);
                }
            }
            $rates = array_map(function($rate) {
                return $rate->closeMid;
            }, $rates);
            return $rates;
        }
        else {
            return false;
        }
    }

    public function fullRates($removeUnfinished = true) {
        $rates = $this->getHistoricalData();
        if (isset($rates->candles)) {
            $rates = $rates->candles;

            if ($removeUnfinished) {
                $lastRate = end($rates);
                if (!$lastRate->complete) {
                    array_pop($rates);
                }
            }
            return $rates;
        }
        else {
            return false;
        }
    }

    public function fullAndSimple($removeUnfinished = true) {
        $rates = $this->getHistoricalData();
        if (isset($rates->candles)) {
            $rates = $rates->candles;

            if ($removeUnfinished) {
                $lastRate = end($rates);
                if (!$lastRate->complete) {
                    array_pop($rates);
                }
            }

            $bothRates = [];
            $bothRates['full'] = $rates;
            $bothRates['simple'] = array_map(function($rate) {
                return $rate;
            }, $rates);
            return $bothRates;
        }
        else {
            return false;
        }
    }
}
