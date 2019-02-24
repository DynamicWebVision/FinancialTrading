<?php

namespace Tests\Unit\Broker;

use Tests\TestCase;

use \App\Model\Exchange;
use \App\Broker\IexTrading;
use \App\Http\Controllers\Equity\StocksCompanyProfileController;
use \App\Http\Controllers\Equity\StocksFinancialsController;

class IexTradingTest extends TestCase
{

    public function testGetCompanyProfile()
    {
        $iexTrading = new IexTrading();
        $iexTrading->getCompanyProfile('XOM');
    }

    public function testGetBook()
    {
        $iexTrading = new IexTrading();
        $iexTrading->getBook('XOM');
    }

    public function testGetCompanyProfileGetOne()
    {
        $iexTrading = new StocksCompanyProfileController();
        $iexTrading->pullOneStock();
    }

    public function testFinancialsGetOne()
    {
        $iexTrading = new StocksFinancialsController();
        $iexTrading->pullOneStockAnnual();
    }
}