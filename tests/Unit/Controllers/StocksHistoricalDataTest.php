<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\CurrencyIndicators;
use App\Model\HistoricalRates;
use App\Model\TmpTestRates;
use App\Http\Controllers\Equity\StocksHistoricalDataController;
use App\Http\Controllers\ServersController;
use App\Broker\OandaV20;

class StocksHistoricalDataTest extends TestCase
{
    public $transactionController;
    public $oanda;

//    public function testGetSymbolData() {
//        $stocksHistoricalDataTest = new StocksHistoricalDataController();
//        $stocksHistoricalDataTest->getStockData();
//    }

    public function testKeepRunning() {
        $serverController = new ServersController();
        $serverController->setServerId();

        $server = Servers::find(Config::get('server_id'));

        $stocksHistoricalDataTest = new StocksHistoricalDataController();
        $stocksHistoricalDataTest->keepRunning();
    }
}
