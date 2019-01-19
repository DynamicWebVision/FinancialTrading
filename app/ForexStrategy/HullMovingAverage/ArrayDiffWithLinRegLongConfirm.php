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

class ArrayDiffWithLinRegLongConfirm extends \App\ForexStrategy\Strategy  {

    public $hullLength;
    public $hullLinearRegressionLength;
    public $hullLinearRegressionSlopeRequirement;

    public $hullLongLength;

    //Whether you will enter a new position
    public function decision() {
        Log::info($this->runId.': Decision Indicators '.json_encode($this->decisionIndicators));

        if ($this->decisionIndicators['hmaDirectionReverse'] == 'crossedUp' && $this->decisionIndicators['hmaLongLinReg']['m'] >= $this->hullLinearRegressionSlopeRequirement) {
            Log::warning($this->runId.': NEW LONG POSITION');
            return 'newLong';
        }
        elseif ($this->decisionIndicators['hmaDirectionReverse'] == 'crossedDown' && $this->decisionIndicators['hmaLongLinReg']['m'] <= $this->hullLinearRegressionSlopeRequirement*-1) {
            Log::warning($this->runId.': NEW SHORT POSITION');
            return 'newShort';
        }
    }

    public function checkForNewPosition($rates) {
        $this->rates = $rates;

        $this->ratesPips = $this->getRatesInPips($this->rates);

        $this->decisionIndicators['hmaPips'] = $this->indicators->hma($this->ratesPips, $this->hullLength);

        $this->decisionIndicators['hmaDirectionReverse'] = $this->indicators->reverseDirection($this->decisionIndicators['hmaPips']);

        $this->decisionIndicators['hmaLong'] = $this->indicators->hma($this->ratesPips, $this->hullLongLength);

        $this->decisionIndicators['hmaLongLinReg'] = $this->indicators->linearRegression($this->decisionIndicators['hmaLong'], $this->hullLinearRegressionLength);

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