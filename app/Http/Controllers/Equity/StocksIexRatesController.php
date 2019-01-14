<?php namespace App\Http\Controllers\Equity;


use \DB;
use \Log;
use Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ServersController;
use App\Broker\TDAmeritrade;
use Illuminate\Support\Facades\Config;

use App\Broker\IexTrading;
use App\Model\Stocks\Stocks;
use App\Model\Stocks\StockIexDailyRates;

class StocksIexRatesController extends Controller {

    public $symbol = false;
    public $stockId = false;
    public $year = false;
    public $keepRunningCheck = true;
    public $lastPullTime = 0;
    public $keepRunningStartTime = 0;

    public function __construct() {
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $serverController = new ServersController();

        $serverController->setServerId();
    }

    public function keepRunning() {
        Log::emergency('Keep Running Stock Financials Start');
        $serverController = new ServersController();
        $lastGitPullTime = $serverController->getLastGitPullTime();
        Config::set('last_git_pull_time', $lastGitPullTime);
        $this->keepRunningStartTime = time();
        Log::emergency('Keep Running Start Time '.$this->keepRunningStartTime);

        while ($this->keepRunningCheck) {
            $this->lastPullTime = time();
            $this->pullOneStock();

            $lastGitPullTime = $serverController->getLastGitPullTime();
            $configLastGitPullTime = Config::get('last_git_pull_time');

            if ($lastGitPullTime != $configLastGitPullTime) {
                $this->keepRunningCheck = false;
            }

            if (($this->keepRunningStartTime + (45*60)) < time()) {
                $this->keepRunningCheck = false;
            }
        }
        Log::emergency('Keep Running Stock Financials End');
    }

    public function pullOneStock() {
        $stock = Stocks::orderBy('last_iex_rates_pull')->first();
        $this->updateFiverYearRates($stock);
        $stock->last_iex_rates_pull = time();
        $stock->save();
    }

    public function updateFiverYearRates($stock) {
        $ieTrading = new IexTrading();

        $response = $ieTrading->getFiveYearRates($stock->symbol);

        $this->saveRates($response, $stock->stock_id);
    }

    public function saveRates($rates, $stockId) {
        foreach ($rates as $rate) {
            $newIexDailyRate = StockIexDailyRates::firstOrNew(['stock_id'=> $stockId, 'date'=> $rate->date]);

            if (isset($rate->date)) {
                $newIexDailyRate->date = $rate->date;
            }

            if (isset($rate->open)) {
                $newIexDailyRate->open = $rate->open;
            }

            if (isset($rate->high)) {
                $newIexDailyRate->high = $rate->high;
            }

            if (isset($rate->low)) {
                $newIexDailyRate->low = $rate->low;
            }

            if (isset($rate->close)) {
                $newIexDailyRate->close = $rate->close;
            }

            if (isset($rate->volume)) {
                $newIexDailyRate->volume = $rate->volume;
            }

            if (isset($rate->unadjustedVolume)) {
                $newIexDailyRate->unadjusted_volume = $rate->unadjustedVolume;
            }

            if (isset($rate->change)) {
                $newIexDailyRate->change_price = round($rate->change, 2);
            }

            if (isset($rate->changePercent)) {
                $newIexDailyRate->change_percent = round($rate->changePercent, 2);
            }

            if (isset($rate->vwap)) {
                $newIexDailyRate->vwap = round($rate->vwap, 2);
            }

            $newIexDailyRate->save();
        }
    }
}