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

    public function testGetNextRates()
    {
        $equityBacktest = new EquityBackTestBroker(4542, 50);

        $equityBacktest->getRates();
        $equityBacktest->getRates();
        $equityBacktest->getRates();


    }

    public function testABc()
    {
        $file_contets = file_get_contents('http://52.3.236.190/laravel-2019-05-16.log');

        $fp = fopen('/Users/boneill/Documents/laravel516.log', 'w');
        fwrite($fp, $file_contets);
        fclose($fp);

        $debug = 1;
    }
}