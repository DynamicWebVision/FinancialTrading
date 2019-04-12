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
use \App\Services\Utility;
use \App\IndicatorEvents\EventHelpers;

class PriceMovements  {

    public $utility;
    public $indicators;

    public $strategyLogger;

    public $currentAverageGain;
    public $currentAverageLoss;
    public $rsi;

    public function __construct() {
        $this->utility = new Utility();
        $this->eventHelpers = new EventHelpers();
    }

    public function periodsWithoutProfitability($openPosition, $periodCutoff) {
        if ($openPosition['periodsOpen'] > $periodCutoff && (float) $this->openPosition['gl'] < 0) {
            return true;
        }
        else {
            return false;
        }
    }

}
