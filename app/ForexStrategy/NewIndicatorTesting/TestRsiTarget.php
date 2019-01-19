<?php

/********************************************
TestRsiTarget Strategy System under NewIndicatorTesting Strategy
Created at: 12/12/18by Brian O'Neill
Description: asdfasdf
********************************************/

namespace App\ForexStrategy\NewIndicatorTesting;
use \Log;
use \App\IndicatorEvents\RsiEvents;
use \App\IndicatorEvents\AdxEvents;

class TestRsiTarget extends \App\ForexStrategy\Strategy  {

    public $rsiLength;
    public $rsiLevel;
    public $adxLength;
    public $adxUndersoldThreshold;

    public function setEntryIndicators() {
        $rsiEvents = new \App\IndicatorEvents\RsiEvents;
        $rsiEvents->strategyLogger = $this->strategyLogger;

        $adxEvents = new \App\IndicatorEvents\AdxEvents;
        $adxEvents->strategyLogger = $this->strategyLogger;


        $this->decisionIndicators['rsiCrossOuter'] = $rsiEvents->getCrossLevelPricePointFromOuter($this->rates['simple'], $this->rsiLength, $this->rsiLevel);

        $this->decisionIndicators['adxAboveThreshold'] = $adxEvents->adxAboveThreshold($this->rates['full'], $this->adxLength, $this->adxUndersoldThreshold);

    }

    public function getEntryDecision() {
        $this->setEntryIndicators();
        $this->strategyLogger->logIndicators($this->decisionIndicators);

        if (
        $this->decisionIndicators['rsiCrossOuter']['side'] == 'short'
         && $this->decisionIndicators['adxAboveThreshold']
        ) {
            $this->marketIfTouchedOrderPrice = max([$this->decisionIndicators['rsiCrossOuter']['targetPrice']]);
            $this->newLongPosition();
        }
        elseif (
        $this->decisionIndicators['rsiCrossOuter']['side'] == 'long'
         && $this->decisionIndicators['adxAboveThreshold']
        ) {
            $this->marketIfTouchedOrderPrice = min([$this->decisionIndicators['rsiCrossOuter']['targetPrice']]);
            $this->newShortPosition();
        }
    }

    public function inPositionDecision() {
        $rsiEvents = new \App\IndicatorEvents\RsiEvents;
        $rsiEvents->strategyLogger = $this->strategyLogger;

        $adxEvents = new \App\IndicatorEvents\AdxEvents;
        $adxEvents->strategyLogger = $this->strategyLogger;


        $this->strategyLogger->logIndicators($this->decisionIndicators);

        $this->decisionIndicators['rsiCrossOuter'] = $rsiEvents->getCrossLevelPricePointFromOuter($this->rates['simple'], $this->rsiLength, $this->rsiLevel);

        $this->decisionIndicators['adxAboveThreshold'] = $adxEvents->adxAboveThreshold($this->rates['full'], $this->adxLength, $this->adxUndersoldThreshold);

        if ($this->openPosition['side'] == 'long') {

            //A Conditions
            // $this->decisionIndicators['rsiCrossOuter']['side'] == 'short'
            // $this->decisionIndicators['adxAboveThreshold']
            //B Conditions
            // $this->decisionIndicators['rsiCrossOuter']['side'] == 'long'
            // $this->decisionIndicators['adxAboveThreshold']

            //if ( CONDITIONS THAT CONTRADICT LONG ) {
                //$this->strategyLogger->logMessage("WE NEED TO CLOSE", 1);
                //$this->closePosition();
                //$this->marketIfTouchedOrderPrice = max([$this->decisionIndicators['rsiCrossOuter']['targetPrice']]);
                //$this->newLongPosition();
            //}
            //else {
                //$this->modifyStopLoss();
                //$this->marketIfTouchedOrderPrice = max([$this->decisionIndicators['rsiCrossOuter']['targetPrice']]);
                //$this->newShortPosition();
            //}
        }
        elseif ($this->openPosition['side'] == 'short') {
            //if ( CONDITIONS THAT CONTRADICT SHORT ) {
                //$this->strategyLogger->logMessage("WE NEED TO CLOSE", 1);
                //$this->closePosition();
                //$this->marketIfTouchedOrderPrice = min([$this->decisionIndicators['rsiCrossOuter']['targetPrice']]);
                //$this->newShortPosition();
            //}
            //else {
                //$this->modifyStopLoss();
                //$this->marketIfTouchedOrderPrice = max([$this->decisionIndicators['rsiCrossOuter']['targetPrice']]);
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