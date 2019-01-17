<?php

/********************************************
HmaPpTrBeSl Strategy System under HmaPricePoint Strategy
Created at: 01/17/19by Brian O'Neill
Description: This is an Hma reversal based on price point that also has stop loss rules centered around True range and break even.
********************************************/

namespace App\Strategy\HmaPricePoint;
use \Log;
use \App\IndicatorEvents\HullMovingAverage;
use \App\IndicatorEvents\TrueRange;

class HmaPpTrBeSl extends \App\Strategy\Strategy  {

    public $hmaLength;
    public $trueRangeLength;
    public $trueRangeMultiplierOpen;
    public $trueRangeMultiplier;

    public function setEntryIndicators() {
        $hmaEvents = new \App\IndicatorEvents\HullMovingAverage;
        $hmaEvents->strategyLogger = $this->strategyLogger;

        $trueRange = new \App\IndicatorEvents\TrueRange;
        $trueRange->strategyLogger = $this->strategyLogger;


        $this->decisionIndicators['hmaChangeDirection'] = $hmaEvents->hullChangeDirectionPoint($this->rates['simple'], $this->hmaLength);

        $this->stopLossPipAmount = $trueRange->getStopLossPipValue($this->rates['full'], $this->trueRangeLength, $this->exchange->pip, $this->trueRangeMultiplierOpen);

    }

    public function getEntryDecision() {
        $this->setEntryIndicators();
        $this->strategyLogger->logIndicators($this->decisionIndicators);

        if (
        $this->decisionIndicators['hmaChangeDirection']['currentTrendingSide'] == 'short'
        ) {
            $this->marketIfTouchedOrderPrice = max([$this->decisionIndicators['hmaChangeDirection']['priceTarget']]);
            $this->newLongPosition();
        }
        elseif (
        $this->decisionIndicators['hmaChangeDirection']['currentTrendingSide'] == 'long'
        ) {
            $this->marketIfTouchedOrderPrice = min([$this->decisionIndicators['hmaChangeDirection']['priceTarget']]);
            $this->newShortPosition();
        }
    }

    public function inPositionDecision() {
        $hmaEvents = new \App\IndicatorEvents\HullMovingAverage;
        $hmaEvents->strategyLogger = $this->strategyLogger;

        $trueRange = new \App\IndicatorEvents\TrueRange;
        $trueRange->strategyLogger = $this->strategyLogger;

        $this->decisionIndicators['hmaChangeDirection'] = $hmaEvents->hullChangeDirectionPoint($this->rates['simple'], $this->hmaLength);

        $this->decisionIndicators['trueRangeBreakevenSLMP'] = $trueRange->getStopLossTrueRangeOrBreakEvenMostProfitable($this->rates['full'], $this->trueRangeLength,
            $this->trueRangeMultiplier, $this->exchange->pip , $this->openPosition);

        $this->strategyLogger->logIndicators($this->decisionIndicators);

        if ($this->openPosition['side'] == 'long') {

            //A Conditions
            // $this->modifyStopLoss($this->decisionIndicators['trueRangeBreakevenSLMP']);
            //B Conditions
            // $this->modifyStopLoss($this->decisionIndicators['trueRangeBreakevenSLMP']);

            if ( $this->decisionIndicators['hmaChangeDirection']['currentTrendingSide'] == 'short' ) {
                $this->strategyLogger->logMessage("WE NEED TO CLOSE", 1);
                $this->closePosition();
                $this->marketIfTouchedOrderPrice = max([$this->decisionIndicators['hmaChangeDirection']['priceTarget']]);
                $this->newLongPosition();
            }
            else {
                $this->modifyStopLoss(max([$this->decisionIndicators['hmaChangeDirection']['priceTarget'], $this->decisionIndicators['trueRangeBreakevenSLMP']]));
                $this->marketIfTouchedOrderPrice = max([$this->decisionIndicators['hmaChangeDirection']['priceTarget']]);
                $this->newShortPosition();
            }
        }
        elseif ($this->openPosition['side'] == 'short') {
            if ( $this->decisionIndicators['hmaChangeDirection']['currentTrendingSide'] == 'long' ) {
                $this->strategyLogger->logMessage("WE NEED TO CLOSE", 1);
                $this->closePosition();
                $this->marketIfTouchedOrderPrice = min([$this->decisionIndicators['hmaChangeDirection']['priceTarget']]);
                $this->newShortPosition();
            }
            else {
                $this->modifyStopLoss(min([$this->decisionIndicators['hmaChangeDirection']['priceTarget'], $this->decisionIndicators['trueRangeBreakevenSLMP']]));
                $this->marketIfTouchedOrderPrice = max([$this->decisionIndicators['hmaChangeDirection']['priceTarget']]);
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