<?php namespace App\Strategy;

use \Log;
use \App\BackTest\BackTestHelpers;
use \App\Services\TransactionAmountHelpers;
use \App\Model\DecisionInputRatesLogs;
use \App\Model\OandaOpenPositions;
use \App\Model\DecodeFrequency;

abstract class Strategy  {

    public $oanda;
    public $strategyId = 1;
    public $strategyDesc = 'TEST';

    public $accountId = '';

    public $task;

    public $takeProfit;
    public $takeProfitPipAmount;

    public $accountPercentToRisk;

    public $stopLoss;
    public $stopLossPipAmount;
    public $exchange;
    public $frequency;

    public $trailingStopPipAmount;
    public $optionalTrailingStopAmount;
    public $trailingStopProtectBreakEven;
    public $trailingStopNewPosition = false;

    public $decision;

    public $currentPriceData;

    public $indicators;
    public $utility;

    public $positionId;

    public $currencyIndicators;

    public $currentPosition;
    public $fullPositionInfo;
    public $livePosition;

    public $positionMultiplier = 5;
    public $positionAmount;
    public $maxPositions = 3;

    public $logDbRates = false;

    //Only Used During Testing Strategy
    public $backtesting;
    public $backTestPositions = [];
    public $backTestCurrentPosition;
    public $backTestTrailingStop = false;
    public $backTestTrailingStopRate;
    public $backTestClosedAllPositions = false;
    public $backTestOpenPositionSecondCutoff = false;
    public $backTestUnfilledMarketIfTouchedOrder = false;
    public $backTestUnfilledMarketIfTouchedOrderPosition = false;
    public $backTestUnfilledMarketIfTouchedOrderPriceTarget = false;
    public $backTestUnfilledMarketIfTouchedExpiration = 0;

    public $limitOrderPrice = false;
    public $marketIfTouchedOrderPrice = false;

    public $slowRates;
    public $slowRatesPips;

    //Indicator Variables
    public $fastEmaLength;
    public $slowEmaLength;
    public $linearRegressionLength;

    public $orderType = 'MARKET';

    public $decisionIndicators = [];

    public $runId;
    public $strategyLogger;

    public $rates;
    public $ratesPips;

    public $logRates;

    public $rateCount = false;
    public $addCurrentPriceToRates = false;
    public $accountInfo;
    public $accountAvailableMargin;

    public $openPosition;

    public function __construct($accountId = 1, $runId = 1, $backtesting = false) {
        $this->oanda = new \App\Broker\OandaV20();

        $this->indicators = new \App\Services\CurrencyIndicators();
        $this->transactionHelpers = new TransactionAmountHelpers();
        $this->utility = new \App\Services\Utility();
        $this->oanda->accountId = $accountId;
        $this->frequency = new DecodeFrequency();
        $this->oanda->runId = $runId;
        $this->runId = $runId;
        $this->accountId = $accountId;
        $this->backtesting = $backtesting;

        if ($this->backtesting) {
            $this->backTestHelpers = new BackTestHelpers();
        }
    }

    public function getAvailableMargin() {
        $this->accountInfo = $this->oanda->accountInfo();
        return $this->accountInfo->marginAvailable;
    }

    public function calculatePositionAmount() {
        if (!$this->backtesting) {
            $this->positionAmount = round(round($this->accountAvailableMargin)*$this->positionMultiplier);
            $this->oanda->positionAmount = $this->positionAmount;

            $this->strategyLogger->logMessage('Margin Available: '.$this->accountAvailableMargin.'Position Multiplier '.$this->positionMultiplier.' Position Amount: '.$this->positionAmount, 1);
        }
    }

    public function checkOpenPositionsThreshold() {
        if (!$this->backtesting) {
            $count = $this->oanda->checkTotalOpenPositions();

            if ($count <= $this->maxPositions) {
                return true;
            }
            else {
                return false;
            }
        }
        else {
            return true;
        }
    }

    public function getTransactionHistory() {
        return $this->oanda->getTransactionHistory();
    }

    public function newLongPosition() {
        if (!$this->backtesting) {
            $this->strategyLogger->logMessage('Starting New Long Position', 2);

            $this->calculatePositionAmount();

            $params = [];

            $params['side'] = 'buy';

            $params['timeInForce'] = 'GTD';
            $params['gtdTime'] = $this->calculateLimitEndTime();

            if ($this->orderType == 'LIMIT') {
                $params['type'] = 'LIMIT';

                if ($this->limitOrderPrice) {
                    $params['limitPrice'] = $this->oanda->getOandaPrecisionPrice($this->limitOrderPrice, $this->exchange->pip);
                }
                else {
                    $params['limitPrice'] = $this->oanda->getOandaPrecisionPrice($this->currentPriceData->mid, $this->exchange->pip);
                }

                $params['timeInForce'] = 'GTD';
                $params['gtdTime'] = $this->calculateLimitEndTime();

                $this->strategyLogger->logMessage('Long Limit Order with limit Price '.$params['limitPrice'].' good until '.$params['gtdTime'], 1);

                if (isset($this->takeProfitPipAmount)) {
                    $this->oanda->takeProfit = $this->oanda->getOandaPrecisionPrice($this->calculateLongTakeProfit($this->limitOrderPrice), $this->exchange->pip);
                }

                if (isset($this->stopLossPipAmount)) {
                    $this->oanda->stopLoss = $this->oanda->getOandaPrecisionPrice($this->calculateLongStopLoss($this->limitOrderPrice), $this->exchange->pip);
                }
            }
            elseif ($this->orderType == 'MARKET_IF_TOUCHED') {
                if ($this->currentPriceData->ask >= $this->marketIfTouchedOrderPrice) {
                    $params['type'] = 'LIMIT';
                    $params['limitPrice'] = $this->oanda->getOandaPrecisionPrice($this->currentPriceData->ask, $this->exchange->pip);
                }
                else {
                    $params['type'] = 'MARKET_IF_TOUCHED';
                    $params['marketIfTouchedOrderPrice'] = $this->oanda->getOandaPrecisionPrice($this->marketIfTouchedOrderPrice, $this->exchange->pip);
                    $this->strategyLogger->logMessage('Long MARKET_IF_TOUCHED Order with mkt if touched Price '.$params['marketIfTouchedOrderPrice'].' good until '.$params['gtdTime'], 1);
                }

                if (isset($this->takeProfitPipAmount)) {
                    $this->oanda->takeProfit = $this->oanda->getOandaPrecisionPrice($this->calculateLongTakeProfit($this->marketIfTouchedOrderPrice), $this->exchange->pip);
                }

                if (isset($this->stopLossPipAmount)) {
                    $this->oanda->stopLoss = $this->oanda->getOandaPrecisionPrice($this->calculateLongStopLoss($this->marketIfTouchedOrderPrice), $this->exchange->pip);
                }
            }
            else {
                $params['type'] = 'LIMIT';
                $params['limitPrice'] = $this->oanda->getOandaPrecisionPrice($this->currentPriceData->ask, $this->exchange->pip);
            }

            if (isset($this->trailingStopPipAmount)) {
                $this->oanda->trailingStop = $this->trailingStopPipAmount;
            }

            $response = $this->oanda->newOrder($params);

            $newDbPosition = OandaOpenPositions::firstOrNew(['oanda_account_id'=>$this->accountId, 'exchange_id'=>$this->exchange->id]);

            $newDbPosition->oanda_account_id = $this->accountId;
            $newDbPosition->position_time = time();
            $newDbPosition->side = 1;
            $newDbPosition->exchange_id = $this->exchange->id;

            $newDbPosition->save();
        }
        else {
            $takeProfit = $this->oanda->getOandaPrecisionPrice($this->calculateLongTakeProfit($this->marketIfTouchedOrderPrice), $this->exchange->pip);
            $stopLoss = $this->oanda->getOandaPrecisionPrice($this->calculateLongStopLoss($this->marketIfTouchedOrderPrice), $this->exchange->pip);

            $newPosition = [
                "amount" =>   $this->currentPriceData->open,
                "positionType" =>   "long",
                "takeProfit" =>   $takeProfit,
                "stopLoss" =>   $stopLoss,
                "dateTime" => $this->currentPriceData->dateTime,
                "highestRate" => $this->currentPriceData->open,
                "lowestRate" => $this->currentPriceData->open,
                "highestRateDate" => $this->currentPriceData->dateTime,
                "lowestRateDate" => $this->currentPriceData->dateTime
            ];

            if ($this->orderType == 'LIMIT') {
                $params['type'] = 'LIMIT';
                $params['limitPrice'] = $this->oanda->getOandaPrecisionPrice($this->currentPriceData->mid, $this->exchange->pip);
                $params['gtdTime'] = $this->calculateLimitEndTime();
                $this->strategyLogger->logMessage('Long Limit Order with limit Price '.$params['limitPrice'].' good until '.$params['gtdTime'], 1);
            }
            elseif ($this->orderType == 'MARKET_IF_TOUCHED') {
                if ($this->currentPriceData->ask >= $this->marketIfTouchedOrderPrice) {
                    $newPosition['amount'] = $this->currentPriceData->ask;
                }
                else {
                    $newPosition['amount'] = $this->marketIfTouchedOrderPrice;
                }

                $newPosition['takeProfit'] = $this->oanda->getOandaPrecisionPrice($this->calculateLongTakeProfit($this->marketIfTouchedOrderPrice), $this->exchange->pip);
                $newPosition['stopLoss'] = $this->oanda->getOandaPrecisionPrice($this->calculateLongStopLoss($this->marketIfTouchedOrderPrice), $this->exchange->pip);

                $this->backTestUnfilledMarketIfTouchedOrder = true;
                $this->backTestUnfilledMarketIfTouchedOrderPosition = $newPosition;
                $this->backTestUnfilledMarketIfTouchedOrderPriceTarget = $this->marketIfTouchedOrderPrice;
                $this->backTestUnfilledMarketIfTouchedExpiration = $this->currentPriceData->rateUnixTime + 60;
            }
            else {
                $this->backTestPositions[] = $newPosition;

                $this->backTestCurrentPosition = "long";
                $this->currentPosition['current_position'] = 1;

                $this->fullPositionInfo['open'] = true;
                $this->fullPositionInfo['side'] = 'buy';

                if ($this->trailingStopNewPosition) {
                    $this->backTestTrailingStop = true;

                    $newPosition['trailingStopAddDate'] = $this->currentPriceData->dateTime;
                    $newPosition['trailingStopRate'] = $this->currentPriceData->open;

                    $this->backTestTrailingStopRate = $this->currentPriceData->open - ($this->trailingStopPipAmount*$this->exchange->pip);
                }
            }
        }
    }

    public function calculateLongStopLoss($buyPrice) {
        return $buyPrice - ($this->exchange->pip * $this->stopLossPipAmount);
    }

    public function calculateShortStopLoss($price) {
        return $price + ($this->exchange->pip * $this->stopLossPipAmount);
    }

    public function calculateLongTakeProfit($price) {
        return $price + ($this->exchange->pip * $this->takeProfitPipAmount);
    }

    public function calculateShortTakeProfit($price) {
        return $price - ($this->exchange->pip * $this->takeProfitPipAmount);
    }

    public function calculateLimitEndTime() {
        $frequency = DecodeFrequency::where('oanda_code', '=', $this->oanda->frequency)->first();
        return time() + ($frequency->frequency_seconds - 60);
    }

    public function modifyLongStopLoss($openPosition) {
        if (!$this->backtesting) {
            $this->oanda->stopLoss = $this->calculateLongStopLoss($this->currentPriceData->mid);

            foreach ($openPosition['positionTradeIds'] as $tradeId) {
                $this->oanda->modifyStopLoss($tradeId);
            }
        }
        else {
            $this->backTestPositions[sizeof($this->backTestPositions)-1]['stopLoss'] = $this->calculateLongStopLoss($this->currentPriceData->open);
        }
    }

    public function modifyStopLoss($newPricePoint) {
        if (!$this->backtesting) {
            $this->oanda->stopLoss = $this->oanda->getOandaPrecisionPrice($newPricePoint, $this->exchange->pip);
            foreach ($this->openPosition['positionTradeIds'] as $tradeId) {
                $this->oanda->modifyStopLoss($tradeId);
            }
//            if (isset($this->openPosition['stopLossId'])) {
//                $this->strategyLogger->logMessage('Modify Stop Loss Start', 2);
//
//                $this->oanda->modifyStopLoss($this->openPosition['stopLossId']);
//            }
//            else {
//                $this->strategyLogger->logMessage('Add Stop Loss Start', 2);
//                $this->oanda->addStopLoss($this->openPosition['tradeId']);
//            }

        }
        else {
            $this->backTestPositions[sizeof($this->backTestPositions)-1]['stopLoss'] = $newPricePoint;
        }
    }

    public function modifyShortStopLoss($openPosition) {
        if (!$this->backtesting) {
            $this->oanda->stopLoss = $this->calculateShortStopLoss($this->currentPriceData->mid);
            $this->oanda->modifyStopLoss($openPosition['stopLossId']);
        }
        else {
            $this->backTestPositions[sizeof($this->backTestPositions)-1]['stopLoss'] = $this->calculateShortStopLoss($this->currentPriceData->open);
        }
    }

    public function newLongOrAdjustSL() {
        $openPosition = $this->checkOpenPosition();

        if ($openPosition) {
            if ($openPosition['side'] == 'short') {
                //Close Current Short Position Because We Want Long Position
                $this->closePosition();
                //New Long
                $this->newLongPosition();
            }
            else {
                //Already in Position, so just Modify Stop Loss
                $this->modifyLongStopLoss($openPosition);
            }
        }
        else {
            $this->newLongPosition();
        }
    }

    public function newShortOrAdjustSL() {
        $openPosition = $this->checkOpenPosition();

        if ($openPosition) {
            if ($openPosition['side'] == 'long') {
                //Close Current Short Position Because We Want Long Position
                $this->closePosition();
                //New Long
                $this->newShortPosition();
            }
            else {
                //Already in Position, so just Modify Stop Loss
                $this->modifyShortStopLoss($openPosition);
            }
        }
        else {
            $this->newShortPosition();
        }
    }

    public function checkOpenPosition() {
        if (!$this->backtesting) {
            return $this->oanda->checkOpenPosition();
        }
        else {
            if (sizeof($this->backTestPositions) > 0) {
                end($this->backTestPositions);
                $lastIndex = key($this->backTestPositions);
                $possibleOpenPosition = $this->backTestPositions[$lastIndex];
                return $this->backTestHelpers->checkOpenPosition($possibleOpenPosition, $this->currentPriceData, $this->backTestTrailingStop, $this->currentPriceData->rateUnixTime);

            }
            else {
                return false;
            }
        }
    }

    public function closeIfOpen() {
        $openPosition = $this->checkOpenPosition();
        if ($openPosition['side']) {
            $this->closePosition();
        }
    }

    public function newLongOrStayInPosition() {
        if (!$this->backtesting) {
            $this->oanda->stopLoss = $this->calculateLongStopLoss($this->currentPriceData->mid);
        }

        $openPosition = $this->checkOpenPosition();

        if ($openPosition) {
                if ($openPosition['side'] == 'short') {
                    $this->closePosition();
                    $this->newLongPosition();
                }
        }
        else {
            $this->newLongPosition();
        }
    }

    public function newShortOrStayInPosition() {
        if (!$this->backtesting) {
            $this->oanda->stopLoss = $this->calculateShortStopLoss($this->currentPriceData->mid);
        }

        $openPosition = $this->checkOpenPosition();

        if ($openPosition) {
            if ($openPosition['side'] == 'long') {
                $this->closePosition();
                $this->newShortPosition();
            }
        }
        else {
            $this->newShortPosition();
        }
    }

    public function newShortPosition() {
        if (!$this->backtesting) {
            $this->strategyLogger->logMessage('New Short Position', 2);
            $this->calculatePositionAmount();

            $params = [];

            $params['side'] = 'sell';

            $params['timeInForce'] = 'GTD';
            $params['gtdTime'] = $this->calculateLimitEndTime();

            if ($this->orderType == 'LIMIT') {
                $params['type'] = 'LIMIT';

                if ($this->limitOrderPrice) {
                    $params['limitPrice'] = $this->oanda->getOandaPrecisionPrice($this->limitOrderPrice, $this->exchange->pip);
                }
                else {
                    $params['limitPrice'] = $this->oanda->getOandaPrecisionPrice($this->currentPriceData->mid, $this->exchange->pip);
                }

                $params['timeInForce'] = 'GTD';
                $params['gtdTime'] = $this->calculateLimitEndTime();
                $this->strategyLogger->logMessage('Short Limit Order with limit Price '.$params['limitPrice'].' good until '.$params['gtdTime'], 1);

                if (isset($this->takeProfitPipAmount)) {
                    $this->oanda->takeProfit = $this->oanda->getOandaPrecisionPrice($this->calculateShortTakeProfit($this->limitOrderPrice), $this->exchange->pip);
                }

                if (isset($this->stopLossPipAmount)) {
                    $this->oanda->stopLoss = $this->oanda->getOandaPrecisionPrice($this->calculateShortStopLoss($this->limitOrderPrice), $this->exchange->pip);
                }
            }
            elseif ($this->orderType == 'MARKET_IF_TOUCHED') {
                if ($this->currentPriceData->mid <= $this->marketIfTouchedOrderPrice) {
                    $params['type'] = 'LIMIT';
                    $params['limitPrice'] = $this->oanda->getOandaPrecisionPrice($this->currentPriceData->mid, $this->exchange->pip);
                }
                else {
                    $params['type'] = 'MARKET_IF_TOUCHED';
                    $params['marketIfTouchedOrderPrice'] = $this->oanda->getOandaPrecisionPrice($this->marketIfTouchedOrderPrice, $this->exchange->pip);

                    $this->strategyLogger->logMessage('Short MARKET_IF_TOUCHED Order with MIT Price '.$params['marketIfTouchedOrderPrice'].' good until '.$params['gtdTime'], 1);
                }

                if (isset($this->takeProfitPipAmount)) {
                    $this->oanda->takeProfit = $this->oanda->getOandaPrecisionPrice($this->calculateShortTakeProfit($this->marketIfTouchedOrderPrice), $this->exchange->pip);
                }

                if (isset($this->stopLossPipAmount)) {
                    $this->oanda->stopLoss = $this->oanda->getOandaPrecisionPrice($this->calculateShortStopLoss($this->marketIfTouchedOrderPrice), $this->exchange->pip);
                }
            }
            else {
                $params['type'] = 'LIMIT';
                $params['limitPrice'] = $this->oanda->getOandaPrecisionPrice($this->currentPriceData->ask, $this->exchange->pip);
            }

            if (isset($this->trailingStopPipAmount)) {
                $this->oanda->trailingStop = $this->trailingStopPipAmount;
            }

            $response = $this->oanda->newOrder($params);

            $newDbPosition = OandaOpenPositions::firstOrNew(['oanda_account_id'=>$this->accountId, 'exchange_id'=>$this->exchange->id]);

            $newDbPosition->oanda_account_id = $this->accountId;
            $newDbPosition->position_time = time();
            $newDbPosition->side = -1;
            $newDbPosition->exchange_id = $this->exchange->id;

            $newDbPosition->save();
        }
        else {
            $takeProfit = $this->oanda->getOandaPrecisionPrice($this->calculateShortTakeProfit($this->currentPriceData->open), $this->exchange->pip);
            $stopLoss = $this->oanda->getOandaPrecisionPrice($this->calculateShortStopLoss($this->currentPriceData->open), $this->exchange->pip);

                $newPosition = [
                    "amount" =>   $this->currentPriceData->open,
                    "positionType" =>   "short",
                    "takeProfit" =>   $takeProfit,
                    "stopLoss" =>   $stopLoss,
                    "dateTime" => $this->currentPriceData->dateTime,
                    "highestRate" => $this->currentPriceData->open,
                    "lowestRate" => $this->currentPriceData->open,
                    "highestRateDate" => $this->currentPriceData->dateTime,
                    "lowestRateDate" => $this->currentPriceData->dateTime
                ];

            if ($this->orderType == 'MARKET_IF_TOUCHED') {
                if ($this->currentPriceData->ask <= $this->marketIfTouchedOrderPrice) {
                    $newPosition['amount'] = $this->currentPriceData->ask;
                }
                else {
                    $newPosition['amount'] = $this->marketIfTouchedOrderPrice;
                }

                $newPosition['takeProfit'] = $this->oanda->getOandaPrecisionPrice($this->calculateShortTakeProfit($this->marketIfTouchedOrderPrice), $this->exchange->pip);
                $newPosition['stopLoss'] = $this->oanda->getOandaPrecisionPrice($this->calculateShortStopLoss($this->marketIfTouchedOrderPrice), $this->exchange->pip);

                $this->backTestUnfilledMarketIfTouchedOrder = true;
                $this->backTestUnfilledMarketIfTouchedOrderPosition = $newPosition;
                $this->backTestUnfilledMarketIfTouchedOrderPriceTarget = $this->marketIfTouchedOrderPrice;
                $this->backTestUnfilledMarketIfTouchedExpiration = $this->currentPriceData->rateUnixTime + 60;
            }
            else {
                $this->backTestPositions[] = $newPosition;

                $this->backTestCurrentPosition = "short";
                $this->currentPosition['current_position'] = -1;

                $this->fullPositionInfo['open'] = true;
                $this->fullPositionInfo['side'] = 'sell';

                if ($this->trailingStopNewPosition) {
                    $this->backTestTrailingStop = true;

                    $newPosition['trailingStopAddDate'] = $this->currentPriceData->dateTime;
                    $newPosition['trailingStopRate'] = $this->currentPriceData->open;

                    $this->backTestTrailingStopRate = $this->currentPriceData->open + ($this->trailingStopPipAmount*$this->exchange->pip);
                }
            }
        }
    }

    public function addTrailingStopToPosition($tsPipAmount) {
        if (!$this->backtesting) {
            $this->strategyLogger->logMessage('Attempt to Add Trailing Stop', 4);

            $this->oanda->addTrailingStopToTrade($this->fullPositionInfo['tradeId'], $tsPipAmount);
        }
        else {
            $this->backTestTrailingStop = true;

            $this->backTestPositions[sizeof($this->backTestPositions)-1]['trailingStopAddDate'] = $this->currentPriceData->dateTime;
            $this->backTestPositions[sizeof($this->backTestPositions)-1]['trailingStopRate'] = $this->currentPriceData->ask;

            if ($this->fullPositionInfo['side'] == 'buy') {
                $this->backTestTrailingStopRate = $this->currentPriceData->high - ($tsPipAmount*$this->exchange->pip);
            }
            else {
                $this->backTestTrailingStopRate = $this->currentPriceData->low + ($tsPipAmount*$this->exchange->pip);
            }
        }
    }

    /******************************
     * GET TASKS
     ******************************/
    public function getCurrentTaskWithTrailingStopAdd() {
        if (!$this->fullPositionInfo['open']) {
            $this->strategyLogger->logMessage('Checking for New Position', 1);
            return 'checkForNew';
        }
        elseif ($this->fullPositionInfo['open'] && $this->fullPositionInfo['trailingStop'] == 0) {
            $this->strategyLogger->logMessage('Checking to add trailing stop', 1);
            return 'checkToAddTrailingStop';
        }
        else {
            $this->strategyLogger->logMessage('Doing Nothing', 1);
            return 'doNothing';
        }
    }

    public function getCurrentTaskBuyAndClose() {
        if ($this->fullPositionInfo['open']) {
            $this->strategyLogger->logMessage('Checking To Close Position', 1);
            return 'checkForClose';
        }
        else {
            $this->strategyLogger->logMessage('Checking For New Position.', 1);
            return 'checkForNew';
        }
    }

   public function logIndicators() {
        if (!$this->backtesting) {
            $logIndicators = $this->decisionIndicators;
            $logIndicators['rates'] = $this->rates;

            $indicators = json_encode($this->utility->trimArraysInComplexArray($logIndicators, 5));

            $this->strategyLogger->logRates($this->rates);
            $this->strategyLogger->logIndicators($indicators);
        }
   }

   public function getRatesInPips($rates) {
        if (isset($rates['simple'])) {
            $ratePips = array_map(function($rate) {
                return $rate/$this->exchange->pip;
            }, $rates['simple']);
        }
        else {
            $ratePips = array_map(function($rate) {
                return $rate/$this->exchange->pip;
            }, $rates);
        }
        return $ratePips;
   }

   public function getOpenPositionProfitInPips($positionResponse) {
       if (!$this->backtesting) {
           if ($positionResponse->side == 'sell') {
               //use bid
               return ($positionResponse->avgPrice - $this->currentPriceData->ask)/$this->exchange->pip;
           }
           else {
               return ($this->currentPriceData->bid - $positionResponse->avgPrice)/$this->exchange->pip;
           }
       }
       else {
           //Back Test Calculation
           if ($this->backTestPositions[sizeof($this->backTestPositions)-1]['positionType'] == 'sell') {
               //use bid
               return ($this->backTestPositions[sizeof($this->backTestPositions)-1]['amount'] - $this->currentPriceData->ask)/$this->exchange->pip;
           }
           else {
               return ($this->currentPriceData->bid - $this->backTestPositions[sizeof($this->backTestPositions)-1]['amount'])/$this->exchange->pip;
           }
       }
   }

    public function getExchanges() {
        return DB::select("select exchange.* from strategy_exchange_rules
                    join exchange on exchange.id = strategy_exchange_rules.exchange_id
                    where strategy_exchange_rules.strategy_id = ? 
                    order by strategy_exchange_rules.rank;", [$this->strategyId]);
    }

    public function getRates($type, $removeUnfinished = false) {
        $this->oanda->exchange = $this->exchange->exchange;

        if ($this->rateCount) {
            $this->oanda->rateCount = $this->rateCount;
        }

        $this->oanda->addCurrentPriceToRates = $this->addCurrentPriceToRates;

        if ($type == 'simple') {
            return $this->oanda->simpleRates($removeUnfinished);
        }
        elseif ($type == 'full') {
            return $this->oanda->fullRates($removeUnfinished);
        }
        elseif ($type == 'both') {
            return $this->oanda->fullAndSimpleRates($removeUnfinished);
        }
    }

    public function closePosition() {
        if (!$this->backtesting) {
           $this->oanda->closePosition();
        }
        else {
            if (sizeof($this->backTestPositions) > 0) {

                end($this->backTestPositions);
                $lastIndex = key($this->backTestPositions);
                $possibleOpenPosition = $this->backTestPositions[$lastIndex];
                $this->backTestPositions[$lastIndex] = $this->backTestHelpers->closeOpenPosition($possibleOpenPosition, $this->currentPriceData);
                $this->backTestCurrentPosition = null;
                $this->backTestTrailingStop = false;
                $this->fullPositionInfo['open'] = false;
            }
        }
    }

    public function calculateKelterPositionAmount() {
        if (!$this->backtesting) {
            $this->accountInfo = $this->oanda->accountInfo();

            $this->positionAmount = round(round($this->accountInfo->balance)*$this->positionMultiplier);
            $this->oanda->positionAmount = $this->positionAmount;
        }
    }

    public function kellyPositionCalculation($winProbability) {
        $b = $this->takeProfit/$this->stopLoss;
        return (($b*$winProbability) - (1 - $winProbability))/$b;
    }

    public function calculatePositionAmountByAmountToRisk() {
        if (!$this->backtesting) {
            $this->accountInfo = $this->oanda->accountInfo();

            $riskAmount = $this->accountPercentToRisk*$this->accountAvailableMargin;

            $this->positionAmount = $this->transactionHelpers->calculatePositionAmount($this->currentPriceData->ask, $this->exchange->pip,$this->stopLossPipAmount, $riskAmount);
            $this->oanda->positionAmount = $this->positionAmount;
        }
    }

    public function recentOpenPositionCheck() {
        if (!$this->backtesting) {
            $secondCutoff = $this->frequency->secondFrequencyCutoffs[$this->oanda->frequency];

            $positionCheck = OandaOpenPositions::where('oanda_account_id', '=', $this->accountId)->where('exchange_id', '=', $this->exchange->id)->first();

            if ($positionCheck == null) {
                return false;
            }
            else {
                $lastOpenPositionTime = $positionCheck->position_time;
                $currentTime = time();
            }
        }
        else {
            if (sizeof($this->backTestPositions) > 0) {
                end($this->backTestPositions);
                $lastIndex = key($this->backTestPositions);
                $lastOpenPosition = $this->backTestPositions[$lastIndex];
                $lastOpenPositionTime = strtotime($lastOpenPosition['dateTime']);
                $currentTime = strtotime($this->currentPriceData->dateTime);
                $secondCutoff = $this->backTestOpenPositionSecondCutoff;

            }
            else {
                return false;
            }
        }

        //If the Current Time Minus Last Open Position Time is sooner thant he cut off, cancel
       if (($currentTime - $lastOpenPositionTime) <= $secondCutoff) {
            return true;
       }
       else {
            return false;
       }
    }

    public function setLogger($strategyLogger) {
        $this->strategyLogger = $strategyLogger;
        $this->oanda->strategyLogger = $strategyLogger;
    }

    public function setCurrentPrice() {
        $this->currentPriceData = $this->oanda->currentPrice();

        if (isset($this->rates['simple'])) {
            $indicatorLastRate = end($this->rates['simple']);
        }
        else {
            $indicatorLastRate = end($this->rates);
        }

        $difference = abs($indicatorLastRate - $this->currentPriceData->mid);
        $differencePips = $difference/$this->exchange->pip;

        $logMessage = 'Current Price Mid '.$this->currentPriceData->mid.' Indicator Last Rate '.$indicatorLastRate.' Diff '.$difference.', '.$differencePips. 'pips';

        $this->strategyLogger->logMessage($logMessage, 1);
    }

    public function checkToAddTrailingStop() {
       // if ($this->currentPriceData->high >= ())
        if (!$this->backtesting) {
            ///Handle Real Live Stuff
        }
        else {
            if ($this->openPosition['gl'] >= $this->optionalTrailingStopAmount) {
                $this->addTrailingStopToPosition($this->optionalTrailingStopAmount);
            }
        }
    }

    public function addBreakEvenTrailingStopCheck() {
        if ($this->trailingStopProtectBreakEven) {
            $this->openPosition = $this->checkOpenPosition();

            if ($this->openPosition) {
                if (!$this->openPosition['trailingStop']) {
                    $this->checkToAddTrailingStop();
                }
            }
        }
    }

    public function entryStayInDecision() {
        if ($this->decision == 'long') {
            $this->newLongOrStayInPosition();
        }
        elseif ($this->decision == 'short') {
            $this->newShortOrStayInPosition();
        }
    }

    public function exitNewPositionOrBreakEven() {
        if ($this->decision == 'short' && $this->openPosition['side'] == 'long') {
            $this->newShortOrStayInPosition();
        }
        elseif ($this->decision == 'long' && $this->openPosition['side'] == 'short') {
            $this->newLongOrStayInPosition();
        }
    }

    public function setOpenPosition() {
        $this->openPosition = $this->checkOpenPosition();

        if (isset($this->frequency->frequency_seconds) && $this->openPosition) {
            $this->openPosition['periodsOpen'] = round($this->openPosition['secondsSinceOpenPosition']/$this->frequency->frequency_seconds);
        }
    }

    public function startStrategy() {
        $this->setOpenPosition();
    }

    public function quickPositionTypeTarget($side) {
        if ($side == 'long') {
            if ($this->currentPriceData->ask >= $this->marketIfTouchedOrderPrice) {

            }
        }
    }
}
