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


namespace App\ForexStrategy\Stochastic;
use \Log;


use \App\IndicatorEvents\StochasticEvents;
use \App\IndicatorEvents\AdxEvents;


class StochRevLowAdx extends \App\ForexStrategy\Strategy  {

    public $oanda;

    public $adxLength = 14;
    public $adxUndersoldThreshold = 20;

    public $stochKLength = 14;
    public $stochSmoothingSlow = 3;
    public $stochSmoothingFull = 3;
    public $stochOverboughtCutoff = 20;

    //Whether you will enter a new position
    public function decision() {
        Log::info($this->runId.': New Position Decision Indicators: '.PHP_EOL.' '.$this->logIndicators());

        //Simple MACD Crossover
        if ($this->decisionIndicators['adxBelowThreshold'] && $this->decisionIndicators['stochReversal'] == 'long') {
            $this->strategyLogger->logDecisionMade('long');
            return "long";
        }
        elseif ($this->decisionIndicators['adxBelowThreshold'] && $this->decisionIndicators['stochReversal'] == 'short') {
            $this->strategyLogger->logDecisionMade('short');
            return "short";
        }
        else {
            $this->strategyLogger->logDecisionMade('none');
            return "none";
        }
    }

    public function checkForNewPosition() {
        $this->utility = new \App\Services\Utility();

        $stochasticEvents = new StochasticEvents();
        $stochasticEvents->strategyLogger = $this->strategyLogger;

        $adxEvents = new AdxEvents();
        $adxEvents->strategyLogger = $this->strategyLogger;

        $this->decisionIndicators['adxBelowThreshold'] = $adxEvents->adxBelowThreshold($this->rates['full'], $this->adxLength, $this->adxUndersoldThreshold);

        $this->decisionIndicators['stochReversal'] = $stochasticEvents->fastKMovesOutOfOverboughtForReversal($this->rates['full'], $this->stochKLength, $this->stochSmoothingSlow, $this->stochSmoothingFull, $this->stochOverboughtCutoff);

        $this->decision = $this->decision();

        if ($this->checkOpenPositionsThreshold()) {
            if ($this->decision == 'long') {
                $this->newLongOrStayInPosition();
            }
            elseif ($this->decision == 'short') {
                $this->newShortOrStayInPosition();
            }
        }
    }
}
