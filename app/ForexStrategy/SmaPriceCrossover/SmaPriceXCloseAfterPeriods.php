<?php

/********************************************
SmaPriceXCloseAfterPeriods Strategy System under SmaPriceCrossover Strategy
Created at: 05/05/19by Brian O'Neill
Description: SmaPriceXCloseAfterPeriods
********************************************/

namespace App\ForexStrategy\SmaPriceCrossover;
use \Log;
use \App\IndicatorEvents\SimpleMovingAverage;
use \App\IndicatorEvents\PeriodsOpenEvents;

class SmaPriceXCloseAfterPeriods extends \App\ForexStrategy\Strategy  {

    public $smaLength;
    public $smaPeriodsWithoutX;
    public $periodsOpenMultiplier;

    public function setEntryIndicators() {
        $smaEvents = new \App\IndicatorEvents\SimpleMovingAverage;
        $smaEvents->strategyLogger = $this->strategyLogger;


        $this->decisionIndicators['smaPriceX'] = $smaEvents->priceCrossedOverFirstTimeInXPeriods($this->rates['simple'], $this->smaLength, $this->smaPeriodsWithoutX);

    }

    public function getEntryDecision() {
        $this->setEntryIndicators();
        $this->strategyLogger->logIndicators($this->decisionIndicators);

        if (
        $this->decisionIndicators['smaPriceX'] == 'crossedAbove'
        ) {
            $this->newLongPosition();
        }
        elseif (
        $this->decisionIndicators['smaPriceX'] == 'crossedBelow'
        ) {
            $this->newShortPosition();
        }
    }

    public function inPositionDecision() {
        $smaEvents = new \App\IndicatorEvents\SimpleMovingAverage;
        $smaEvents->strategyLogger = $this->strategyLogger;

        $periodsOpenEvents = new \App\IndicatorEvents\PeriodsOpenEvents;
        $periodsOpenEvents->strategyLogger = $this->strategyLogger;


        $this->decisionIndicators['smaPriceX'] = $smaEvents->priceCrossedOverFirstTimeInXPeriods($this->rates['simple'], $this->smaLength, $this->smaPeriodsWithoutX);

        $this->decisionIndicators['periodsOpenCutoff'] = $periodsOpenEvents->openPositionIndicatorDrivenCheck($this->openPosition, $this->smaLength, $this->periodsOpenMultiplier);

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

                if (
                    $this->decisionIndicators['smaPriceX'] == 'crossedBelow'
                ) {
                    $this->newShortPosition();
                }
            }
        }
        elseif ($this->openPosition['side'] == 'short') {
            if (  $this->decisionIndicators['periodsOpenCutoff']) {
//            if ( $this->decisionIndicators['hmaSlope'] == 'long' || $this->decisionIndicators['periodsOpenCutoff']) {
                $this->strategyLogger->logMessage("WE NEED TO CLOSE", 1);
                $this->closePosition();

                if (
                    $this->decisionIndicators['smaPriceX'] == 'crossedAbove'
                ) {
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