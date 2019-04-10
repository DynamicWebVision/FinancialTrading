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

class EmaEvents {

    public $utility;
    public $indicators;
    public $strategyLogger;

    public function __construct() {
        $this->utility = new Utility();
        $this->indicators = new CurrencyIndicators();
        $this->eventHelpers = new EventHelpers();
    }

    //Exponential Moving Average
    public function ema($rates, $numberOfPeriods){
        $emaValues = [];

        //calculate K
        $k = 2 / ($numberOfPeriods + 1);

        $smaValues = array_slice($rates,0,$numberOfPeriods);

        $yesterdayEma = $this->indicators->average($smaValues);

        $emaRates = array_slice($rates, $numberOfPeriods);

        foreach($emaRates as $rate) {
            $yesterdayEma = (($rate * $k) + $yesterdayEma * (1 - $k));
            $emaValues[] = $yesterdayEma;
        }
        return $emaValues;
    }

    public function k($numberOfPeriods) {
        $k = 2 / ($numberOfPeriods + 1);
        return $k;
    }

    public function calculateEmaNextPeriodCrossoverRate($rates, $fastLength, $slowLength) {
        $fastEma = $this->indicators->ema($rates, $fastLength);
        $slowEma = $this->indicators->ema($rates, $slowLength);

        $fastK = $this->k($fastLength);
        $currentFastEma = end($fastEma);


        $slowK = $this->k($slowLength);
        $currentSlowEma = end($slowEma);

        $previousFastValues = $currentFastEma * (1 - $fastK);
        $previousSlowValues = $currentSlowEma * (1 - $slowK);

        $response = [];
        $response['crossRate'] = (($previousSlowValues) - $previousFastValues)/($fastK-$slowK);

        if ($currentFastEma < $currentSlowEma) {
            $response['side'] = 'long';
        }
        elseif ($currentFastEma > $currentSlowEma) {
            $response['side'] = 'short';
        }
        return $response;
    }

    public function priceCrossEmaRate($rates, $length) {
        $ema = $this->indicators->ema($rates, $length);

        $k = $this->k($length);
        $currentEmaValue = end($ema);

        return -($currentEmaValue*(1-$k)/($k-1));
    }

    public function priceAboveBelowEma($rates, $length) {
        $ema = $this->indicators->ema($rates, $length);

        if (end($rates) > end($ema)) {
            return 'above';
        }
        elseif (end($rates) < end($ema)) {
            return 'below';
        }
    }

    public function priceCrossedOver($rates, $length) {
        $ema = $this->ema($rates, $length);
        return $this->eventHelpers->priceCrossoverLine($ema,$rates);
    }

    public function checkTwoLineCrossover($rates, $fastLength, $slowLength) {
        $fastEma = $this->ema($rates, $fastLength);
        $slowEma = $this->ema($rates, $slowLength);

        return $this->eventHelpers->lineCrossedOverLine($fastEma, $slowEma);
    }
}
