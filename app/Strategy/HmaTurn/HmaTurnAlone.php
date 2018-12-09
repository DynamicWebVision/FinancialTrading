<?php

/********************************************
HmaTurnAlone Strategy System under HmaTurn Strategy
Created at: 12/09/18by Brian O'Neill
Description: This is the HmaTurn alone, all by itself.
********************************************/

namespace App\Strategy\HmaTurn;
use \Log;
use \App\IndicatorEvents\HullMovingAverage;

class HmaTurnAlone extends \App\Strategy\Strategy  {

    public $hmaLength;

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
            //$this->marketIfTouchedOrderPrice = ;
        }
        elseif (
        $this->decisionIndicators['hmaChangeDirection'] == 'reversedDown'
        ) {
            $this->newShortPosition();
            //$this->marketIfTouchedOrderPrice = ;
        }
    }

    public function inPositionDecision() {
        $hmaEvents = new \App\IndicatorEvents\HullMovingAverage;
        $hmaEvents->strategyLogger = $this->strategyLogger;


        $this->strategyLogger->logIndicators($this->decisionIndicators);

        $this->decisionIndicators['hmaChangeDirection'] = $hmaEvents->hullChangeDirectionCheck($this->rates['simple'], $this->hmaLength);

        if ($this->openPosition['side'] == 'long') {
            if ( $this->decisionIndicators['hmaChangeDirection'] == 'reversedDown' ) {
                $this->closePosition();
                $this->newShortPosition();
            }
        }
        elseif ($this->openPosition['side'] == 'short') {
            if ( $this->decisionIndicators['hmaChangeDirection'] == 'reversedUp' ) {
                $this->closePosition();
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