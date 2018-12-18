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

    public $currentAverageGain;
    public $currentAverageLoss;
    public $rsi;

    public function __construct() {
        $this->indicators = new CurrencyIndicators();
        $this->utility = new Utility();
        $this->eventHelpers = new EventHelpers();
    }

    public function averageGainLoss($rates, $length) {

    }

    public function initialAverageGainLoss($rates, $length) {
        $gains = [];
        $losses = [];

        foreach ($rates as $index=>$rate) {
            if (isset($endRates[$index+1])) {

                $diff = $endRates[$index+1] - $rate;

                if ($diff > 0) {
                    $gains[] = (($diff/$rate)*100);
                }
                elseif ($diff < 0) {
                    $losses[] = abs((($diff/$rate)*100));
                }
            }
        }
        $this->currentAverageGain = array_sum($gains)/$length;
        $this->currentAverageLoss = array_sum($losses)/$length;
    }

    public function rsi($rates, $period) {
        $endRates = $this->utility->getFirstXElementsInArray($rates, $period + 1);

        foreach ($rates as $index=>$rate) {
            if ($index >= $period) {
                $currentRates = array_slice($rates,$index-$period,$period + 1);

                if ($this->currentAverageGain) {
                    $currentGainLoss = $rate - $rates[$index-1];

                    $averageGains = $this->currentAverageGain*($period-1);
                    $averageLosses = $this->currentAverageLoss*($period-1);

                    if ($currentGainLoss >= 0) {
                        $percentGain = ($currentGainLoss/$rates[$index-1])*100;
                        $this->currentAverageGain = ($averageGains + $percentGain)/$period;
                        $this->currentAverageLoss = ($averageLosses)/$period;
                    }
                    else {
                        $percentLoss = abs(($currentGainLoss/$rates[$index-1])*100);
                        $this->currentAverageGain = ($averageGains)/$period;
                        $this->currentAverageLoss = ($averageLosses + $percentLoss)/$period;
                    }
                }
                else {
                    $this->initialAverageGainLoss($currentRates, $period);
                }

                if ($this->currentAverageLoss == 0) {
                    $this->rsi[] = 100;
                }
                else {
                    $rs = $this->currentAverageGain/$this->currentAverageLoss;

                    $this->rsi[] = 100 - (100/(1+$rs));
                }
            }
        }
        return end($this->rsi);
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

    public function getGainLossSums($rates) {
        $gains = [];
        $losses = [];

        foreach ($rates as $index=>$rate) {
            if (isset($rates[$index+1])) {

                $diff = $rates[$index+1] - $rate;

                if ($diff > 0) {
                    $gains[] = $diff;
                }
                elseif ($diff < 0) {
                    $losses[] = abs($diff);
                }
            }
        }
        return ['gains'=>$gains, 'losses'=>$losses];
    }

    public function calculateTargetRS($targetRsi) {
        return (-$targetRsi)/($targetRsi - 100);
    }

    public function getCrossLevelPricePointFromOuter($rates, $length, $rsiCrossLevel) {
        $ratesBesidesNext = $this->utility->getLastXElementsInArray($rates, $length);
        $currentRSIWithoutNextRate = $this->rsi($ratesBesidesNext, $length-1);

        $currentGainsLosses = $this->getGainLossSums($ratesBesidesNext);

        $rsTarget = $this->calculateTargetRS($rsiCrossLevel);

        $response = [];

        if ($currentRSIWithoutNextRate > $rsiCrossLevel) {
            $gainAverage = array_sum($currentGainsLosses['gains'])/$length;
            $losses = array_sum($currentGainsLosses['losses']);

            $averageLoss = $gainAverage/$rsTarget;

            $lastLoss = ($averageLoss*$length) - $losses;

            $response['targetPrice'] = end($rates) - $lastLoss;
            $response['side'] = 'short';
        }
        else {
            $gains = array_sum($currentGainsLosses['gains']);
            $lossAverage = array_sum($currentGainsLosses['losses'])/$length;

            $averageGain = $lossAverage*$rsTarget;

            $lastGain = ($averageGain*$length) - $gains;

            $response['targetPrice'] = end($rates) + $lastGain;
            $response['side'] = 'long';
        }
        return $response;
    }

    public function outsideLevel($rates, $period, $outerLimit) {
        $rsi = $this->rsi($rates, $period);

        if ($rsi < $outerLimit) {
            return 'overBoughtShort';
        }
        elseif ($rsi > (100 - $outerLimit)) {
            return 'overBoughtLong';
        }
        else {
            return 'none';
        }
    }
}
