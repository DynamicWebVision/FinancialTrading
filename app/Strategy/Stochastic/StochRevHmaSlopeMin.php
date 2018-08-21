<?php
/**
 * Created by PhpStorm.
 * User: diego.rodriguez
 * Date: 8/31/17
 * Time: 5:11 PM
 * Description: This is a basic strategy that uses an EMA break through with a longer (100 EMA to start) and short EMA (50 to start).
 *
 * Decisions:
 * BUY      ---> Short EMA crosses above Long EMA
 * Short    ---> Short EMA crosses below Long EMA
 *
 *During Open Position:
 * -If another breakthrouch occurs we will close the current position and open a new, opposite one
 * -If the linear regression slope of the fast EMA is opposite of the position direction, a tighter stop loss will be added.
 */


namespace App\Strategy\Stochastic;
use \Log;
use App\IndicatorEvents\HullMovingAverage;
use App\IndicatorEvents\RsiEvents;
use \App\IndicatorEvents\EventHelpers;
use \App\IndicatorEvents\AdxEvents;
use \App\IndicatorEvents\TrueRange;
use \App\IndicatorEvents\StochasticEvents;

class StochRevHmaSlopeMin extends \App\Strategy\Strategy  {

    public $slowHma;

    public $stochLength;
    public $stochOverboughtCutoff;

    public $trueRangeLength = 14;

    public $slowHmaSlopeMin;

    public $stopLossTrueRangeMultiplier;
    public $takeProfitTrueRangeMultiplier = 2;

    public $openStochTakeProfitTarget;
    public $openStochStopLossTarget;

    public function setEntryIndicators() {
        $hmaEvents = new HullMovingAverage();
        $hmaEvents->strategyLogger = $this->strategyLogger;

        $trueRange = new TrueRange();
        $trueRange->strategyLogger = $this->strategyLogger;

        $stochEvents = new StochasticEvents();
        $stochEvents->strategyLogger = $this->strategyLogger;

        $this->decisionIndicators['stochReversal'] = $stochEvents->getReturnFromOverBoughtPrice($this->rates['full'], $this->stochLength, $this->stochOverboughtCutoff);

        $this->decisionIndicators['averageTrueRangeProfitLossValues'] = $trueRange->getTakeProfitLossPipValues($this->rates['full'], $this->trueRangeLength,
            $this->exchange->pip, $this->takeProfitTrueRangeMultiplier, $this->stopLossTrueRangeMultiplier);

        $this->decisionIndicators['slowHmaSlope'] = $hmaEvents->hmaSlope($this->rates['simple'], $this->slowHma, $this->exchange->pip, $this->slowHmaSlopeMin);

        $this->stopLossPipAmount = round($this->decisionIndicators['averageTrueRangeProfitLossValues']['lossPips']);
    }

    public function getEntryDecision() {
        $this->setEntryIndicators();

        Log::info($this->runId.': New Position Decision Indicators: '.PHP_EOL.' '.$this->logIndicators());

        if ($this->decisionIndicators['stochReversal']['entry'] == 'long' && $this->decisionIndicators['slowHmaSlope'] == 'long') {
            $this->marketIfTouchedOrderPrice = $this->decisionIndicators['stochReversal']['priceTarget'];
            $this->newLongPosition();
        }
        elseif ($this->decisionIndicators['stochReversal']['entry'] == 'short' && $this->decisionIndicators['slowHmaSlope'] == 'short') {
            $this->marketIfTouchedOrderPrice = $this->decisionIndicators['stochReversal']['priceTarget'];
            $this->newShortPosition();
        }
    }

    public function inPositionDecision() {
//        $hmaEvents = new HullMovingAverage();
//        $hmaEvents->strategyLogger = $this->strategyLogger;
//
//        $this->decisionIndicators['fastHmaChangeDirection'] = $hmaEvents->hullChangeDirectionPoint($this->rates['simple'], $this->fastHma);
//
//        if ($this->openPosition['side'] == 'long') {
//            if ($this->decisionIndicators['fastHmaChangeDirection']['side'] == 'short' ) {
//                $this->closePosition();
//            }
//            else {
//                $this->modifyStopLoss($this->decisionIndicators['fastHmaChangeDirection']['priceTarget']);
//            }
//        }
//        elseif ($this->openPosition['side'] == 'short') {
//            if ($this->decisionIndicators['fastHmaChangeDirection']['side'] == 'long' ) {
//                $this->closePosition();
//            }
//            else {
//                $this->modifyStopLoss($this->decisionIndicators['fastHmaChangeDirection']['priceTarget']);
//            }
//        }

        $this->closePosition();
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
