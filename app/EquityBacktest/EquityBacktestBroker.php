<?php namespace App\EquityBacktest;
use \Log;
use App\Model\Stocks\StocksBackTestPositions;
use App\Model\Stocks\Stocks;
use App\Services\Utility;
use App\Broker\IexTrading;

class EquityBackTestBroker {
    public $stockId;
    public $fiveYearRates;
    public $symbol;

    public $currentRateIndex;
    public $rateCount;

    public $lastRate = false;
    public $lastRatesIndex;

    public function __construct($stockId, $indicatorRateMin) {
        $stock = Stocks::find($stockId);
        $this->symbol = $stock->symbol;

        $this->loadAllRates();
        $this->findFirstRateIndex($indicatorRateMin);
    }

    public function loadAllRates() {
        $iexTrading = new IexTrading();
        $this->fiveYearRates = $iexTrading->getFiveYearRates($this->symbol);
        $rates = $this->fiveYearRates;
        end($rates);         // move the internal pointer to the end of the array
        $this->lastRatesIndex = key($array);
    }

    public function findFirstRateIndex($indicatorRateMin) {
        $this->rateCount = $indicatorRateMin;
        $count = 0;
        foreach ($this->fiveYearRates as $index=>$rate) {
            $count = $count + 1;
            if ($count == $indicatorRateMin) {
                $this->currentRateIndex = $index;
            }
        }
    }

    public function getRates() {
        $this->currentRateIndex = $this->currentRateIndex + 1;

        if (($this->currentRateIndex + 1) == $this->lastRatesIndex) {
            $this->lastRatesIndex = true;
        }
        return array_slice($this->fiveYearRates,$this->currentRateIndex - $this->rateCount,$this->rateCount);
    }


}
