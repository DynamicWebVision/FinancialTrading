<?php

/********************************************
HmaRevPriceClose Strategy System under HmaReversal Strategy
Created at: 01/04/19by Brian O'Neill
Description: This is Hma reversal where we close the position when the price
********************************************/

namespace App\Strategy\HmaReversal;
use \Log;
use \App\IndicatorEvents\HullMovingAverage;

class HmaRevPriceClose extends \App\Strategy\Strategy  {

    public $hmaLength;
    public $hmaSlopeMin;

    public function setEntryIndicators() {
        $hmaEvents = new \App\IndicatorEvents\HullMovingAverage;
        $hmaEvents->strategyLogger = $this->strategyLogger;


        $this->decisionIndicators['hmaChangeDirection'] = $hmaEvents->hullChangeDirectionCheck($this->rates['simple'], $this->hmaLength);
        $this->decisionIndicators['priceAboveBelowHma'] = $hmaEvents->hmaPriceAboveBelowHma($this->rates['simple'], $this->hmaLength);

    }

    public function getEntryDecision() {
        $this->setEntryIndicators();
        $this->strategyLogger->logIndicators($this->decisionIndicators);

        if (
        $this->decisionIndicators['hmaChangeDirection'] == 'reversedUp' && $this->decisionIndicators['priceAboveBelowHma'] == 'above'
        ) {
            $this->newLongPosition();
        }
        elseif (
        $this->decisionIndicators['hmaChangeDirection'] == 'reversedDown' && $this->decisionIndicators['priceAboveBelowHma'] == 'below'
        ) {
            $this->newShortPosition();
        }
    }

    public function inPositionDecision() {
        $hmaEvents = new \App\IndicatorEvents\HullMovingAverage;
        $hmaEvents->strategyLogger = $this->strategyLogger;


        $this->strategyLogger->logIndicators($this->decisionIndicators);

        $this->decisionIndicators['priceAboveBelowHma'] = $hmaEvents->hmaPriceAboveBelowHma($this->rates['simple'], $this->hmaLength);

        $this->decisionIndicators['hmaMeetsSlopeMin'] = $hmaEvents->hmaSlopeMeetsMin($this->rates['simple'], $this->hmaLength, $this->exchange->pip, $this->hmaSlopeMin);

        if ($this->openPosition['side'] == 'long') {
            //A Conditions
            // $this->decisionIndicators['priceAboveBelowHma'] == 'above'
            // $this->decisionIndicators['hmaMeetsSlopeMin'] == 'long'
            //B Conditions
            // $this->decisionIndicators['priceAboveBelowHma'] == 'below'
            // $this->decisionIndicators['hmaMeetsSlopeMin'] == 'short'

            if ( $this->decisionIndicators['priceAboveBelowHma'] == 'below' ||  $this->decisionIndicators['hmaMeetsSlopeMin'] == 'short') {
                $this->strategyLogger->logMessage("WE NEED TO CLOSE", 1);
                $this->closePosition();
            }
        }
        elseif ($this->openPosition['side'] == 'short') {
            if ( $this->decisionIndicators['priceAboveBelowHma'] == 'above' ||  $this->decisionIndicators['hmaMeetsSlopeMin'] == 'long') {
                $this->strategyLogger->logMessage("WE NEED TO CLOSE", 1);
                $this->closePosition();
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