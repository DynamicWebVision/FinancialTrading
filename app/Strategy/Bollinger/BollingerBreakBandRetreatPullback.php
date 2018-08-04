<?php
/**
 * Created by PhpStorm.
 * User: brian
 * Date: 10/12/2017
 * Time: 5:11 PM
 * Description: This is a strategy that tries to follow the Hull Momentum Average HMA trend.
 *
 * Decisions:
 * BUY      ---> HMA meets positive up slope linear regression requirement
 * Short    ---> HMA meets negative up slope linear regression requirement
 */


namespace App\Strategy\Bollinger;
use \Log;
use Illuminate\Support\Facades\DB;
use App\IndicatorEvents\Bollinger;
use App\IndicatorEvents\RsiEvents;
use App\IndicatorEvents\EmaEvents;

class BollingerBreakBandRetreatPullback extends \App\Strategy\Strategy  {

    public $bollingerLength;
    public $bollingerSdMultiplier;

    public $upperRsiBreakthroughLevel;
    public $lowerRsiBreakthroughLevel;

    public $rsiLength = 14;
    public $rsiUpperThreshold = 75;
    public $rsiLowerThreshold = 25;
    public $rsiPeriodsBack = 5;

    public function setEntryIndicators() {
        $bollinger = new Bollinger();
        $bollinger->strategyLogger = $this->strategyLogger;
        $rsiEvents = new RsiEvents();
        $rsiEvents->strategyLogger = $this->strategyLogger;

        $this->decisionIndicators['bollingerRetreat'] = $bollinger->bollingerPriceMaxOutClosesInside($this->rates['simple'], $this->bollingerLength, $this->bollingerSdMultiplier, $this->rates['full']);

        $this->decisionIndicators['rsiUpperThresholdCheck'] = $rsiEvents->crossedLevelWithinPastXPeriods(
            $this->rates['simple'], $this->rsiLength, $this->rsiUpperThreshold, $this->rsiPeriodsBack);

        $this->decisionIndicators['rsiLowerThresholdCheck'] = $rsiEvents->crossedLevelWithinPastXPeriods(
            $this->rates['simple'], $this->rsiLength, $this->rsiLowerThreshold, $this->rsiPeriodsBack);
    }

    public function getEntryDecision() {
        $this->setEntryIndicators();

        Log::info($this->runId.': New Position Decision Indicators: '.PHP_EOL.' '.$this->logIndicators());

        //Simple MACD Crossover
        if ($this->decisionIndicators['bollingerRetreat'] == 'long' && $this->decisionIndicators['rsiLowerThresholdCheck'] == 'crossedAbove') {
            Log::warning($this->runId.': NEW LONG POSITION');
            return "long";
        }
        elseif ($this->decisionIndicators['bollingerRetreat'] == 'short' && $this->decisionIndicators['rsiUpperThresholdCheck'] == 'crossedBelow') {
            Log::warning($this->runId.': NEW SHORT POSITION');
            return "short";
        }
        else {
            Log::info($this->runId.': Failed Ema Breakthrough');
            return "none";
        }
    }

    public function setExitIndicators() {
        $bollinger = new Bollinger();
        $bollinger->strategyLogger = $this->strategyLogger;

        $this->decisionIndicators['bollingerCross'] = $bollinger->closeAcrossCenterLine($this->rates['simple'], $this->bollingerLength, $this->bollingerSdMultiplier, $this->openPosition['side']);
    }

    public function getNewStopLoss() {
        $emaEvents = new EmaEvents();

        return $emaEvents->priceCrossEmaRate($this->rates['simple'], $this->bollingerLength);
    }

    public function checkForNewPosition() {
        $this->openPosition = $this->checkOpenPosition();

        if (!$this->openPosition) {
            $this->decision = $this->getEntryDecision();
            $this->entryStayInDecision();
        }
        else {
            $newStopLoss = $this->getNewStopLoss();
            $this->modifyStopLoss($newStopLoss);
            $this->setExitIndicators();

            if ($this->decisionIndicators['bollingerCross'] == 'close') {
                $this->closePosition();
            }
        }
    }
}