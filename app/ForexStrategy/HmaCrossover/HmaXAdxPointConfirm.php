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


namespace App\ForexStrategy\HmaCrossover;
use \Log;

class HmaXAdxPointConfirm extends \App\ForexStrategy\Strategy  {

    public $oanda;

    public $fifteenMinuteRates;
    public $passedOuter;

    public $hmaFastLength;
    public $hmaSlowLength;

    public $adxCutoff;
    public $adxPeriodLength;

    //Whether you will enter a new position
    public function decision() {
        Log::info($this->runId.': New Position Decision Indicators: '.PHP_EOL.' '.$this->logIndicators());

        //Simple MACD Crossover
        if ($this->decisionIndicators['hmaCrossover'] == "crossedAbove" && end($this->decisionIndicators['adx']) >= $this->adxCutoff) {
            Log::warning($this->runId.': NEW LONG POSITION');
            return "long";
        }
        elseif ($this->decisionIndicators['hmaCrossover'] == "crossedBelow" && end($this->decisionIndicators['adx']) >= $this->adxCutoff) {
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

        $this->decisionIndicators['fastHma'] = $this->indicators->hma($this->rates['simple'], $this->hmaFastLength);
        $this->decisionIndicators['slowHma'] = $this->indicators->hma($this->rates['simple'], $this->hmaSlowLength);

        $this->decisionIndicators['adx'] = $this->indicators->adx($this->rates['full'], $this->adxPeriodLength);

        $this->decisionIndicators['hmaCrossover'] = $this->indicators->checkCrossoverGoBackTwo($this->decisionIndicators['fastHma'], $this->decisionIndicators['slowHma']);

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
