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


namespace App\Strategy\TwoTier;
use \Log;

class HmaSlowMomentumBollinger extends \App\Strategy\Strategy  {

    public $slowHullLength;

    public $adxPeriodLength;
    public $adxCutOff;
    public $adxCutOffForUpwardSlope;

    //Whether you will enter a new position
    public function decision() {
        Log::info($this->runId.': Decision Indicators '.PHP_EOL.$this->logIndicators());


        if ($this->decisionIndicators['hmaSlowLinReg']['m'] >= 10 && $this->decisionIndicators['emaCrossover'] == "crossedAbove" && end($this->decisionIndicators['adx']) >= $this->adxCutoff) {
            Log::warning($this->runId.': NEW LONG POSITION');
            return 'long';
        }
        elseif ($this->decisionIndicators['hmaSlowLinReg']['m'] <= 10 && $this->decisionIndicators['emaCrossover'] == "crossedBelow" && end($this->decisionIndicators['adx']) >= $this->adxCutoff) {
            Log::warning($this->runId.': NEW SHORT POSITION');
            return 'short';
        }
        else {
            Log::warning($this->runId.': NO POSITION DECISION');
            return 'nothing';
        }
    }

    public function checkForNewPosition() {
        $this->slowRatesPips = $this->getRatesInPips($this->slowRates);

        $this->decisionIndicators['hmaSlow'] = $this->indicators->hma($this->slowRatesPips, $this->slowHullLength);
        $this->decisionIndicators['hmaSlowLinReg'] = $this->indicators->linearRegression($this->decisionIndicators['hmaSlow'], 2);

        $this->decisionIndicators['fastEma'] = $this->indicators->ema($this->rates['simple'], $this->fastEmaLength);
        $this->decisionIndicators['slowEma'] = $this->indicators->ema($this->rates['simple'], $this->slowEmaLength);

        $this->decisionIndicators['emaCrossover'] = $this->indicators->checkCrossoverGoBackTwo($this->decisionIndicators['fastEma'], $this->decisionIndicators['slowEma']);

        $this->decisionIndicators['adx'] = $this->indicators->adx($this->rates['full'], $this->adxPeriodLength);

        $this->decision = $this->decision();

        if ($this->checkOpenPositionsThreshold()) {
            $this->calculatePositionAmount();
            if ($this->decision == 'long') {
                $this->closePosition();
                $this->newLongPosition();
            }
            elseif ($this->decision == 'short') {
                $this->closePosition();
                $this->newShortPosition();
            }
        }
    }
}