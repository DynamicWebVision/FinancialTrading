<?php

/********************************************
RsiPbEmaProf Strategy System under RsiPullback Strategy
Created at: 02/14/19by Brian O'Neill
Description: This is similar to the other Rsi Pb Ema Strategies, but now rather than a break even point I'm shooting for  a 1 pip profit.
********************************************/

namespace App\ForexStrategy\RsiPullback;
use \Log;
use \App\IndicatorEvents\RsiEvents;
use \App\IndicatorEvents\EmaEvents;
use \App\IndicatorEvents\TrueRange;

class RsiPbEmaProf extends \App\ForexStrategy\Strategy  {

    public $rsiLength;
    public $rsiCutoff;
    public $emaSlowLength;
    public $emaLength;
    public $trueRangeLength = 14;
    public $trueRangeMultiplier;

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

        $this->decisionIndicators['trueRangeBreakevenSLMP'] = $trueRange->getStopLossTrueRangeOrOnePipProfitable($this->rates['full'], $this->trueRangeLength,
            $this->trueRangeMultiplier, $this->exchange->pip , $this->openPosition);

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