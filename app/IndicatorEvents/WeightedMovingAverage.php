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

class WeightedMovingAverage {

    public function __construct() {
        $this->indicators = new CurrencyIndicators();
        $this->eventHelpers = new EventHelpers();
        $this->utility = new Utility();
    }

    public function weightedMovingAverageSinglePeriod($rates, $length) {
        $rates = $this->utility->getLastXElementsInArray($rates, $length);
        $rates = array_reverse($rates);
        $divisor = 0;
        $sumTotal = 0;

        $currentPeriodMultiplier = $length;

        foreach ($rates as $rate) {
            $sumTotal = $sumTotal + ($rate*$currentPeriodMultiplier);
            $divisor = $currentPeriodMultiplier + $divisor;
            $currentPeriodMultiplier = $currentPeriodMultiplier - 1;
        }
        try {

        }
        catch (\Exception $e) {
            $debug=1;
        }
        return $sumTotal/$divisor;
    }

    public function getDivisior($length) {
        $divisor = 0;
        while ($length > 0) {
            $divisor = $divisor + $length;
            $length--;
        }
        return $divisor;
    }

    public function walkValuesWithIndexMultiplier($values) {
        $valueTotal = 0;
        foreach ($values as $index=>$value) {
            $valueTotal = $valueTotal + ($value*($index +1));
        }
        return $valueTotal;
    }
}
