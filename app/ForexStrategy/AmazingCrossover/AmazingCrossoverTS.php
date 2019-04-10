<?php

/********************************************
AmazingCrossoverTS Strategy System under AmazingCrossover Strategy
Created at: 04/10/19by Brian O'Neill
Description: This is the amazing crossover system with a trailing stop.
********************************************/

namespace App\ForexStrategy\AmazingCrossover;
use \Log;
use \App\IndicatorEvents\EmaEvents;
use \App\IndicatorEvents\RsiEvents;

class AmazingCrossoverTS extends \App\ForexStrategy\Strategy  {

    public $emaFastLength = 5;
    public $emaSlowLength = 10;
    public $rsiLength = 10;
    public $rsiBreakthroughLevel = 50;

    public function setEntryIndicators() {
        $emaEvents = new \App\IndicatorEvents\EmaEvents;
        $emaEvents->strategyLogger = $this->strategyLogger;

        $rsiEvents = new \App\IndicatorEvents\RsiEvents;
        $rsiEvents->strategyLogger = $this->strategyLogger;


        $this->decisionIndicators['emaCrossover'] = $emaEvents->checkTwoLineCrossover($this->rates['simple'], $this->emaFastLength, $this->emaSlowLength);

        $this->decisionIndicators['rsiCrossedLevel'] = $rsiEvents->crossedLevel($this->rates['simple'], $this->rsiLength, $this->rsiBreakthroughLevel);

    }

    public function getEntryDecision() {
        $this->setEntryIndicators();
        $this->strategyLogger->logIndicators($this->decisionIndicators);

        if (
        $this->decisionIndicators['emaCrossover'] == 'crossedAbove'
         && $this->decisionIndicators['rsiCrossedLevel'] == 'crossedAbove'
        ) {
            $this->newLongPosition();
        }
        elseif (
        $this->decisionIndicators['emaCrossover'] == 'crossedBelow'
         && $this->decisionIndicators['rsiCrossedLevel'] == 'crossedBelow'
        ) {
            $this->newShortPosition();
        }
    }

    public function inPositionDecision() {

        $this->strategyLogger->logIndicators($this->decisionIndicators);

        if ($this->openPosition['side'] == 'long') {

            //A Conditions
            //B Conditions

            //if ( CONDITIONS THAT CONTRADICT LONG ) {
                //$this->strategyLogger->logMessage("WE NEED TO CLOSE", 1);
                //$this->closePosition();
                //$this->newLongPosition();
            //}
            //else {
                //$this->modifyStopLoss();
                //$this->newShortPosition();
            //}
        }
        elseif ($this->openPosition['side'] == 'short') {
            //if ( CONDITIONS THAT CONTRADICT SHORT ) {
                //$this->strategyLogger->logMessage("WE NEED TO CLOSE", 1);
                //$this->closePosition();
                //$this->newShortPosition();
            //}
            //else {
                //$this->modifyStopLoss();
                //$this->newLongPosition();
            //}
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