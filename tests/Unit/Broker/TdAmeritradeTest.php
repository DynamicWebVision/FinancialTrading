<?php

namespace Tests\Unit\Broker;

use Tests\TestCase;

use \App\Model\Exchange;
use \App\Broker\TDAmeritrade;

class TdAmeritradeTest extends TestCase
{

    public function testGetToken()
    {
        $tdAmeritrade = new TDAmeritrade();
        $tdAmeritrade->getAuthorizationToken();
    }

    public function testHistoricalRates()
    {
        $tdAmeritrade = new TDAmeritrade();

        $symbol = 'XOM';
        $params = [
            'periodType'=>'day',
            'period'=> '2'
        ];

        $tdAmeritrade->getStockPriceHistory($symbol, $params);
    }

    public function testFundamentalData()
    {
        $tdAmeritrade = new TDAmeritrade();

        $symbol = 'XOM';

        $tdAmeritrade->getStockFundamentalData($symbol);
    }

    public function testGetNewAuthToken()
    {
        $tdAmeritrade = new TDAmeritrade();
        $tdAmeritrade->refreshAuthorizationToken();
    }

    public function testValidateAccessToken()
    {
        $tdAmeritrade = new TDAmeritrade();
        $tdAmeritrade->validateAccessToken();
    }
}