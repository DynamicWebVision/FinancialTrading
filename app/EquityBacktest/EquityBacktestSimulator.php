<?php namespace App\EquityBacktest;

use \Log;
use App\Model\Stocks\StocksBackTestPositions;
use App\Model\Stocks\Stocks;
use App\Services\Utility;
use App\EquityBacktest\EquityBackTestBroker;


class EquityBacktestSimulator {
    public $technicalCheck;
    public $broker;
    public $indicatorMin;
    public $openPosition = false;
    public $daysToHold = 4;
    
    public $orderType;

    public function __construct($stockId, $indicatorMin, $technicalCheck)
    {
        $this->broker = new EquityBackTestBroker($stockId, $indicatorMin);
        $this->technicalCheck = $technicalCheck;
    }

    public function run() {
        while (!$this->broker->backtestComplete) {
            $rates = $this->broker->getRates();

            if ($this->broker->openPosition) {

            }
            else {

            }

            $this->broker->endPeriodTasks();
        }
    }
}
