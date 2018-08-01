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


namespace App\Strategy\EmaMomentum;
use \Log;
use App\StrategyEvents\EmaEvents;
use App\StrategyEvents\RsiEvents;
use \App\StrategyEvents\EventHelpers;
use \App\StrategyEvents\AdxEvents;
use \App\StrategyEvents\TrueRange;

class EmaXAdxConfirmWithMarketIfTouched extends \App\Strategy\Strategy  {

    public $fastEma;
    public $slowEma;

    public $adxLength = 14;
    public $adxUndersoldThreshold = 25;

    public $trueRangeLength = 14;

    public $takeProfitTrueRangeMultiplier;
    public $trailingStopTrueRangeMultiplier;
    public $stopLossTrueRangeMultiplier;

    public function setEntryIndicators() {
        $emaEvents = new EmaEvents();
        $emaEvents->strategyLogger = $this->strategyLogger;

        $trueRange = new TrueRange();
        $trueRange->strategyLogger = $this->strategyLogger;
        $adxEvents = new AdxEvents();
        $adxEvents->strategyLogger = $this->strategyLogger;

        $this->decisionIndicators['emaCrossInfo'] = $emaEvents->calculateEmaNextPeriodCrossoverRate($this->rates['simple'], $this->fastEma, $this->slowEma);

        $this->decisionIndicators['adxAboveThreshold'] = $adxEvents->adxAboveThreshold($this->rates['full'], $this->adxLength, $this->adxUndersoldThreshold);

        $this->decisionIndicators['averageTrueRangeProfitLossValues'] = $trueRange->getTakeProfitLossPipValues($this->rates['full'], $this->trueRangeLength,
            $this->exchange->pip, $this->takeProfitTrueRangeMultiplier, $this->stopLossTrueRangeMultiplier);

        $this->takeProfitPipAmount = $this->decisionIndicators['averageTrueRangeProfitLossValues']['profitPips'];
        $this->stopLossPipAmount = $this->decisionIndicators['averageTrueRangeProfitLossValues']['lossPips'];
    }

    public function getEntryDecision() {
        $this->setEntryIndicators();

        Log::info($this->runId.': New Position Decision Indicators: '.PHP_EOL.' '.$this->logIndicators());

        if ($this->decisionIndicators['adxAboveThreshold']) {
            $this->marketIfTouchedOrderPrice = $this->decisionIndicators['emaCrossInfo']['crossRate'];
            if ($this->decisionIndicators['emaCrossInfo']['side'] == 'long') {
                $this->newLongPosition();
            }
            elseif ($this->decisionIndicators['emaCrossInfo']['side'] == 'short') {
                $this->newShortPosition();
            }
        }
    }

    public function inPositionDecision() {
        $emaEvents = new EmaEvents();
        $emaEvents->strategyLogger = $this->strategyLogger;

        $trueRange = new TrueRange();
        $trueRange->strategyLogger = $this->strategyLogger;

        $adxEvents = new AdxEvents();
        $adxEvents->strategyLogger = $this->strategyLogger;

        $this->decisionIndicators['emaCrossInfo'] = $emaEvents->calculateEmaNextPeriodCrossoverRate($this->rates['simple'], $this->fastEma, $this->slowEma);

        $this->decisionIndicators['adxAboveThreshold'] = $adxEvents->adxAboveThreshold($this->rates['full'], $this->adxLength, $this->adxUndersoldThreshold);

        $this->decisionIndicators['averageTrueRangeProfitLossValues'] = $trueRange->getTakeProfitLossPipValues($this->rates['full'], $this->trueRangeLength,
            $this->exchange->pip, $this->takeProfitTrueRangeMultiplier, $this->stopLossTrueRangeMultiplier);

        $this->marketIfTouchedOrderPrice = $this->decisionIndicators['emaCrossInfo']['crossRate'];

        if ($this->openPosition['side'] == 'long') {
            if ($this->decisionIndicators['emaCrossInfo']['side'] == 'long') {
                $this->closePosition();
                if ($this->decisionIndicators['adxAboveThreshold']) {
                    $this->newLongPosition();
                }
            }
            else {
                $this->modifyStopLoss($this->marketIfTouchedOrderPrice);
                if ($this->decisionIndicators['adxAboveThreshold']) {
                    $this->newShortPosition();
                }

            }
        }
        elseif ($this->openPosition['side'] == 'short') {
            if ($this->decisionIndicators['emaCrossInfo']['side'] == 'short') {
                $this->closePosition();
                if ($this->decisionIndicators['adxAboveThreshold']) {
                    $this->newShortPosition();
                }
            }
            else {
                $this->modifyStopLoss($this->marketIfTouchedOrderPrice);
                if ($this->decisionIndicators['adxAboveThreshold']) {
                    $this->newLongPosition();
                }
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
