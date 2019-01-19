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


namespace App\ForexStrategy\HullMovingAverage;
use \Log;
use App\IndicatorEvents\HullMovingAverage;
use App\IndicatorEvents\RsiEvents;
use \App\IndicatorEvents\EventHelpers;
use \App\IndicatorEvents\AdxEvents;
use \App\IndicatorEvents\TrueRange;

class HmaPriceCrossover extends \App\ForexStrategy\Strategy  {

    public $fastHma;

    public $adxLength = 14;
    public $adxUndersoldThreshold = 25;

    public $trueRangeLength = 14;

    public $stopLossTrueRangeMultiplier = 2;
    public $takeProfitTrueRangeMultiplier = 2;

    public function setEntryIndicators() {
        $hmaEvents = new HullMovingAverage();
        $hmaEvents->strategyLogger = $this->strategyLogger;
        $trueRange = new TrueRange();
        $trueRange->strategyLogger = $this->strategyLogger;
        $adxEvents = new AdxEvents();
        $adxEvents->strategyLogger = $this->strategyLogger;

        $this->decisionIndicators['hmaCrossover'] = $hmaEvents->hmaPriceCrossover($this->rates['simple'], $this->fastHma);
        $this->decisionIndicators['adxAboveThreshold'] = $adxEvents->adxAboveThreshold($this->rates['full'], $this->adxLength, $this->adxUndersoldThreshold);

        $this->decisionIndicators['stopLossPipValues'] = $trueRange->getStopLossPipValue($this->rates['full'], $this->trueRangeLength,
            $this->exchange->pip, $this->stopLossTrueRangeMultiplier);

        $this->stopLossPipAmount = round($this->decisionIndicators['stopLossPipValues']);
    }

    public function getEntryDecision() {
        $this->setEntryIndicators();

        $this->strategyLogger->logIndicators($this->decisionIndicators);

        if ($this->decisionIndicators['hmaCrossover'] == 'crossedAbove') {
            $this->newLongPosition();
        }
        elseif ($this->decisionIndicators['hmaCrossover'] == 'crossedBelow') {
            $this->newShortPosition();
        }
    }

    public function inPositionDecision() {
        $hmaEvents = new HullMovingAverage();
        $hmaEvents->strategyLogger = $this->strategyLogger;
        $trueRange = new TrueRange();
        $trueRange->strategyLogger = $this->strategyLogger;

        $this->strategyLogger->logIndicators($this->decisionIndicators);

        $this->decisionIndicators['hmaCrossover'] = $hmaEvents->hmaPriceCrossover($this->rates['simple'], $this->fastHma);

        $this->decisionIndicators['stopLossPipValues'] = $trueRange->getStopLossPipValue($this->rates['full'], $this->trueRangeLength,
            $this->exchange->pip, $this->stopLossTrueRangeMultiplier);

        $this->stopLossPipAmount = round($this->decisionIndicators['stopLossPipValues']);

        if ($this->openPosition['side'] == 'long') {
            if ($this->decisionIndicators['hmaCrossover'] == 'crossedBelow') {
                $this->strategyLogger->logMessage('Closing Long Position when Hma is Short. Then creating new Short Position', 1);
                $this->closePosition();
                $this->newShortPosition();
            }
        }
        elseif ($this->openPosition['side'] == 'short') {
            if ($this->decisionIndicators['hmaCrossover'] == 'crossedAbove' ) {
                $this->strategyLogger->logMessage('Closing Short Position when Hma is Short. Then creating new Long Position', 1);
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
