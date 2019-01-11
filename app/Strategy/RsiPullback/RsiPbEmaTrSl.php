<?php

/********************************************
RsiPbEmaTrSl Strategy System under RsiPullback Strategy
Created at: 01/11/19by Brian O'Neill
Description: This is a copy of RsiPullbackEmaPriceAction but with True Range Stop Loss Rules
********************************************/

namespace App\Strategy\RsiPullback;
use \Log;
use \App\IndicatorEvents\EmaEvents;
use \App\IndicatorEvents\RsiEvents;
use \App\IndicatorEvents\TrueRange;

class RsiPbEmaTrSl extends \App\Strategy\Strategy  {

    public $emaLengthSlow;
    public $emaLengthFast;
    public $rsiLength;
    public $rsiCutoff;
    public $trueRangeLength;
    public $trueRangeMultiplier;

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

        $trueRange = new \App\IndicatorEvents\TrueRange;
        $trueRange->strategyLogger = $this->strategyLogger;

        $this->strategyLogger->logIndicators($this->decisionIndicators);

        $this->decisionIndicators['emaPriceAboveBelowFast'] = $emaEvents->priceAboveBelowEma($this->rates['simple'], $this->emaLengthFast);

        $this->decisionIndicators['trueRangeBreakevenSL'] = $trueRange->getStopLossTrueRangeOrBreakEven($this->rates['full'], $this->trueRangeLength, $this->trueRangeMultiplier, $this->exchange->pip , $this->openPosition);

        if ($this->openPosition['side'] == 'long') {

            //A Conditions
            // $this->decisionIndicators['emaPriceAboveBelow'] == 'above'
            //B Conditions
            // $this->decisionIndicators['emaPriceAboveBelow'] == 'below'
            if ( $this->decisionIndicators['emaPriceAboveBelowFast'] == 'above' ) {
                $this->strategyLogger->logMessage("WE NEED TO CLOSE", 1);
                $this->closePosition();
            }
            else {
                $this->modifyStopLoss($this->decisionIndicators['trueRangeBreakevenSL']);
            }
        }
        elseif ($this->openPosition['side'] == 'short') {
            if ( $this->decisionIndicators['emaPriceAboveBelowFast'] == 'below' ) {
                $this->strategyLogger->logMessage("WE NEED TO CLOSE", 1);
                $this->closePosition();
            }
            else {
                $this->modifyStopLoss($this->decisionIndicators['trueRangeBreakevenSL']);
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