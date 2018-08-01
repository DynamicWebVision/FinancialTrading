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

class HmaLongHmaConfirm extends \App\Strategy\Strategy  {

    public $hullLength;
    public $hullLinearRegressionLength;


    public $hullLongLength;
    public $hullLongLengthLinRegLength;
    public $hullLinearRegressionSlopeRequirement;

    //Whether you will enter a new position
    public function decision() {
        Log::info($this->runId.': Decision Indicators '.json_encode($this->decisionIndicators));

        //if (!$this->fullPositionInfo['open']) {
        Log::info($this->runId.': Decision Completely Open.');

        if ($this->decisionIndicators['currentHmaLinRegSlope'] > 0 && $this->decisionIndicators['previousHmaLinRegSlope'] < 0 &&
            $this->decisionIndicators['hmaLongLinRegPipRates'] >= $this->hullLinearRegressionSlopeRequirement) {
            Log::warning($this->runId.': NEW LONG POSITION');
            return 'newLong';
        }
        elseif ($this->decisionIndicators['currentHmaLinRegSlope'] < 0 && $this->decisionIndicators['previousHmaLinRegSlope'] > 0 &&
            $this->decisionIndicators['hmaLongLinRegPipRates'] <= $this->hullLinearRegressionSlopeRequirement*-1) {
            Log::warning($this->runId.': NEW SHORT POSITION');
            return 'newShort';
        }

    }

    public function checkForNewPosition($rates) {
        $this->rates = $rates;

        $this->decisionIndicators['hma'] = $this->indicators->hma($rates, $this->hullLength);

        $this->decisionIndicators['hmaLinearRegressionSet'] = $this->indicators->linearRegressionSet($this->decisionIndicators['hma'], 3, $this->hullLinearRegressionLength);
        $this->decisionIndicators['currentHmaLinRegSlope'] = end($this->decisionIndicators['hmaLinearRegressionSet'])['m'];
        $this->decisionIndicators['previousHmaLinRegSlope'] = $this->decisionIndicators['hmaLinearRegressionSet'][count($this->decisionIndicators['hmaLinearRegressionSet'])-2]['m'];

        $this->ratesPips = $this->getRatesInPips($this->rates);

        $this->decisionIndicators['hmaLongPipRates'] = $this->indicators->hma($this->ratesPips, $this->hullLongLength);

        $this->decisionIndicators['hmaLongLinRegPipRates'] = $this->indicators->linearRegression($this->decisionIndicators['hmaLongPipRates'], $this->hullLongLengthLinRegLength);

        $this->decision = $this->decision();

        $this->calculatePositionAmount();
        if ($this->decision == 'newLong') {
            $this->closePosition();
            $this->newLongPosition();
        }
        elseif ($this->decision == 'newShort') {
            $this->closePosition();
            $this->newShortPosition();
        }
    }
}