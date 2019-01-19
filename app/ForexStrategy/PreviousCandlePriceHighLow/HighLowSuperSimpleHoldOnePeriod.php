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


namespace App\ForexStrategy\PreviousCandlePriceHighLow;
use \Log;
use App\IndicatorEvents\HullMovingAverage;
use App\IndicatorEvents\RsiEvents;
use \App\IndicatorEvents\EventHelpers;
use \App\IndicatorEvents\AdxEvents;
use \App\IndicatorEvents\TrueRange;

class HighLowSuperSimpleHoldOnePeriod extends \App\ForexStrategy\Strategy  {

    public function checkForNewPosition() {
        $this->setOpenPosition();

        $previousRate = end($this->rates['full']);

        if (!$this->openPosition) {
            $this->stopLossPipAmount = 25;
            $this->marketIfTouchedOrderPrice = $previousRate->highMid + $this->exchange->pip;
            $this->newLongPosition();

            $this->marketIfTouchedOrderPrice = $previousRate->lowMid - $this->exchange->pip;
            $this->newShortPosition();
        }
        else {
            $this->closePosition();

            $this->stopLossPipAmount = 25;
            $this->marketIfTouchedOrderPrice = $previousRate->highMid + $this->exchange->pip;
            $this->newLongPosition();

            $this->marketIfTouchedOrderPrice = $previousRate->lowMid - $this->exchange->pip;
            $this->newShortPosition();
        }
    }
}
