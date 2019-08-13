<?php namespace App\EquityTechnicalCheck;

use \Log;
use App\Broker\IexTrading;
use App\Model\Stocks\Stocks;
use App\Model\Stocks\StocksTechnicalCheckResult;
use App\Services\ProcessLogger;


abstract class EquityTechnicalCheckBase  {

    public $logger;
    public $stockId;
    public $symbol;
    public $broker;
    public $rates = [];
    public $decisionIndicators;


    public $backTesting;

    public $result;
    public $resultSide;

    public function __construct($stockId) {
        $this->logger = new ProcessLogger('stc_hma_rev');
        $this->stockId = $stockId;

        $this->broker = new IexTrading();
        $this->getStockInfo();
        $this->getBothRates();
    }

    public function getStockInfo() {
        $stock = Stocks::find($this->stockId);

        $this->logger->logMessage('Starting '.get_class($this).' check for stock '.$stock->symbol.' - '.$stock->name.'.');

        $this->symbol = $stock->symbol;
    }

    public function getBothRates() {
        //$this->rates = $this->broker->getBothRates($this->symbol);
    }

    public function storeResult() {
        if (!$this->backTesting) {
            $technicalCheckResult = new StocksTechnicalCheckResult();
            $technicalCheckResult->stock_id = $this->stockId;

            if ($this->result == 'long') {
                $this->logger->logMessage('Result of Long');
                $technicalCheckResult->result_id = 1;
            }
            elseif ($this->result == 'short') {
                $this->logger->logMessage('Result of Short');
                $technicalCheckResult->result_id = -1;
            }
            else {
                $technicalCheckResult->result_id = 0;
            }
            $technicalCheckResult->save();
        }
    }
}