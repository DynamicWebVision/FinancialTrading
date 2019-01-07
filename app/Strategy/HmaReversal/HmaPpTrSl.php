<?php

/********************************************
HmaPpTrSl Strategy System under HmaReversal Strategy
Created at: 01/07/19by Brian O'Neill
Description: Hma system with a price point close and a Stop Loss based off of true range.
********************************************/

namespace App\Strategy\HmaReversal;
use \Log;
use \App\IndicatorEvents\HullMovingAverage;
use \App\IndicatorEvents\TrueRange;

class HmaPpTrSl extends \App\Strategy\Strategy  {

    public $hmaLength;
    public $hmaSlopeMin;
    public $trueRangeLength;
    public $trueRangeMultiplier;

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

        $trueRange = new \App\IndicatorEvents\TrueRange;
        $trueRange->strategyLogger = $this->strategyLogger;


        $this->strategyLogger->logIndicators($this->decisionIndicators);

        $this->decisionIndicators['hmaSlope'] = $hmaEvents->hmaSlope($this->rates['simple'], $this->hmaLength, $this->exchange->pip, $this->hmaSlopeMin);

        $this->decisionIndicators['priceAboveBelowHma'] = $hmaEvents->hmaPriceAboveBelowHma($this->rates['simple'], $this->hmaLength);

        $this->decisionIndicators['hmaChangeDirection'] = $hmaEvents->hullChangeDirectionCheck($this->rates['simple'], $this->hmaLength);

        $this->decisionIndicators['trueRanageBreakevenSL'] = $trueRange->getStopLossTrueRangeOrBreakEven($this->rates['full'], $this->trueRangeLength, $this->trueRangeMultiplier, $this->exchange->pip , $this->openPosition);

        if ($this->openPosition['side'] == 'long') {

            //A Conditions
            // $this->decisionIndicators['hmaSlope'] == 'long'
            // $this->decisionIndicators['priceAboveBelowHma'] == 'above'
            // $this->modifyStopLoss($this->decisionIndicators['trueRanageBreakevenSL']);
            //B Conditions
            // $this->decisionIndicators['hmaSlope'] == 'short'
            // $this->decisionIndicators['priceAboveBelowHma'] == 'below'
            // $this->modifyStopLoss($this->decisionIndicators['trueRanageBreakevenSL']);

            if ( $this->decisionIndicators['hmaSlope'] == 'short' ||  $this->decisionIndicators['priceAboveBelowHma'] == 'below') {
                $this->strategyLogger->logMessage("WE NEED TO CLOSE", 1);
                $this->closePosition();

                if ($this->decisionIndicators['hmaChangeDirection'] == 'reversedDown') {
                    $this->newShortPosition();
                }
            }
            else {
                $this->modifyStopLoss($this->decisionIndicators['trueRanageBreakevenSL']);
            }
        }
        elseif ($this->openPosition['side'] == 'short') {
            if ( $this->decisionIndicators['hmaSlope'] == 'long' ||  $this->decisionIndicators['priceAboveBelowHma'] == 'above' ) {
                $this->strategyLogger->logMessage("WE NEED TO CLOSE", 1);
                $this->closePosition();

                if ($this->decisionIndicators['hmaChangeDirection'] == 'reversedUp') {
                    $this->newLongPosition();
                }

            }
            else {
                $this->modifyStopLoss($this->decisionIndicators['trueRanageBreakevenSL']);
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