<?php

/********************************************
HmaTurnAdxBelow Strategy System under NewIndicatorTesting Strategy
Created at: 12/09/18by Brian O'Neill
Description: asdfasdf
********************************************/

namespace App\ForexStrategy\NewIndicatorTesting;
use \Log;
use \App\IndicatorEvents\HullMovingAverage;
use \App\IndicatorEvents\AdxEvents;

class HmaTurnAdxBelow extends \App\ForexStrategy\Strategy  {

    public $hmaLength;
    public $adxLength;
    public $adxThreshold;

    public function setEntryIndicators() {
        $hmaEvents = new \App\IndicatorEvents\HullMovingAverage;
        $hmaEvents->strategyLogger = $this->strategyLogger;

        $adxEvents = new \App\IndicatorEvents\AdxEvents;
        $adxEvents->strategyLogger = $this->strategyLogger;


        $this->decisionIndicators['hmaChangeDirection'] = $hmaEvents->hullChangeDirectionCheck($this->rates['simple'], $this->hmaLength);

        $this->decisionIndicators['adxBelowThreshold'] = $adxEvents->adxBelowThreshold($this->rates['full'], $this->adxLength, $this->adxThreshold);

    }

    public function getEntryDecision() {
        $this->setEntryIndicators();
        $this->strategyLogger->logIndicators($this->decisionIndicators);

        if (
        $this->decisionIndicators['hmaChangeDirection'] == 'reversedUp'
         && $this->decisionIndicators['adxBelowThreshold']
        ) {
            $this->newLongPosition();
            //$this->marketIfTouchedOrderPrice = ;
        }
        elseif (
        $this->decisionIndicators['hmaChangeDirection'] == 'reversedDown'
         && $this->decisionIndicators['adxBelowThreshold']
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

        $this->decisionIndicators['hmaChangeDirection'] = $hmaEvents->hullChangeDirectionCheck($this->rates['simple'], $this->hmaLength);

        $this->decisionIndicators['adxBelowThreshold'] = $adxEvents->adxBelowThreshold($this->rates['full'], $this->adxLength, $this->adxThreshold);

        if ($this->openPosition['side'] == 'long') {

            //A Conditions
            // $this->decisionIndicators['hmaChangeDirection'] == 'reversedUp'
            // $this->decisionIndicators['adxBelowThreshold']

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