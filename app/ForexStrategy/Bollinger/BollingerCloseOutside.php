<?php

/********************************************
BollingerCloseOutside Strategy System under Bollinger Strategy
Created at: 09/06/19by Brian O'Neill
Description: BollingerCloseOutside
********************************************/

namespace App\ForexStrategy\Bollinger;
use \Log;
use \App\IndicatorEvents\Bollinger;

class BollingerCloseOutside extends \App\ForexStrategy\Strategy  {

    public $bollingerLength;
    public $bollingerSdMultiplier;

    public function setEntryIndicators() {
        $bollinger = new \App\IndicatorEvents\Bollinger;
        $bollinger->strategyLogger = $this->strategyLogger;


        $this->decisionIndicators['bollingerCloseOutside'] = $bollinger->bollingerCloseOutside($this->rates['simple'],
            $this->bollingerLength, $this->bollingerSdMultiplier, $this->rates['full']);

    }

    public function getEntryDecision() {
        $this->setEntryIndicators();
        $this->strategyLogger->logIndicators($this->decisionIndicators);

        if (
        $this->decisionIndicators['bollingerCloseOutside'] == 'above'
        ) {
            $this->newLongPosition();
        }
        elseif (
        $this->decisionIndicators['bollingerCloseOutside'] == 'below'
        ) {
            $this->newShortPosition();
        }
    }

    public function inPositionDecision() {
        $bollinger = new \App\IndicatorEvents\Bollinger;
        $bollinger->strategyLogger = $this->strategyLogger;


        $this->decisionIndicators['bollingerCloseOutside'] = $bollinger->bollingerCloseOutside($this->rates['simple'],
            $this->bollingerLength, $this->bollingerSdMultiplier, $this->rates['full']);

        $this->strategyLogger->logIndicators($this->decisionIndicators);

        if ($this->openPosition['side'] == 'long') {

            //A Conditions
            // $this->decisionIndicators['bollingerCloseOutside'] == 'above'
            //B Conditions
            // $this->decisionIndicators['bollingerCloseOutside'] == 'below'

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