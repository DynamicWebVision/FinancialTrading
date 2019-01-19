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

class HmaWithArrayDiff extends \App\ForexStrategy\Strategy  {

    public $hullLength;
    public $hullLinearRegressionLength;
    public $hullLinearRegressionSlopeRequirement;

    public $hullLongLength;

    //Whether you will enter a new position
    public function decision() {
        Log::info($this->runId.': Decision Indicators '.json_encode($this->decisionIndicators));

        if ($this->decisionIndicators['hmaDirectionReverse'] == 'crossedUp') {
            Log::warning($this->runId.': NEW LONG POSITION');
            return 'newLong';
        }
        elseif ($this->decisionIndicators['hmaDirectionReverse'] == 'crossedDown') {
            Log::warning($this->runId.': NEW SHORT POSITION');
            return 'newShort';
        }

    }

    public function checkForNewPosition($rates) {
        $this->rates = $rates;

        $this->ratesPips = $this->getRatesInPips($this->rates);

        $this->decisionIndicators['hmaPips'] = $this->indicators->hma($this->ratesPips, $this->hullLength);

        $this->decisionIndicators['hmaDirectionReverse'] = $this->indicators->reverseDirection($this->decisionIndicators['hmaPips']);

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