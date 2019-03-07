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

class EventHelpers {
    public $utility;
    public $indicators;
    public $strategyLogger;

    public function __construct() {
        $this->utility = new Utility();
        $this->indicators = new CurrencyIndicators();
    }

    public function periodsBackGetLastResultEvent($events, $importantEvents) {
        $response = 'none';
        foreach ($events as $event) {
            foreach ($importantEvents as $importantEvent) {
                if ($event == $importantEvent) {
                    $response = $importantEvent;
                }
            }
        }
        return $response;
    }

    public function ratesAverageHighLow($rates) {

        $rates = array_map(function($rate) {
           return ($rate->lowMid + $rate->highMid)/2;
        }, $rates);

        return $rates;
    }

    public function sdSquare($x, $mean) { return pow($x - $mean,2); }

    //Calculate Standard deviation of Array
    public function standardDeviation($array) {
        if (sizeof($array) < 3) {
            return 0;
        }

        try {
            return sqrt(array_sum(array_map(array($this, 'sdSquare'), $array, array_fill(0,count($array), (array_sum($array) / count($array)) ) ) ) / (count($array)-1) );
        }
        catch (\Exception $e) {
            Log::critical(json_encode($e));
            Log::critical('Standard Deveiation Error array'.PHP_EOL.$array);
        }
    }

    public function lineChangeDirection($line) {
        $lastThreeElements = $this->utility->getLastXElementsInArray($line, 3);

        if ($lastThreeElements[0] >= $lastThreeElements[1] && $lastThreeElements[1] < $lastThreeElements[2]) {
            return 'reversedUp';
        }
        elseif ($lastThreeElements[0] <= $lastThreeElements[1] && $lastThreeElements[1] > $lastThreeElements[2]) {
            return 'reversedDown';
        }
        else {
            return 'none';
        }
    }

    public function priceCrossoverLine($line, $rateCloses) {
        $currentPrice = end($rateCloses);
        $previousPrice = $this->utility->getXFromLastValue($rateCloses, 1);

        $secondToLastLineValue = $this->utility->getXFromLastValue($line, 1);;
        $lastLineValue = end($line);

        if ($previousPrice < $secondToLastLineValue && $currentPrice > $lastLineValue) {
            return 'crossedAbove';
        }
        elseif ($previousPrice > $secondToLastLineValue && $currentPrice < $lastLineValue) {
            return 'crossedBelow';
        }
        return 'none';
    }

    public function lineSameDirectionOverPastPeriods($line) {
        $pointDifferences = [];
        foreach ($line as $index=>$linePoint) {
            if ($index > 0) {
                $previousPoint = $line[$index-1];

                if ($linePoint > $previousPoint) {
                    $pointDifferences[] = 'up';
                }
                elseif ($linePoint < $previousPoint) {
                    $pointDifferences[] = 'down';
                }
                else {
                    $pointDifferences[] = 'equal';
                }
            }
        }

        if (count(array_unique($pointDifferences)) === 1) {
            return $pointDifferences[0];
        }
        else {
            return false;
        }
    }
}

