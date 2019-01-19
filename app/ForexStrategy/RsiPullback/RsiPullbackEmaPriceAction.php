<?php

/********************************************
RsiPullbackEmaPriceAction Strategy System under RsiPullback Strategy
Created at: 01/09/19by Brian O'Neill
Description: This strategy is centered around an RSI pullback when price is above a very long ema and then exiting when price rises above a very short ema.
********************************************/

namespace App\ForexStrategy\RsiPullback;
use \Log;
use \App\IndicatorEvents\EmaEvents;
use \App\IndicatorEvents\RsiEvents;

class RsiPullbackEmaPriceAction extends \App\ForexStrategy\Strategy  {

    public $emaLengthSlow;
    public $emaLengthFast;
    public $rsiLength;
    public $rsiCutoff;

    public function setEntryIndicators() {
        $emaEvents = new \App\IndicatorEvents\EmaEvents;
        $emaEvents->strategyLogger = $this->strategyLogger;

        $rsiEvents = new \App\IndicatorEvents\RsiEvents;
        $rsiEvents->strategyLogger = $this->strategyLogger;


        $this->decisionIndicators['emaPriceAboveBelowSlow'] = $emaEvents->priceAboveBelowEma($this->rates['simple'], $this->emaLengthSlow);
        $this->decisionIndicators['emaPriceAboveBelowFast'] = $emaEvents->priceAboveBelowEma($this->rates['simple'], $this->emaLengthFast);

        $this->decisionIndicators['rsiOutsideLevel'] = $rsiEvents->outsideLevel(
            $this->rates['simple'], $this->rsiLength, $this->rsiCutoff);

    }

    public function getEntryDecision() {
        $this->setEntryIndicators();
        $this->strategyLogger->logIndicators($this->decisionIndicators);

        if (
        $this->decisionIndicators['emaPriceAboveBelowSlow'] == 'above'
         && $this->decisionIndicators['rsiOutsideLevel'] == 'overBoughtShort'
         && $this->decisionIndicators['emaPriceAboveBelowFast'] == 'below'
        ) {
            $this->newLongPosition();
        }
        elseif (
        $this->decisionIndicators['emaPriceAboveBelowSlow'] == 'below'
         && $this->decisionIndicators['rsiOutsideLevel'] == 'overBoughtLong'
         && $this->decisionIndicators['emaPriceAboveBelowFast'] == 'above'
        ) {
            $this->newShortPosition();
        }
    }

    public function inPositionDecision() {
        $emaEvents = new \App\IndicatorEvents\EmaEvents;
        $emaEvents->strategyLogger = $this->strategyLogger;


        $this->strategyLogger->logIndicators($this->decisionIndicators);

        $this->decisionIndicators['emaPriceAboveBelowFast'] = $emaEvents->priceAboveBelowEma($this->rates['simple'], $this->emaLengthFast);

        if ($this->openPosition['side'] == 'long') {

            //A Conditions
            // $this->decisionIndicators['emaPriceAboveBelow'] == 'above'
            //B Conditions
            // $this->decisionIndicators['emaPriceAboveBelow'] == 'below'

            if ( $this->decisionIndicators['emaPriceAboveBelowFast'] == 'above' ) {
                $this->strategyLogger->logMessage("WE NEED TO CLOSE", 1);
                $this->closePosition();
            }
            //else {
                //$this->modifyStopLoss();
                //$this->newShortPosition();
            //}
        }
        elseif ($this->openPosition['side'] == 'short') {
            if ( $this->decisionIndicators['emaPriceAboveBelowFast'] == 'below' ) {
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