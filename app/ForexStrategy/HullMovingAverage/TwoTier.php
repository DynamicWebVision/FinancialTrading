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

class TwoTier extends \App\ForexStrategy\Strategy  {

    public $fastHullLength;
    public $fastHullLinearRegressionLength;
    public $fastHullLinearRegressionSlopeRequirement;

    public $slowHullLength;
    public $slowHullLinearRegressionLength;
    public $slowHullLinearRegressionSlopeRequirement;

    public $adxPeriodLength;
    public $adxCutOffForDownwardSlope;
    public $adxCutOffForUpwardSlope;

    //Whether you will enter a new position
    public function decision() {
        $this->logIndicators();

        if ($this->decisionIndicators['fastHmaLinReg']['m'] >= $this->fastHullLinearRegressionSlopeRequirement
            && $this->decisionIndicators['slowHmaLinReg']['m'] >= $this->slowHullLinearRegressionSlopeRequirement
            && (end($this->decisionIndicators['adx']) >= $this->adxCutOffForDownwardSlope
                || (end($this->decisionIndicators['adx']) >= $this->adxCutOffForUpwardSlope && $this->decisionIndicators['adxLinReg']['m'] > 0))) {
            $this->strategyLogger->logDecisionMade('new_long');
            return 'long';
        }
        elseif ($this->decisionIndicators['fastHmaLinReg']['m'] <= ($this->fastHullLinearRegressionSlopeRequirement*-1)
            && $this->decisionIndicators['slowHmaLinReg']['m'] <= ($this->slowHullLinearRegressionSlopeRequirement*-1)
            && (end($this->decisionIndicators['adx']) >= $this->adxCutOffForDownwardSlope
                || (end($this->decisionIndicators['adx']) >= $this->adxCutOffForUpwardSlope && $this->decisionIndicators['adxLinReg']['m'] > 0))) {
            $this->strategyLogger->logDecisionMade('new_short');
            return 'short';
        }
        else {
            $this->strategyLogger->logDecisionMade('nothing');
            return 'nothing';
        }
    }

    public function checkForNewPosition() {
        $this->strategyLogger->logDecisionMade('check_new');

        $this->ratesPips = $this->getRatesInPips($this->rates);

        $this->decisionIndicators['fastHma'] = $this->indicators->hma($this->ratesPips, $this->fastHullLength);
        $this->decisionIndicators['fastHmaLinReg'] = $this->indicators->linearRegression($this->decisionIndicators['fastHma'], $this->fastHullLinearRegressionLength);

        $this->decisionIndicators['slowHma'] = $this->indicators->hma($this->ratesPips, $this->slowHullLength);
        $this->decisionIndicators['slowHmaLinReg'] = $this->indicators->linearRegression($this->decisionIndicators['slowHma'], $this->slowHullLinearRegressionLength);

        $this->decisionIndicators['adx'] = $this->indicators->adx($this->rates['full'], $this->adxPeriodLength);

        $this->decisionIndicators['adxLinReg'] = $this->indicators->linearRegression($this->decisionIndicators['adx'], $this->adxPeriodLength);

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