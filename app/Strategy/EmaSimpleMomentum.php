<?php
/**
 * Created by PhpStorm.
 * User: diego.rodriguez
 * Date: 8/31/17
 * Time: 5:11 PM
 * Description: This is a basic strategy that uses an EMA break through with a longer (100 EMA to start) and short EMA (50 to start).
 *
 * Decisions:
 * BUY      ---> Short EMA crosses above Long EMA
 * Short    ---> Short EMA crosses below Long EMA
 *
 *During Open Position:
 * -If another breakthrouch occurs we will close the current position and open a new, opposite one
 * -If the linear regression slope of the fast EMA is opposite of the position direction, a tighter stop loss will be added.
 */


namespace App\Strategy;
use \Log;

class EmaSimpleMomentum extends \App\Strategy\Strategy  {

    public $oanda;
    public $utility;

    public $fifteenMinuteRates;

    public $passedOuter;

    //Whether you will enter a new position
    public function decision($indicators) {
        Log::info($this->runId.': New Position Decision Indicators '.json_encode($indicators));

        //Simple MACD Crossover
        if ($this->decisionIndicators['emaCrossover'] == "crossedAbove") {
            Log::warning($this->runId.': NEW LONG POSITION');
            return "long";
        }
        elseif ($this->decisionIndicators['emaCrossover'] == "crossedBelow") {
            Log::warning($this->runId.': NEW SHORT POSITION');
            return "short";
        }
        else {
            Log::info($this->runId.': Failed Ema Breakthrough');
            return "none";
        }
    }

    public function checkForNewPosition($rates) {
        $this->utility = new \App\Services\Utility();

        $this->decisionIndicators['fastEma'] = $this->indicators->ema($rates, 50);

        $this->decisionIndicators['slowEma'] = $this->indicators->ema($rates, 100);

        $this->decisionIndicators['emaCrossover'] = $this->indicators->checkCrossover($this->decisionIndicators['fastEma'], $this->decisionIndicators['slowEma']);

        $this->decision = $this->decision($indicators);

        if ($this->checkOpenPositionsThreshold()) {
            $this->calculatePositionAmount();
            if ($this->decision == 'long') {
                $this->newLongPosition();
            }
            elseif ($this->decision == 'short') {
                $this->newShortPosition();
            }
        }
    }
}
