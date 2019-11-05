<?php

namespace Tests\Unit\Services;

use Tests\TestCase;

use \App\Model\Exchange;
use \App\Model\Stocks\Stocks;
use \App\Services\BackTest;
use \App\Services\YahooFinance;
use \App\Http\Controllers\Equity\YahooFinanceController;
use \App\Http\Controllers\ProcessScheduleController;

class YahooFinanceTest extends TestCase
{

//    public function testHistoricalRates() {
//        $textMessage = new YahooFinance();
//        $textMessage->getHistoricalRates();
//    }
//
//    public function testHistoricalRatesWithDates() {
//        $textMessage = new YahooFinance();
//        $textMessage->symbol = 'XOM';
//        $textMessage->getHistoricalRates(['start_date'=>'2016-01-01','end_date'=>'2017-01-01',]);
//    }

    public function testProcessOneStock() {
        $textMessage = new YahooFinanceController();

        $textMessage->checkPricesOneStock(4542);
    }

    public function testCreateCheckPriceRecords() {
        $stocks = Stocks::where('initial_daily_load','=', 1)->get()->toArray();

        $ids = array_column($stocks, 'id');

        $scheduler = new ProcessScheduleController();

        $scheduler->createQueueRecordsWithVariableIds('yahoo_price', $ids);
    }

}
