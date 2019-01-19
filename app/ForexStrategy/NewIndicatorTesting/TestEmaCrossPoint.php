<?php

/********************************************
TestEmaCrossPoint Strategy System under NewIndicatorTesting Strategy
Created at: 12/11/18by Brian O'Neill
Description: asdfasdf
********************************************/

namespace App\ForexStrategy\NewIndicatorTesting;
use \Log;
use \App\IndicatorEvents\EmaEvents;
use \App\IndicatorEvents\AdxEvents;

class TestEmaCrossPoint extends \App\ForexStrategy\Strategy  {

    public $fastEma;
    public $slowEma;
    public $adxLength;
    public $adxUndersoldThreshold;

    public function setEntryIndicators() {
        $emaEvents = new \App\IndicatorEvents\EmaEvents;
        $emaEvents->strategyLogger = $this->strategyLogger;

        $adxEvents = new \App\IndicatorEvents\AdxEvents;
        $adxEvents->strategyLogger = $this->strategyLogger;


        $this->decisionIndicators['emaCrossInfo'] = $emaEvents->calculateEmaNextPeriodCrossoverRate($this->rates['simple'], $this->fastEma, $this->slowEma);

        $this->decisionIndicators['adxAboveThreshold'] = $adxEvents->adxAboveThreshold($this->rates['full'], $this->adxLength, $this->adxUndersoldThreshold);

    }

    public function getEntryDecision() {
        $this->setEntryIndicators();
        $this->strategyLogger->logIndicators($this->decisionIndicators);

        if (
        $this->decisionIndicators['emaCrossInfo']['side'] == 'long'
         && $this->decisionIndicators['adxAboveThreshold']
        ) {
            $this->marketIfTouchedOrderPrice = max([$this->decisionIndicators['emaCrossInfo']['crossRate']]);
            $this->newLongPosition();
        }
        elseif (
        $this->decisionIndicators['emaCrossInfo']['side'] == 'short'
         && $this->decisionIndicators['adxAboveThreshold']
        ) {
            $this->marketIfTouchedOrderPrice = min([$this->decisionIndicators['emaCrossInfo']['crossRate']]);
            $this->newShortPosition();
        }
    }

    public function inPositionDecision() {
        $emaEvents = new \App\IndicatorEvents\EmaEvents;
        $emaEvents->strategyLogger = $this->strategyLogger;


        $this->strategyLogger->logIndicators($this->decisionIndicators);

        $this->decisionIndicators['emaCrossInfo'] = $emaEvents->calculateEmaNextPeriodCrossoverRate($this->rates['simple'], $this->fastEma, $this->slowEma);

        if ($this->openPosition['side'] == 'long') {

            //A Conditions
            // $this->decisionIndicators['emaCrossInfo']['side'] == 'long'
            //B Conditions
            // $this->decisionIndicators['emaCrossInfo']['side'] == 'short'

            //if ( CONDITIONS THAT CONTRADICT LONG ) {
                //$this->strategyLogger->logMessage("WE NEED TO CLOSE", 1);
                //$this->closePosition();
                //$this->marketIfTouchedOrderPrice = max([$this->decisionIndicators['emaCrossInfo']['crossRate']]);
                //$this->newLongPosition();
            //}
            //else {
                //$this->modifyStopLoss();
                //$this->marketIfTouchedOrderPrice = max([$this->decisionIndicators['emaCrossInfo']['crossRate']]);
                //$this->newShortPosition();
            //}
        }
        elseif ($this->openPosition['side'] == 'short') {
            //if ( CONDITIONS THAT CONTRADICT SHORT ) {
                //$this->strategyLogger->logMessage("WE NEED TO CLOSE", 1);
                //$this->closePosition();
                //$this->marketIfTouchedOrderPrice = min([$this->decisionIndicators['emaCrossInfo']['crossRate']]);
                //$this->newShortPosition();
            //}
            //else {
                //$this->modifyStopLoss();
                //$this->marketIfTouchedOrderPrice = max([$this->decisionIndicators['emaCrossInfo']['crossRate']]);
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