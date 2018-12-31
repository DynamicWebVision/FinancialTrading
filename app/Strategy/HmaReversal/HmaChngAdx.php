<?php

/********************************************
HmaChngAdx Strategy System under HmaReversal Strategy
Created at: 12/30/18by Brian O'Neill
Description: This is an Hma reversal where it waits for the change to occur rather than entering on the price point. It also has an ADX minimum confirmation.
********************************************/

namespace App\Strategy\HmaReversal;
use \Log;
use \App\IndicatorEvents\HullMovingAverage;
use \App\IndicatorEvents\AdxEvents;

class HmaChngAdx extends \App\Strategy\Strategy  {

    public $hmaLength;
    public $adxLength;
    public $adxUndersoldThreshold;
    public $hmaSlopeMin;

    public function setEntryIndicators() {
        $hmaEvents = new \App\IndicatorEvents\HullMovingAverage;
        $hmaEvents->strategyLogger = $this->strategyLogger;

        $adxEvents = new \App\IndicatorEvents\AdxEvents;
        $adxEvents->strategyLogger = $this->strategyLogger;


        $this->decisionIndicators['hmaChangeDirection'] = $hmaEvents->hullChangeDirectionCheck($this->rates['simple'], $this->hmaLength);

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
        }
        elseif (
        $this->decisionIndicators['hmaChangeDirection'] == 'reversedDown'
         && $this->decisionIndicators['adxAboveThreshold']
        ) {
            $this->newShortPosition();
        }
    }

    public function inPositionDecision() {
        $hmaEvents = new \App\IndicatorEvents\HullMovingAverage;
        $hmaEvents->strategyLogger = $this->strategyLogger;


        $this->strategyLogger->logIndicators($this->decisionIndicators);

        $this->decisionIndicators['hmaChangeDirection'] = $hmaEvents->hullChangeDirectionCheck($this->rates['simple'], $this->hmaLength);

        $this->decisionIndicators['hmaSlope'] = $hmaEvents->hmaSlope($this->getRatesInPips($this->rates['simple']), $this->hmaLength, $this->exchange->pip, $this->hmaSlopeMin);

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