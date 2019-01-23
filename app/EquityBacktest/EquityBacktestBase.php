<?php namespace App\ForexBackTest;

use App\Model\BackTestToBeProcessed;
use \DB;
use \App\Model\HistoricalRates;
use \App\Model\DebugLog;
use \App\Model\DecodeFrequency;
use \App\Services\Utility;
use \Log;
use Illuminate\Support\Facades\Config;

abstract class EquityBackTestBase  {

    public $oanda;
    public $utility;
    public $passedOuter;

    public $strategyRunName;

    public $rateCount;
    public $slowRateCount;
    public $rateIndicatorMin;
    public $slowRateIndicatorMin = 0;

    public $rates;
    protected $shortMinimum;
    public $slowRates;
    public $twoTierRates = false;
    public $currentSlowRates;

    public $currentRates;

    protected $longCutOffDate;

    public $lastId;
    public $lastSlowId;

    public $currencyId;
    public $frequencyId;

    public $slowFrequencyId;

    public $exchange;

    public $backTestId;
    public $rateIndex;
    public $slowRateIndex;

    public $trailingStopRate;

    public $currentRatesProcessed;
    public $currentSlowRatesProcessed;

    public $strategy;

    public $currentPriceData;

    public $rateUnixStart;
    public $slowRateUnixStart;

    public $strategyId;

    public $keepLooping = true;

    public $stopLoss;
    public $takeProfit;
    public $trailingStop;

    public $currentPositionMaxRate;
    public $currentPositionMinRate;

    public $rateLevel = 'simple';
    public $slowRateLevel = 'simple';

    public $processId;
    public $forceEnd = false;

    public function __construct($testDesc) {
        Log::info('BACK TEST '.$testDesc.' START');
        $this->utility = new Utility();

        //Set it so there is no process timeout
        set_time_limit(0);
    }

    public function recordBackTestStart($processId = 0) {
        $backTestDB = new \App\Model\BackTest();

        $backTestDB->strategy_id = $this->strategyId;

        $backTestDB->frequency_id = $this->frequencyId;

        $backTestDB->exchange_id = $this->currencyId;

        $backTestDB->process_id = $processId;

        $backTestDB->start = 1;

        $backTestDB->save();

        $this->backTestId = $backTestDB->id;

        $this->processId = $processId;
    }

    public function startNewPeriod() {
        $this->strategy->startNewPeriod();
    }

    public function endPeriod() {
        //Check to see if a stop loss was hit
        $this->checkStopLoss();
        //Handle Trailing Stop
        $this->checkTrailingStop();
        $this->checkMarketIfTouched();
        //Check to see if take profit was hit
        $this->checkTakeProfit();
    }

    public function checkStopLoss() {

    }
}