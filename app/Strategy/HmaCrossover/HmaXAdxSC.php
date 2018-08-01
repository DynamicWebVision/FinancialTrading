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


namespace App\Strategy\HmaCrossover;
use \Log;
use \App\StrategyEvents\Momentum;
use \App\StrategyEvents\AdxEvents;

class HmaXAdxSC extends \App\Strategy\Strategy  {

    public $hmaFastLength;
    public $hmaSlowLength;

    public $adxCutoff;
    public $adxPeriodLength;

    //Whether you will enter a new position
    public function decision() {
        Log::info($this->runId.': Decision Indicators '.json_encode($this->decisionIndicators));

        //if (!$this->fullPositionInfo['open']) {
        Log::info($this->runId.': Decision Completely Open.');

        if ($this->decisionIndicators['hma'] == 'crossedAbove' && !$this->decisionIndicators['adxBelowThreshold'] && $this->decisionIndicators['hmaDirection'] == 'up') {
            Log::warning($this->runId.': NEW LONG POSITION');
            return 'long';
        }
        elseif ($this->decisionIndicators['hma'] == 'crossedBelow' && !$this->decisionIndicators['adxBelowThreshold'] && $this->decisionIndicators['hmaDirection'] == 'down') {
            Log::warning($this->runId.': NEW SHORT POSITION');
            return 'short';
        }
    }

    public function entryCheck() {
        $momentum = new Momentum();
        $momentum->strategyLogger = $this->strategyLogger;
        $adxEvents = new AdxEvents();
        $adxEvents->strategyLogger = $this->strategyLogger;

        $this->decisionIndicators['hma'] = $momentum->hmaCrossover($this->rates['simple'], $this->hmaFastLength, $this->hmaSlowLength);

        $this->decisionIndicators['adxBelowThreshold'] = $adxEvents->adxAboveThreshold($this->rates['full'], $this->adxPeriodLength, $this->adxCutoff);

        $this->decisionIndicators['hmaDirection'] = $momentum->hmaSlopeDirection($this->rates['simple'], $this->hmaFastLength);

        $this->decision = $this->decision();

        if ($this->decision == 'long') {
            $this->newLongOrStayInPosition();
        }
        elseif ($this->decision == 'short') {
            $this->newShortOrStayInPosition();
        }
    }

    public function exitCheck() {
        $momentum = new Momentum();
        $momentum->strategyLogger = $this->strategyLogger;

        $this->decisionIndicators['hmaDirection'] = $momentum->hmaSlopeDirection($this->rates['simple'], $this->hmaFastLength);
        $this->decisionIndicators['hmaFastSlowPlacement'] = $momentum->fastSlowPlacement($this->rates['simple'], $this->hmaFastLength, $this->hmaSlowLength);

        if (($this->decisionIndicators['hmaDirection'] == 'down' || $this->decisionIndicators['hmaFastSlowPlacement'] == 'fastBelow') && $this->openPosition['side'] == 'long') {
            $this->closePosition();
        }
        elseif (($this->decisionIndicators['hmaDirection'] == 'up' || $this->decisionIndicators['hmaFastSlowPlacement'] == 'fastAbove') && $this->openPosition['side'] == 'short') {
            $this->closePosition();
        }
        else {
            return 'nothing';
        }
    }

    public function checkForNewPosition() {
        if (!$this->openPosition) {
            $this->entryCheck();
        }
        else {
            $this->exitCheck();
        }
    }
}
