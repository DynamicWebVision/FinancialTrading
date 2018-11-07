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


namespace App\Strategy\TwoTier\ThreeMa;
use \Log;
use \App\IndicatorEvents\Momentum;

class HullMaNoOtherConfirm extends \App\Strategy\Strategy  {

    public $oanda;

    public $fifteenMinuteRates;
    public $passedOuter;

    public $adxCutoff;
    public $adxPeriodLength;

    public $fastMaLength;
    public $mediumMaLength;
    public $slowMaLength;

    //Whether you will enter a new position
    public function decision() {
        Log::info($this->runId.': New Position Decision Indicators: '.PHP_EOL.' '.$this->logIndicators());

        //Simple MACD Crossover
        if ($this->decisionIndicators['slowThreeLine'] == "long" && $this->decisionIndicators['fastThreeLine'] == "long") {
            Log::warning($this->runId.': NEW LONG POSITION');
            return "long";
        }
        elseif ($this->decisionIndicators['slowThreeLine'] == "short" && $this->decisionIndicators['fastThreeLine'] == "short") {
            Log::warning($this->runId.': NEW SHORT POSITION');
            return "short";
        }
        else {
            Log::info($this->runId.': Failed Ema Breakthrough');
            return "none";
        }
    }

    public function checkForNewPosition() {
        $momentumEvents = new Momentum();

        $this->decisionIndicators['slowThreeLine'] = $momentumEvents->threeMAHull($this->fastMaLength, $this->mediumMaLength, $this->slowMaLength, $this->slowRates);

        $this->decisionIndicators['fastThreeLine'] = $momentumEvents->threeMAHull($this->fastMaLength, $this->mediumMaLength, $this->slowMaLength, $this->rates);

        $this->decision = $this->decision();

        if ($this->checkOpenPositionsThreshold()) {
            if ($this->decision == 'long') {
                $this->newLongOrStayInPosition();
            }
            elseif ($this->decision == 'short') {
                $this->newShortOrStayInPosition();
            }
            elseif ($this->decision == 'none') {
                $this->closePosition();
            }
        }
    }
}
