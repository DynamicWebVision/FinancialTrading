<?php

namespace Tests\Unit\IndicatorEventTests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\CurrencyIndicators;
use App\IndicatorEvents\StochasticEvents;
use App\Model\HistoricalRates;
use App\Model\TmpTestRates;

class StochasticEventsTest extends TestCase
{

    public function testStochIndicator() {
        $historicalRates = new \App\Model\HistoricalRates();
        $rates = $historicalRates->getRatesSpecificTimeFull(1,3,100,'2018-07-25 9:00:00');

        $stochasticEvents = new StochasticEvents();

        $stoch = $stochasticEvents->stochastics($rates, 14, 1, 3);
        $debug=1;
        $this->assertEquals(94, round(end($stoch['fast']['k'])));
    }

    //Test from
    public function testGetReturnFromOverBoughtPrice() {
        $historicalRates = new \App\Model\HistoricalRates();
        $stochasticEvents = new StochasticEvents();

        $rates = $historicalRates->getRatesSpecificTimeFull(1,4,100,'2018-08-08 1:00:00');

        //Low Check

        $response = $stochasticEvents->getReturnFromOverBoughtPrice($rates, 14, 20);

        $stdRate = new \StdClass();

        $stdRate->highMid = $response['priceTarget'];
        $stdRate->closeMid = $response['priceTarget'];
        $stdRate->lowMid = $response['priceTarget'];
        $stdRate->openMid = $response['priceTarget'];

        $rates[] = $stdRate;

        $stoch = $stochasticEvents->stochastics($rates, 14, 1, 3);

        $endStoch = round(end($stoch['fast']['k']));
        $this->assertEquals($endStoch,79 );

        $rates = $historicalRates->getRatesSpecificTimeFull(1,4,100,'2018-08-03 5:00:00');

        $stochasticEvents = new StochasticEvents();

        //Low Check

        $response = $stochasticEvents->getReturnFromOverBoughtPrice($rates, 14, 20);

        $stdRate = new \StdClass();

        $stdRate->highMid = $response['priceTarget'];
        $stdRate->closeMid = $response['priceTarget'];
        $stdRate->lowMid = $response['priceTarget'];
        $stdRate->openMid = $response['priceTarget'];

        $rates[] = $stdRate;

        $stoch = $stochasticEvents->stochastics($rates, 14, 1, 3);

        $endStoch = round(end($stoch['fast']['k']));
        $this->assertEquals($endStoch, 21 );

    }
}
