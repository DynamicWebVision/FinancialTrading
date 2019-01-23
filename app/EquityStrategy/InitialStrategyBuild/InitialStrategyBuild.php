<?php

/********************************************
RsiPbHmaTrSl Strategy System under RsiPullback Strategy
Created at: 01/14/19by Brian O'Neill
Description: This is is the Rsi pullback strategy with better stop loss rules from the beginning.
 ********************************************/

namespace App\EquityStrategy\InitialStrategyBuild;
use \Log;
use \App\IndicatorEvents\HullMovingAverage;
use \App\IndicatorEvents\RsiEvents;
use \App\IndicatorEvents\TrueRange;
use \App\IndicatorEvents\EmaEvents;

class InitialStrategyBuild extends \App\ForexStrategy\Strategy  {

    public $hmaLength;
    public $rsiLength;
    public $rsiCutoff;
    public $trueRangeLength = 14;
    public $trueRangeMultiplier;
    public $emaLength;

    public function startNewPeriod() {
        $this->setOpenPosition();

        if (!$this->openPosition) {
            $this->decision = $this->getEntryDecision();
        }
        else {
            $this->inPositionDecision();
        }
    }
}