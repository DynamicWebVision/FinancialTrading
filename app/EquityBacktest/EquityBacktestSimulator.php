<?php namespace App\EquityBacktest;

use \Log;
use App\Model\Stocks\StocksBackTestPositions;
use App\Model\Stocks\Stocks;
use App\Services\Utility;
use \App\EquityBacktest\EquityBackTestBroker;


class EquityBacktestSimulator {
    public $technicalCheck;
    public $broker;
    public $indicatorMin;
    public $openPosition = false;
    public $orderType;

    public function __construct($stockId, $indicatorMin, $technicalCheck, $iteration_id)
    {
        $this->broker = new \App\EquityBacktest\EquityBackTestBroker($stockId, $indicatorMin, $iteration_id);
        $this->technicalCheck = $technicalCheck;

    }

    public function run() {
        while (!$this->broker->backtestComplete) {
            $rates = $this->broker->getCurrentRates();
            $this->technicalCheck->rates = $rates;

            if ($this->broker->openPosition) {
                $this->technicalCheck->openPositionCheck();

                if ($this->technicalCheck->result) {
                    if ($this->technicalCheck->resultSide == 'close') {
                        $this->broker->closePosition();
                    }
                }
            }
            else {
                $this->technicalCheck->newPositionCheck();
                if ($this->technicalCheck->result) {
                    if ($this->technicalCheck->resultSide == 'long') {
                        $this->broker->newLongPosition();
                    }
                }
            }

            $lastCurrentRate = end($rates['full']);

            echo 'LastCurrentRate: '.json_encode($lastCurrentRate)."<BR>";
            echo 'Current Rate: '.json_encode($this->broker->currentRate)."<BR>";

            echo '--------------------------------------------------------<BR><BR>';

            $this->broker->endPeriodTasks();
        }
    }
}