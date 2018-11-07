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


namespace App\Strategy\TwoTier\PivotPoints;
use \Log;
use \App\IndicatorEvents\Momentum;

class HmaMomentumConfirm extends \App\Strategy\Strategy  {

    public $oanda;

    public $hmaLength;
    public $hmaSlopeMin;

    public $pivotPointVariable;

    //Whether you will enter a new position
    public function decision() {
        Log::info($this->runId.': New Position Decision Indicators: '.PHP_EOL.' '.$this->logIndicators());

        //Simple MACD Crossover
        if ($this->decisionIndicators['hmaSlopePass'] == "long" && $this->decisionIndicators['pivotPoints']['r2'] <= $this->decisionIndicators['currentRate'] && $this->decisionIndicators['pivotPoints']['r2'] > $this->decisionIndicators['previousRate']) {
            Log::warning($this->runId.': NEW LONG POSITION');
            return "long";
        }
        elseif ($this->decisionIndicators['hmaSlopePass'] == "short" && $this->decisionIndicators['pivotPoints']['s2'] >= $this->decisionIndicators['currentRate'] && $this->decisionIndicators['pivotPoints']['s2'] < $this->decisionIndicators['previousRate']) {
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

        $ratePips = $this->getRatesInPips($this->slowRates['simple']);

        $this->decisionIndicators['hmaSlopePass'] = $momentumEvents->hmaMeetsCutoffPipSlope($this->hmaLength, $this->hmaSlopeMin, $ratePips);

        $this->decisionIndicators['pivotPoints'] = $this->indicators->pivotPoints(end($this->slowRates['full']));

        $this->decisionIndicators['previousRate'] = $this->slowRates['simple'][count($this->slowRates['simple'])-2];
        $this->decisionIndicators['currentRate'] = end($this->slowRates['simple']);

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
