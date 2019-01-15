<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\CurrencyIndicators;
use App\Model\HistoricalRates;
use App\Model\TmpTestRates;
use App\Http\Controllers\Equity\StocksIexRatesController;
use App\Http\Controllers\ServersController;
use App\Model\Servers;
use Illuminate\Support\Facades\Config;
use App\Broker\OandaV20;

class StocksIexRatesControllerTest extends TestCase
{
    public $transactionController;
    public $oanda;

//    public function testGetSymbolData() {
//        $stocksHistoricalDataTest = new StocksHistoricalDataController();
//        $stocksHistoricalDataTest->getStockData();
//    }

    public function testOneRateStock()
{
    $stocksIexRatesController = new StocksIexRatesController();
    $stocksIexRatesController->pullOneStock();
}

    public function testKeepRunning()
    {
        $stocksIexRatesController = new StocksIexRatesController();
        $stocksIexRatesController->keepRunning();
    }
}
