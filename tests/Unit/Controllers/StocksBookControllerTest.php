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
use App\Model\Stocks\Stocks;
use Illuminate\Support\Facades\Config;
use App\Broker\OandaV20;

class StocksBookControllerTest extends TestCase
{
    public $transactionController;
    public $oanda;

    public function testPullOneStock() {
        $stock = Stocks::find(528);

        $stocksHistoricalDataTest = new StocksBookController();
        $stocksHistoricalDataTest->pullOneStock($stock);
    }

    public function testKeepRunning() {
        $stocksHistoricalDataTest = new StocksBookController();
        $stocksHistoricalDataTest->keepRunning();
    }
}