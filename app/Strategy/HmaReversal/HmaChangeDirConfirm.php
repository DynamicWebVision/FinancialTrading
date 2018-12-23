<?php

/********************************************
HmaChangeDirConfirm Strategy System under HmaReversal Strategy
Created at: 12/21/18by Brian O'Neill
Description: This is the HMA change direction.
********************************************/

namespace App\Strategy\HmaReversal;
use \Log;
use \AppIndicatorEventsHullMovingAverage;

class HmaChangeDirConfirm extends \App\Strategy\Strategy  {

    public $hmaLength;
    public $hmaSlopeMin;

    public function setEntryIndicators() {
        $hmaEvents = new \App\IndicatorEvents\HullMovingAverage;
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
        $hmaEvents = new \App\IndicatorEvents\HullMovingAverage;
        $hmaEvents->strategyLogger = $this->strategyLogger;


        $this->strategyLogger->logIndicators($this->decisionIndicators);

        $this->decisionIndicators['hmaChangeDirection'] = $hmaEvents->hullChangeDirectionCheck($this->rates['simple'], $this->hmaLength);

        $this->decisionIndicators['hmaSlope'] = $hmaEvents->hmaSlope($this->rates['simple'], $this->hmaLength, $this->exchange->pip, $this->hmaSlopeMin);

        if ($this->openPosition['side'] == 'long') {
            //A Conditions
            // $this->decisionIndicators['hmaChangeDirection'] == 'reversedUp'
            // $this->decisionIndicators['hmaSlope'] == 'long'
            //B Conditions
            // $this->decisionIndicators['hmaChangeDirection'] == 'reversedDown'
            // $this->decisionIndicators['hmaSlope'] == 'short'

            if ( $this->decisionIndicators['hmaSlope'] == 'short' ) {
                $this->strategyLogger->logMessage("WE NEED TO CLOSE", 1);
                $this->closePosition();
                if ($this->decisionIndicators['hmaChangeDirection'] == 'reversedDown') {
                    $this->newShortPosition();
                }
            }
        }
        elseif ($this->openPosition['side'] == 'short') {
            if ( $this->decisionIndicators['hmaSlope'] == 'long' ) {
                $this->strategyLogger->logMessage("WE NEED TO CLOSE", 1);
                $this->closePosition();
                if ($this->decisionIndicators['hmaChangeDirection'] == 'reversedUp') {
                    $this->newLongPosition();
                }
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