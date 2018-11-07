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


namespace App\Strategy;
use \Log;
use Illuminate\Support\Facades\DB;

class HighLowBreakout extends \App\Strategy\Strategy  {

    public $entryPeriodCount;
    public $exitPeriodCount;

    //Whether you will enter a new position
    public function newPositionDecision() {
        Log::info($this->runId.': New Decision Indicators '.PHP_EOL.' '.$this->logIndicators());

        if ($this->decisionIndicators['breakOut'] == 'upperBreakthrough' && $this->decisionIndicators['mostRecentPrice'] > end($this->decisionIndicators['slowEma'])) {
            Log::warning($this->runId.': NEW LONG POSITION');
            return 'newLong';
        }
        elseif ($this->decisionIndicators['breakOut'] == 'lowerBreakthrough' && $this->decisionIndicators['mostRecentPrice'] > end($this->decisionIndicators['slowEma'])) {
            Log::warning($this->runId.': NEW SHORT POSITION');
            return 'newShort';
        }
    }

    //Whether you will enter a new position
    public function exitDecision() {
        Log::info($this->runId.': Exit Decision Indicators '.PHP_EOL.' '.$this->logIndicators());

        if ($this->fullPositionInfo['side'] == "buy") {
            if ($this->decisionIndicators['breakOut'] == 'lowerBreakthrough') {
                Log::warning($this->runId.': CLOSE POSITION');
                return "close";
            }
            else {
                Log::info($this->runId.': close position fail');
                return "nothing";
            }
        }
        elseif ($this->fullPositionInfo['side'] == "sell") {
            if ($this->decisionIndicators['breakOut'] == 'upperBreakthrough') {
                Log::warning($this->runId.': CLOSE POSITION');
                return "close";
            }
            else {
                Log::info($this->runId.': close position fail');
                return "nothing";
            }
        }
    }

    public function checkForNewPosition($rates) {
        $this->rates = $rates;
        $this->utility = new \App\Services\Utility();

        $this->decisionIndicators['breakOut'] = $this->indicators->periodCountMaxMin($rates, $this->entryPeriodCount);
        $this->decisionIndicators['slowEma'] = $this->indicators->ema($rates, $this->slowEmaLength);
        $this->decisionIndicators['mostRecentPrice'] = end($rates);

        $this->decision = $this->newPositionDecision();

        $this->calculatePositionAmount();
        if ($this->decision == 'newLong') {
            $this->newLongPosition();
        }
        elseif ($this->decision == 'newShort') {
            $this->newShortPosition();
        }
    }

    public function checkToClosePosition($rates) {
        $this->utility = new \App\Services\Utility();

        $this->decisionIndicators['breakOut'] = $this->indicators->periodCountMaxMin($rates, $this->exitPeriodCount);

        $this->decision = $this->exitDecision();

        if ($this->decision == 'close') {
            $this->closePosition();
        }
    }
}
