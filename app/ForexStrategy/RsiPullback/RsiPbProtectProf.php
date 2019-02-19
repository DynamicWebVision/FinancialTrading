<?php

/********************************************
RsiPbProtectProf Strategy System under RsiPullback Strategy
Created at: 02/18/19by Brian O'Neill
Description: RsiPbProtectProf
********************************************/

namespace App\ForexStrategy\RsiPullback;
use \Log;
use \App\IndicatorEvents\EmaEvents;
use \App\IndicatorEvents\RsiEvents;
use \App\IndicatorEvents\TrueRange;

class RsiPbProtectProf extends \App\ForexStrategy\Strategy  {

    public $emaSlowLength;
    public $emaLength;
    public $rsiLength;
    public $rsiCutoff;
    public $trueRangeLength;
    public $trueRangeMultiplier;
    public $trueRangeSLPeriodOpenCutoff;

    public function setEntryIndicators() {
        $rsiEvents = new \App\IndicatorEvents\RsiEvents;
        $rsiEvents->strategyLogger = $this->strategyLogger;

        $emaEvents = new \App\IndicatorEvents\EmaEvents;
        $emaEvents->strategyLogger = $this->strategyLogger;

        $trueRange = new \App\IndicatorEvents\TrueRange;
        $trueRange->strategyLogger = $this->strategyLogger;

        $this->decisionIndicators['rsiOutsideLevel'] = $rsiEvents->outsideLevel(
            $this->rates['simple'], $this->rsiLength, $this->rsiCutoff);

        $this->decisionIndicators['emaSlowPriceAboveBelow'] = $emaEvents->priceAboveBelowEma($this->rates['simple'], $this->emaSlowLength);

        $this->decisionIndicators['emaPriceAboveBelow'] = $emaEvents->priceAboveBelowEma($this->rates['simple'], $this->emaLength);
        $this->stopLossPipAmount = $trueRange->getStopLossPipValue($this->rates['full'], $this->trueRangeLength, $this->exchange->pip, $this->trueRangeMultiplier);

    }

    public function getEntryDecision() {
        $this->setEntryIndicators();
        $this->strategyLogger->logIndicators($this->decisionIndicators);

        if (
            $this->decisionIndicators['rsiOutsideLevel'] == 'overBoughtShort'
            && $this->decisionIndicators['emaSlowPriceAboveBelow'] == 'above'
            && $this->decisionIndicators['emaPriceAboveBelow'] == 'below'
        ) {
            $this->newLongPosition();
        }
        elseif (
            $this->decisionIndicators['rsiOutsideLevel'] == 'overBoughtLong'
            && $this->decisionIndicators['emaSlowPriceAboveBelow'] == 'below'
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

        $this->decisionIndicators['trueRangeBreakevenSLMP'] = $trueRange->getSLOpenTrUntilCurrentTrProfOrNumberOfPeriods($this->rates['full'], $this->trueRangeLength,
            $this->trueRangeMultiplier, $this->exchange->pip , $this->openPosition, $this->trueRangeSLPeriodOpenCutoff);

        $this->strategyLogger->logIndicators($this->decisionIndicators);

        if ($this->openPosition['side'] == 'long') {
            //A Conditions
            // $this->decisionIndicators['emaPriceAboveBelow'] == 'above'
            //B Conditions
            // $this->decisionIndicators['emaPriceAboveBelow'] == 'below'
            if ( $this->decisionIndicators['emaPriceAboveBelow'] == 'above' ) {
                $this->strategyLogger->logMessage("WE NEED TO CLOSE", 1);
                $this->closePosition();
            }
            else {
                $this->modifyStopLoss($this->decisionIndicators['trueRangeBreakevenSLMP']);
            }
        }
        elseif ($this->openPosition['side'] == 'short') {
            if ( $this->decisionIndicators['emaPriceAboveBelow'] == 'below' ) {
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