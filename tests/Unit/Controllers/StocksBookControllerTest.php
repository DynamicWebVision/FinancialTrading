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

        $stocksHistoricalDataTest->updateBookSingleStockBook(1371);
    }

    public function testProcessOneStockSpecificTime() {
        $stocksHistoricalDataTest = new StocksBookController();
        $stocksHistoricalDataTest->logger = new ProcessLogger('eq_book_iex');

        $stocksHistoricalDataTest->stock = Stocks::find(6113);
        $stocksHistoricalDataTest->currentStockDate = '2013-04-21 00:00:00';
        $stockBook = $stocksHistoricalDataTest->getStockBook();

        $test = 1;
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

    public function testGetStockBook() {
        $stocksHistoricalDataTest = new StocksBookController();
        $stocksHistoricalDataTest->logger = new ProcessLogger('stck_book_hist');

        $stocksHistoricalDataTest->stock = Stocks::find(1047);

        $book = $stocksHistoricalDataTest->getStockBook();

        $this->assertEquals('asdfasf', $book);
    }
}