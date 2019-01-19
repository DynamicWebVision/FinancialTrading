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


namespace App\ForexStrategy\HullMovingAverage;
use \Log;
use Illuminate\Support\Facades\DB;

class HmaStayInDifferentEntryExitAdx extends \App\ForexStrategy\Strategy  {

    public $hullLength;

    public $hmaLinRegCutoff = 0;
    public $hmaCloseLinRegCutoff = 0;

    public $adxPeriodLength;
    public $adxCutOff;
    public $adxCutOffForUpwardSlope;

    //Whether you will enter a new position
    public function decision() {
        $this->logIndicators();

        //if (!$this->fullPositionInfo['open']) {

        if ($this->decisionIndicators['hmaLinReg']['m'] >= $this->hmaLinRegCutoff && $this->decisionIndicators['adx'] > $this->adxCutOff) {
            $this->strategyLogger->logDecisionMade('new_long');
            return 'long';
        }
        elseif ($this->decisionIndicators['hmaLinReg']['m'] <= ($this->hmaLinRegCutoff*-1) && $this->decisionIndicators['adx'] > $this->adxCutOff) {
            $this->strategyLogger->logDecisionMade('new_short');
            return 'short';
        }
        else {
            $this->strategyLogger->logDecisionMade('close-none');
            return 'close';
        }
    }

    public function openDecision() {
        Log::info($this->runId.': Open Decision Indicators '.PHP_EOL.$this->logIndicators());

        //if (!$this->fullPositionInfo['open']) {

        if ($this->openPosition['side'] == 'long') {
            if ($this->decisionIndicators['hmaLinReg']['m'] <= $this->hmaCloseLinRegCutoff) {
                $this->strategyLogger->logDecisionMade('close');
                return 'close';
            }
        }
        else {
            if ($this->decisionIndicators['hmaLinReg']['m'] >= $this->hmaCloseLinRegCutoff*-1) {
                $this->strategyLogger->logDecisionMade('close');
                return 'close';
            }
        }
    }

    public function checkForNewPosition() {
        $this->ratesPips = $this->getRatesInPips($this->rates);

        $this->decisionIndicators['hma'] = $this->indicators->hma($this->ratesPips, $this->hullLength);
        $this->decisionIndicators['hmaLinReg'] = $this->indicators->linearRegression($this->decisionIndicators['hma'], 2);

        $this->decisionIndicators['adx'] = $this->indicators->adx($this->rates['full'], $this->adxPeriodLength);

        $this->openPosition = $this->checkOpenPosition();

        if (!$this->openPosition) {
            $this->strategyLogger->logDecisionType('check_new');
            $this->decision = $this->decision();
        }
        else {
            $this->strategyLogger->logDecisionType('check_open');
            $this->decision = $this->openDecision();
        }


        if ($this->decision == 'long') {
            $this->newLongOrAdjustSL();
        }
        elseif ($this->decision == 'short') {
            $this->newShortOrAdjustSL();
        }
        elseif ($this->decision == 'close') {
            $this->closePosition();
        }
    }
}