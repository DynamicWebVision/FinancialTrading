<?php

/********************************************
RsiPbHmaSLBe Strategy System under RsiPullback Strategy
Created at: 01/19/19by Brian O'Neill
Description: RsiPbHmaSLBe
 ********************************************/

namespace App\EquityNewPositionConditions\RsiPullback;
use \Log;
use \App\IndicatorEvents\RsiEvents;
use \App\IndicatorEvents\HullMovingAverage;
use \App\IndicatorEvents\TrueRange;
use \App\IndicatorEvents\EmaEvents;

class RsiPbHmaSLBe extends \App\ForexStrategy\Strategy  {

    public $rsiLength;
    public $rsiCutoff;

    public $hmaLength;

    public $trueRangeLength = 14;
    public $trueRangeMultiplierNew;
    public $trueRangeMultiplierOpen;

    public $emaLength;

    public function longCheck($rates) {
        $rsiEvents = new \App\IndicatorEvents\RsiEvents;
        $rsiEvents->strategyLogger = $this->strategyLogger;

        $hmaEvents = new \App\IndicatorEvents\HullMovingAverage;
        $hmaEvents->strategyLogger = $this->strategyLogger;

        $trueRange = new \App\IndicatorEvents\TrueRange;
        $trueRange->strategyLogger = $this->strategyLogger;

        $emaEvents = new \App\IndicatorEvents\EmaEvents;
        $emaEvents->strategyLogger = $this->strategyLogger;

        $this->decisionIndicators['rsiOutsideLevel'] = $rsiEvents->outsideLevel(
            $this->rates['simple'], $this->rsiLength, $this->rsiCutoff);

        $this->decisionIndicators['priceAboveBelowHma'] = $hmaEvents->hmaPriceAboveBelowHma($this->rates['simple'], $this->hmaLength);

        $this->stopLossPipAmount = $trueRange->getStopLossPipValue($this->rates['full'], $this->trueRangeLength, $this->exchange->pip, $this->trueRangeMultiplierNew);

        $this->decisionIndicators['emaPriceAboveBelow'] = $emaEvents->priceAboveBelowEma($this->rates['simple'], $this->emaLength);
    }
}