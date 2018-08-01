<?php namespace App\Services;

class AutomatedSystem  {

    public $macdShortPeriod;
    public $macdLongPeriod;

    public $emaShortPeriod;
    public $emaLongPeriod;

    public $oanda;

    public $exchangePip;
    public $exchange;

    public $currentPriceData;

    public $rsi;

    public $currentPosition;

    public $yesterdayShortEma;
    public $liveShortEma;
    public $yesterdayLongEma;
    public $liveLongEma;

    public $indicators;

    public $decision;

    public $phantomOpenPositions;

    public function storeHourlyRates() {
        $oanda = new \App\Services\Oanda();

        $exchanges = \App\Model\Exchange::get();

        $ratesReturned = 0;

        foreach ($exchanges as $exchange) {
            $oanda->exchange = $exchange->exchange;

            $currentPriceData = $oanda->getCurrentPrice();

            if (!isset($currentPriceData->status)) {
                    $newCurrencyHourly = new \App\Model\CurrencyHourly;

                    $newCurrencyHourly->rate_date_time = date('Y-m-d H:i:s');

                    $newCurrencyHourly->bid_rate = $currentPriceData->bid;

                    $newCurrencyHourly->ask_rate = $currentPriceData->ask;

                    $newCurrencyHourly->currency_hourly_id = $exchange->id;

                    $newCurrencyHourly->save();

                    $ratesReturned++;
            }
            else {
                if ($currentPriceData->status != 'halted') {

                    $newCurrencyHourly = new \App\Model\CurrencyHourly;

                    $newCurrencyHourly->rate_date_time = date('Y-m-d H:i:s');

                    $newCurrencyHourly->bid_rate = $currentPriceData->bid;

                    $newCurrencyHourly->ask_rate = $currentPriceData->ask;

                    $newCurrencyHourly->currency_hourly_id = $exchange->id;

                    $newCurrencyHourly->save();

                    $ratesReturned++;
                }
            }
        }
        return $ratesReturned;
    }

    public function storeFifteenRates() {
        $oanda = new \App\Services\Oanda();

        $exchanges = \App\Model\Exchange::get();

        $ratesReturned = 0;

        foreach ($exchanges as $exchange) {
            $oanda->exchange = $exchange->exchange;

            $currentPriceData = $oanda->getCurrentPrice();

            if (!isset($currentPriceData->status)) {
                $newCurrencyHourly = new \App\Model\CurrencyFifteenMinutes;

                $newCurrencyHourly->rate_date_time = date('Y-m-d H:i:s');

                $newCurrencyHourly->bid_rate = $currentPriceData->bid;

                $newCurrencyHourly->ask_rate = $currentPriceData->ask;

                $newCurrencyHourly->exchange_id = $exchange->id;

                $newCurrencyHourly->save();

                $ratesReturned++;
            }
            else {
                if ($currentPriceData->status != 'halted') {

                    $newCurrencyHourly = new \App\Model\CurrencyFifteenMinutes;

                    $newCurrencyHourly->rate_date_time = date('Y-m-d H:i:s');

                    $newCurrencyHourly->bid_rate = $currentPriceData->bid;

                    $newCurrencyHourly->ask_rate = $currentPriceData->ask;

                    $newCurrencyHourly->exchange_id = $exchange->id;

                    $newCurrencyHourly->save();

                    $ratesReturned++;
                }
            }
        }
        return $ratesReturned;
    }

    public function checkOpenPosition() {
        $transactionHistory = $this->oanda->getTransactionHistory();
    }

    public function newLongPosition() {
        $this->oanda->takeProfit = $this->currentPriceData->ask + $this->exchange->pip * 200;
        $this->oanda->stopLoss = $this->currentPriceData->ask - $this->exchange->pip * 100;

        $response = $this->oanda->newOrder("buy", $this->exchange->exchange);

        $transactionRecord = new \App\Model\CurrencyTransactionHistory;

        $transactionRecord->currency_hourly_id = $this->exchange->id;
        $transactionRecord->type = 1;
        $transactionRecord->strategy_id = $this->strategyId;
        $transactionRecord->price = $this->currentPriceData->ask;
        $transactionRecord->response = json_encode($response);

        $transactionRecord->save();

        $this->currentPosition = 1;
    }

    public function newShortPosition() {
        $this->oanda->takeProfit = $this->currentPriceData->ask - $this->exchange->pip * 200;
        $this->oanda->stopLoss = $this->currentPriceData->ask + $this->exchange->pip * 100;

        $response = $this->oanda->newOrder("sell", $this->exchange->exchange);

        $transactionRecord = new \App\Model\CurrencyTransactionHistory;

        $transactionRecord->currency_hourly_id = $this->exchange->id;
        $transactionRecord->type = -1;
        $transactionRecord->strategy_id = $this->strategyId;
        $transactionRecord->price = $this->currentPriceData->ask;
        $transactionRecord->response = json_encode($response);

        $transactionRecord->save();

        $this->currentPosition = -1;
    }

    public function logFiveMinuteCheck($decisionInput, $newDecision) {
        $fiveMinuteLog = new \App\Model\FiveMinuteCheckLog;

        $fiveMinuteLog->currency_hourly_id = $this->exchange->id;

        $fiveMinuteLog->rate_date_time = date('Y-m-d H:i:s');;

        $fiveMinuteLog->rsi = $decisionInput['rsi'];

        $fiveMinuteLog->current_position_status = $this->currentPosition;

        $fiveMinuteLog->yesterday_short_ema = $this->yesterdayShortEma;

        $fiveMinuteLog->today_short_ema = $this->liveShortEma;

        $fiveMinuteLog->yesterday_long_ema = $this->yesterdayLongEma;

        $fiveMinuteLog->today_long_ema = $this->liveLongEma;

        $fiveMinuteLog->ema_status = $decisionInput['emaEvent'];

        $fiveMinuteLog->decision = $newDecision['newPosition'];

        $fiveMinuteLog->save();
    }

    public function saveFiveMinuteRate($currentPriceData) {
        //Save Price for Back Testing Later
        $currencyHourlyFiveMinutes = new \App\Model\CurrencyHourlyFiveMinutes();

        $currencyHourlyFiveMinutes->rate_date_time = date('Y-m-d H:i:s');

        $currencyHourlyFiveMinutes->bid_rate = $currentPriceData->bid;

        $currencyHourlyFiveMinutes->ask_rate = $currentPriceData->ask;

        $currencyHourlyFiveMinutes->currency_hourly_id = $this->exchange->id;

        $currencyHourlyFiveMinutes->save();
    }

    public function quickDecision() {
        $this->oanda = new \App\Services\Oanda();

        $exchanges = \App\Model\Exchange::get();

        $this->indicators = new \App\Services\CurrencyIndicators();

        $this->decision = new \App\Services\CurrencyPositionDecisions();

        $decisionInput = [];

        foreach ($exchanges as $exchange) {

            $this->oanda->exchange = $exchange->exchange;
            $this->exchange = $exchange;

            $outstandingPositions = $this->oanda->getPositions();

            if (empty($outstandingPositions->positions)) {
                $this->currentPosition = 0;
            }
            else {
                $currentPositionStatus = $this->checkOpenPosition();

                if ($outstandingPositions->positions[0]->side == "sell") {
                    $this->currentPosition = -1;
                }
                else {
                    $this->currentPosition = 1;
                }
            }

            $currentPriceData = $this->oanda->getCurrentPrice();

            if (!isset($currentPriceData->status)) {

                //Save the Returned Rate for Future Back Testing
                $this->saveFiveMinuteRate($currentPriceData);

                //Get Previous Hourly Rates
                $takeCount = $this->emaLongPeriod*2 + 1;
                $hourlyRates = \App\Model\CurrencyHourly::where('currency_hourly_id', '=', $exchange->id)->orderBy('id', 'desc')->take($takeCount)->get();
                $hourlyRates = $hourlyRates->toArray();

                $rates = array_map(function($rate) {
                    return $rate['ask_rate'];
                }, $hourlyRates);

                $rates[] = $currentPriceData->ask;

                /*************************************************************
                 * BEGIN STRATEGY CALCULATIONS
                 *************************************************************/

                $this->phantomCrossover($rates);

//                $index = 0;
//                $test = 2;
//
//                $sma = $this->indicators->calculateSMALive($this->emaLongPeriod, $rates, $this->emaLongPeriod);
//
//                $maxIndex = max(array_keys($rates));
//                $currentLongIndex = $this->emaLongPeriod + 1;
//
//                $indicatorValues = [];
//
//                //Calculate Long EMA
//                while ($currentLongIndex <= $maxIndex) {
//                    $previousIndex = $currentLongIndex -1;
//                    if (!isset($indicatorValues[$previousIndex])) {
//                        $this->yesterdayLongEma = $sma;
//                    }
//                    else {
//                        $this->yesterdayLongEma = $indicatorValues[$previousIndex]['longEma'];
//                    }
//                    $indicatorValues[$currentLongIndex]['longEma'] = $this->indicators->calculateEMA($this->emaLongPeriod, 100, $rates[$currentLongIndex], $this->yesterdayLongEma);
//
//                    if ($currentLongIndex == $maxIndex) {
//                        $this->liveLongEma = $indicatorValues[$currentLongIndex]['longEma'];
//                    }
//                    $currentLongIndex++;
//                }
//
//                //Calculate Short EMA
//                $currentrateIndex = $this->emaShortPeriod + 1;
//
//                while ($currentrateIndex <= $maxIndex) {
//                    $previousIndex = $currentrateIndex -1;
//                    if (!isset($indicatorValues[$previousIndex])) {
//                        $this->yesterdayShortEma = $sma;
//                    }
//                    else {
//                        $this->yesterdayShortEma = $indicatorValues[$previousIndex]['shortEma'];
//                    }
//                    $indicatorValues[$currentrateIndex]['shortEma'] = $this->indicators->calculateEMA($this->emaShortPeriod,100, $rates[$currentrateIndex], $this->yesterdayShortEma);
//
//                    if ($currentrateIndex == $maxIndex) {
//                        $this->liveShortEma = $indicatorValues[$currentrateIndex]['shortEma'];
//                    }
//                    $currentrateIndex++;
//                }
//
//                if ($this->yesterdayLongEma >= $this->yesterdayShortEma &&  $this->liveLongEma <= $this->liveShortEma ) {
//                    $decisionInput['emaEvent'] = "crossedAbove";
//                }
//                elseif ($this->yesterdayLongEma <= $this->yesterdayShortEma &&  $this->liveLongEma >= $this->liveShortEma) {
//                    $decisionInput['emaEvent'] = "crossedBelow";
//                }
//                else {
//                    $decisionInput['emaEvent'] = "none";
//                }
//
//                $rsiCurrentIndex = $takeCount - $this->rsiPeriods;
//                $rsiGains = [];
//                $rsiLosses = [];
//
//                while ($rsiCurrentIndex < $maxIndex) {
//                    $nextIndex = $rsiCurrentIndex+1;
//
//                    $difference = $rates[$nextIndex] - $rates[$rsiCurrentIndex];
//
//                    if ($difference > 0) {
//                        $rsiGains[] = $difference;
//                    }
//                    else {
//                        $rsiLosses[] = $difference;
//                    }
//                    $rsiCurrentIndex++;
//                }
//
//                $rsiAverageGain = array_sum($rsiGains)/$this->rsiPeriods;
//                $rsiAverageLoss = array_sum($rsiLosses)/$this->rsiPeriods;
//
//                if ($rsiAverageLoss == 0 && $rsiAverageGain > 0) {
//                    $decisionInput['rsi'] = 100;
//                }
//                elseif (($rsiAverageLoss > 0 && $rsiAverageGain == 0)) {
//                    $decisionInput['rsi'] = 0;
//                }
//                elseif (($rsiAverageLoss == 0 && $rsiAverageGain == 0)) {
//                    $decisionInput['rsi'] = 50;
//                }
//                else {
//                    $rs = abs($rsiAverageGain/$rsiAverageLoss);
//                    $decisionInput['rsi'] = round(100 - (100 / (1 + ($rs))), 2);
//                }
//
//                $newDecision = $this->decision->emaDecision($decisionInput, $this->currentPosition);

                /*************************************************************
                 * END STRATEGY CALCULATIONS
                 *************************************************************/

//                if ($newDecision['newPosition'] == "long") {
//                    $this->newLongPosition();
//                }
//                elseif ($newDecision['newPosition'] == "short") {
//                    $this->newShortPosition();
//                }

 //               $this->logFiveMinuteCheck($decisionInput, $newDecision);
            }
        }
    }

    public function macdDivergenceStrategy() {

        $hourlyRates = \App\Model\CurrencyHourly::where('currency_hourly_id', '=', 1)->orderBy('id', 'desc')->take(53)->get();
        $hourlyRates = $hourlyRates->toArray();

        $rates = array_map(function($rate) {
            return $rate['ask_rate'];
        }, $hourlyRates);

        $rates = array_map('floatval', $rates);

        $macdRates = [];

        foreach ($rates as $rate) {
            $macdRates[] = $rate*1000;
        }

        $macdData = trader_macd($macdRates, 12, 26, 9);

        $stochValues = array_slice($rates, 39, 14);
        $stochValues[13] = 1.13;

        $ratesLinearRegression = trader_linearreg_slope($rates);

        $stochastic = trader_stoch ( [max($stochValues)] , [min($stochValues)] , [end($stochValues)]);
        $stochastic = $stochastic[0];
    }

    public function phantomCrossover($rates) {
        $this->oanda = new \App\Services\Oanda();

        $this->oanda->accountId = '3577742';
        $this->oanda->strategyId = 1;

        $this->oanda->exchange = 'EUR_USD';

        if (!isset($this->phantomOpenPositions)) {
            $this->phantomOpenPositions = $this->oanda->getPositions();
        }


        $shortEma = trader_ema($rates, 5);

        $longEma = trader_ema($rates, 10);

        $rsi = trader_rsi($rates, 10);

        $linearRegression = trader_linearreg_slope($rsi, 5);

        $crossover = $this->indicators->checkCrossover($shortEma, $longEma);

        $decisionInput = [
            'emaCrossover' => $crossover,
            'rsi' => $rsi,
            'rsiLinearRegression' => $linearRegression
        ];

        $decision = $this->decision->phantomDecision($decisionInput, $this->currentPosition);

        if ($decision == 'long') {
            $this->newLongPosition();
        }
        elseif ($decision == 'long') {
            $this->newShortPosition();
        }

    }
}
