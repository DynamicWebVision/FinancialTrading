<?php

namespace Tests\Unit\Services;

use Tests\TestCase;

use \App\Model\Exchange;
use \App\Services\BackTest;
use \App\Broker\Tiingo;

class TiingoTest extends TestCase
{

    public function testTiingo() {
        $tiingo = new Tiingo(12);
        $tiingo->testApi();
    }
    public function testHistoricalPrices() {
        $tiingo = new Tiingo(12);

        $tiingo->symbol = 'XOM';

        $tiingo->historicalPrices();
    }

}
