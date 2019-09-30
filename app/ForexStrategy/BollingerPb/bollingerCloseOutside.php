<?php

/********************************************
bollingerCloseOutside Strategy System under BollingerPb Strategy
Created at: 09/06/19by Brian O'Neill
Description: bollingerCloseOutside
********************************************/

namespace App\ForexStrategy\BollingerPb;
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
            $this->newShortPosition();
        }
        elseif (
        $this->decisionIndicators['bollingerCloseOutside'] == 'below'
        ) {
            $this->newLongPosition();
        }
    }

    public function inPositionDecision() {
        $bollinger = new \App\IndicatorEvents\Bollinger;
        $bollinger->strategyLogger = $this->strategyLogger;


        $this->decisionIndicators['bollingerCloseOutside'] = $bollinger->bollingerCloseOutside($this->rates['simple'],
            $this->bollingerLength, $this->bollingerSdMultiplier, $this->rates['full']);

        $this->strategyLogger->logIndicators($this->decisionIndicators);

        if ($this->openPosition['side'] == 'long') {
            if ($this->decisionIndicators['bollingerCloseOutside'] == 'above') {
                $this->strategyLogger->logMessage("WE NEED TO CLOSE", 1);
                $this->closePosition();
                $this->newShortPosition();
            }
        }
        elseif ($this->openPosition['side'] == 'short') {
            if ( $this->decisionIndicators['bollingerCloseOutside'] == 'below' ) {
                $this->strategyLogger->logMessage("WE NEED TO CLOSE", 1);
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