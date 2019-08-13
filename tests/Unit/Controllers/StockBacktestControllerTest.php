<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\CurrencyIndicators;
use App\Model\HistoricalRates;
use App\Model\TmpTestRates;
use App\Http\Controllers\Equity\StockBacktestController;
use App\Http\Controllers\ServersController;
use App\Model\Stocks\Stocks;
use Illuminate\Support\Facades\Config;
use App\Broker\OandaV20;
use App\Services\ProcessLogger;

class StockBacktestControllerTest extends TestCase
{
    public $transactionController;
    public $oanda;

    public function testRunOne() {
        $stocksHistoricalDataTest = new StockBacktestController();
        $stocksHistoricalDataTest->processStockBacktest(1);
    }
}