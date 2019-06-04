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


namespace App\IndicatorEvents;
use App\Services\Utility;
use \Log;
use \App\Services\CurrencyIndicators;
use \App\IndicatorEvents\EventHelpers;

class CandlePatterns {
    public $utility;
    public $eventHelpers;

    public function __construct() {
        $this->utility = new Utility();
        $this->eventHelpers = new EventHelpers();
    }

    public function harami($rates) {
        $currentCandle = end($rates);
        $currentCandleDirection = $this->eventHelpers->candlDirection($currentCandle);
        $previousCandle = $this->utility->getXFromLastValue($rates, 1);
        $previousCandleDirection = $this->eventHelpers->candlDirection($previousCandle);


        if ($previousCandle->highMid >= $currentCandle->highMid && $previousCandle->lowMid <= $currentCandle->lowMid) {
            if ($previousCandleDirection == 'short' && $currentCandleDirection == 'long') {
                return 'long';
            }
            elseif ($previousCandleDirection == 'long' && $currentCandleDirection == 'short') {
                return 'short';
            }
        }
        else {
            return false;
        }
    }

    public function hammerShootingStarCheck($rates) {
        $currentCandle = end($rates);

        $gainLoss = $currentCandle->closeMid - $currentCandle->openMid;

        if ($gainLoss > 0) {
            $upperRange = $currentCandle->highMid - $currentCandle->closeMid;
            $lowerRange = $currentCandle->openMid - $currentCandle->lowMid;

            $maxGainUpper = max([$gainLoss, $upperRange]);

            if ($lowerRange > ($maxGainUpper*2)) {
                return 'hammer';
            }
        }
        elseif ($gainLoss < 0) {
            $upperRange = $currentCandle->highMid - $currentCandle->openMid;
            $lowerRange = $currentCandle->closeMid - $currentCandle->lowMid;

            $maxLossUpper = max([abs($gainLoss), $lowerRange]);

            if ($upperRange > ($maxLossUpper*2)) {
                return 'shooting-star';
            }
        }
        return false;
    }

    public function previousWickStopLoss($currentPriceData, $previousRate, $direction, $pip) {
        if ($direction == 'long') {
            return round(($currentPriceData->open - $previousRate->lowMid)/$pip);
        }
        elseif ($direction == 'short') {
            return round(($previousRate->highMid - $currentPriceData->open)/$pip);
        }
    }
}
