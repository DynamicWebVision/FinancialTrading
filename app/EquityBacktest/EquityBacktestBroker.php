<?php namespace App\EquityBacktest;
use \Log;
use App\Model\Stocks\StocksBackTestPositions;
use App\Model\Stocks\Stocks;
use App\Model\Stocks\StocksDailyPrice;
use App\Services\Utility;
use App\Broker\IexTrading;

class EquityBackTestBroker {
    public $stockId;
    public $fiveYearRates;
    public $symbol;

    public $currentRateIndex;
    public $rateCount;

    public $lastRateId = false;
    public $backtestComplete = false;

    public $openPosition = false;

    public $currentRate;
    public $currentId;

    public $rateTakeCount;

    public function __construct($stockId, $indicatorRateMin) {
        $stock = Stocks::find($stockId);
        $this->symbol = $stock->symbol;
        $this->stockId = $stockId;
        $this->rateTakeCount = $indicatorRateMin;

        $this->getFirstAndLastId();
        $this->getInitialRates();
    }

    public function getFirstAndLastId() {
        $this->lastRateId = StocksDailyPrice::where('stock_id', '=', $this->stockId)
                                ->max('id');
    }

    public function getInitialRates() {
        $rates = StocksDailyPrice::where('stock_id', '=', $this->stockId)
                            ->orderBy('date_time_unix', 'desc')
                            ->take($this->rateTakeCount)
                            ->get()
                            ->toArray();

        $lastRate = end($rates);

        $this->currentRate = StocksDailyPrice::where('stock_id', '=', $this->stockId)
                            ->where('id', '>', $lastRate['id'])
                            ->orderBy('date_time_unix')
                            ->first()
                            ->toArray();

        $bothRates = $this->convertToBothRates($rates);

        return $bothRates;
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
        return StocksDailyPrice::where('stock_id', '=', $this->stockId)
            ->where('id', '>', $this->currentRate['id'])
            ->orderBy('date_time_unix')
            ->first()
            ->toArray();
    }

    public function getCurrentRates() {
        $rates = StocksDailyPrice::where('stock_id', '=', $this->stockId)
                ->where('id', '<', $this->currentRate['id'])
            ->orderBy('date_time_unix', 'desc')
            ->take($this->rateTakeCount)
            ->get()
            ->toArray();

        return $this->convertToBothRates($rates);
    }

    public function endPeriodTasks() {
        $this->currentRate = $this->getCurrentRate();
    }
}
