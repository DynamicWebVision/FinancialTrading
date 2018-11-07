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
use App\IndicatorEvents\RsiEvents;
use \Log;
use Illuminate\Support\Facades\DB;
use App\IndicatorEvents\Bollinger;
use App\IndicatorEvents\AdxEvents;
use App\IndicatorEvents\EmaEvents;

class BollingerPriceTouchCloseAdx extends \App\Strategy\Strategy  {

    public $bollingerLength;
    public $bollingerSdMultiplier;

    public $rsiLength = 14;
    public $adxThreshold = 30;

    public $rsiUpperThreshold = 80;
    public $rsiLowerThreshold = 20;
    public $rsiPeriodsBack = 5;

    public function setEntryIndicators() {
        $bollinger = new Bollinger();
        $bollinger->strategyLogger = $this->strategyLogger;
        $adxEvents = new AdxEvents();
        $adxEvents->strategyLogger = $this->strategyLogger;
        $rsiEvents = new RsiEvents();
        $rsiEvents->strategyLogger = $this->strategyLogger;

        $this->decisionIndicators['bollingerOuterTouch'] = $bollinger->bollingerPriceMaxOutClosesInside($this->rates['simple'],
            $this->bollingerLength, $this->bollingerSdMultiplier, $this->rates['full']);

        $this->decisionIndicators['rsiUpperThresholdCheck'] = $rsiEvents->crossedLevelWithinPastXPeriods(
            $this->rates['simple'], $this->rsiLength, $this->rsiUpperThreshold, $this->rsiPeriodsBack);

        $this->decisionIndicators['rsiLowerThresholdCheck'] = $rsiEvents->crossedLevelWithinPastXPeriods(
            $this->rates['simple'], $this->rsiLength, $this->rsiLowerThreshold, $this->rsiPeriodsBack);
    }

    public function getEntryDecision() {
        $this->setEntryIndicators();

        Log::info($this->runId.': New Position Decision Indicators: '.PHP_EOL.' '.$this->logIndicators());

        //Simple MACD Crossover
        if ($this->decisionIndicators['bollingerOuterTouch'] == 'long' && $this->decisionIndicators['rsiLowerThresholdCheck'] == 'crossedAbove') {
            Log::warning($this->runId.': NEW LONG POSITION');
            return "long";
        }
        elseif ($this->decisionIndicators['bollingerOuterTouch'] == 'short' && $this->decisionIndicators['rsiLowerThresholdCheck'] == 'crossedBelow') {
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

        $this->decisionIndicators['bollingerCrossClose'] = $bollinger->closeAcrossCenterLine($this->rates['simple'], $this->bollingerLength, $this->bollingerSdMultiplier, $this->openPosition['side']);
    }

    public function getNewStopLoss() {
        $emaEvents = new EmaEvents();

        return $emaEvents->priceCrossEmaRate($this->rates['simple'], $this->bollingerLength);
    }

    public function checkForNewPosition() {
        $this->setOpenPosition();

        if (!$this->openPosition) {
            $this->decision = $this->getEntryDecision();
            $this->entryStayInDecision();
        }
        else {
            $this->setExitIndicators();

            if ($this->decisionIndicators['bollingerCrossClose'] == 'close') {
                $this->closePosition();
            }
            else {
                $newStopLoss = $this->getNewStopLoss();
                $this->modifyStopLoss($newStopLoss);
            }
        }
    }
}