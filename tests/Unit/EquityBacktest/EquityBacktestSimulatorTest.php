<?php

namespace Tests\Unit\Broker;

use Tests\TestCase;

use \App\Model\Exchange;
use \App\Broker\IexTrading;
use \App\Http\Controllers\Equity\StocksCompanyProfileController;
use \App\EquityBacktest\EquityBackTestBroker;
use \App\EquityBacktest\EquityBacktestSimulator;


class EquityBacktestSimulatorTest extends TestCase
{

//    public function testGetCompanyProfile()
//    {
//        $equityBacktest = new EquityBacktestSimulator(4542, 20, false);
//        $equityBacktest->run();
//    }

    public function testProcessQueue() {
        $controller = new \App\Http\Controllers\Equity\StockBacktestController();
        $controller->processStockBacktest(4);
    }
}