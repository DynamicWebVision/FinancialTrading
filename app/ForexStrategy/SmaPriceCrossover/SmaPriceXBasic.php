<?php

/********************************************
SmaPriceXBasic Strategy System under SmaPriceCrossover Strategy
Created at: 04/14/19by Brian O'Neill
Description: SmaPriceXBasic
********************************************/

namespace App\ForexStrategy\SmaPriceCrossover;
use \Log;
use \App\IndicatorEvents\SimpleMovingAverage;

class SmaPriceXBasic extends \App\ForexStrategy\Strategy  {

    public $smaLength;
    public $periodsOpenCutoff;

    public function setEntryIndicators() {
        $smaEvents = new \App\IndicatorEvents\SimpleMovingAverage;
        $smaEvents->strategyLogger = $this->strategyLogger;


        $this->decisionIndicators['smaPriceX'] = $smaEvents->priceCrossedOver($this->rates['simple'], $this->smaLength);

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


        $this->decisionIndicators['smaPriceRelation'] = $smaEvents->currentPriceRelationMA($this->rates['simple'], $this->smaLength);

        $this->strategyLogger->logIndicators($this->decisionIndicators);

        if ($this->openPosition['side'] == 'long' && $this->openPosition['periodsOpen'] >= $this->periodsOpenCutoff) {

            //A Conditions
            // $this->decisionIndicators['smaPriceX'] == 'crossedAbove'
            //B Conditions
            // $this->decisionIndicators['smaPriceX'] == 'crossedBelow'

            if ($this->decisionIndicators['smaPriceRelation'] == 'below') {
                $this->strategyLogger->logMessage("WE NEED TO CLOSE", 1);
                $this->closePosition();
            }
            //else {
                //$this->modifyStopLoss();
                //$this->newShortPosition();
            //}
        }
        elseif ($this->openPosition['side'] == 'short' && $this->openPosition['periodsOpen'] >= $this->periodsOpenCutoff) {
            if ( $this->decisionIndicators['smaPriceRelation'] == 'above') {
                $this->strategyLogger->logMessage("WE NEED TO CLOSE", 1);
                $this->closePosition();
            }
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