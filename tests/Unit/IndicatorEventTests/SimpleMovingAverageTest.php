<?php

namespace Tests\Unit\IndicatorEventTests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\CurrencyIndicators;
use App\IndicatorEvents\SimpleMovingAverage;
use App\Model\HistoricalRates;
use App\Model\TmpTestRates;

class SimpleMovingAverageTest extends TestCase
{

    public function testPriceTarget() {
        $simpleMovingAverage = new SimpleMovingAverage();

        $array = [1, 4, 6, 7, 9, 12, 14, 17, 22, 4, 7];

        $test = $simpleMovingAverage->getCrossPriceTarget($array, 5);

        $de=1;
    }

    public function testPriceCrossover() {
        $simpleMovingAverage = new SimpleMovingAverage();

        $historicalRates = new \App\Model\HistoricalRates();
        $rates = $historicalRates->getRatesSpecificTimeSimple(1,3,1000,'2019-2-28 13:00');

        $expectedCrossedBelow = $simpleMovingAverage->priceCrossedOver($rates, 5);

        $this->assertEquals('crossedBelow', $expectedCrossedBelow);

        $historicalRates = new \App\Model\HistoricalRates();
        $rates = $historicalRates->getRatesSpecificTimeSimple(1,3,1000,'2019-2-28 7:00');

        $expectedCrossedAbove = $simpleMovingAverage->priceCrossedOver($rates, 5);

        $this->assertEquals('crossedAbove', $expectedCrossedAbove);

        $historicalRates = new \App\Model\HistoricalRates();
        $rates = $historicalRates->getRatesSpecificTimeSimple(1,3,1000,'2019-2-28 2:00');

        $expectedNone = $simpleMovingAverage->priceCrossedOver($rates, 5);

        $this->assertEquals('none', $expectedNone);
    }

    public function testPriceCrossoverAfterXPeriods() {
        $simpleMovingAverage = new SimpleMovingAverage();

        $historicalRates = new \App\Model\HistoricalRates();
        $rates = $historicalRates->getRatesSpecificTimeSimple(1,3,1000,'2019-4-26 13:00');

        $expectedCrossedBelow = $simpleMovingAverage->priceCrossedOverFirstTimeInXPeriods($rates, 50, 5);

        $this->assertEquals('crossedAbove', $expectedCrossedBelow);

        $simpleMovingAverage = new SimpleMovingAverage();

        $historicalRates = new \App\Model\HistoricalRates();
        $rates = $historicalRates->getRatesSpecificTimeSimple(1,3,1000,'2019-4-23 12:00');

        $expectedCrossedBelow = $simpleMovingAverage->priceCrossedOverFirstTimeInXPeriods($rates, 50, 5);

        $this->assertEquals('none', $expectedCrossedBelow);

        $simpleMovingAverage = new SimpleMovingAverage();

        $historicalRates = new \App\Model\HistoricalRates();
        $rates = $historicalRates->getRatesSpecificTimeSimple(1,3,1000,'2019-4-23 00:00');

        $expectedCrossedBelow = $simpleMovingAverage->priceCrossedOverFirstTimeInXPeriods($rates, 50, 5);

        $this->assertEquals('crossedBelow', $expectedCrossedBelow);
    }
}
