<?php

/********************************************
MacdHistTest Strategy System under MacdHistogramX Strategy
Created at: 09/06/19by Brian O'Neill
Description: MacdHistTest
********************************************/

namespace App\ForexStrategy\MacdHistogramX;
use \Log;
use \App\IndicatorEvents\Macd;

class MacdHistTest extends \App\ForexStrategy\Strategy  {

    public $$macdShortPeriod;
    public $$macdLongPeriod;
    public $$macdSignalPeriod;

    public function setEntryIndicators() {
        $macd = new \App\IndicatorEvents\Macd;
        $macd->strategyLogger = $this->strategyLogger;


        $this->decisionIndicators['macdHistogramX'] = $macd->histogramCrossover($this->rates['simple'], $this->macdShortPeriod, $this->macdLongPeriod, $this->macdSignalPeriod);

    }

    public function getEntryDecision() {
        $this->setEntryIndicators();
        $this->strategyLogger->logIndicators($this->decisionIndicators);

        if (
        $this->decisionIndicators['macdHistogramX'] == 'crossedAbove'
        ) {
            $this->newLongPosition();
        }
        elseif (
        $this->decisionIndicators['macdHistogramX'] == 'crossedBelow'
        ) {
            $this->newShortPosition();
        }
    }

    public function inPositionDecision() {
        $macd = new \App\IndicatorEvents\Macd;
        $macd->strategyLogger = $this->strategyLogger;


        $this->decisionIndicators['macdHistogramX'] = $macd->histogramCrossover($this->rates['simple'], $this->macdShortPeriod, $this->macdLongPeriod, $this->macdSignalPeriod);

        $this->strategyLogger->logIndicators($this->decisionIndicators);

        if ($this->openPosition['side'] == 'long') {

            //A Conditions
            // $this->decisionIndicators['macdHistogramX'] == 'crossedAbove'
            //B Conditions
            // $this->decisionIndicators['macdHistogramX'] == 'crossedBelow'

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