<?php namespace App\ForexStrategy;
use \Log;
class MacdOuter extends \App\ForexStrategy\Strategy  {

    public $oanda;
    public $utility;

    public $fifteenMinuteRates;

    public $passedOuter;


    public function log($indicators, $newDecision) {
        if (!$this->backtesting) {
//            $log = new \App\Model\Logs\MacdOuterLog;
//
//            $log->rate_date_time = date('Y-m-d H:i:s');
//
//            $log->current_position_status = $this->livePosition;
//
//            $log->slow_outer_macd_macd = end($this->decisionIndicators['outerMacd']['macd']);
//            $log->slow_outer_macd_signal = end($this->decisionIndicators['outerMacd']['signal']);
//
//            $log->slow_ema_lin_reg_slope = $this->decisionIndicators['outerEmaLinearRegression']['m'];
//
//            $log->macd_macd = end($this->decisionIndicators['fastMacd']['macd']);
//            $log->macd_signal = end($this->decisionIndicators['fastMacd']['signal']);
//
//            $log->prev_outer_macd_macd = $this->decisionIndicators['fastMacd']['macd'][count($this->decisionIndicators['fastMacd']['macd'])-2];
//            $log->prev_outer_macd_signal = $this->decisionIndicators['fastMacd']['signal'][count($this->decisionIndicators['fastMacd']['signal'])-2];
//
//            $log->macd_crossover = $this->decisionIndicators['fastMacdCrossover'];
//
//            $log->task = 'checkForNew';
//
//            $log->decision = $newDecision;
//
//            $log->exchange = $this->exchange->id;
//
//            $log->save();
        }
    }

    public function logTrailingStopCheck($indicators, $newDecision) {
        if (!$this->backtesting) {
//            $log = new \App\Model\Logs\MacdOuterLog;
//
//            $log->rate_date_time = date('Y-m-d H:i:s');
//
//            $log->current_position_status = $this->livePosition;
//
//            $log->task = 'checkForNew';
//
//            $log->decision = $newDecision;
//
//            $log->short_ema_lin_reg_slope = $this->decisionIndicators['shortEmaLinearRegression'];
//
//            $log->exchange = $this->exchange->id;
//
//            $log->save();
        }
    }

    public function logOpenPosition() {
        if (!$this->backtesting) {
            $log = new \App\Model\Logs\MacdOuterLog;

            $log->rate_date_time = date('Y-m-d H:i:s');

            $log->current_position_status = $this->livePosition;

            $log->save();
        }
    }

    //Whether you will enter a new position with Phantom
    public function decision($indicators) {
        Log::info($this->runId.': New Position Decision Indicators '.json_encode($indicators));

        //Outer Check
        //Macd Above Signal and Slope of EMA trending up
        if (end($this->decisionIndicators['outerMacd']['macd']) > end($this->decisionIndicators['outerMacd']['signal']) && $this->decisionIndicators['outerEmaLinearRegression']['m'] > 0) {
            Log::info($this->runId.': Passed Long Outer');
            //Simple MACD Crossover
            if ($this->decisionIndicators['fastMacdCrossover'] == "crossedAbove") {
                Log::warning($this->runId.': NEW LONG POSITION');
                return "long";
            }
            else {
                Log::info($this->runId.': Failed Long Quick Criteria');
                return "none";
            }
        }
        //Short
        //Macd Crossed Below
        elseif (end($this->decisionIndicators['outerMacd']['macd']) < end($this->decisionIndicators['outerMacd']['signal']) && $this->decisionIndicators['outerEmaLinearRegression']['m'] < 0) {
            Log::info($this->runId.': Passed Short Outer');

            if ($this->decisionIndicators['fastMacdCrossover'] == "crossedBelow") {
                Log::warning($this->runId.': NEW SHORT POSITION');

                return "short";
            }
        }
        else {
            Log::info($this->runId.': Failed Slow Criteria');
            return "none";
        }
    }

    public function addTrailingStopDecision($indicators) {
        Log::info($this->runId.': Trailing Stop Decision Indicators \n'.json_encode($indicators));

        if ($this->fullPositionInfo['side'] == "buy") {
            if ($this->decisionIndicators['shortEmaLinearRegression']['m'] < 0) {
                Log::warning($this->runId.': ADD TRAILING STOP TO LONG POSITION PASS');
                return true;
            }
            else {
                Log::info($this->runId.': add trailing stop to long position fail');
                return false;
            }
        }
        else {
            if ($this->decisionIndicators['shortEmaLinearRegression']['m'] > 0) {
                Log::warning($this->runId.': ADD TRAILING STOP TO SHORT POSITION PASS');
                return true;
            }
            else {
                Log::info($this->runId.': Add trailing stop to short position fail');
                return false;
            }
        }
    }

    public function checkForNewPosition($quickRates, $slowRates) {

        $this->oanda->positionAmount = $this->positionAmount;
        $this->oanda->exchange = $this->exchange->exchange;

        //Get Check For New Indicators
        $this->decisionIndicators['outerMacd'] = $this->indicators->macd($slowRates, 12, 26, 9);

        $this->decisionIndicators['outerEma'] = $this->indicators->ema($slowRates, 5);

        $this->decisionIndicators['outerEmaLinearRegression'] = $this->indicators->linearRegression($this->decisionIndicators['outerEma'], 3);

        $this->decisionIndicators['fastMacd'] = $this->indicators->macd($quickRates, 12, 26, 9);

        $this->decisionIndicators['fastMacdCrossover'] = $this->indicators->checkCrossover($this->decisionIndicators['fastMacd']['macd'], $this->decisionIndicators['fastMacd']['signal']);

        $this->decision = $this->decision($indicators);


        if ($this->checkOpenPositionsThreshold()) {
            $this->calculatePositionAmount();
            if ($this->decision == 'long') {
                $this->closePosition();
                $this->newLongPosition();
            }
            elseif ($this->decision == 'short') {
                $this->closePosition();
                $this->newShortPosition();
            }
        }
        else {
            $this->logOpenPosition();
        }
    }

    public function checkToAddTrailingStop($quickRates, $slowRates) {
        $this->trailingStopPipAmount = $this->optionalTrailingStopAmount;

        $this->decisionIndicators['shortEma'] = $this->indicators->ema($quickRates, 5);

        $this->decisionIndicators['shortEmaLinearRegression'] = $this->indicators->linearRegression($this->decisionIndicators['shortEma'], 3);

        $addTrailingStop = $this->addTrailingStopDecision($indicators);

        if ($addTrailingStop) {
            $this->addTrailingStopToPosition($this->trailingStopPipAmount);
        }
    }
}
