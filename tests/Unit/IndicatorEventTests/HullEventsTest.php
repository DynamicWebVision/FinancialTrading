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

        //$rates = $historicalRates->getRatesSpecificTimeSimpleInPips(1,3,1000,'2018-10-09 3:00:00');
        $rates = $historicalRates->getRatesSpecificTimeSimpleInPips(1,3,150,'2018-12-12 7:00:00');

        $hullMovingAverage = new HullMovingAverage();

        $minSlope = $hullMovingAverage->hmaSlope($rates, 50, .0001, 0);

        $this->assertEquals($minSlope, 'short');

        //$rates = $historicalRates->getRatesSpecificTimeSimpleInPips(1,3,1000,'2018-10-09 3:00:00');
        $rates = $historicalRates->getRatesSpecificTimeSimpleInPips(1,3,150,'2018-12-19 21:00:00');

        $hullMovingAverage = new HullMovingAverage();

        $minSlope = $hullMovingAverage->hmaSlope($rates, 50, .0001, 0);

        $this->assertEquals($minSlope, 'short');
    }

    public function testHmaPriceAboveBelowHma() {
        $historicalRates = new \App\Model\HistoricalRates();

        $rates = $historicalRates->getRatesSpecificTimeSimpleInPips(1,3,1000,'2018-12-20 9:00:00');

        $hullMovingAverage = new HullMovingAverage();

        $hmaAboveBelow = $hullMovingAverage->hmaPriceAboveBelowHma($rates, 9);

        $this->assertEquals($hmaAboveBelow, 'above');

        $historicalRates = new \App\Model\HistoricalRates();

        $rates = $historicalRates->getRatesSpecificTimeSimpleInPips(1,3,1000,'2018-12-21 9:00:00');

        $hullMovingAverage = new HullMovingAverage();

        $hmaAboveBelow = $hullMovingAverage->hmaPriceAboveBelowHma($rates, 9);

        $this->assertEquals($hmaAboveBelow, 'below');
    }

    public function testHmaSameSlopeDirectionMultiplePeriods() {
        $historicalRates = new \App\Model\HistoricalRates();

        $rates = $historicalRates->getRatesSpecificTimeSimpleInPips(1,3,1000,'2018-7-30 20:00:00');

        $hullMovingAverage = new HullMovingAverage();

        $hmaSameDirectionExpectedUp = $hullMovingAverage->hmaSameSlopeDirectionMultiplePeriods($rates, 25, 5);

        $this->assertEquals($hmaSameDirectionExpectedUp, 'up');

        $rates = $historicalRates->getRatesSpecificTimeSimpleInPips(1,3,1000,'2018-7-30 9:00:00');

        $hullMovingAverage = new HullMovingAverage();

        $hmaSameDirectionExpectedUp = $hullMovingAverage->hmaSameSlopeDirectionMultiplePeriods($rates, 25, 19);

        $this->assertEquals($hmaSameDirectionExpectedUp, false);

        $rates = $historicalRates->getRatesSpecificTimeSimpleInPips(1,3,1000,'2018-8-3 00:00:00');

        $hullMovingAverage = new HullMovingAverage();

        $hmaSameDirectionExpectedUp = $hullMovingAverage->hmaSameSlopeDirectionMultiplePeriods($rates, 25, 5);

        $this->assertEquals($hmaSameDirectionExpectedUp, 'down');

        $rates = $historicalRates->getRatesSpecificTimeSimpleInPips(1,3,1000,'2018-8-23 17:00:00');

        $hullMovingAverage = new HullMovingAverage();

        $hmaSameDirectionExpectedUp = $hullMovingAverage->hmaSameSlopeDirectionMultiplePeriods($rates, 25, 15);

        $this->assertEquals($hmaSameDirectionExpectedUp, false);
    }
}
