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
use \App\Services\ProcessLogger;

class StocksHistoricalDataController extends Controller {

    public $symbol = false;
    public $stockId = false;
    public $year = false;
    public $keepRunningCheck = true;
    public $lastPullTime = 0;
    public $keepRunningStartTime = 0;
    public $logger;

    protected $tdAmeritrade;

    public function __construct() {
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $serverController = new ServersController();
        $serverController->setServerId();
    }

    public function keepRunning() {
        $serverController = new ServersController();
        $lastGitPullTime = $serverController->getLastGitPullTime();
        $this->logger = new ProcessLogger('stck_historical');

        $this->tdAmeritrade = new TDAmeritrade($this->logger);

        Config::set('last_git_pull_time', $lastGitPullTime);
        $this->keepRunningStartTime = time();

        while ($this->keepRunningCheck) {
            sleep(2);

            $this->lastPullTime = time();
            $this->checkCurrentStock();
            $this->getStockData();
            //$this->checkStockInitialComplete();

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
        $this->getYear();

        $startDate = strtotime('1/1/'.$this->year)*1000;
        $endDate = strtotime('12/10/'.$this->year)*1000;

        $params = [
            'frequencyType'=>'daily',
            'frequency'=>1,
            'periodType'=>'year',
            'startDate'=>$startDate,
            'endDate'=>$endDate
        ];

        $response = $this->tdAmeritrade->getStockPriceHistory($this->symbol, $params);

        $this->saveCandles($response->candles);
    }

    public function saveCandles($candles) {
        foreach ($candles as $candle) {

            try {
                $stockPriceRecord = StocksDailyPrice::firstOrNew([
                    'stock_id'=> $this->stockId,
                    'date_time_unix' => round($candle->datetime/1000)
                ]);

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
            catch (\Exception $e) {
                $errorStock = Stocks::find($this->stockId);
                $errorStock->price_error = 1;
                $errorStock->save();

                $this->logger->logMessage('Stock '.$this->stockId.'-'.$this->symbol.' ran into save price error with message '.$e->getMessage());
            }

        }
    }

    public function checkCurrentStock() {
        if (!$this->stockId) {
            $this->logger->logMessage('Stock Not Set Resetting');
            $stock = Stocks::where('current_daily_load', '=', 1)->first();
            $this->symbol = $stock->symbol;
            $this->stockId = $stock->id;
        }

        $this->logger->logMessage('Running Process for '.$this->symbol.' with stock id '.$this->stockId);

        $maxRateUnixTime = StocksDailyPrice::where('stock_id', '=', $this->stockId)->max('date_time_unix');

        if (is_null($maxRateUnixTime)) {
            $stock = Stocks::find($this->stockId);
            $serverStockYear = $stock->price_populate_year;
        }
        else {
            $serverStockYear = date('Y', $maxRateUnixTime);
        }

        if ($serverStockYear >= date("Y")) {
            $this->logger->logMessage('Stock '.$this->symbol.' max rate year '.$serverStockYear.' is equal to current year, marking complete');
            $this->markStockInitalLoadComplete();
            $this->getNextStock();
        }
    }

    public function markStockInitalLoadComplete() {
        $currentStock = Stocks::find($this->stockId);

        $currentStock->initial_daily_load = 1;
        $currentStock->current_daily_load = 0;
        $currentStock->last_price_pull = time();

        $currentStock->save();
    }

    public function getNextStock() {
        $nextStock = Stocks::where('initial_daily_load', '=', 0)
            ->where('market_cap', '>', 0)
            ->first();

        $this->logger->logMessage('Got Next Stock '.$this->symbol.' with ID '.$this->stockId);

        $this->symbol = $nextStock->symbol;
        $this->stockId = $nextStock->id;

        $nextStock->current_daily_load = 1;
        $nextStock->save();
    }

    public function getYear() {
        $currentStock = Stocks::find($this->stockId);

        if ($currentStock->price_populate_year == 0) {
            if ($currentStock->ipo_year == 'n/a') {
                $this->logger->logMessage('IPO Year is n/a, setting as 2010.');
                $this->year = '2010';
            }
            elseif ((int) trim($currentStock->ipo_year) < 2010) {
                $this->logger->logMessage('IPO Year less than 2010, setting as 2010');
                $this->year = '2010';
            }
            else {
                $this->logger->logMessage('Setting as IP Year');
                $this->year = $currentStock->ipo_year;
            }
        }
        else {
            $this->year = $currentStock->price_populate_year + 1;
        }

        $stockUpdate = Stocks::find($this->stockId);
        $stockUpdate->price_populate_year = $this->year;
        $stockUpdate->save();
    }
}