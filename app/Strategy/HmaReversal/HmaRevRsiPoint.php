<?php

/********************************************
HmaRevRsiPoint Strategy System under HmaReversal Strategy
Created at: 12/16/18by Brian O'Neill
Description: This is an Hma Reversal with an RSI point confirm.
********************************************/

namespace App\Strategy\HmaReversal;
use \Log;
use \App\IndicatorEvents\HullMovingAverage;
use \App\IndicatorEvents\RsiEvents;

class HmaRevRsiPoint extends \App\Strategy\Strategy  {

    public $hmaLength;
    public $rsiLength;
    public $rsiOuterLimit;
    public $rsiOpenPositionOuterLimit;

    public function setEntryIndicators() {
        $hmaEvents = new \App\IndicatorEvents\HullMovingAverage;
        $hmaEvents->strategyLogger = $this->strategyLogger;

        $rsiEvents = new \App\IndicatorEvents\RsiEvents;
        $rsiEvents->strategyLogger = $this->strategyLogger;


        $this->decisionIndicators['hmaChangeDirection'] = $hmaEvents->hullChangeDirectionCheck($this->rates['simple'], $this->hmaLength);

        $this->decisionIndicators['rsiOutsideLevel'] = $rsiEvents->outsideLevel($this->rates['simple'], $this->rsiLength, $this->rsiOuterLimit);

    }

    public function getEntryDecision() {
        $this->setEntryIndicators();
        $this->strategyLogger->logIndicators($this->decisionIndicators);

        if (
        $this->decisionIndicators['hmaChangeDirection'] == 'reversedUp'
         && $this->decisionIndicators['rsiOutsideLevel'] == 'overBoughtShort'
        ) {
            $this->newLongPosition();
        }
        elseif (
        $this->decisionIndicators['hmaChangeDirection'] == 'reversedDown'
         && $this->decisionIndicators['rsiOutsideLevel'] == 'overBoughtLong'
        ) {
            $this->newShortPosition();
        }
    }

    public function inPositionDecision() {
        $hmaEvents = new \App\IndicatorEvents\HullMovingAverage;
        $hmaEvents->strategyLogger = $this->strategyLogger;

        $rsiEvents = new \App\IndicatorEvents\RsiEvents;
        $rsiEvents->strategyLogger = $this->strategyLogger;


        $this->strategyLogger->logIndicators($this->decisionIndicators);

        $this->decisionIndicators['hmaChangeDirection'] = $hmaEvents->hullChangeDirectionCheck($this->rates['simple'], $this->hmaLength);

        $this->decisionIndicators['rsiOutsideLevel'] = $rsiEvents->outsideLevel($this->rates['simple'], $this->rsiLength, $this->rsiOpenPositionOuterLimit);

        if ($this->openPosition['side'] == 'long') {
            //A Conditions
            // $this->decisionIndicators['hmaChangeDirection'] == 'reversedUp'
            // $this->decisionIndicators['rsiOutsideLevel'] == 'overBoughtShort'
            //B Conditions
            // $this->decisionIndicators['hmaChangeDirection'] == 'reversedDown'
            // $this->decisionIndicators['rsiOutsideLevel'] == 'overBoughtLong'

            if ( $this->decisionIndicators['rsiOutsideLevel'] == 'overBoughtShort' || $this->decisionIndicators['hmaChangeDirection'] == 'reversedUp') {
                $this->strategyLogger->logMessage("WE NEED TO CLOSE", 1);
                $this->closePosition();
            }
        }
        elseif ($this->openPosition['side'] == 'short') {
            if ( $this->decisionIndicators['rsiOutsideLevel'] == 'overBoughtLong' || $this->decisionIndicators['hmaChangeDirection'] == 'reversedDown') {
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