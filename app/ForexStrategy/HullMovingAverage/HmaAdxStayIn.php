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

class HmaAdxStayIn extends \App\ForexStrategy\Strategy  {

    public $hullLength;

    public $hmaLinRegCutoff = 0;

    public $adxPeriodLength;
    public $adxCutOff;
    public $adxCutOffForUpwardSlope;

    //Whether you will enter a new position
    public function decision() {
        Log::info($this->runId.': Decision Indicators '.PHP_EOL.$this->logIndicators());

        //if (!$this->fullPositionInfo['open']) {

        if ($this->decisionIndicators['hmaLinReg']['m'] >= .25 && $this->decisionIndicators['adx'] > 30) {
            $this->strategyLogger->logDecisionMade('new_long');
            return 'long';
        }
        elseif ($this->decisionIndicators['hmaLinReg']['m'] <= -.25 && $this->decisionIndicators['adx'] > 30) {
            Log::warning($this->runId.': NEW SHORT POSITION');
            return 'short';
        }
        else {
            Log::warning($this->runId.': NO POSITION DECISION');
            return 'nothing';
        }
    }

    public function checkForNewPosition() {
        $this->ratesPips = $this->getRatesInPips($this->rates);

        $this->decisionIndicators['hma'] = $this->indicators->hma($this->ratesPips, $this->hullLength);
        $this->decisionIndicators['hmaLinReg'] = $this->indicators->linearRegression($this->decisionIndicators['hma'], 2);

        $this->decisionIndicators['adx'] = $this->indicators->adx($this->rates['full'], $this->adxPeriodLength);


        $this->decision = $this->decision();

        if ($this->decision == 'long') {
            $this->newLongOrAdjustSL();
        }
        elseif ($this->decision == 'short') {
            $this->newShortOrAdjustSL();
        }
        elseif ($this->decision == 'nothing') {
            $this->closePosition();
        }
    }
}