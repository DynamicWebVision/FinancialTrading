<?php

/********************************************
RsiPbKillIfWrong Strategy System under RsiPullback Strategy
Created at: 04/12/19by Brian O'Neill
Description: This is the Rsi pullback strategy. The strategy will be killed if it's not going in the right direction.
********************************************/

namespace App\ForexStrategy\RsiPullback;
use \Log;
use \App\IndicatorEvents\RsiEvents;
use \App\IndicatorEvents\EmaEvents;
use \App\IndicatorEvents\PriceMovements;

class RsiPbKillIfWrong extends \App\ForexStrategy\Strategy  {

    public $rsiLength;
    public $rsiCutoff;
    public $emaLength;
    public $emaLengthSlow;
    public $withoutProfPeriodsCutoffs;

    public function setEntryIndicators() {
        $rsiEvents = new \App\IndicatorEvents\RsiEvents;
        $rsiEvents->strategyLogger = $this->strategyLogger;

        $emaEvents = new \App\IndicatorEvents\EmaEvents;
        $emaEvents->strategyLogger = $this->strategyLogger;


        $this->decisionIndicators['rsiOutsideLevel'] = $rsiEvents->outsideLevel(
            $this->rates['simple'], $this->rsiLength, $this->rsiCutoff);

        $this->decisionIndicators['emaPriceAboveBelow'] = $emaEvents->priceAboveBelowEma($this->rates['simple'], $this->emaLength);

        $this->decisionIndicators['emaPriceAboveBelowSlow'] = $emaEvents->priceAboveBelowEma($this->rates['simple'], $this->emaLengthSlow);

    }

    public function getEntryDecision() {
        $this->setEntryIndicators();
        $this->strategyLogger->logIndicators($this->decisionIndicators);

        if (
        $this->decisionIndicators['rsiOutsideLevel'] == 'overBoughtShort'
         && $this->decisionIndicators['emaPriceAboveBelow'] == 'below'
         && $this->decisionIndicators['emaPriceAboveBelowSlow'] == 'above'
        ) {
            $this->newLongPosition();
        }
        elseif (
        $this->decisionIndicators['rsiOutsideLevel'] == 'overBoughtLong'
         && $this->decisionIndicators['emaPriceAboveBelow'] == 'above'
         && $this->decisionIndicators['emaPriceAboveBelowSlow'] == 'below'
        ) {
            $this->newShortPosition();
        }
    }

    public function inPositionDecision() {
        $priceMovements = new \App\IndicatorEvents\PriceMovements;
        $priceMovements->strategyLogger = $this->strategyLogger;

        $emaEvents = new \App\IndicatorEvents\EmaEvents;
        $emaEvents->strategyLogger = $this->strategyLogger;


        $this->decisionIndicators['periodsWithoutProfitability'] = $priceMovements->periodsWithoutProfitability($this->openPosition, $this->withoutProfPeriodsCutoffs);

        $this->decisionIndicators['emaPriceAboveBelow'] = $emaEvents->priceAboveBelowEma($this->rates['simple'], $this->emaLength);

        $this->strategyLogger->logIndicators($this->decisionIndicators);

        if ($this->openPosition['side'] == 'long') {

            if ($this->decisionIndicators['periodsWithoutProfitability'] || $this->decisionIndicators['emaPriceAboveBelow'] == 'above') {
                $this->strategyLogger->logMessage("WE NEED TO CLOSE", 1);
                $this->closePosition();
            }
        }
        elseif ($this->openPosition['side'] == 'short') {
            if ( $this->decisionIndicators['periodsWithoutProfitability'] || $this->decisionIndicators['emaPriceAboveBelow'] == 'below' ) {
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