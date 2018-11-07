<?php namespace App\Strategy;

class Cowabunga extends \App\Strategy\Strategy  {

    public $oanda;
    public $utility;

    public $fifteenMinuteRates;

    public $passedOuter;


    public function log($indicators, $newDecision) {

        if (!$this->backtesting) {
//            $log = new \App\Model\Logs\CowabungaLog;
//
//            $log->rate_date_time = date('Y-m-d H:i:s');
//
//            $log->current_position_status = $this->livePosition;
//
//            $log->four_hour_today_short_ema = end($this->decisionIndicators['fourHourShortEma']);
//            $log->four_hour_yesterday_short_ema = $this->decisionIndicators['fourHourShortEma'][count($this->decisionIndicators['fourHourShortEma'])-2];
//
//            $log->four_hour_today_long_ema = end($this->decisionIndicators['fourHourLongEma']);
//            $log->four_hour_yesterday_long_ema = $this->decisionIndicators['fourHourLongEma'][count($this->decisionIndicators['fourHourLongEma'])-2];
//
//            $log->four_hour_rsi = $this->decisionIndicators['fourHourRsi'];
//
////            $log->four_hour_stoch_linear_regression_2 = end($this->decisionIndicators['fourHourStochLinRegSlopeTwo']);
//            $log->four_hour_stoch_linear_regression_5 = round(end($this->decisionIndicators['fourHourStochLinRegSlopeFive']), 5);
//
//            $log->pass_outer = $this->passedOuter;
//
//            $log->fifteen_minute_today_short_ema = end($this->decisionIndicators['fifteenMinuteShortEma']);
//            $log->fifteen_minute_yesterday_short_ema = $this->decisionIndicators['fifteenMinuteShortEma'][count($this->decisionIndicators['fifteenMinuteShortEma'])-2];
//
//            $log->fifteen_minute_today_long_ema = end($this->decisionIndicators['fifteenMinuteLongEma']);
//            $log->fifteen_minute_yesterday_long_ema = $this->decisionIndicators['fifteenMinuteLongEma'][count($this->decisionIndicators['fifteenMinuteLongEma'])-2];
//
//            $log->fifteen_minute_ema_crossover = $this->decisionIndicators['fifteenMinuteEmaCrossOver'];
//
//            $log->fifteen_minute_rsi = $this->decisionIndicators['fifteenMinuteRsi'];
//
//            //$log->fifteen_minute_stoch_linear_regression_2 = end($this->decisionIndicators['fifteenMinuteStochLinRegSlopeTwo']);
//            $log->fifteen_minute_stoch_linear_regression_5 = round(end($this->decisionIndicators['fifteenMinuteStochLinRegSlopeFive']), 5);
//            $log->fifteen_minute_stochastic = end($this->decisionIndicators['fifteenMinuteStochastic']['slow']['k']);
//
//            $log->fifteen_minute_macd_trend = $this->decisionIndicators['fifteenMinuteMacdPositiveNegativeTrend'];
//
//            $log->decision = $newDecision;
//
//            $log->exchange = $this->exchange->id;
//
//            $log->save();
        }
    }

    public function logOpenPosition() {
        $log = new \App\Model\Logs\CowabungaLog;

        $log->rate_date_time = date('Y-m-d H:i:s');

        $log->current_position_status = $this->livePosition;

        $log->save();
    }

    //Whether you will enter a new position with Phantom
    public function decision($indicators) {

        //Four Hour Conditions For Long
        //Short Ema Must be greater than Long Ema
        //RSI Must be above 50
        //Stochastics must be trending up
        if (end($this->decisionIndicators['fourHourShortEma']) > end($this->decisionIndicators['fourHourLongEma']) && $this->decisionIndicators['fourHourRsi'] > 50 &&
            $this->decisionIndicators['fourHourStochLinRegSlopeFive']['m'] > 0) {

            $this->passedOuter = 1;

            //Fifteen Minute Conditions For Long
            //Ema must have a "Crossed Above" event, with the Short Ema cross above the Long Ema
            //RSI Must be greater than 50
            //Stoch must not be in overbought territory and trending in a positive direction
            //Macd must have either just crossed from negative to positive, or be positive trending up
            if ($this->decisionIndicators['fifteenMinuteEmaCrossOver'] == "crossedAbove" && $this->decisionIndicators['fifteenMinuteRsi'] > 50 && end($this->decisionIndicators['fifteenMinuteStochastic']['slow']['k']) < 80
                && $this->decisionIndicators['fifteenMinuteStochLinRegSlopeFive']['m'] > 0 && $this->decisionIndicators['fifteenMinuteMacdPositiveNegativeTrend'] == "passedLong") {
                return "long";
            }
            else {
                return "none";
            }
        }
        //Four Hour Conditions For Short
        //Short Ema Must be less than Long Ema
        //RSI Must be below 50
        //Stochastics must be trending down
        elseif (end($this->decisionIndicators['fourHourShortEma']) < end($this->decisionIndicators['fourHourLongEma']) && $this->decisionIndicators['fourHourRsi'] < 50 &&
            $this->decisionIndicators['fourHourStochLinRegSlopeFive']['m'] < 0) {

            $this->passedOuter = -1;

            //Fifteen Minute Conditions For Short
            //Ema must have a "Crossed Below" event, with the Short Ema cross below the Long Ema
            //RSI Must be less than 50
            //Stoch must not be in oversold territory and trending in a negative direction
            //Macd must have either just crossed from positive to negative, or be trending negative, down
            if ($this->decisionIndicators['fifteenMinuteEmaCrossOver'] == "crossedBelow" && $this->decisionIndicators['fifteenMinuteRsi'] < 50 && end($this->decisionIndicators['fifteenMinuteStochastic']['slow']['k']) > 20
                && $this->decisionIndicators['fifteenMinuteStochLinRegSlopeFive']['m'] < 0 && $this->decisionIndicators['fifteenMinuteMacdPositiveNegativeTrend'] == "passedShort") {
                return "short";
            }
            else {
                return "none";
            }
        }
        else {
            return "none";
        }
    }

    public function runStrategy($fourHourRates, $fifteenMinuteRates) {
        $this->utility = new \App\Services\Utility();

        $this->oanda->accountId = $this->accountId;

        $this->oanda->strategyId = 2;

        $this->oanda->positionAmount = $this->positionAmount;

        $this->oanda->exchange = $this->exchange->exchange;

        $this->checkDbPosition();

        $this->livePosition = $this->checkOpenPosition();

        if ($this->livePosition != $this->currentPosition['current_position']) {
            $this->updatePositionToNone();
        }

        if ($this->livePosition == 0) {

            $this->passedOuter = 0;

            $this->fifteenMinuteRates = $fifteenMinuteRates;

            //Four Hour Stuff
            $this->decisionIndicators['fourHourShortEma'] = $this->indicators->ema($fourHourRates, 5);
            $this->decisionIndicators['fourHourLongEma'] = $this->indicators->ema($fourHourRates, 10);

            $this->decisionIndicators['fourHourRsi'] = $this->indicators->rsi($fourHourRates, 9);

            $stochastic = $this->indicators->stochastic($fourHourRates, 10, 3, 3);

            $this->decisionIndicators['fourHourStochLinRegSlopeFive'] = $this->indicators->linearRegression($stochastic['slow']['d'], 5);
            //$this->decisionIndicators['fourHourStochLinRegSlopeTwo'] = $this->indicators->linearRegression($stochastic, 2);

            //Fifteen Minute Stuff
            $this->decisionIndicators['fifteenMinuteShortEma'] = $this->indicators->ema($fifteenMinuteRates, 5);
            $this->decisionIndicators['fifteenMinuteLongEma'] = $this->indicators->ema($fifteenMinuteRates, 10);

            $this->decisionIndicators['fifteenMinuteEmaCrossOver'] = $this->indicators->checkCrossover($this->decisionIndicators['fifteenMinuteShortEma'], $this->decisionIndicators['fifteenMinuteLongEma']);

            $this->decisionIndicators['fifteenMinuteStochastic'] = $this->indicators->stochastic($fifteenMinuteRates, 10, 3, 3);

            $this->decisionIndicators['fifteenMinuteStochLinRegSlopeFive'] = $this->indicators->linearRegression($this->decisionIndicators['fifteenMinuteStochastic']['slow']['d'], 5);

            $this->decisionIndicators['fifteenMinuteRsi'] = $this->indicators->rsi($fifteenMinuteRates, 9);

            $this->decisionIndicators['fifteenMinuteMacd'] = $this->indicators->macd($fifteenMinuteRates, 12, 26, 9);

            $this->decisionIndicators['fifteenMinuteMacdPositiveNegativeTrend'] = $this->indicators->positiveNegativeIncreaseValueCheck($this->decisionIndicators['fifteenMinuteMacd']['histogram']);

            $this->decision = $this->decision($this->indicators);

            $this->log($this->indicators, $this->decision);

            if ($this->checkOpenPositionsThreshold()) {
                $this->calculatePositionAmount();
                if ($this->decision == 'long') {
                    $positionHelper = new \App\Services\PositionHelpers();

                    $this->takeProfitPipAmount = $positionHelper->closestFiftyOneHundredUp($this->currentPriceData->ask, $this->exchange->pip, 50);

                    if ($this->takeProfitPipAmount < 20) {
                        $this->takeProfitPipAmount = 20;
                    }
                    $this->newLongPosition();
                }
                elseif ($this->decision == 'short') {
                    $positionHelper = new \App\Services\PositionHelpers();
                    $this->takeProfitPipAmount = $positionHelper->closestFiftyOneHundredDown($this->currentPriceData->ask, $this->exchange->pip, 50);

                    if ($this->takeProfitPipAmount < 20) {
                        $this->takeProfitPipAmount = 20;
                    }

                    $this->newShortPosition();
                }
            }
        }
        else {
            $this->logOpenPosition();
        }
    }
}
