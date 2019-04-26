<?php namespace App\EquityTechnicalCheck;

use \Log;
use App\Broker\IexTrading;
use App\Model\Stocks\Stocks;
use App\Model\Stocks\StocksTechnicalCheckResult;


abstract class EquityTechnicalCheckBase  {

    public $logger;
    public $stockId;
    public $symbol;
    public $broker;
    public $rates = [];

    public $result;

    public function __construct($stockId, $processLogger) {
        $this->logger = $processLogger;
        $this->stockId = $stockId;

        $this->broker = new IexTrading();
        $this->getStockInfo();
        $this->getBothRates();
    }

    public function getStockInfo() {
        $stock = Stocks::find($this->stockId);
        $this->symbol = $stock->symbol;
    }

    public function getBothRates() {
        $this->rates = $this->broker->getBothRates($this->symbol);
    }

    public function storeResult() {
        $technicalCheckResult = new StocksTechnicalCheckResult();
        $technicalCheckResult->stock_id = $this->stockId;

        if ($this->result == 'long') {
            $technicalCheckResult->result_id = 1;
        }
        elseif ($this->result == 'short') {
            $technicalCheckResult->result_id = -1;
        }
        else {
            $technicalCheckResult->result_id = 0;
        }
        $technicalCheckResult->save();
    }
}