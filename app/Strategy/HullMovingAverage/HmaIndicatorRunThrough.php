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


namespace App\Strategy\HullMovingAverage;
use \Log;
use Illuminate\Support\Facades\DB;

class HmaIndicatorRunThrough extends \App\Strategy\Strategy  {

    public $hullLength;
    public $hullLinearRegressionLength;
    public $hullLinearRegressionSlopeRequirement;

    public $hullLongLength;

    public $slowHullLength;
    public $slowHullSlopeCutoff;

    public $adxPeriodLength;
    public $adxCutOff;
    public $adxCutOffForUpwardSlope;

    public $fastHmaSlopeCutoff;
    public $fastHmaRocCutoff;

    public $fastEmaLength;

    public function checkForNewPosition() {

        $ratePips = $this->getRatesInPips($this->rates);

        $this->decisionIndicators['fastHma'] = $this->indicators->hma($ratePips, $this->fastEmaLength);
        $this->decisionIndicators['fastHmaLinReg'] = $this->indicators->linearRegression($this->decisionIndicators['fastHma'], 2);
        $this->decisionIndicators['fastHmaRoc'] = $this->indicators->rateOfChange($this->decisionIndicators['fastHma'], 2);

        DB::table('tbd_linear_regression_slope')->insert(
            ['slope_lin_reg' => $this->decisionIndicators['fastHmaRoc'], 'date_time' => date("Y-m-d H:i:s", strtotime($this->currentPriceData->dateTime))]
        );
    }
}