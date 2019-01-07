<?php namespace App\Http\Controllers\Equity;


use \DB;
use \Log;
use Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ServersController;
use App\Broker\TDAmeritrade;
use Illuminate\Support\Facades\Config;

use App\Model\Stocks\StocksDump;
use App\Model\Stocks\Stocks;
use App\Model\Stocks\StocksDailyPrice;
use App\Model\Servers;
use App\Services\Csv;

class StocksHistoricalDataController extends Controller {

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
        Log::emergency('Keep Running Start');
        $serverController = new ServersController();
        $lastGitPullTime = $serverController->getLastGitPullTime();
        Config::set('last_git_pull_time', $lastGitPullTime);
        $this->keepRunningStartTime = time();
        Log::emergency('Keep Running Start Time '.$this->keepRunningStartTime);

        while ($this->keepRunningCheck) {
            if ($this->lastPullTime == time()) {
                sleep(2);
            }
            $this->lastPullTime = time();
            $this->getStockData();

            $lastGitPullTime = $serverController->getLastGitPullTime();
            $configLastGitPullTime = Config::get('last_git_pull_time');

            if ($lastGitPullTime != $configLastGitPullTime) {
                $this->keepRunningCheck = false;
            }

            if (($this->keepRunningStartTime + (55*60)) < time()) {
                $this->keepRunningCheck = false;
            }
        }
    }

    public function getStockData() {

        if (!$this->symbol) {
            $this->setStockSymbol();
        }

        //Year Stuff
        $this->getYear();

        $tdAmeritrade = new TDAmeritrade();

        $startDate = strtotime('1/1/'.$this->year)*1000;
        $endDate = strtotime('12/10/'.$this->year)*1000;

        $params = [
            'frequencyType'=>'daily',
            'frequency'=>1,
            'periodType'=>'year',
            'startDate'=>$startDate,
            'endDate'=>$endDate
        ];

        $response = $tdAmeritrade->getStockPriceHistory($this->symbol, $params);

        foreach ($response->candles as $candle) {

            $stockPriceRecord = new StocksDailyPrice();
            $stockPriceRecord->stock_id = $this->stockId;
            $stockPriceRecord->open = $candle->open;
            $stockPriceRecord->high = $candle->high;
            $stockPriceRecord->low = $candle->low;
            $stockPriceRecord->close = $candle->close;
            $stockPriceRecord->volume = $candle->volume;

            $stockPriceRecord->date_time_unix = round($candle->datetime/1000);
            $stockPriceRecord->price_date_time = date('Y-m-d', round($candle->datetime/1000));

            $stockPriceRecord->save();
        }
    }

    public function setStockSymbol() {
        $serverId = Config::get('server_id');
        $server = Servers::find($serverId);

        $maxRateUnixTime = StocksDailyPrice::where('stock_id', '=', $server->stock_id)->max('date_time_unix');

        $serverStockYear = date('Y', $maxRateUnixTime);

        if ($serverStockYear == date("Y")) {

            $updatePullStock = Stocks::find($this->stockId);

            $updatePullStock->last_price_pull = time();

            $updatePullStock->save();

            $this->getNextServerStock();

            $server->stock_id = $this->stockId;
            $server->save();
        }
        else {
            $stock = Stocks::find($server->stock_id);

            $this->symbol = $stock->symbol;
            $this->stockId = $stock->id;
        }
    }

    public function getNextServerStock() {
        $nextStock = Stocks::where('price_populate_year', '=', 0)
            ->where('market_cap', '>', 0)
            ->where('ipo_year', '!=', 'n/a')
            ->first();


        $this->symbol = $nextStock->symbol;
        $this->stockId = $nextStock->id;

        $serverId = Config::get('server_id');
        $server = Servers::find($serverId);
        $server->stock_id = $nextStock->id;
        $server->save();
    }

    public function getYear() {
        $currentStock = Stocks::find($this->stockId);

        if ($currentStock->price_populate_year == 0) {
            $serverId = Config::get('server_id');
            $server = Servers::find($serverId);
            $stock = Stocks::find($server->task_id);
            $this->year = $stock->ipo_year;
        }
        else {
            $nextYear = $currentStock->price_populate_year + 1;

            if ($nextYear > date('Y', time())) {
                $serverId = Config::get('server_id');
                $server = Servers::find($serverId);

                $this->getNextServerStock();

                $server->stock_id = $this->stockId;
                $server->save();

                $stock = Stocks::find($this->stockId);

                $this->year = $stock = $stock->ipo_year;
            }
            else {
                $this->year = $nextYear;
            }
        }

        $stockUpdate = Stocks::find($this->stockId);
        $stockUpdate->price_populate_year = $this->year;
        $stockUpdate->save();
    }
}