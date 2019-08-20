<?php

namespace Tests\Unit\Broker;

use Tests\TestCase;

use \App\Model\Exchange;
use \App\Broker\IexTrading;
use \App\Http\Controllers\Equity\StocksBacktestStatsController;
use \App\EquityBacktest\EquityBackTestBroker;
use \App\EquityBacktest\EquityBacktestSimulator;


class StocksBacktestStatsControllerTest extends TestCase
{

    public function testProcessSingleIterationStats() {
        $controller = new \App\Http\Controllers\Equity\StocksBacktestStatsController();
        $controller->analyzeBacktest(1);
    }

    public function testAnalyzeUnprocessBacktests() {
        $controller = new \App\Http\Controllers\Equity\StocksBacktestStatsController();
        $controller->analyzeUnprocessBacktests();
    }
}