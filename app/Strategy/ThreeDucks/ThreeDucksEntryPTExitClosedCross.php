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


namespace App\Strategy\ThreeDucks;
use \Log;
use App\IndicatorEvents\HullMovingAverage;
use App\IndicatorEvents\RsiEvents;
use \App\IndicatorEvents\EventHelpers;
use \App\IndicatorEvents\AdxEvents;
use \App\IndicatorEvents\SimpleMovingAverage;
use \App\IndicatorEvents\TrueRange;

class ThreeDucksEntryPTExitClosedCross extends \App\Strategy\Strategy  {

    public $fastMa;
    public $mediumMa;
    public $slowMa;

    public $adxLength = 14;
    public $adxUndersoldThreshold = 25;

    public $trueRangeLength = 14;
    public $stopLossTrueRangeMultiplier;

    public function setEntryIndicators() {
        $simpleMovingAverage = new SimpleMovingAverage();
        $simpleMovingAverage->strategyLogger = $this->strategyLogger;
        $trueRange = new TrueRange();
        $trueRange->strategyLogger = $this->strategyLogger;
        $adxEvents = new AdxEvents();
        $adxEvents->strategyLogger = $this->strategyLogger;

        $this->decisionIndicators['slowMAPriceRelation'] = $simpleMovingAverage->currentPriceRelationMA($this->rates['simple'], $this->slowMa);
        $this->decisionIndicators['mediumMAPriceRelation'] = $simpleMovingAverage->currentPriceRelationMA($this->rates['simple'], $this->mediumMa);
        $this->decisionIndicators['fastMAPriceRelation'] = $simpleMovingAverage->currentPriceRelationMA($this->rates['simple'], $this->fastMa);

        $this->decisionIndicators['fastMAPriceTarget'] = $simpleMovingAverage->getCrossPriceTarget($this->rates['simple'], $this->fastMa);

        $this->decisionIndicators['stopLossPips'] = $trueRange->getStopLossPipValue($this->rates['full'], $this->trueRangeLength,
            $this->exchange->pip, $this->stopLossTrueRangeMultiplier);

        $this->stopLossPipAmount = round($this->decisionIndicators['stopLossPips']);
    }

    public function getEntryDecision() {
        $this->setEntryIndicators();

        $this->strategyLogger->logIndicators($this->decisionIndicators);

        if ($this->decisionIndicators['slowMAPriceRelation'] == 'below' && $this->decisionIndicators['mediumMAPriceRelation'] == 'below'
            && $this->decisionIndicators['fastMAPriceRelation'] == 'above') {
            $this->marketIfTouchedOrderPrice = $this->decisionIndicators['fastMAPriceTarget'] - $this->exchange->pip;
            $this->newShortPosition();
        }
        elseif ($this->decisionIndicators['slowMAPriceRelation'] == 'above' && $this->decisionIndicators['mediumMAPriceRelation'] == 'above'
            && $this->decisionIndicators['fastMAPriceRelation'] == 'below') {
            $this->marketIfTouchedOrderPrice = $this->decisionIndicators['fastMAPriceTarget'] + $this->exchange->pip;
            $this->newLongPosition();
        }
    }

    public function inPositionDecision() {
        $simpleMovingAverage = new SimpleMovingAverage();
        $simpleMovingAverage->strategyLogger = $this->strategyLogger;

        $trueRange = new TrueRange();
        $trueRange->strategyLogger = $this->strategyLogger;

        $this->decisionIndicators['slowMAPriceRelation'] = $simpleMovingAverage->currentPriceRelationMA($this->rates['simple'], $this->slowMa);
        $this->decisionIndicators['mediumMAPriceRelation'] = $simpleMovingAverage->currentPriceRelationMA($this->rates['simple'], $this->mediumMa);
        $this->decisionIndicators['fastMAPriceRelation'] = $simpleMovingAverage->currentPriceRelationMA($this->rates['simple'], $this->fastMa);

        $this->decisionIndicators['fastMAPriceTarget'] = $simpleMovingAverage->getCrossPriceTarget($this->rates['simple'], $this->fastMa);

        $this->decisionIndicators['stopLossPips'] = $trueRange->getStopLossPipValue($this->rates['full'], $this->trueRangeLength,
            $this->exchange->pip, $this->stopLossTrueRangeMultiplier);

        $this->strategyLogger->logIndicators($this->decisionIndicators);

        if ($this->openPosition['side'] == 'long') {
            if ($this->decisionIndicators['fastMAPriceRelation'] == 'below' ) {
                $this->strategyLogger->logMessage('Closing Long Position since Price is below MA', 1);
                $this->closePosition();

                if ($this->decisionIndicators['slowMAPriceRelation'] == 'above' && $this->decisionIndicators['mediumMAPriceRelation'] == 'above') {
                    //Looking for New Entry
                    $this->marketIfTouchedOrderPrice = $this->decisionIndicators['fastMAPriceTarget'] + $this->exchange->pip;
                    $this->newLongPosition();
                }
            }
            else {
                $this->strategyLogger->logMessage('Modifying Stop Loss', 1);
                $stopLossTarget = $this->decisionIndicators['fastMAPriceTarget'] - $this->decisionIndicators['stopLossPips'];
                $this->modifyStopLoss($stopLossTarget);
            }
        }
        elseif ($this->openPosition['side'] == 'short') {
            if ($this->decisionIndicators['fastMAPriceRelation'] == 'above' ) {
                $this->strategyLogger->logMessage('Closing Long Position since Price is below MA', 1);
                $this->closePosition();

                if ($this->decisionIndicators['slowMAPriceRelation'] == 'below' && $this->decisionIndicators['mediumMAPriceRelation'] == 'below') {
                    //Looking for New Entry
                    $this->marketIfTouchedOrderPrice = $this->decisionIndicators['fastMAPriceTarget'] - $this->exchange->pip;
                    $this->newShortPosition();
                }

            }
            else {
                $this->strategyLogger->logMessage('Modifying Stop Loss', 1);
                $stopLossTarget = $this->decisionIndicators['fastMAPriceTarget'] + $this->decisionIndicators['stopLossPips'];
                $this->modifyStopLoss($stopLossTarget);
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
