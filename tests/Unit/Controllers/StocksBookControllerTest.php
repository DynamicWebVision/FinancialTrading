<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\CurrencyIndicators;
use App\Model\HistoricalRates;
use App\Model\TmpTestRates;
use App\Http\Controllers\Equity\StocksBookController;
use App\Http\Controllers\ServersController;
use App\Model\Servers;
use Illuminate\Support\Facades\Config;
use App\Broker\OandaV20;

class StocksBookControllerTest extends TestCase
{
    public $transactionController;
    public $oanda;

//    public function testPullOneStock() {
//        $stocksHistoricalDataTest = new StocksBookController();
//        $stocksHistoricalDataTest->pullOneStock();
//    }

    public function testKeepRunning() {
        $stocksHistoricalDataTest = new StocksBookController();
        $stocksHistoricalDataTest->keepRunning();
    }
}