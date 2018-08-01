<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Services\Utility;
use App\StrategyEvents\Momentum;

class OandaTest extends TestCase
{
    public function testConvertOandaDate()
    {
        $oanda = new \App\Broker\OandaV20();

        $expectedValue = '2018-05-25T17:54:56.000000Z';

        $responseDate = $oanda->convertOandaDate(1527270896);

        $this->assertEquals($responseDate, $expectedValue);
    }

    public function testSetPrice()
    {
        $oanda = new \App\Broker\OandaV20();

        $expectedValue = '1.2342';

        $currentPrice = '1.234183434';

        $exchange = \App\Model\Exchange::find(1);

        $oanda->exchange = $exchange;

        $responsePrice = $oanda->getOandaPrecisionPrice($currentPrice);

        $this->assertEquals($responsePrice, $expectedValue);
    }


}
