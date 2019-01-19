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


namespace App\ForexStrategy\EmaMomentum;
use \Log;

class EmaMomentumAdxTwoTierConfirm extends \App\ForexStrategy\Strategy  {

    public $oanda;

    public $fifteenMinuteRates;
    public $passedOuter;

    public $adxHardCutoff;
    public $adxUpwardSlopeCutoff;
    public $adxUpwardSlopeMin;
    public $adxPeriodLength;

    //Whether you will enter a new position
    public function decision() {
        Log::info($this->runId.': New Position Decision Indicators: '.PHP_EOL.' '.$this->logIndicators());

        //Simple MACD Crossover
        if ($this->decisionIndicators['emaCrossover'] == "crossedAbove" && $this->decisionIndicators['adxPass']) {
            Log::warning($this->runId.': NEW LONG POSITION');
            return "long";
        }
        elseif ($this->decisionIndicators['emaCrossover'] == "crossedBelow" && $this->decisionIndicators['adxPass']) {
            Log::warning($this->runId.': NEW SHORT POSITION');
            return "short";
        }
        else {
            Log::info($this->runId.': Failed Ema Breakthrough');
            return "none";
        }
    }

    public function checkForNewPosition() {
        $this->utility = new \App\Services\Utility();

        $this->decisionIndicators['fastEma'] = $this->indicators->ema($this->rates['simple'], $this->fastEmaLength);
        $this->decisionIndicators['slowEma'] = $this->indicators->ema($this->rates['simple'], $this->slowEmaLength);

        $this->decisionIndicators['adx'] = $this->indicators->adx($this->rates['full'], $this->adxPeriodLength);
        $this->decisionIndicators['adxPass'] = $this->indicators->upwardSlopeOrHardCutoff($this->decisionIndicators['adx'], $this->adxHardCutoff, $this->adxUpwardSlopeCutoff, $this->adxUpwardSlopeMin);

        $this->decisionIndicators['emaCrossover'] = $this->indicators->checkCrossoverGoBackTwo($this->decisionIndicators['fastEma'], $this->decisionIndicators['slowEma']);

        $this->decision = $this->decision();

        if ($this->checkOpenPositionsThreshold()) {
            $this->calculatePositionAmount();
            if ($this->decision == 'long') {
                $this->newLongOrStayInPosition();
            }
            elseif ($this->decision == 'short') {
                $this->newShortOrStayInPosition();
            }
        }
    }
}
