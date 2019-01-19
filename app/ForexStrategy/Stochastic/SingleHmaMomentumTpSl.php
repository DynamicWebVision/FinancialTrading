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


class SingleHmaMomentumTpSl extends \App\ForexStrategy\Strategy  {

    public $oanda;

    public $hmaLength;
    public $hmaSlopeMin;

    public $kLength;
    public $smoothingSlow;
    public $smoothingFull;
    public $stochOverboughtCutoff = 20;

    //Whether you will enter a new position
    public function decision() {
        Log::info($this->runId.': New Position Decision Indicators: '.PHP_EOL.' '.$this->logIndicators());

        //Simple MACD Crossover
        if ($this->decisionIndicators['stochastic'] == 'long' && $this->decisionIndicators['hmaSlopePass'] == 'long') {
            $this->strategyLogger->logDecisionMade('long');
            return "long";
        }
        elseif ($this->decisionIndicators['stochastic'] == 'short' && $this->decisionIndicators['hmaSlopePass'] == 'short') {
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

        $this->decisionIndicators['stochastic'] = $stochasticEvents->fastKMovesOutOfOverbought($this->rates['full'], $this->kLength, $this->smoothingSlow, $this->smoothingFull, $this->stochOverboughtCutoff);

        $this->decision = $this->decision();

        if ($this->checkOpenPositionsThreshold()) {
            if ($this->decision == 'long') {
                $this->newLongOrStayInPosition();
            }
            elseif ($this->decision == 'short') {
                $this->newShortOrStayInPosition();
            }
//            elseif ($this->decision == 'none') {
//                $this->closePosition();
//            }
        }
    }
}
