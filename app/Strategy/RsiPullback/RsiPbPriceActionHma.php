<?php

/********************************************
RsiPbPriceActionHma Strategy System under RsiPullback Strategy
Created at: 01/10/19by Brian O'Neill
Description: This is the same as with the EMA, but with the HMA
********************************************/

namespace App\Strategy\RsiPullback;
use \Log;
use \App\IndicatorEvents\HullMovingAverage;
use \App\IndicatorEvents\RsiEvents;
use \App\IndicatorEvents\EmaEvents;

class RsiPbPriceActionHma extends \App\Strategy\Strategy  {

    public $hmaLength;
    public $rsiLength;
    public $rsiCutoff;
    public $emaLength;

    public function setEntryIndicators() {
        $hmaEvents = new \App\IndicatorEvents\HullMovingAverage;
        $hmaEvents->strategyLogger = $this->strategyLogger;

        $rsiEvents = new \App\IndicatorEvents\RsiEvents;
        $rsiEvents->strategyLogger = $this->strategyLogger;

        $emaEvents = new \App\IndicatorEvents\EmaEvents;
        $emaEvents->strategyLogger = $this->strategyLogger;


        $this->decisionIndicators['priceAboveBelowHma'] = $hmaEvents->hmaPriceAboveBelowHma($this->rates['simple'], $this->hmaLength);

        $this->decisionIndicators['rsiOutsideLevel'] = $rsiEvents->outsideLevel(
            $this->rates['simple'], $this->rsiLength, $this->rsiCutoff);

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

            //A Conditions
            // $this->decisionIndicators['emaPriceAboveBelow'] == 'above'
            //B Conditions
            // $this->decisionIndicators['emaPriceAboveBelow'] == 'below'

            if ( $this->decisionIndicators['emaPriceAboveBelow'] == 'above' ) {
                $this->strategyLogger->logMessage("WE NEED TO CLOSE", 1);
                $this->closePosition();
            }
            //else {
                //$this->modifyStopLoss();
                //$this->newShortPosition();
            //}
        }
        elseif ($this->openPosition['side'] == 'short') {
            if ( $this->decisionIndicators['emaPriceAboveBelow'] == 'below' ) {
                $this->strategyLogger->logMessage("WE NEED TO CLOSE", 1);
                $this->closePosition();
            }
            //else {
                //$this->modifyStopLoss();
                //$this->newLongPosition();
            //}
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