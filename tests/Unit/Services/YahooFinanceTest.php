<?php

namespace Tests\Unit\Services;

use Tests\TestCase;

use \App\Model\Exchange;
use \App\Services\BackTest;
use \App\Services\YahooFinance;
use \App\Http\Controllers\Equity\YahooFinanceController;

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

}
