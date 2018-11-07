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


namespace App\Strategy\HullMovingAverage;
use \Log;
use App\IndicatorEvents\HullMovingAverage;
use App\IndicatorEvents\RsiEvents;
use \App\IndicatorEvents\EventHelpers;
use \App\IndicatorEvents\AdxEvents;
use \App\IndicatorEvents\TrueRange;

class HmaSimple extends \App\Strategy\Strategy  {

    public $fastHma;

    public $adxLength = 14;
    public $adxUndersoldThreshold = 25;

    public $trueRangeLength = 14;

    public $stopLossTrueRangeMultiplier;
    public $takeProfitTrueRangeMultiplier = 2;

    public function setEntryIndicators() {
        $hmaEvents = new HullMovingAverage();
        $hmaEvents->strategyLogger = $this->strategyLogger;
        $trueRange = new TrueRange();
        $trueRange->strategyLogger = $this->strategyLogger;
        $adxEvents = new AdxEvents();
        $adxEvents->strategyLogger = $this->strategyLogger;

        $this->decisionIndicators['fastHmaChangeDirection'] = $hmaEvents->hullChangeDirectionPoint($this->rates['simple'], $this->fastHma);
        $this->decisionIndicators['adxAboveThreshold'] = $adxEvents->adxAboveThreshold($this->rates['full'], $this->adxLength, $this->adxUndersoldThreshold);

        $this->decisionIndicators['averageTrueRangeProfitLossValues'] = $trueRange->getTakeProfitLossPipValues($this->rates['full'], $this->trueRangeLength,
            $this->exchange->pip, $this->takeProfitTrueRangeMultiplier, $this->stopLossTrueRangeMultiplier);

        $this->stopLossPipAmount = round($this->decisionIndicators['averageTrueRangeProfitLossValues']['lossPips']);
    }

    public function getEntryDecision() {
        $this->setEntryIndicators();

        $this->strategyLogger->logIndicators($this->decisionIndicators);

        if ($this->decisionIndicators['fastHmaChangeDirection']['currentTrendingSide'] == 'long') {
            $this->marketIfTouchedOrderPrice = $this->decisionIndicators['fastHmaChangeDirection']['priceTarget'];
            $this->newShortPosition();
        }
        elseif ($this->decisionIndicators['fastHmaChangeDirection']['currentTrendingSide'] == 'short') {
            $this->marketIfTouchedOrderPrice = $this->decisionIndicators['fastHmaChangeDirection']['priceTarget'];
            $this->newLongPosition();
        }
    }

    public function inPositionDecision() {
        $hmaEvents = new HullMovingAverage();
        $hmaEvents->strategyLogger = $this->strategyLogger;

        $this->strategyLogger->logIndicators($this->decisionIndicators);

        $this->decisionIndicators['fastHmaChangeDirection'] = $hmaEvents->hullChangeDirectionPoint($this->rates['simple'], $this->fastHma);

        if ($this->openPosition['side'] == 'long') {
            if ($this->decisionIndicators['fastHmaChangeDirection']['currentTrendingSide'] == 'short' ) {
                $this->strategyLogger->logMessage('Closing Long Position when Hma is Short. Then creating new Short Position', 1);
                $this->closePosition();
                $this->marketIfTouchedOrderPrice = $this->decisionIndicators['fastHmaChangeDirection']['priceTarget'];
                $this->quickPositionTypeTarget('short');
                $this->newShortPosition();
            }
            else {
                $this->strategyLogger->logMessage('Modifying Stop Loss and Creating New MIT Short', 1);
                $this->modifyStopLoss($this->decisionIndicators['fastHmaChangeDirection']['priceTarget']);
                $this->marketIfTouchedOrderPrice = $this->decisionIndicators['fastHmaChangeDirection']['priceTarget'];
                $this->newShortPosition();
            }
        }
        elseif ($this->openPosition['side'] == 'short') {
            if ($this->decisionIndicators['fastHmaChangeDirection']['currentTrendingSide'] == 'long' ) {
                $this->strategyLogger->logMessage('Closing Short Position when Hma is Short. Then creating new Long Position', 1);
                $this->closePosition();
                $this->marketIfTouchedOrderPrice = $this->decisionIndicators['fastHmaChangeDirection']['priceTarget'];
                $this->quickPositionTypeTarget('long');
                $this->newLongPosition();
            }
            else {
                $this->strategyLogger->logMessage('Modifying Stop Loss and Creating New MIT Long', 1);
                $this->modifyStopLoss($this->decisionIndicators['fastHmaChangeDirection']['priceTarget']);
                $this->marketIfTouchedOrderPrice = $this->decisionIndicators['fastHmaChangeDirection']['priceTarget'];
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
