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

    public function hmaSlope($rates, $length, $pip, $minSlope) {
        $lastThree = $this->hullLineLastThree($rates, $length);
        $slope = round(($lastThree[2] - $lastThree[1])/$pip);

        if ($slope >= $minSlope) {
            return 'long';
        }
        elseif ($slope <= $minSlope*-1) {
            return 'short';
        }
        else {
            return false;
        }
    }

    public function hullChangeDirectionPoint($rates, $length) {
        $lastThreeHma = $this->hullLineLastThree($rates, $length);
        $secondToLast = $lastThreeHma[1];
        $lastHma = $lastThreeHma[2];

        $response = [];

        if ($lastHma > $secondToLast) {
            $targetHma = $lastHma;
            $response['currentTrendingSide'] = 'long';
        }
        elseif ($lastHma < $secondToLast) {
            $targetHma = $lastHma;
            $response['currentTrendingSide'] = 'short';
        }
        else {
            $targetHma = $lastHma;
            $response['currentTrendingSide'] = 'none';
        }

        $squareRootLength = round(sqrt($length));

        $differenceValues = $this->getDifferenceValues($rates, $length);

        $outerDivisor = $this->weightedMovingAverage->getDivisior($squareRootLength);

        $differenceValuesBesidesNext = $this->utility->getLastXElementsInArray($differenceValues, $squareRootLength-1);

        $diffValuesTotalBesidesNext = $this->weightedMovingAverage->walkValuesWithIndexMultiplier($differenceValuesBesidesNext);

        $necessaryDifference = (($targetHma*$outerDivisor) - $diffValuesTotalBesidesNext)/$squareRootLength;

        $fastLength = round($length/2);
        $fastDivisor = $this->weightedMovingAverage->getDivisior($fastLength);
        $slowDivisor = $this->weightedMovingAverage->getDivisior($length);

        //Fast Value Sum Besides Next
        $fastRatesBesidesNext = $this->utility->getLastXElementsInArray($rates, $fastLength-1);
        $fastDiffValuesSumBesidesNext = $this->weightedMovingAverage->walkValuesWithIndexMultiplier($fastRatesBesidesNext);

        //Slow Value Sum Besides Next
        $slowRatesBesidesNext = $this->utility->getLastXElementsInArray($rates, $length-1);
        $slowDiffValuesSumBesidesNext = $this->weightedMovingAverage->walkValuesWithIndexMultiplier($slowRatesBesidesNext);

        $response['priceTarget'] = (($fastDivisor*$slowDivisor*$necessaryDifference) + ($fastDivisor*$slowDiffValuesSumBesidesNext) - (2*$slowDivisor*$fastDiffValuesSumBesidesNext))/((-$fastDivisor*$length) + (2*$slowDivisor*$fastLength));
        return $response;
    }
}
