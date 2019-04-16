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


class PeriodsOpenEvents {

    public $utility;
    public $indicators;
    public $strategyLogger;

    public function __construct() {
        $this->utility = new Utility();
        $this->eventHelpers = new CurrencyIndicators();
    }

    //Working Indicator
    public function openPositionIndicatorDrivenCheck($openPosition, $indicatorVariable, $multiplier) {
        $periodsOpenCutoff = round($indicatorVariable*$multiplier);

        if ($openPosition['periodsOpen'] >= $periodsOpenCutoff) {
            return true;
        }
        else {
            return false;
        }
    }
}
