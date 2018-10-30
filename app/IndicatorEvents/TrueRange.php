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

class TrueRange {

    public function __construct() {
        $this->indicators = new CurrencyIndicators();
        $this->eventHelpers = new EventHelpers();
    }

    public function getTakeProfitLossPipValues($rates, $periods, $exchangePips, $profitMultiplier , $lossMultiplier) {
        $trueRange = $this->indicators->averageTrueRange($rates, $periods);

        $response = [];

        $response['profitPips'] = round(($trueRange/$exchangePips)*$profitMultiplier);
        $response['lossPips'] = round(($trueRange/$exchangePips)*$lossMultiplier);
        return $response;
    }

    public function getStopLossPipValue($rates, $periods, $exchangePips , $lossMultiplier) {
        $trueRange = $this->indicators->averageTrueRange($rates, $periods);

        return round(($trueRange/$exchangePips)*$lossMultiplier);
    }
}
