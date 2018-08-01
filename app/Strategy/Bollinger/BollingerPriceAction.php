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
use App\StrategyEvents\Bollinger;

class BollingerPriceAction extends \App\Strategy\Strategy  {

    public $bollingerLength;
    public $bollingerSdMultiplier;

    public function setEntryIndicators() {
        $bollinger = new Bollinger();
        $bollinger->strategyLogger = $this->strategyLogger;

        $this->decisionIndicators['bollingerCross'] = $bollinger->getOuterBandCrossEvent($this->rates['simple'], $this->bollingerLength, $this->bollingerSdMultiplier);
    }

    public function getEntryDecision() {
        $this->setEntryIndicators();

        Log::info($this->runId.': New Position Decision Indicators: '.PHP_EOL.' '.$this->logIndicators());

        //Simple MACD Crossover
        if ($this->decisionIndicators['bollingerCross'] == 'crossedAbove') {
            Log::warning($this->runId.': NEW LONG POSITION');
            return "long";
        }
        elseif ($this->decisionIndicators['bollingerCross'] == 'crossedBelow') {
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

    public function getExitDecision() {
        $this->setExitIndicators();
        if ($this->decisionIndicators['bollingerCross'] == 'close') {
            return 'close';
        }
        else {
            return 'none';
        }
    }

    public function checkForNewPosition() {
        $this->openPosition = $this->checkOpenPosition();

        if (!$this->openPosition) {
            $this->decision = $this->getEntryDecision();
            $this->entryStayInDecision();
        }
        else {
            $this->decision = $this->getExitDecision();
            if ($this->decision == 'close') {
                $this->closePosition();
            }
        }
    }
}