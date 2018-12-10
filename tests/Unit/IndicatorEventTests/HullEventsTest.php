<?php

namespace Tests\Unit\IndicatorEventTests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\CurrencyIndicators;
use App\IndicatorEvents\HullMovingAverage;
use App\Model\HistoricalRates;
use App\Model\TmpTestRates;

class HullEventsTest extends TestCase
{

    public function testHullChangeDirectionCheck() {
        $historicalRates = new \App\Model\HistoricalRates();
        //$rates = $historicalRates->getRatesSpecificTimeSimpleInPips(1,3,1000,'2018-10-09 3:00:00');
        $rates = $historicalRates->getRatesSpecificTimeSimpleInPips(1,3,1000,'2018-11-28 17:00:00');

        $hullMovingAverage = new HullMovingAverage();

        $changeDirection = $hullMovingAverage->hullChangeDirectionCheck($rates, 9);

        $this->assertEquals($changeDirection, 'reversedUp');
        //$rates = $historicalRates->getRatesSpecificTimeSimpleInPips(1,3,1000,'2018-10-09 3:00:00');
        $rates = $historicalRates->getRatesSpecificTimeSimpleInPips(1,3,1000,'2018-11-28 22:00:00');

        $hullMovingAverage = new HullMovingAverage();

        $changeDirection = $hullMovingAverage->hullChangeDirectionCheck($rates, 9);

        $this->assertEquals($changeDirection, 'reversedDown');
    }

    public function testHmaSlope() {
        $historicalRates = new \App\Model\HistoricalRates();
        //$rates = $historicalRates->getRatesSpecificTimeSimpleInPips(1,3,1000,'2018-10-09 3:00:00');
        $rates = $historicalRates->getRatesSpecificTimeSimpleInPips(1,3,1000,'2018-11-29 12:00:00');

        $hullMovingAverage = new HullMovingAverage();

        $minSlope = $hullMovingAverage->hmaSlope($rates, 200, .0001, 1);

        $this->assertEquals($minSlope, 'long');

        //$rates = $historicalRates->getRatesSpecificTimeSimpleInPips(1,3,1000,'2018-10-09 3:00:00');
        $rates = $historicalRates->getRatesSpecificTimeSimpleInPips(1,3,1000,'2018-11-27 9:00:00');

        $hullMovingAverage = new HullMovingAverage();

        $minSlope = $hullMovingAverage->hmaSlope($rates, 200, .0001, 1);

        $this->assertEquals($minSlope, 'short');
    }
}
