<?php

/********************************************
RsiPbHmaSLBe Strategy System under RsiPullback Strategy
Created at: 01/19/19by Brian O'Neill
Description: RsiPbHmaSLBe
********************************************/

namespace App\ForexStrategy\RsiPullback;
use \Log;
use \App\IndicatorEvents\RsiEvents;
use \App\IndicatorEvents\HullMovingAverage;
use \App\IndicatorEvents\TrueRange;
use \App\IndicatorEvents\EmaEvents;

class RsiPbHmaSLBe extends \App\ForexStrategy\Strategy  {

    public $rsiLength;
    public $rsiCutoff;
    public $hmaLength;
    public $trueRangeLength = 14;
    public $trueRangeMultiplierNew;
    public $trueRangeMultiplierOpen;
    public $emaLength;

    public function setEntryIndicators() {
        $rsiEvents = new \App\IndicatorEvents\RsiEvents;
        $rsiEvents->strategyLogger = $this->strategyLogger;

        $hmaEvents = new \App\IndicatorEvents\HullMovingAverage;
        $hmaEvents->strategyLogger = $this->strategyLogger;

        $trueRange = new \App\IndicatorEvents\TrueRange;
        $trueRange->strategyLogger = $this->strategyLogger;

        $emaEvents = new \App\IndicatorEvents\EmaEvents;
        $emaEvents->strategyLogger = $this->strategyLogger;


        $this->decisionIndicators['rsiOutsideLevel'] = $rsiEvents->outsideLevel(
            $this->rates['simple'], $this->rsiLength, $this->rsiCutoff);

        $this->decisionIndicators['priceAboveBelowHma'] = $hmaEvents->hmaPriceAboveBelowHma($this->rates['simple'], $this->hmaLength);

        $this->stopLossPipAmount = $trueRange->getStopLossPipValue($this->rates['full'], $this->trueRangeLength, $this->exchange->pip, $this->trueRangeMultiplierNew);

        $this->decisionIndicators['emaPriceAboveBelow'] = $emaEvents->priceAboveBelowEma($this->rates['simple'], $this->emaLength);

    }

    public function getEntryDecision() {
        $this->setEntryIndicators();
        $this->strategyLogger->logIndicators($this->decisionIndicators);

        if (
        $this->decisionIndicators['rsiOutsideLevel'] == 'overBoughtShort'
         && $this->decisionIndicators['priceAboveBelowHma'] == 'above'
         && $this->decisionIndicators['emaPriceAboveBelow'] == 'below'
        ) {
            $this->newLongPosition();
        }
        elseif (
        $this->decisionIndicators['rsiOutsideLevel'] == 'overBoughtLong'
         && $this->decisionIndicators['priceAboveBelowHma'] == 'below'
         && $this->decisionIndicators['emaPriceAboveBelow'] == 'above'
        ) {
            $this->newShortPosition();
        }
    }

    public function inPositionDecision() {
        $emaEvents = new \App\IndicatorEvents\EmaEvents;
        $emaEvents->strategyLogger = $this->strategyLogger;

        $trueRange = new \App\IndicatorEvents\TrueRange;
        $trueRange->strategyLogger = $this->strategyLogger;


        $this->decisionIndicators['emaPriceAboveBelow'] = $emaEvents->priceAboveBelowEma($this->rates['simple'], $this->emaLength);

        $this->decisionIndicators['trueRangeBreakevenSLMP'] = $trueRange->getStopLossTrueRangeOrBreakEvenMostProfitable($this->rates['full'], $this->trueRangeLength,
            $this->trueRangeMultiplierOpen, $this->exchange->pip , $this->openPosition);

        $this->strategyLogger->logIndicators($this->decisionIndicators);

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
                $this->modifyStopLoss($this->decisionIndicators['trueRangeBreakevenSLMP']);
            }
        }
        elseif ($this->openPosition['side'] == 'short') {
            if ( $this->decisionIndicators['emaPriceAboveBelowFast'] == 'below' ) {
                $this->strategyLogger->logMessage("WE NEED TO CLOSE", 1);
                $this->closePosition();
            }
            else {
                $this->modifyStopLoss($this->decisionIndicators['trueRangeBreakevenSLMP']);
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