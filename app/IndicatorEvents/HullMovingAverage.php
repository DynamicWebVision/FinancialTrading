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
use \Log;
use \App\Services\CurrencyIndicators;
use \App\IndicatorEvents\EventHelpers;
use \App\Services\Utility;
use \App\IndicatorEvents\WeightedMovingAverage;

class HullMovingAverage {

    public function __construct() {
        $this->indicators = new CurrencyIndicators();
        $this->eventHelpers = new EventHelpers();
        $this->utility = new Utility();
        $this->weightedMovingAverage = new WeightedMovingAverage();
    }

    public function getDifferenceValues($rates, $length) {
        $differences = [];
        foreach ($rates as $index => $rate) {
            if (($index +1) >= $length) {
                $averageValuesArray = $rates;

                $arrayIndexStart = $index - $length + 1;

                $currentRates = array_slice($averageValuesArray, $arrayIndexStart, $length);

                $halfTimes2 = 2 * $this->weightedMovingAverage->weightedMovingAverageSinglePeriod($currentRates, round($length/2));
                $regularWma = $this->weightedMovingAverage->weightedMovingAverageSinglePeriod($currentRates, $length);

                $differences[] = $halfTimes2 - $regularWma;
            }
        }
        return $differences;
    }

    public function endHullPoint($rates,$length) {
        $differenceValues = $this->getDifferenceValues($rates, $length);
        return $this->weightedMovingAverage->weightedMovingAverageSinglePeriod($differenceValues, round(sqrt($length)));
    }

    public function hullLineLastThree($rates, $length) {
        $arraySets = $this->utility->getMultipleArraySets($rates, $length*2, 3);

        $hmaPoints = [];
        $hmaPoints[] = $this->endHullPoint($arraySets[0], $length);
        $hmaPoints[] = $this->endHullPoint($arraySets[1], $length);
        $hmaPoints[] = $this->endHullPoint($arraySets[2], $length);
        return $hmaPoints;
    }

    public function hullChangeDirectionCheck($rates, $length) {
        $lastThree = $this->hullLineLastThree($rates, $length);
        return $this->eventHelpers->lineChangeDirection($lastThree);
    }

    public function bruteForceHmaDirectionChange() {
//        X + ()
    }
}
