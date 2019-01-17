<?php

/********************************************
HmaPPSimple Strategy System under HmaPricePoint Strategy
Created at: 01/16/19by Brian O'Neill
Description: Test
********************************************/

namespace App\Strategy\HmaPricePoint;
use \Log;
use \App\IndicatorEvents\HullMovingAverage;

class HmaPPSimple extends \App\Strategy\Strategy  {

    public $hmaLength;

    public function setEntryIndicators() {
        $hmaEvents = new \App\IndicatorEvents\HullMovingAverage;
        $hmaEvents->strategyLogger = $this->strategyLogger;

        $this->decisionIndicators['hmaChangeDirection'] = $hmaEvents->hullChangeDirectionPoint($this->rates['simple'], $this->hmaLength);

    }

    public function getEntryDecision() {
        $this->setEntryIndicators();
        $this->strategyLogger->logIndicators($this->decisionIndicators);

        if (
        $this->decisionIndicators['hmaChangeDirection']['currentTrendingSide'] == 'short'
        ) {
            $this->marketIfTouchedOrderPrice = max([$this->decisionIndicators['hmaChangeDirection']['priceTarget']]);
            $this->newLongPosition();
        }
        elseif (
        $this->decisionIndicators['hmaChangeDirection']['currentTrendingSide'] == 'long'
        ) {
            $this->marketIfTouchedOrderPrice = min([$this->decisionIndicators['hmaChangeDirection']['priceTarget']]);
            $this->newShortPosition();
        }
    }

    public function inPositionDecision() {
        $hmaEvents = new \App\IndicatorEvents\HullMovingAverage;
        $hmaEvents->strategyLogger = $this->strategyLogger;


        $this->strategyLogger->logIndicators($this->decisionIndicators);

        $this->decisionIndicators['hmaChangeDirection'] = $hmaEvents->hullChangeDirectionPoint($this->rates['simple'], $this->hmaLength);

        if ($this->openPosition['side'] == 'long') {

            //A Conditions
            // $this->decisionIndicators['hmaChangeDirection']['currentTrendingSide'] == 'short'
            //B Conditions
            // $this->decisionIndicators['hmaChangeDirection']['currentTrendingSide'] == 'long'

            if ( $this->decisionIndicators['hmaChangeDirection']['currentTrendingSide'] == 'short' ) {
                $this->strategyLogger->logMessage("WE NEED TO CLOSE", 1);
                $this->closePosition();
                $this->marketIfTouchedOrderPrice = max([$this->decisionIndicators['hmaChangeDirection']['priceTarget']]);
                $this->newLongPosition();
            }
            else {
                $this->modifyStopLoss(max([$this->decisionIndicators['hmaChangeDirection']['priceTarget']]));
                $this->marketIfTouchedOrderPrice = max([$this->decisionIndicators['hmaChangeDirection']['priceTarget']]);
                $this->newShortPosition();
            }
        }
        elseif ($this->openPosition['side'] == 'short') {
            if ( $this->decisionIndicators['hmaChangeDirection']['currentTrendingSide'] == 'long' ) {
                $this->strategyLogger->logMessage("WE NEED TO CLOSE", 1);
                $this->closePosition();
                $this->marketIfTouchedOrderPrice = min([$this->decisionIndicators['hmaChangeDirection']['priceTarget']]);
                $this->newShortPosition();
            }
            else {
                $this->modifyStopLoss(max([$this->decisionIndicators['hmaChangeDirection']['priceTarget']]));
                $this->marketIfTouchedOrderPrice = max([$this->decisionIndicators['hmaChangeDirection']['priceTarget']]);
                $this->newLongPosition();
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