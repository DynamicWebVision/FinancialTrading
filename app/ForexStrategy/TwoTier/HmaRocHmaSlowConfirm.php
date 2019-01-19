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


namespace App\ForexStrategy\TwoTier;
use \Log;

class HmaRocHmaSlowConfirm extends \App\ForexStrategy\Strategy  {

    public $slowHullLength;
    public $slowHullSlopeCutoff;

    public $adxPeriodLength;
    public $adxCutOff;
    public $adxCutOffForUpwardSlope;

    public $fastHmaSlopeCutoff;
    public $fastHmaRocCutoff;

    public $fastHmaLength;

    //Whether you will enter a new position
    public function decision() {
        Log::info($this->runId.': Decision Indicators '.PHP_EOL.$this->logIndicators());

            //Outer Check
        if ($this->decisionIndicators['hmaSlowLinReg']['m'] >= $this->slowHullSlopeCutoff

            && ($this->decisionIndicators['fastHmaLinReg'] >= $this->fastHmaSlopeCutoff*-1 && $this->decisionIndicators['fastHmaRoc'] >= $this->fastHmaRocCutoff)) {
            Log::warning($this->runId.': NEW LONG POSITION');
            return 'long';
        }
        elseif ($this->decisionIndicators['hmaSlowLinReg']['m'] <= $this->slowHullSlopeCutoff*-1

            && ($this->decisionIndicators['fastHmaLinReg'] <= $this->fastHmaSlopeCutoff && $this->decisionIndicators['fastHmaRoc'] <= $this->fastHmaRocCutoff*-1)) {
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

        $this->decisionIndicators['fastHma'] = $this->indicators->hma($this->rates['simple'], $this->fastHmaLength);
        $this->decisionIndicators['fastHmaLinReg'] = $this->indicators->linearRegression($this->decisionIndicators['fastHma'], 2);
        $this->decisionIndicators['fastHmaRoc'] = $this->indicators->rateOfChange($this->decisionIndicators['fastHma'], 2);

        $this->decision = $this->decision();

        if ($this->checkOpenPositionsThreshold()) {
            $this->calculatePositionAmount();
            if ($this->decision == 'long') {
                $this->newLongOrStayInPosition();
            }
            elseif ($this->decision == 'short') {
                $this->newShortOrStayInPosition();
            }
        }
    }
}