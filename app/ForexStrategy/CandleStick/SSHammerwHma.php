<?php

/********************************************
SSHammerwHma Strategy System under CandleStick Strategy
Created at: 06/04/19by Brian O'Neill
Description: SSHammerwHma
********************************************/

namespace App\ForexStrategy\CandleStick;
use \Log;
use \App\IndicatorEvents\CandlePatterns;
use \App\IndicatorEvents\HullMovingAverage;

class SSHammerwHma extends \App\ForexStrategy\Strategy  {

    public $takeProfitMultiplier;
    public $hmaLength;
    public $hmaSlopeMin;

    public function setEntryIndicators() {
        $candlePatterns = new \App\IndicatorEvents\CandlePatterns;
        $candlePatterns->strategyLogger = $this->strategyLogger;

        $hmaEvents = new \App\IndicatorEvents\HullMovingAverage;
        $hmaEvents->strategyLogger = $this->strategyLogger;

        $this->decisionIndicators['hammerShootingStar'] = $candlePatterns->hammerShootingStarCheck($this->rates['full']);

        $this->decisionIndicators['hmaSlope'] = $hmaEvents->hmaSlopeMeetsMin($this->rates['simple'], $this->hmaLength, $this->exchange->pip, $this->hmaSlopeMin);



    }

    public function getEntryDecision() {
        $candlePatterns = new \App\IndicatorEvents\CandlePatterns;
        $candlePatterns->strategyLogger = $this->strategyLogger;

        $this->setEntryIndicators();
        $this->strategyLogger->logIndicators($this->decisionIndicators);

        if (
        $this->decisionIndicators['hammerShootingStar'] == 'hammer'
         && $this->decisionIndicators['hmaSlope'] == 'short'
        ) {
            $this->stopLossPipAmount = $candlePatterns->previousWickStopLoss($this->currentPriceData, end($this->rates['full']), 'long', $this->exchange->pip);
            $this->takeProfitPipAmount = round($this->stopLossPipAmount*$this->takeProfitMultiplier);
            $this->newLongPosition();
        }
        elseif (
        $this->decisionIndicators['hammerShootingStar'] == 'shooting-star'
         && $this->decisionIndicators['hmaSlope'] == 'long'
        ) {
            $this->stopLossPipAmount = $candlePatterns->previousWickStopLoss($this->currentPriceData, end($this->rates['full']), 'short', $this->exchange->pip);
            $this->takeProfitPipAmount = round($this->stopLossPipAmount*$this->takeProfitMultiplier);
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
    public function checkForNewPosition()
    {
        $this->setOpenPosition();

        if (!$this->openPosition) {
            $this->decision = $this->getEntryDecision();
        }
    }
}