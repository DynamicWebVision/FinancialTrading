<?php

/********************************************
HmaRevAfterPeriodsHold Strategy System under HmaReversal Strategy
Created at: 04/15/19by Brian O'Neill
Description: ThisHmaRevAfterPeriods
********************************************/

namespace App\ForexStrategy\HmaReversal;
use \Log;
use \App\IndicatorEvents\HullMovingAverage;
use \App\IndicatorEvents\PeriodsOpenEvents;

class HmaRevAfterPeriodsHold extends \App\ForexStrategy\Strategy  {

    public $hmaLength;
    public $hmaChangeDirPeriods;
    public $hmaSlopeMin;
    public $periodsOpenMultiplier;

    public function setEntryIndicators() {
        $hmaEvents = new \App\IndicatorEvents\HullMovingAverage;
        $hmaEvents->strategyLogger = $this->strategyLogger;


        $this->decisionIndicators['hmaRevAfterXPeriods'] = $hmaEvents->hmaChangeDirectionForFirstTimeInXPeriods($this->rates['simple'], $this->hmaLength, $this->hmaChangeDirPeriods);

    }

    public function getEntryDecision() {
        $this->setEntryIndicators();
        $this->strategyLogger->logIndicators($this->decisionIndicators);

        if (
        $this->decisionIndicators['hmaRevAfterXPeriods'] == 'reversedUp'
        ) {
            $this->newLongPosition();
        }
        elseif (
        $this->decisionIndicators['hmaRevAfterXPeriods'] == 'reversedDown'
        ) {
            $this->newShortPosition();
        }
    }

    public function inPositionDecision() {
        $hmaEvents = new \App\IndicatorEvents\HullMovingAverage;
        $hmaEvents->strategyLogger = $this->strategyLogger;

        $periodsOpenEvents = new \App\IndicatorEvents\PeriodsOpenEvents;
        $periodsOpenEvents->strategyLogger = $this->strategyLogger;


        $this->decisionIndicators['hmaSlope'] = $hmaEvents->hmaSlopeMeetsMin($this->rates['simple'], $this->hmaLength, $this->exchange->pip, $this->hmaSlopeMin);

        $this->decisionIndicators['periodsOpenCutoff'] = $periodsOpenEvents->openPositionIndicatorDrivenCheck($this->openPosition, $this->hmaLength, $this->periodsOpenMultiplier);

        $this->decisionIndicators['hmaRevAfterXPeriods'] = $hmaEvents->hmaChangeDirectionForFirstTimeInXPeriods($this->rates['simple'], $this->hmaLength, $this->hmaChangeDirPeriods);

        $this->strategyLogger->logIndicators($this->decisionIndicators);

        if ($this->openPosition['side'] == 'long') {

            //A Conditions
            // $this->decisionIndicators['hmaMeetsSlopeMin'] == 'long'
            // $this->decisionIndicators['periodsOpenCutoff']
            //B Conditions
            // $this->decisionIndicators['hmaMeetsSlopeMin'] == 'short'
            // $this->decisionIndicators['periodsOpenCutoff']

            if (  $this->decisionIndicators['periodsOpenCutoff']) {
//            if ( $this->decisionIndicators['hmaSlope'] == 'short' || $this->decisionIndicators['periodsOpenCutoff']) {
                $this->strategyLogger->logMessage("WE NEED TO CLOSE", 1);
                $this->closePosition();
            }

            if (
                $this->decisionIndicators['hmaRevAfterXPeriods'] == 'reversedDown'
            ) {
                $this->newShortPosition();
            }
        }
        elseif ($this->openPosition['side'] == 'short') {
            if (  $this->decisionIndicators['periodsOpenCutoff']) {
//            if ( $this->decisionIndicators['hmaSlope'] == 'long' || $this->decisionIndicators['periodsOpenCutoff']) {
                $this->strategyLogger->logMessage("WE NEED TO CLOSE", 1);
                $this->closePosition();
            }

            if (
                $this->decisionIndicators['hmaRevAfterXPeriods'] == 'reversedUp'
            ) {
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