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

class Bollinger {

    public $utility;
    public $indicators;
    public $strategyLogger;

    public function __construct() {
        $this->utility = new Utility();
        $this->indicators = new CurrencyIndicators();
        $this->eventHelpers = new EventHelpers();
    }

    public function bollingerBands($array, $length, $standardDeviationMultiplier) {
        $arrayValuesNeeded = $this->utility->getLastXElementsInArray($array, $length);

        $average = array_sum($arrayValuesNeeded)/$length;

        $standardDeviation = $this->eventHelpers->standardDeviation($arrayValuesNeeded);

        $high = $average + ($standardDeviation*$standardDeviationMultiplier);
        $low = $average - ($standardDeviation*$standardDeviationMultiplier);

        return [
            'average'=> $average,
            'high'=> $high,
            'low'=> $low
        ];
    }

    //Done
    public function getOuterBandCrossEvent($array, $length, $standardDeviationMultiplier) {
        $currentClose = end($array);
        $bollingerBandsCurrent = $this->bollingerBands($array, $length, $standardDeviationMultiplier);

        $secondToLastClose = $this->utility->getXFromLastValue($array, 1);
        $previousRates = $this->utility->removeLastValueInArray($array);
        $bollingerBandsPrevious = $this->bollingerBands($previousRates, $length, $standardDeviationMultiplier);

        if ($secondToLastClose < $bollingerBandsPrevious['high'] && $currentClose > $bollingerBandsCurrent['high']) {
            return 'crossedAbove';
        }
        elseif ($secondToLastClose > $bollingerBandsPrevious['low'] && $currentClose < $bollingerBandsCurrent['low']) {
            return 'crossedBelow';
        }
        else {
            return 'none';
        }
    }

    public function closeAcrossCenterLine($array, $length, $standardDeviationMultiplier, $openSide) {
        $currentClose = end($array);
        $bollingerBands = $this->bollingerBands($array, $length, $standardDeviationMultiplier);

        if ($openSide == 'long') {
            if ($currentClose <= $bollingerBands['average']) {
                return 'close';
            }
        }
        elseif ($openSide == 'short') {
            if ($currentClose >= $bollingerBands['average']) {
                return 'close';
            }
        }
        return 'none';
    }

    public function bollingerPriceMaxOutClosesInside($array, $length, $standardDeviationMultiplier, $fullRates) {
        $currentFullRate = end($fullRates);
        $bollingerBands = $this->bollingerBands($array, $length, $standardDeviationMultiplier);

        if ($bollingerBands['high'] < $currentFullRate->highMid && $currentFullRate->closeMid < $bollingerBands['high'] && $currentFullRate->openMid < $bollingerBands['high']) {
            return 'short';
        }
        elseif ($bollingerBands['low'] > $currentFullRate->lowMid && $currentFullRate->closeMid > $bollingerBands['low'] && $currentFullRate->openMid > $bollingerBands['low']) {
            return 'long';
        }
        else {
            return 'none';
        }
    }
}
