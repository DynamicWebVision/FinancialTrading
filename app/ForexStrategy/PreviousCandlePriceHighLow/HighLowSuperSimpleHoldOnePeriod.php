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
use \App\IndicatorEvents\EmaEvents;
use \App\IndicatorEvents\EventHelpers;
use \App\IndicatorEvents\AdxEvents;
use \App\IndicatorEvents\TrueRange;

class HighLowSuperSimpleHoldOnePeriod extends \App\ForexStrategy\Strategy  {

    public function checkForNewPosition() {
        $emaEvents = new \App\IndicatorEvents\EmaEvents;
        $emaEvents->strategyLogger = $this->strategyLogger;

        $decisionIndicators['emaPriceAboveBelow'] = $emaEvents->priceAboveBelowEma($this->rates['simple'], 200);

        $this->setOpenPosition();

        $previousRate = end($this->rates['full']);
        $this->strategyLogger->logMessage('PreviousRate: '.json_encode($previousRate));

        if (!$this->openPosition) {
            $this->stopLossPipAmount = 25;

            $this->marketIfTouchedOrderPrice = $previousRate->highMid;
            $this->newLongPosition();

            $this->marketIfTouchedOrderPrice = $previousRate->lowMid;
            $this->newShortPosition();

//            if ($decisionIndicators['emaPriceAboveBelow'] == 'above') {
//                $this->marketIfTouchedOrderPrice = $previousRate->highMid;
//                $this->newLongPosition();
//            }
//            elseif ($decisionIndicators['emaPriceAboveBelow'] == 'below') {
//                $this->marketIfTouchedOrderPrice = $previousRate->lowMid;
//                $this->newShortPosition();
//            }
        }
        else {
            $this->closePosition();
            $this->stopLossPipAmount = 25;

            $this->marketIfTouchedOrderPrice = $previousRate->highMid;
            $this->newLongPosition();

            $this->marketIfTouchedOrderPrice = $previousRate->lowMid;
            $this->newShortPosition();
        }
    }
}
