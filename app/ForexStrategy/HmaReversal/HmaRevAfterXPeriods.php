<?php

/********************************************
HmaRevAfterXPeriods Strategy System under HmaReversal Strategy
Created at: 03/27/19by Brian O'Neill
Description: HmaRevAfterXPeriods
********************************************/

namespace App\ForexStrategy\HmaReversal;
use \Log;
use \App\IndicatorEvents\HullMovingAverage;

class HmaRevAfterXPeriods extends \App\ForexStrategy\Strategy  {

    public $hmaLength;
    public $hmaChangeDirPeriods;
    public $hmaSlopeMin = 0;

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


        $this->decisionIndicators['hmaRevAfterXPeriods'] = $hmaEvents->hmaChangeDirectionForFirstTimeInXPeriods($this->rates['simple'], $this->hmaLength, $this->hmaChangeDirPeriods);

        $this->decisionIndicators['hmaMeetsSlopeMin'] = $hmaEvents->hmaSlopeMeetsMin($this->getRatesInPips($this->rates['simple']), $this->hmaLength, $this->exchange->pip, $this->hmaSlopeMin);

        $this->strategyLogger->logIndicators($this->decisionIndicators);

        if ($this->openPosition['side'] == 'long') {
            if ($this->decisionIndicators['hmaMeetsSlopeMin'] == 'short') {
                $this->closePosition();
            }

            if (
                $this->decisionIndicators['hmaRevAfterXPeriods'] == 'reversedDown'
            ) {
                $this->newShortPosition();
            }
        }
        elseif ($this->openPosition['side'] == 'short') {
            if ($this->decisionIndicators['hmaMeetsSlopeMin'] == 'long') {
                $this->closePosition();
            }

            if (
                $this->decisionIndicators['hmaRevAfterXPeriods'] == 'reversedUp'
            ) {
                $this->newShortPosition();
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