<?php

namespace Tests\Unit\IndicatorEventTests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\CurrencyIndicators;
use App\Model\HistoricalRates;
use App\Model\TmpTestRates;
use \App\IndicatorEvents\EmaEvents;

class EmaEventsTest extends TestCase
{

    public function testCalculateEmaNextPeriodCrossoverRate() {
        $historicalRates = new \App\Model\HistoricalRates();
        $rates = $historicalRates->getRatesSpecificTimeSimple(2,3,1000,'2018-08-09 13:00');

        $emaEvents = new EmaEvents();

        $rate = $emaEvents->calculateEmaNextPeriodCrossoverRate($rates, 50, 100);
    }
    public function testPriceAboveBelowEma() {
        $historicalRates = new \App\Model\HistoricalRates();
        $rates = $historicalRates->getRatesSpecificTimeSimple(1,3,1000,'2018-12-21 10:00');

        $emaEvents = new EmaEvents();

        $pricePosition = $emaEvents->priceAboveBelowEma($rates, 9);

        $this->assertEquals('below', $pricePosition);


        $historicalRates = new \App\Model\HistoricalRates();
        $rates = $historicalRates->getRatesSpecificTimeSimple(1,3,1000,'2018-12-20 9:00');

        $emaEvents = new EmaEvents();

        $pricePosition = $emaEvents->priceAboveBelowEma($rates, 9);

        $this->assertEquals('above', $pricePosition);


        $historicalRates = new \App\Model\HistoricalRates();
        $rates = $historicalRates->getRatesSpecificTimeSimple(1,3,1000,'2018-12-10 1:00');

        $emaEvents = new EmaEvents();

        $pricePosition = $emaEvents->priceAboveBelowEma($rates, 9);

        $this->assertEquals('above', $pricePosition);


        $historicalRates = new \App\Model\HistoricalRates();
        $rates = $historicalRates->getRatesSpecificTimeSimple(1,3,1000,'2018-12-14 9:00');

        $emaEvents = new EmaEvents();

        $pricePosition = $emaEvents->priceAboveBelowEma($rates, 9);

        $this->assertEquals('below', $pricePosition);
    }

    public function testEmaValue() {
        $historicalRates = new \App\Model\HistoricalRates();
        $rates = $historicalRates->getRatesSpecificTimeSimple(1,3,1000,'2018-12-10 19:00');

        $emaEvents = new EmaEvents();

        $ema = $emaEvents->ema($rates, 9);

        $this->assertEquals(1.13784, round(end($ema), 5));
    }

    public function testPriceCrossedOver() {
        $historicalRates = new \App\Model\HistoricalRates();
        $rates = $historicalRates->getRatesSpecificTimeSimple(1,3,1000,'2018-12-21 8:00');

        $emaEvents = new EmaEvents();

        $cross = $emaEvents->priceCrossedOver($rates, 5);

        $this->assertEquals($cross, 'crossedBelow');
        $historicalRates = new \App\Model\HistoricalRates();
        $rates = $historicalRates->getRatesSpecificTimeSimple(1,3,1000,'2018-12-24 8:00');

        $emaEvents = new EmaEvents();

        $cross = $emaEvents->priceCrossedOver($rates, 5);

        $this->assertEquals($cross, 'crossedAbove');
    }

    public function testLineCrossover() {
        $historicalRates = new \App\Model\HistoricalRates();
        $rates = $historicalRates->getRatesSpecificTimeSimple(1,5,1000,'2018-9-28 0:00');

        $emaEvents = new EmaEvents();

        $cross = $emaEvents->checkTwoLineCrossover($rates, 5, 10);

        $this->assertEquals($cross, 'crossedBelow');

        $historicalRates = new \App\Model\HistoricalRates();
        $rates = $historicalRates->getRatesSpecificTimeSimple(1,5,1000,'2018-8-21 0:00');

        $emaEvents = new EmaEvents();

        $cross = $emaEvents->checkTwoLineCrossover($rates, 5, 10);

        $this->assertEquals($cross, 'crossedAbove');
    }

    public function testFastAboveSlow() {
        $historicalRates = new \App\Model\HistoricalRates();
        $rates = $historicalRates->getRatesSpecificTimeSimple(1,5,1000,'2019-3-20 0:00');

        $emaEvents = new EmaEvents();

        $test = $emaEvents->fastAboveSlowCheck($rates, 5, 10);

        $this->assertTrue($test);

        $historicalRates = new \App\Model\HistoricalRates();
        $rates = $historicalRates->getRatesSpecificTimeSimple(1,5,1000,'2018-10-25 0:00');

        $emaEvents = new EmaEvents();

        $test = $emaEvents->fastAboveSlowCheck($rates, 5, 10);

        $this->assertFalse($test);
    }
}