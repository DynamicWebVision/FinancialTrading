<?php namespace App\EquityBacktest;

use \Log;
use App\Model\Stocks\StocksBackTestPositions;
use App\Model\Stocks\Stocks;
use App\Model\Stocks\StocksDailyPrice;
use App\Services\Utility;
use App\Broker\IexTrading;
use \DB;

class EquityBackTestBroker {
    public $stockId;
    public $fiveYearRates;
    public $symbol;

    public $currentRateIndex;
    public $rateCount;

    public $lastRateId = false;
    public $backtestComplete = false;

    public $openPosition = false;

    public $currentRate = [];
    public $currentId;

    public $rateTakeCount;

    public $accountValue = 10000;

    public $iteration_id;

    public function __construct($stockId, $indicatorRateMin, $iteration_id) {
        $stock = Stocks::find($stockId);
        $this->symbol = $stock->symbol;
        $this->stockId = $stockId;
        $this->rateTakeCount = $indicatorRateMin;
        $this->iteration_id = $iteration_id;

        $this->getFirstAndLastId();
        $this->getInitialRate();
    }

    public function getFirstAndLastId() {
        $this->lastRateId = StocksDailyPrice::where('stock_id', '=', $this->stockId)
                                ->max('id');
    }

    public function getInitialRate() {

        $rate_count = $this->rateTakeCount + 5;

        $firstRate = DB::select( DB::raw("SELECT * FROM stocks_daily_prices WHERE stock_id = :stock_id ORDER BY date_time_unix LIMIT :rate_min, 1"),
            ['stock_id'=>$this->stockId, 'rate_min'=>$rate_count]
        );

        $this->currentRate['id'] = $firstRate[0]->id;
    }

    public function convertToBothRates($rates) {
        $fullRates = array_map(function($rate) {

            $stdRate = new \StdClass();

            $stdRate->highMid = (float) $rate['high'];
            $stdRate->closeMid = (float) $rate['close'];
            $stdRate->lowMid = (float) $rate['low'];
            $stdRate->openMid = (float) $rate['open'];
            $stdRate->volume = (float) $rate['volume'];

            return $stdRate;
        }, $rates);

        $rates = array_map(function($rate) {
            return (float) $rate['close'];
        }, $rates);

        return ['full'=> array_reverse($fullRates), 'simple' => array_reverse($rates)];
    }

    public function getCurrentRate() {
        if ($this->currentRate['id'] >= $this->lastRateId) {
            $this->backtestComplete = true;
            return null;
        }
        else {
            return StocksDailyPrice::where('stock_id', '=', $this->stockId)
                ->where('id', '>', $this->currentRate['id'])
                ->orderBy('date_time_unix')
                ->first()
                ->toArray();
        }
    }

    public function getCurrentRates() {
        try {
            $rates = StocksDailyPrice::where('stock_id', '=', $this->stockId)
                ->where('id', '<', $this->currentRate['id'])
                ->orderBy('date_time_unix', 'desc')
                ->take($this->rateTakeCount)
                ->get()
                ->toArray();
        }
        catch (\Exception $e) {
            $debug=1;
        }

        return $this->convertToBothRates($rates);
    }

    public function endPeriodTasks() {
        $this->currentRate = $this->getCurrentRate();

        if (is_null($this->currentRate)) {
            $debug=1;
        }
    }

    public function newLongPosition() {
        $newBackTestPosition = new StocksBackTestPositions();

        $newBackTestPosition->stocks_back_test_iteration_id = $this->iteration_id;

        $newBackTestPosition->position_type = 1;

        $newBackTestPosition->stock_id = $this->stockId;

        //CHANGE LATER!!!
        $newBackTestPosition->shares_count = 100;

        $newBackTestPosition->open_date = $this->currentRate['price_date_time'];

        $newBackTestPosition->open_date_date = date("Y-m-d H:i:s", $this->currentRate['date_time_unix']);

        $newBackTestPosition->open_price = $this->currentRate['open'];

        $newBackTestPosition->save();

        $this->openPosition = $newBackTestPosition;
    }

    public function closePosition() {
        $this->openPosition->close_date = $this->currentRate['price_date_time'];

        $this->openPosition->close_date_date = date("Y-m-d H:i:s", $this->currentRate['date_time_unix']);

        $this->openPosition->close_price = $this->currentRate['open'];

        $this->openPosition->gain_loss = $this->getGainLoss();

        $this->openPosition->save();

        $this->openPosition = false;
    }

    protected function getGainLoss() {
        if ($this->openPosition->position_type == 1) {
            $open_close_diff = $this->openPosition->close_price - $this->openPosition->open_price;
            return round($open_close_diff/$this->openPosition->open_price, 5);
        }
        else {
            $open_close_diff = $this->openPosition->open_price - $this->openPosition->close_price;
            round($open_close_diff/$this->openPosition->close_price, 5);
        }
    }

}