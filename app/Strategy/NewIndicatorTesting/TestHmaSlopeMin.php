<?php

/********************************************
TestHmaSlopeMin Strategy System under NewIndicatorTesting Strategy
Created at: 12/10/18by Brian O'Neill
Description: This is a test strategy for the Hma Slope Min
********************************************/

namespace App\Strategy\NewIndicatorTesting;
use \Log;
use \App\IndicatorEvents\HullMovingAverage;
use \App\IndicatorEvents\AdxEvents;

class TestHmaSlopeMin extends \App\Strategy\Strategy  {

    public $hmaLength;
    public $hmaSlopeCutoff;
    public $adxLength;
    public $adxUndersoldThreshold;

    public function setEntryIndicators() {
        $hmaEvents = new \App\IndicatorEvents\HullMovingAverage;
        $hmaEvents->strategyLogger = $this->strategyLogger;

        $adxEvents = new \App\IndicatorEvents\AdxEvents;
        $adxEvents->strategyLogger = $this->strategyLogger;

        $this->decisionIndicators['hmaSlopeCutoff'] = $hmaEvents->hmaSlope($this->rates['simple'], $this->hmaLength, $this->exchange->pip, $this->hmaSlopeCutoff);

        $this->decisionIndicators['adxAboveThreshold'] = $adxEvents->adxAboveThreshold($this->rates['full'], $this->adxLength, $this->adxUndersoldThreshold);

    }

    public function getEntryDecision() {
        $this->setEntryIndicators();
        $this->strategyLogger->logIndicators($this->decisionIndicators);

        if (
        $this->decisionIndicators['hmaSlopeCutoff'] == 'long'
         && $this->decisionIndicators['adxAboveThreshold']
        ) {
            $this->newLongPosition();
            //$this->marketIfTouchedOrderPrice = ;
        }
        elseif (
        $this->decisionIndicators['hmaSlopeCutoff'] == 'short'
         && $this->decisionIndicators['adxAboveThreshold']
        ) {
            $this->newShortPosition();
            //$this->marketIfTouchedOrderPrice = ;
        }
    }

    public function inPositionDecision() {
        $hmaEvents = new \App\IndicatorEvents\HullMovingAverage;
        $hmaEvents->strategyLogger = $this->strategyLogger;

        $adxEvents = new \App\IndicatorEvents\AdxEvents;
        $adxEvents->strategyLogger = $this->strategyLogger;


        $this->strategyLogger->logIndicators($this->decisionIndicators);

        $this->decisionIndicators['hmaSlopeCutoff'] = $hmaEvents->hmaSlope($this->rates['simple'], $this->hmaLength, $this->exchange->pip, $this->hmaSlopeCutoff);

        $this->decisionIndicators['adxAboveThreshold'] = $adxEvents->adxAboveThreshold($this->rates['full'], $this->adxLength, $this->adxUndersoldThreshold);

        if ($this->openPosition['side'] == 'long') {

            //A Conditions
            // $this->decisionIndicators['hmaSlopeCutoff'] == 'long'
            // $this->decisionIndicators['adxAboveThreshold']

            //if ( CONDITIONS THAT CONTRADICT LONG ) {
                //$this->strategyLogger->logMessage("WE NEED TO CLOSE", 1);
                //$this->closePosition();
                //$this->marketIfTouchedOrderPrice = ;
                //$this->newLongPosition();
            //}
            //else {
                //$this->marketIfTouchedOrderPrice = ;
                //$this->newShortPosition();
            //}
        }
        elseif ($this->openPosition['side'] == 'short') {
            //if ( CONDITIONS THAT CONTRADICT SHORT ) {
                //$this->strategyLogger->logMessage("WE NEED TO CLOSE", 1);
                //$this->closePosition();
                //$this->marketIfTouchedOrderPrice = ;
                //$this->newShortPosition();
            //}
            //else {
                //$this->marketIfTouchedOrderPrice = ;
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