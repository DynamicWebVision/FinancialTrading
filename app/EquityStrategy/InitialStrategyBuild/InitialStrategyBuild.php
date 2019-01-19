<?php

/********************************************
RsiPbHmaTrSl Strategy System under RsiPullback Strategy
Created at: 01/14/19by Brian O'Neill
Description: This is is the Rsi pullback strategy with better stop loss rules from the beginning.
 ********************************************/

namespace App\EquityStrategy\InitialStrategyBuild;
use \Log;
use \App\IndicatorEvents\HullMovingAverage;
use \App\IndicatorEvents\RsiEvents;
use \App\IndicatorEvents\TrueRange;
use \App\IndicatorEvents\EmaEvents;

class RsiPbHmaTrSl extends \App\ForexStrategy\Strategy  {

    public $hmaLength;
    public $rsiLength;
    public $rsiCutoff;
    public $trueRangeLength = 14;
    public $trueRangeMultiplier;
    public $emaLength;

    public function setEntryIndicators() {
        $hmaEvents = new \App\IndicatorEvents\HullMovingAverage;
        $hmaEvents->strategyLogger = $this->strategyLogger;

        $rsiEvents = new \App\IndicatorEvents\RsiEvents;
        $rsiEvents->strategyLogger = $this->strategyLogger;

        $trueRange = new \App\IndicatorEvents\TrueRange;
        $trueRange->strategyLogger = $this->strategyLogger;

        $emaEvents = new \App\IndicatorEvents\EmaEvents;
        $emaEvents->strategyLogger = $this->strategyLogger;


        $this->decisionIndicators['priceAboveBelowHma'] = $hmaEvents->hmaPriceAboveBelowHma($this->rates['simple'], $this->hmaLength);

        $this->decisionIndicators['rsiOutsideLevel'] = $rsiEvents->outsideLevel(
            $this->rates['simple'], $this->rsiLength, $this->rsiCutoff);

        $this->stopLossPipAmount = $trueRange->getStopLossPipValue($this->rates['full'], $this->trueRangeLength, $this->exchange->pip, $this->trueRangeMultiplier);

        $this->decisionIndicators['emaPriceAboveBelow'] = $emaEvents->priceAboveBelowEma($this->rates['simple'], $this->emaLength);

    }

    public function getEntryDecision() {
        $this->setEntryIndicators();
        $this->strategyLogger->logIndicators($this->decisionIndicators);

        if (
            $this->decisionIndicators['priceAboveBelowHma'] == 'above'
            && $this->decisionIndicators['rsiOutsideLevel'] == 'overBoughtShort'
            && $this->decisionIndicators['emaPriceAboveBelow'] == 'below'
        ) {
            $this->newLongPosition();
        }
        elseif (
            $this->decisionIndicators['priceAboveBelowHma'] == 'below'
            && $this->decisionIndicators['rsiOutsideLevel'] == 'overBoughtLong'
            && $this->decisionIndicators['emaPriceAboveBelow'] == 'above'
        ) {
            $this->newShortPosition();
        }
    }

    public function inPositionDecision() {
        $emaEvents = new \App\IndicatorEvents\EmaEvents;
        $emaEvents->strategyLogger = $this->strategyLogger;


        $this->strategyLogger->logIndicators($this->decisionIndicators);

        $this->decisionIndicators['emaPriceAboveBelow'] = $emaEvents->priceAboveBelowEma($this->rates['simple'], $this->emaLength);

        if ($this->openPosition['side'] == 'long') {
            if ( $this->decisionIndicators['emaPriceAboveBelow'] == 'above' ) {
                $this->strategyLogger->logMessage("WE NEED TO CLOSE", 1);
                $this->closePosition();
            }
        }
        elseif ($this->openPosition['side'] == 'short') {
            if ( $this->decisionIndicators['emaPriceAboveBelow'] == 'below' ) {
                $this->strategyLogger->logMessage("WE NEED TO CLOSE", 1);
                $this->closePosition();
            }
        }
    }

    public function checkForNewPosition() {
        $this->setOpenPosition();

        if (!$this->openPosition) {
            $this->decision = $this->getEntryDecision();
        }
        else {
            $this->inPositionDecision();
        }
    }
}