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
use \App\Services\Utility;
use \App\IndicatorEvents\EventHelpers;

class RsiEvents  {

    public $utility;
    public $indicators;

    public $strategyLogger;

    public function __construct() {
        $this->indicators = new CurrencyIndicators();
        $this->utility = new Utility();
        $this->eventHelpers = new EventHelpers();
    }

    public function rsi($rates, $period) {
        $endRates = $this->utility->getLastXElementsInArray($rates, $period + 1);

        $gains = [];
        $losses = [];

        foreach ($endRates as $index=>$rate) {
            if (isset($endRates[$index+1])) {

                $diff = $endRates[$index+1] - $rate;

                if ($diff > 0) {
                    $gains[] = $diff;
                }
                elseif ($diff < 0) {
                    $losses[] = abs($diff);
                }
            }
        }

        if (array_sum($losses) == 0) {
            $rsi = 100;
        }
        else {
            $rs = (array_sum($gains)/$period)/(array_sum($losses)/$period);

            $rsi = 100 - (100/(1+$rs));
        }

        return $rsi;
    }

    public function getLastTwoRsi($rates, $periods) {
        $secondToLastRates = $rates;
        unset($secondToLastRates[count($secondToLastRates)-1]);

        $secondToLastRsi = $this->rsi($secondToLastRates, $periods);
        $lastRsi = $this->rsi($rates, $periods);

        return [$secondToLastRsi, $lastRsi];
    }

    public function crossedLevel($rates, $periods, $crossLevel) {
        $rsiValues = $this->getLastTwoRsi($rates, $periods);
        return $this->utility->checkCrossOverLevel($rsiValues, $crossLevel);
    }

    public function crossedLevelWithinPastXPeriods($rates, $periods, $crossLevel, $periodsBack) {
        $arraySets = $this->utility->getMultipleArraySets($rates, $periods, $periodsBack);

        $rsiCrossResults = [];

        foreach ($arraySets as $array) {
            $rsiCrossResults[] = $this->crossedLevel($array, $periodsBack, $crossLevel);
        }
        return $this->eventHelpers->periodsBackGetLastResultEvent($rsiCrossResults, ['crossedAbove', 'crossedBelow']);
    }
}
