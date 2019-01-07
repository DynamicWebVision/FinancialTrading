<?php

/********************************************
HmaRevAlone Strategy System under HmaReversal Strategy
Created at: 12/19/18by Brian O'Neill
Description: This is a strategy with an HMA Reversal by itself.
********************************************/

namespace App\Strategy\HmaReversal;
use \Log;
use \AppIndicatorEventsHullMovingAverage;

class HmaRevAlone extends \App\Strategy\Strategy  {

    public $hmaLength;

    public function setEntryIndicators() {
        $hmaEvents = new \AppIndicatorEventsHullMovingAverage;
        $hmaEvents->strategyLogger = $this->strategyLogger;


        $this->decisionIndicators['hmaChangeDirection'] = $hmaEvents->hullChangeDirectionCheck($this->rates['simple'], $this->hmaLength);

    }

    public function getEntryDecision() {
        $this->setEntryIndicators();
        $this->strategyLogger->logIndicators($this->decisionIndicators);

        if (
        $this->decisionIndicators['hmaChangeDirection'] == 'reversedUp'
        ) {
            $this->newLongPosition();
        }
        elseif (
        $this->decisionIndicators['hmaChangeDirection'] == 'reversedDown'
        ) {
            $this->newShortPosition();
        }
    }

    public function inPositionDecision() {
        $hmaEvents = new \AppIndicatorEventsHullMovingAverage;
        $hmaEvents->strategyLogger = $this->strategyLogger;


        $this->strategyLogger->logIndicators($this->decisionIndicators);

        $this->decisionIndicators['hmaChangeDirection'] = $hmaEvents->hullChangeDirectionCheck($this->rates['simple'], $this->hmaLength);

        if ($this->openPosition['side'] == 'long') {

            //A Conditions
            // $this->decisionIndicators['hmaChangeDirection'] == 'reversedUp'
            //B Conditions
            // $this->decisionIndicators['hmaChangeDirection'] == 'reversedDown'

            if ( true ) {
                $this->strategyLogger->logMessage("WE NEED TO CLOSE", 1);
                $this->closePosition();
                $this->newLongPosition();
            }
            else {
                $this->modifyStopLoss();
                $this->newShortPosition();
            }
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