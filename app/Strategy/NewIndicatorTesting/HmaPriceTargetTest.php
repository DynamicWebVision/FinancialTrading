<?php

/********************************************
HmaPriceTargetTest Strategy System under NewIndicatorTesting Strategy
Created at: 12/10/18by Brian O'Neill
Description: asdfas
********************************************/

namespace App\Strategy\NewIndicatorTesting;
use \Log;
use \App\IndicatorEvents\HullMovingAverage;
use \App\IndicatorEvents\AdxEvents;

class HmaPriceTargetTest extends \App\Strategy\Strategy  {

    public $hmaLength;
    public $adxLength;
    public $adxUndersoldThreshold;

    public function setEntryIndicators() {
        $hmaEvents = new \App\IndicatorEvents\HullMovingAverage;
        $hmaEvents->strategyLogger = $this->strategyLogger;

        $adxEvents = new \App\IndicatorEvents\AdxEvents;
        $adxEvents->strategyLogger = $this->strategyLogger;

        $this->decisionIndicators['hmaChangeDirection'] = $hmaEvents->hullChangeDirectionPoint($this->rates['simple'], $this->hmaLength);

        $this->decisionIndicators['adxAboveThreshold'] = $adxEvents->adxAboveThreshold($this->rates['full'], $this->adxLength, $this->adxUndersoldThreshold);

    }

    public function getEntryDecision() {
        $this->setEntryIndicators();
        $this->strategyLogger->logIndicators($this->decisionIndicators);

        if (
        $this->decisionIndicators['hmaChangeDirection']['currentTrendingSide'] == 'short'
         && $this->decisionIndicators['adxAboveThreshold']
        ) {
            $this->marketIfTouchedOrderPrice = max([$this->decisionIndicators['hmaChangeDirection']['priceTarget']]);
            $this->newLongPosition();
        }
        elseif (
        $this->decisionIndicators['hmaChangeDirection']['currentTrendingSide'] == 'long'
         && $this->decisionIndicators['adxAboveThreshold']
        ) {
            $this->marketIfTouchedOrderPrice = min([$this->decisionIndicators['hmaChangeDirection']['priceTarget']]);
            $this->newShortPosition();
        }
    }

    public function inPositionDecision() {
        $hmaEvents = new \App\IndicatorEvents\HullMovingAverage;
        $hmaEvents->strategyLogger = $this->strategyLogger;

        $adxEvents = new \App\IndicatorEvents\AdxEvents;
        $adxEvents->strategyLogger = $this->strategyLogger;


        $this->strategyLogger->logIndicators($this->decisionIndicators);

        $this->decisionIndicators['hmaChangeDirection'] = $hmaEvents->hullChangeDirectionPoint($this->rates['simple'], $this->hmaLength);

        $this->decisionIndicators['adxAboveThreshold'] = $adxEvents->adxAboveThreshold($this->rates['full'], $this->adxLength, $this->adxUndersoldThreshold);

        if ($this->openPosition['side'] == 'long') {

            //A Conditions
            // $this->decisionIndicators['hmaChangeDirection']['currentTrendingSide'] == 'short'
            // $this->decisionIndicators['adxAboveThreshold']
            //B Conditions
//             $this->decisionIndicators['hmaChangeDirection']['currentTrendingSide'] == 'long'
//             $this->decisionIndicators['adxAboveThreshold']

            if ( $this->decisionIndicators['hmaChangeDirection']['currentTrendingSide'] == 'short' ) {
                $this->strategyLogger->logMessage("WE NEED TO CLOSE", 1);
                $this->closePosition();
                if ($this->decisionIndicators['adxAboveThreshold']) {
                    $this->marketIfTouchedOrderPrice = max([$this->decisionIndicators['hmaChangeDirection']['priceTarget']]);
                    $this->newLongPosition();
                }
            }
            else {
                if ($this->decisionIndicators['adxAboveThreshold']) {
                    $this->marketIfTouchedOrderPrice = min([$this->decisionIndicators['hmaChangeDirection']['priceTarget']]);
                    $this->newShortPosition();
                }
            }
        }
        elseif ($this->openPosition['side'] == 'short') {
            if ( $this->decisionIndicators['hmaChangeDirection']['currentTrendingSide'] == 'long' ) {
                $this->strategyLogger->logMessage("WE NEED TO CLOSE", 1);
                $this->closePosition();

                if ($this->decisionIndicators['adxAboveThreshold']) {
                    $this->marketIfTouchedOrderPrice = min([$this->decisionIndicators['hmaChangeDirection']['priceTarget']]);
                    $this->newShortPosition();
                }
            }
            else {
                if ($this->decisionIndicators['adxAboveThreshold']) {
                    $this->marketIfTouchedOrderPrice = max([$this->decisionIndicators['hmaChangeDirection']['priceTarget']]);
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