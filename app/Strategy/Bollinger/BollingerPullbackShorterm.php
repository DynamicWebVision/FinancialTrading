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
use App\IndicatorEvents\AdxEvents;
use App\IndicatorEvents\EmaEvents;

class BollingerPullbackShorterm extends \App\Strategy\Strategy  {

    public $bollingerLength;
    public $bollingerSdMultiplier;

    public $adxLength = 14;
    public $adxThreshold = 30;

    public function setEntryIndicators() {
        $bollinger = new Bollinger();
        $bollinger->strategyLogger = $this->strategyLogger;
        $adxEvents = new AdxEvents();
        $adxEvents->strategyLogger = $this->strategyLogger;

        $this->decisionIndicators['bollingerCross'] = $bollinger->getOuterBandCrossEvent($this->rates['simple'], $this->bollingerLength, $this->bollingerSdMultiplier);

        $this->decisionIndicators['adxAboveThreshold'] = $adxEvents->adxBelowThreshold($this->rates['full'], $this->adxLength, $this->adxThreshold);
    }

    public function getEntryDecision() {
        $this->setEntryIndicators();

        Log::info($this->runId.': New Position Decision Indicators: '.PHP_EOL.' '.$this->logIndicators());

        //Simple MACD Crossover
        if ($this->decisionIndicators['bollingerCross'] == 'crossedAbove' && $this->decisionIndicators['adxAboveThreshold']) {
            Log::warning($this->runId.': NEW LONG POSITION');
            return "long";
        }
        elseif ($this->decisionIndicators['bollingerCross'] == 'crossedBelow' && $this->decisionIndicators['adxAboveThreshold']) {
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
        }
    }
}