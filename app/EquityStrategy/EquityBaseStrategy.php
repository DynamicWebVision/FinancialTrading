<?php namespace App\EquityStrategy;

use \Log;
use \App\ForexBackTest\BackTestHelpers;
use \App\Services\TransactionAmountHelpers;
use \App\Model\DecisionInputRatesLogs;
use \App\Model\OandaOpenPositions;
use \App\Model\DecodeFrequency;

abstract class EquityBaseStrategy  {

    public $broker;

    public function __construct($broker) {
        $this->broker = $broker;
    }

    public function newPeriodTasks() {
        $this->broker->setAccountInfo();
    }
}