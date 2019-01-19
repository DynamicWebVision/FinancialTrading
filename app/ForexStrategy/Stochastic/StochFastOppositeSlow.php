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

use \App\IndicatorEvents\Momentum;
use \App\IndicatorEvents\StochasticEvents;


class StochFastOppositeSlow extends \App\ForexStrategy\Strategy  {

    public $oanda;

    public $hmaLength;
    public $hmaSlopeMin;

    public $fastKLength = 5;
    public $fastSmoothingSlow = 2;
    public $fastSmoothingFull = 2;
    public $fastStochOverboughtCutoff = 20;

    public $slowKLength = 21;
    public $slowSmoothingSlow = 10;
    public $slowSmoothingFull = 4;
    public $slowStochOverboughtCutoff = 20;

    //Whether you will enter a new position
    public function decision() {
        Log::info($this->runId.': New Position Decision Indicators: '.PHP_EOL.' '.$this->logIndicators());

        //Simple MACD Crossover
        if ($this->decisionIndicators['fastStochastic'] == 'long' && $this->decisionIndicators['slowStochastic'] == 'overBoughtLong' && $this->decisionIndicators['hmaSlopePass'] == 'long') {
            $this->strategyLogger->logDecisionMade('long');
            return "long";
        }
        elseif ($this->decisionIndicators['fastStochastic'] == 'short' && $this->decisionIndicators['slowStochastic'] == 'overBoughtShort' && $this->decisionIndicators['hmaSlopePass'] == 'short') {
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

        $momentum = new Momentum();
        $momentum->strategyLogger = $this->strategyLogger;
        $stochasticEvents = new StochasticEvents();
        $stochasticEvents->strategyLogger = $this->strategyLogger;

        $ratePips = $this->getRatesInPips($this->rates['simple']);

        $this->decisionIndicators['hmaSlopePass'] = $momentum->hmaMeetsCutoffPipSlope($this->hmaLength, $this->hmaSlopeMin, $ratePips);

        $this->decisionIndicators['fastStochastic'] = $stochasticEvents->fastKMovesOutOfOverboughtForReversal($this->rates['full'], $this->fastKLength, $this->fastSmoothingSlow, $this->fastSmoothingFull, $this->fastStochOverboughtCutoff);

        $this->decisionIndicators['slowStochastic'] = $stochasticEvents->overBoughtCheck($this->rates['full'], $this->slowKLength, $this->slowSmoothingSlow, $this->slowSmoothingFull, $this->slowStochOverboughtCutoff);

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
