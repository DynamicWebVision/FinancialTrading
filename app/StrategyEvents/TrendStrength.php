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


namespace App\StrategyEvents;
use \Log;
use \App\Services\CurrencyIndicators;

class TrendStrength {

    public function adxSimpleCutoff($rates, $cutoffValue) {
        $indicators = new CurrencyIndicators();

        $adx = $indicators->adx($rates, 14);

        $adxEnd = end($adx);

        if ($adxEnd >= $cutoffValue) {
            return 'pass';
        }
        else {
            return 'fail';
        }
    }

    public function adxMultiTierCutoff($rates, $hardCutoff, $upwardSlopeCutoff, $slopeMin) {
        $indicators = new CurrencyIndicators();

        $adx = $indicators->adx($rates, 14);

        $linReg = $indicators->linearRegression($adx, 2);

        $adxEnd = end($adx);

        if ($adxEnd >= $hardCutoff) {
            return 'pass';
        }
        elseif ($adxEnd >= $upwardSlopeCutoff && $linReg['m'] >= $slopeMin) {
            return 'pass';
        }
        else {
            return 'fail';
        }
    }
}
