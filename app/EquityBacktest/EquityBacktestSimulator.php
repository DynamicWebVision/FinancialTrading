<?php namespace App\EquityBacktest;

use \Log;
use App\Model\Stocks\StocksBackTestPositions;
use App\Model\Stocks\Stocks;
use App\Services\Utility;
use App\EquityBacktest\EquityBackTestBroker;


class EquityBackTestBase {
    public $equityCheck;
    public $broker;
    public $indicatorMin;
    public $openPosition = false;
    public $daysToHold = 4;

    public function __construct($stockId, $indicatorMin)
    {
        $this->broker = new EquityBackTestBroker($stockId, $indicatorMin);
    }

    public function newPositionCheck() {
        if ($this->equityCheck->result == 'long') {

        }
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
