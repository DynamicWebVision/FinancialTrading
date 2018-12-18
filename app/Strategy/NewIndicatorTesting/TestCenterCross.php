<?php

/********************************************
TestCenterCross Strategy System under NewIndicatorTesting Strategy
Created at: 12/11/18by Brian O'Neill
Description: asdfljasfdljk
********************************************/

namespace App\Strategy\NewIndicatorTesting;
use \Log;
use \App\IndicatorEvents\Bollinger;

class TestCenterCross extends \App\Strategy\Strategy  {

    public $bollingerLength;
    public $bollingerSdMultiplier;

    public function setEntryIndicators() {
        $bollinger = new \App\IndicatorEvents\Bollinger;
        $bollinger->strategyLogger = $this->strategyLogger;


        $this->decisionIndicators['bollingerCross'] = $bollinger->getOuterBandCrossEvent($this->rates['simple'], $this->bollingerLength, $this->bollingerSdMultiplier);

    }

    public function getEntryDecision() {
        $this->setEntryIndicators();
        $this->strategyLogger->logIndicators($this->decisionIndicators);

        if (
        $this->decisionIndicators['bollingerCross'] == 'crossedAbove'
        ) {
            $this->newLongPosition();
        }
        elseif (
        $this->decisionIndicators['bollingerCross'] == 'crossedBelow'
        ) {
            $this->newShortPosition();
        }
    }

    public function inPositionDecision() {
        $bollinger = new \App\IndicatorEvents\Bollinger;
        $bollinger->strategyLogger = $this->strategyLogger;


        $this->strategyLogger->logIndicators($this->decisionIndicators);

        $this->decisionIndicators['bollingerCenterCross'] = $bollinger->closeAcrossCenterLine($this->rates['simple'], $this->bollingerLength, $this->bollingerSdMultiplier, $this->openPosition['side']);

        if ($this->openPosition['side'] == 'long') {

            //A Conditions
            // $this->decisionIndicators['bollingerCenterCross'] == 'close'
            //B Conditions
            // $this->decisionIndicators['bollingerCenterCross'] == 'close'

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