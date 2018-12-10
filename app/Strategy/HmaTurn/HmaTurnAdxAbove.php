<?php

/********************************************
HmaTurnAdxAbove Strategy System under HmaTurn Strategy
Created at: 12/09/18by Brian O'Neill
Description: This is an Hma Turn where we only enter a new position when the Adx is also above a given threshold.
********************************************/

namespace App\Strategy\HmaTurn;
use \Log;
use \App\IndicatorEvents\HullMovingAverage;
use \App\IndicatorEvents\AdxEvents;

class HmaTurnAdxAbove extends \App\Strategy\Strategy  {

    public $hmaLength;
    public $adxLength;
    public $adxUndersoldThreshold;

    public function setEntryIndicators() {
        $hmaEvents = new \App\IndicatorEvents\HullMovingAverage;
        $hmaEvents->strategyLogger = $this->strategyLogger;

        $adxEvents = new \App\IndicatorEvents\AdxEvents;
        $adxEvents->strategyLogger = $this->strategyLogger;


        $this->decisionIndicators['hmaChangeDirection'] = hullChangeDirectionCheck($this->rates['simple'], $this->hmaLength);

        $this->decisionIndicators['adxAboveThreshold'] = $adxEvents->adxAboveThreshold($this->rates['full'], $this->adxLength, $this->adxUndersoldThreshold);

    }

    public function getEntryDecision() {
        $this->setEntryIndicators();
        $this->strategyLogger->logIndicators($this->decisionIndicators);

        if (
        $this->decisionIndicators['hmaChangeDirection'] == 'reversedUp'
         && $this->decisionIndicators['adxAboveThreshold']
        ) {
            $this->newLongPosition();
            //$this->marketIfTouchedOrderPrice = ;
        }
        elseif (
        $this->decisionIndicators['hmaChangeDirection'] == 'reversedDown'
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

        $this->decisionIndicators['hmaChangeDirection'] = hullChangeDirectionCheck($this->rates['simple'], $this->hmaLength);

        $this->decisionIndicators['adxAboveThreshold'] = $adxEvents->adxAboveThreshold($this->rates['full'], $this->adxLength, $this->adxUndersoldThreshold);

        if ($this->openPosition['side'] == 'long') {

            //A Conditions
            // $this->decisionIndicators['hmaChangeDirection'] == 'reversedUp'
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