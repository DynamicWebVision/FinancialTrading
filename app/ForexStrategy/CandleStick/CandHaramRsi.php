<?php

/********************************************
CandHaramRsi Strategy System under CandleStick Strategy
Created at: 06/07/19by Brian O'Neill
Description: CandHaramRsi
********************************************/

namespace App\ForexStrategy\CandleStick;
use \Log;
use \App\IndicatorEvents\CandlePatterns;
use \App\IndicatorEvents\RsiEvents;

class CandHaramRsi extends \App\ForexStrategy\Strategy  {

    public $rsiLength;
    public $rsiCutoff;
    public $rsiInPositionCutoff;

    public function setEntryIndicators() {
        $candlePatterns = new \App\IndicatorEvents\CandlePatterns;
        $candlePatterns->strategyLogger = $this->strategyLogger;

        $rsiEvents = new \App\IndicatorEvents\RsiEvents;
        $rsiEvents->strategyLogger = $this->strategyLogger;


        $this->decisionIndicators['harami'] = $candlePatterns->harami($this->rates['full']);

        $this->decisionIndicators['rsiOutsideLevel'] = $rsiEvents->outsideLevel(
            $this->rates['simple'], $this->rsiLength, $this->rsiCutoff);

    }

    public function getEntryDecision() {
        $this->setEntryIndicators();
        $this->strategyLogger->logIndicators($this->decisionIndicators);

        if (
        $this->decisionIndicators['harami'] == 'long'
         && $this->decisionIndicators['rsiOutsideLevel'] == 'overBoughtShort'
        ) {
            $this->newLongPosition();
        }
        elseif (
        $this->decisionIndicators['harami'] == 'short'
         && $this->decisionIndicators['rsiOutsideLevel'] == 'overBoughtLong'
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