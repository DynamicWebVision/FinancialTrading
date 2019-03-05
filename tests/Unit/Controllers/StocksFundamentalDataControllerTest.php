<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\CurrencyIndicators;
use App\Model\HistoricalRates;
use App\Model\TmpTestRates;

use App\Http\Controllers\Equity\StockFundamentalDataController;
use App\Http\Controllers\ServersController;
use App\Model\Stocks\Stocks;
use Illuminate\Support\Facades\Config;
use App\Broker\OandaV20;

class StocksFundamentalDataControllerTest extends TestCase
{
    public $transactionController;
    public $oanda;

//    public function testGetSymbolData() {
//        $stock = Stocks::where('symbol', '=', 'NH')->first();
//        $stocksHistoricalDataTest = new StockFundamentalDataController();
//        $stocksHistoricalDataTest->updateFundamentalData($stock);
//    }

    public function testPullOneStock() {

        $stocksHistoricalDataTest = new StockFundamentalDataController();
        $stocksHistoricalDataTest->keepRunning();
    }

//    public function testFixFundamnetalData() {
//        $stocks = Stocks::get();
//
//        foreach ($stocks as $stock) {
//            $symbol = trim($stock->symbol);
//
//            $goodTrimStock = Stocks::find($stock->id);
//            $goodTrimStock->symbol = $symbol;
//            $goodTrimStock->save();
//        }
//    }
}