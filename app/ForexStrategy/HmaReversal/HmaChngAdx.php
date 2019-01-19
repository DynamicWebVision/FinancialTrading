<?php

/********************************************
HmaChngAdx Strategy System under HmaReversal Strategy
Created at: 12/30/18by Brian O'Neill
Description: This is an Hma reversal where it waits for the change to occur rather than entering on the price point. It also has an ADX minimum confirmation.
********************************************/

namespace App\ForexStrategy\HmaReversal;
use \Log;
use \App\IndicatorEvents\HullMovingAverage;
use \App\IndicatorEvents\AdxEvents;
use \App\IndicatorEvents\TrueRange;

class HmaChngAdx extends \App\ForexStrategy\Strategy  {

    public $hmaLength;
    public $adxLength;
    public $adxUndersoldThreshold;
    public $hmaSlopeMin;
    public $trueRangeLength = 14;
    public $trueRangeMultiplier = 1;

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

        $trueRange = new \App\IndicatorEvents\TrueRange();
        $trueRange->strategyLogger = $this->strategyLogger;

        $this->strategyLogger->logIndicators($this->decisionIndicators);

        $this->decisionIndicators['hmaChangeDirection'] = $hmaEvents->hullChangeDirectionCheck($this->rates['simple'], $this->hmaLength);

        $this->decisionIndicators['hmaMeetsSlopeMin'] = $hmaEvents->hmaSlopeMeetsMin($this->getRatesInPips($this->rates['simple']), $this->hmaLength, $this->exchange->pip, $this->hmaSlopeMin);

        $this->decisionIndicators['trueRanageBreakevenSL'] = $trueRange->getStopLossTrueRangeOrBreakEven($this->rates['full'], $this->trueRangeLength, $this->trueRangeMultiplier, $this->exchange->pip , $this->openPosition);

        if ($this->openPosition['side'] == 'long') {
            //A Conditions
            // $this->decisionIndicators['hmaChangeDirection'] == 'reversedUp'
            // $this->decisionIndicators['hmaMeetsSlopeMin'] == 'long'
            //B Conditions
            // $this->decisionIndicators['hmaChangeDirection'] == 'reversedDown'
            // $this->decisionIndicators['hmaMeetsSlopeMin'] == 'short'

            if ( $this->decisionIndicators['hmaMeetsSlopeMin'] == 'short' ) {
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
            if ( $this->decisionIndicators['hmaMeetsSlopeMin'] == 'long' ) {
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