<?php

/********************************************
ShootingStarRsi Strategy System under CandleStick Strategy
Created at: 06/07/19by Brian O'Neill
Description: ShootingStarRsi
********************************************/

namespace App\ForexStrategy\CandleStick;
use \Log;
use \App\IndicatorEvents\RsiEvents;
use \App\IndicatorEvents\CandlePatterns;

class ShootingStarRsi extends \App\ForexStrategy\Strategy  {

    public $rsiLength;
    public $rsiCutoff;
    public $rsiInPositionCutoff;

    public function setEntryIndicators() {
        $rsiEvents = new \App\IndicatorEvents\RsiEvents;
        $rsiEvents->strategyLogger = $this->strategyLogger;

        $candlePatterns = new \App\IndicatorEvents\CandlePatterns;
        $candlePatterns->strategyLogger = $this->strategyLogger;


        $this->decisionIndicators['rsiOutsideLevel'] = $rsiEvents->outsideLevel(
            $this->rates['simple'], $this->rsiLength, $this->rsiCutoff);

        $this->decisionIndicators['hammerShootingStar'] = $candlePatterns->hammerShootingStarCheck($this->rates['full']);

    }

    public function getEntryDecision() {
        $this->setEntryIndicators();
        $this->strategyLogger->logIndicators($this->decisionIndicators);

        if (
        $this->decisionIndicators['rsiOutsideLevel'] == 'overBoughtShort'
         && $this->decisionIndicators['hammerShootingStar'] == 'hammer'
        ) {
            $this->newLongPosition();
        }
        elseif (
        $this->decisionIndicators['rsiOutsideLevel'] == 'overBoughtLong'
         && $this->decisionIndicators['hammerShootingStar'] == 'shooting-star'
        ) {
            $this->newShortPosition();
        }
    }

    public function inPositionDecision() {
        $rsiEvents = new \App\IndicatorEvents\RsiEvents;
        $rsiEvents->strategyLogger = $this->strategyLogger;


        $this->decisionIndicators['rsiOutsideLevel'] = $rsiEvents->outsideLevel(
            $this->rates['simple'], $this->rsiLength, $this->rsiInPositionCutoff);

        $this->strategyLogger->logIndicators($this->decisionIndicators);

        if ($this->openPosition['side'] == 'long') {

            //A Conditions
            // $this->decisionIndicators['rsiOutsideLevel'] == 'overBoughtShort'
            //B Conditions
            // $this->decisionIndicators['rsiOutsideLevel'] == 'overBoughtLong'

            if ( $this->decisionIndicators['rsiOutsideLevel'] == 'overBoughtLong' ) {
                $this->strategyLogger->logMessage("WE NEED TO CLOSE", 1);
                $this->closePosition();
            }
        }
        elseif ($this->openPosition['side'] == 'short') {
            if ( $this->decisionIndicators['rsiOutsideLevel'] == 'overBoughtShort' ) {
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