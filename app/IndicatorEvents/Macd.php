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
use \App\IndicatorEvents\EmaEvents;

use \App\IndicatorEvents\EventHelpers;

class Macd {
    public $utility;
    public $indicators;
    public $strategyLogger;
    protected $emaEvents;

    public function __construct() {
        $this->utility = new Utility();
        $this->eventHelpers = new EventHelpers();
        $this->emaEvents = new EmaEvents();
    }

    public function macd($rates, $shortPeriod, $longPeriod, $signalPeriod) {
        $shortEma = $this->emaEvents->ema($rates, $shortPeriod);
        $longEma = $this->emaEvents->ema($rates, $longPeriod);

        $shortPortionWithLong = array_slice($shortEma, sizeof($shortEma) - sizeof($longEma));

        $macdLine = [];

        foreach ($longEma as $index=>$value) {
            $macdLine[] = $shortPortionWithLong[$index] - $value;
        }

        $signalLine = $this->emaEvents->ema($macdLine, $signalPeriod);

        $macdWithSignal = array_slice($macdLine, sizeof($macdLine) - sizeof($signalLine));

        $histogram = [];

        foreach ($signalLine as $index=>$value) {
            $histogram[] = $macdWithSignal[$index] - $value;
        }

        return [
            'macd'=>$macdWithSignal,
            'signal'=>$signalLine,
            'histogram'=>$histogram
        ];
    }
}
