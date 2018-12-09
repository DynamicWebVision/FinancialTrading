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


namespace App\Strategy\Hlhb;
use \Log;
use App\IndicatorEvents\Momentum;
use App\IndicatorEvents\RsiEvents;
use \App\IndicatorEvents\EventHelpers;
use \App\IndicatorEvents\AdxEvents;

class HlhbAdx extends \App\Strategy\Strategy  {

    public $fastEma;
    public $slowEma;

    public $rsiLength;
    public $rsiBreakthroughLevel;

    public $adxLength = 14;
    public $adxUndersoldThreshold = 25;

    public function setEntryIndicators() {
        $momentum = new Momentum();
        $momentum->strategyLogger = $this->strategyLogger;
        $rsiEvents = new RsiEvents();
        $rsiEvents->strategyLogger = $this->strategyLogger;
        $adxEvents = new AdxEvents();
        $adxEvents->strategyLogger = $this->strategyLogger;

        $eventHelpers = new EventHelpers();

        $this->decisionIndicators['emaCrossover'] = $momentum->emaCrossover($this->rates['simple'], $this->fastEma, $this->slowEma);

        $this->decisionIndicators['rsiCrossedLevel'] = $rsiEvents->crossedLevel($this->rates['simple'], $this->rsiLength, $this->rsiBreakthroughLevel);

        $this->decisionIndicators['adxAboveThreshold'] = $adxEvents->adxAboveThreshold($this->rates['full'], $this->adxLength, $this->adxUndersoldThreshold);
    }

    public function getEntryDecision() {
        $this->setEntryIndicators();

        Log::info($this->runId.': New Position Decision Indicators: '.PHP_EOL.' '.$this->logIndicators());

        if ($this->decisionIndicators['emaCrossover'] == 'crossedAbove' && $this->decisionIndicators['rsiCrossedLevel'] == 'crossedAbove'
            && $this->decisionIndicators['adxAboveThreshold']) {
            Log::warning($this->runId.': NEW LONG POSITION');
            return "long";
        }
        elseif ($this->decisionIndicators['emaCrossover'] == 'crossedBelow' && $this->decisionIndicators['rsiCrossedLevel'] == 'crossedBelow'
            && $this->decisionIndicators['adxAboveThreshold']) {
            Log::warning($this->runId.': NEW SHORT POSITION');
            return "short";
        }
        else {
            Log::info($this->runId.': Failed Ema Breakthrough');
            return "none";
        }
    }

    public function checkForNewPosition() {
        $this->setOpenPosition();

        $this->decision = $this->getEntryDecision();
        if (!$this->openPosition) {
            $this->entryStayInDecision();
        }
        else {
            $this->exitNewPositionOrBreakEven();
        }
    }
}
