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
use App\Services\Utility;
use \Log;
use \App\Services\CurrencyIndicators;

class AdxEvents {
    public $utility;
    public $indicators;
    public $strategyLogger;

    public function __construct() {
        $this->utility = new Utility();
        $this->indicators = new CurrencyIndicators();
    }

    public function adxBelowThreshold($ratesFull, $adxLength, $threshold) {
        $adx = $this->indicators->adx($ratesFull, $adxLength);
        $adx = end($adx);

        if ($adx <= $threshold) {
            $this->strategyLogger->logMessage('adxBelowThreshold: BELOW', 1);
            return true;
        }else {
            $this->strategyLogger->logMessage('adxBelowThreshold: NOT BELOW', 1);
            return false;
        }
    }

    public function adxAboveThreshold($ratesFull, $adxLength, $threshold) {
        $adx = $this->indicators->adx($ratesFull, $adxLength);
        $adx = end($adx);

        if ($adx >= $threshold) {
            $this->strategyLogger->logMessage('adxBelowThreshold: ABOVE', 1);
            return true;
        }else {
            $this->strategyLogger->logMessage('adxBelowThreshold: NOT ABOVE', 1);
            return false;
        }
    }
}
