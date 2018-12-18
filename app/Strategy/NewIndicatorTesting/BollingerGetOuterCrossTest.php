<?php

/********************************************
BollingerGetOuterCrossTest Strategy System under NewIndicatorTesting Strategy
Created at: 12/11/18by Brian O'Neill
Description: This is a test.
********************************************/

namespace App\Strategy\NewIndicatorTesting;
use \Log;
use \App\IndicatorEvents\Bollinger;
use \App\IndicatorEvents\AdxEvents;

class BollingerGetOuterCrossTest extends \App\Strategy\Strategy  {

    public $bollingerLength;
    public $bollingerSdMultiplier;
    public $adxLength;
    public $adxUndersoldThreshold;

    public function setEntryIndicators() {
        $bollinger = new \App\IndicatorEvents\Bollinger;
        $bollinger->strategyLogger = $this->strategyLogger;

        $adxEvents = new \App\IndicatorEvents\AdxEvents;
        $adxEvents->strategyLogger = $this->strategyLogger;


        $this->decisionIndicators['bollingerCross'] = $bollinger->getOuterBandCrossEvent($this->rates['simple'], $this->bollingerLength, $this->bollingerSdMultiplier);

        $this->decisionIndicators['adxAboveThreshold'] = $adxEvents->adxAboveThreshold($this->rates['full'], $this->adxLength, $this->adxUndersoldThreshold);

    }

    public function getEntryDecision() {
        $this->setEntryIndicators();
        $this->strategyLogger->logIndicators($this->decisionIndicators);

        if (
        $this->decisionIndicators['bollingerCross'] == 'crossedAbove'
         && $this->decisionIndicators['adxAboveThreshold']
        ) {
            $this->newLongPosition();
        }
        elseif (
        $this->decisionIndicators['bollingerCross'] == 'crossedBelow'
         && $this->decisionIndicators['adxAboveThreshold']
        ) {
            $this->newShortPosition();
        }
    }

    public function inPositionDecision() {
        $bollinger = new \App\IndicatorEvents\Bollinger;
        $bollinger->strategyLogger = $this->strategyLogger;

        $adxEvents = new \App\IndicatorEvents\AdxEvents;
        $adxEvents->strategyLogger = $this->strategyLogger;


        $this->strategyLogger->logIndicators($this->decisionIndicators);

        $this->decisionIndicators['bollingerCross'] = $bollinger->getOuterBandCrossEvent($this->rates['simple'], $this->bollingerLength, $this->bollingerSdMultiplier);

        $this->decisionIndicators['adxAboveThreshold'] = $adxEvents->adxAboveThreshold($this->rates['full'], $this->adxLength, $this->adxUndersoldThreshold);

        if ($this->openPosition['side'] == 'long') {

            //A Conditions
            // $this->decisionIndicators['bollingerCross'] == 'crossedAbove'
            // $this->decisionIndicators['adxAboveThreshold']
            //B Conditions
            // $this->decisionIndicators['bollingerCross'] == 'crossedBelow'
            // $this->decisionIndicators['adxAboveThreshold']

            //if ( CONDITIONS THAT CONTRADICT LONG ) {
                //$this->strategyLogger->logMessage("WE NEED TO CLOSE", 1);
                //$this->closePosition();
                //$this->newLongPosition();
            //}
            //else {
                //$this->modifyStopLoss();
                //$this->newShortPosition();
            //}
        }
        elseif ($this->openPosition['side'] == 'short') {
            //if ( CONDITIONS THAT CONTRADICT SHORT ) {
                //$this->strategyLogger->logMessage("WE NEED TO CLOSE", 1);
                //$this->closePosition();
                //$this->newShortPosition();
            //}
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