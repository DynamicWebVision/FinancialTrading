<?php

/********************************************
EmaPriceXMomRsi Strategy System under EmaPriceCross Strategy
Created at: 02/19/19by Brian O'Neill
Description: EmaPriceXMomRsi
********************************************/

namespace App\ForexStrategy\EmaPriceCross;
use \Log;
use \App\IndicatorEvents\EmaEvents;
use \App\IndicatorEvents\RsiEvents;
use \App\IndicatorEvents\TrueRange;

class EmaPriceXMomRsi extends \App\ForexStrategy\Strategy  {

    public $emaLength;
    public $emaSlowLength;
    public $rsiLength;
    public $rsiCutoff;
    public $trueRangeLength;
    public $trueRangeMultiplier;

    public function setEntryIndicators() {
        $emaEvents = new \App\IndicatorEvents\EmaEvents;
        $emaEvents->strategyLogger = $this->strategyLogger;

        $rsiEvents = new \App\IndicatorEvents\RsiEvents;
        $rsiEvents->strategyLogger = $this->strategyLogger;


        $this->decisionIndicators['emaSlowPriceAboveBelow'] = $emaEvents->priceAboveBelowEma($this->rates['simple'], $this->emaSlowLength);

        $this->decisionIndicators['emaPriceCross'] = $emaEvents->priceCrossedOver($this->rates['simple'], $this->emaLength);

        $this->decisionIndicators['rsiOutsideLevel'] = $rsiEvents->outsideLevel(
            $this->rates['simple'], $this->rsiLength, $this->rsiCutoff);

    }

    public function getEntryDecision() {
        $this->setEntryIndicators();
        $this->strategyLogger->logIndicators($this->decisionIndicators);

        if (
        $this->decisionIndicators['emaSlowPriceAboveBelow'] == 'above'
         && $this->decisionIndicators['emaPriceCross'] == 'crossedAbove'
         && $this->decisionIndicators['rsiOutsideLevel'] != 'overBoughtLong'
        ) {
            $this->newLongPosition();
        }
        elseif (
        $this->decisionIndicators['emaSlowPriceAboveBelow'] == 'below'
         && $this->decisionIndicators['emaPriceCross'] == 'crossedBelow'
         && $this->decisionIndicators['rsiOutsideLevel'] != 'overBoughtShort'
        ) {
            $this->newShortPosition();
        }
    }

    public function inPositionDecision() {
        $emaEvents = new \App\IndicatorEvents\EmaEvents;
        $emaEvents->strategyLogger = $this->strategyLogger;

        $rsiEvents = new \App\IndicatorEvents\RsiEvents;
        $rsiEvents->strategyLogger = $this->strategyLogger;

        $trueRange = new \App\IndicatorEvents\TrueRange;
        $trueRange->strategyLogger = $this->strategyLogger;

        $this->decisionIndicators['emaPriceAboveBelow'] = $emaEvents->priceAboveBelowEma($this->rates['simple'], $this->emaLength);

        $this->decisionIndicators['rsiOutsideLevel'] = $rsiEvents->outsideLevel(
            $this->rates['simple'], $this->rsiLength, $this->rsiCutoff);

        $this->stopLossPipAmount = $trueRange->getStopLossPipValue($this->rates['full'], $this->trueRangeLength, $this->exchange->pip, $this->trueRangeMultiplier);

        $this->strategyLogger->logIndicators($this->decisionIndicators);

        if ($this->openPosition['side'] == 'long') {

            //A Conditions
            // $this->decisionIndicators['emaPriceAboveBelow'] == 'above'
            // $this->decisionIndicators['rsiOutsideLevel'] == 'overBoughtShort'
            // 
            //B Conditions
            // $this->decisionIndicators['emaPriceAboveBelow'] == 'below'
            // $this->decisionIndicators['rsiOutsideLevel'] == 'overBoughtLong'
            // 

            if ( $this->decisionIndicators['emaPriceAboveBelow'] == 'above' || $this->decisionIndicators['rsiOutsideLevel'] == 'overBoughtLong') {
                $this->strategyLogger->logMessage("WE NEED TO CLOSE", 1);
                $this->closePosition();
            }
        }
        elseif ($this->openPosition['side'] == 'short') {
            if ( $this->decisionIndicators['emaPriceAboveBelow'] == 'below' || $this->decisionIndicators['rsiOutsideLevel'] == 'overBoughtShort') {
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