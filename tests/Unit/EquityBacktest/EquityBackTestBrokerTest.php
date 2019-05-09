<?php

namespace Tests\Unit\Broker;

use Tests\TestCase;

use \App\Model\Exchange;
use \App\Broker\IexTrading;
use \App\Http\Controllers\Equity\StocksCompanyProfileController;
use \App\EquityBacktest\EquityBackTestBroker;

class EquityBackTestBrokerTest extends TestCase
{

    public function testGetCompanyProfile()
    {
        $equityBacktest = new EquityBackTestBroker(4542);
        $equityBacktest->loadAllRates('XOM');
    }
}