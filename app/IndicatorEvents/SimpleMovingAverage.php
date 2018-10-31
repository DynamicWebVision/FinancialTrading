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

class SimpleMovingAverage {

    public function __construct() {
        $this->indicators = new CurrencyIndicators();
        $this->eventHelpers = new EventHelpers();
        $this->utility = new Utility();
    }

    public function smaCurrentValue($rates, $length) {
        $arraySubset = $this->utility->getLastXElementsInArray($rates, $length);
        return array_sum($arraySubset)/count($arraySubset);
    }

    public function currentPriceRelationMA($rates, $length) {
        $price = end($rates);
        $currentMa = $this->smaCurrentValue($rates, $length);

        if ($price > $currentMa) {
            return 'above';
        }
        elseif ($price < $currentMa) {
            return 'below';
        }
        else {
            return 'equal';
        }
    }

    public function getCrossPriceTarget($rates, $length) {
        $arraySubset = $this->utility->getLastXElementsInArray($rates, $length);
        unset($arraySubset[0]);
        return (-array_sum($arraySubset))/(-$length + 1);
    }

    public function getPriceTargetOnCrossAndOnePip($rates, $length, $pip) {
        $priceRelation = $this->currentPriceRelationMA($rates, $length);
        $priceBreakthrough = $this->getCrossPriceTarget($rates, $length);

        if ($priceRelation == 'above') {
            return $priceBreakthrough - $pip;
        }
        elseif ($priceRelation == 'below') {
            return $priceBreakthrough + $pip;
        }
    }

    public function wholeCandleCross($rates, $length) {
        $lastRatePriceData = end($rates);

        $secondToLastRate = $this->utility->getXFromLastValue($rates, 1);

        $simpleRates = array_map(function($rate) {
            return $rate->closeMid;
        }, $rates);

        $arrayWithoutLast = $this->utility->removeLastValueInArray($simpleRates);
        $previousSma = $this->smaCurrentValue($arrayWithoutLast, $length);
        $currentSma = $this->smaCurrentValue($simpleRates, $length);

        if ($secondToLastRate->closeMid < $previousSma && $lastRatePriceData->openMid > $currentSma && $lastRatePriceData->closeMid > $currentSma) {
            return 'crossedAbove';
        }
        elseif ($secondToLastRate->closeMid > $previousSma && $lastRatePriceData->openMid < $currentSma && $lastRatePriceData->closeMid < $currentSma) {
            return 'crossedBelow';
        }
    }
}
