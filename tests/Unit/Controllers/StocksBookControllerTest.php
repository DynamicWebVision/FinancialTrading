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
use App\Services\ProcessLogger;

class StocksBookControllerTest extends TestCase
{
    public $transactionController;
    public $oanda;

//    public function testPullOneStock() {
//        $stock = Stocks::find(528);
//
//        $stocksHistoricalDataTest = new StocksBookController();
//        $stocksHistoricalDataTest->pullOneStock($stock);
//    }

    public function testKeepRunning() {
        $stocksHistoricalDataTest = new StocksBookController();
        $stocksHistoricalDataTest->keepRunning();
    }

    public function testProcessOneStock() {
        $stocksHistoricalDataTest = new StocksBookController();
        $stocksHistoricalDataTest->logger = new ProcessLogger('eq_book_iex');

        $stocksHistoricalDataTest->stock = Stocks::find(4740);
        $stocksHistoricalDataTest->updateBook();
    }

    public function testCreateHistoricalStockBooks() {
        $stocksHistoricalDataTest = new StocksBookController();
        $stocksHistoricalDataTest->logger = new ProcessLogger('eq_book_iex');

        $stocksHistoricalDataTest->createHistoricalStockBooks(5050);
    }

    public function testCreateQueeu() {
        $stocksHistoricalDataTest = new StocksBookController();
        $stocksHistoricalDataTest->logger = new ProcessLogger('stck_book_hist');

        $stocksHistoricalDataTest->createHistoricalStockBookProcesses(5050);
    }
}