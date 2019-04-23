<?php namespace App\EquityTechnicalCheck;

use \Log;
use App\Broker\IexTrading;
use App\Model\Stocks;


abstract class EquityTechnicalCheckBase  {

    public $logger;
    public $stockId;
    public $broker;
    public $rates = [];

    public function __construct($stockId, $processLogger) {
        $this->logger = $processLogger;
        $this->stockId = $stockId;

        $this->broker = new IexTrading();

        $this->getRates();
    }

    public function getStockInfo() {
        $stock = Stocks::first($this->stockId);

    }

    public function getBothRates() {
        $this->rates = $this->broker->getBothRates();
    }
}