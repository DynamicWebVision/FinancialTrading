<?php

namespace Tests\Unit\IndicatorEventTests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\CurrencyIndicators;
use App\IndicatorEvents\AdxEvents;
use \App\Services\StrategyLogger;
use App\Model\HistoricalRates;
use App\Model\TmpTestRates;

class AdxEventsTest extends TestCase
{

    public function testAdxBelowThreshold() {
        $adxEvents = new AdxEvents();
        $adxEvents->strategyLogger = new StrategyLogger();

        $historicalRates = new \App\Model\HistoricalRates();
        $rates = $historicalRates->getRatesSpecificTimeFull(1,3,100,'2018-10-30 5:00:00');

        $response = $adxEvents->adxBelowThreshold($rates, 14, 15);

        $this->assertTrue($response);

        $rates = $historicalRates->getRatesSpecificTimeFull(1,3,100,'2018-11-1 9:00:00');

        $response = $adxEvents->adxBelowThreshold($rates, 14, 20);

        $this->assertFalse($response);
    }

    public function testAdxAboveThreshold() {
        $adxEvents = new AdxEvents();
        $adxEvents->strategyLogger = new StrategyLogger();

        $historicalRates = new \App\Model\HistoricalRates();
        $rates = $historicalRates->getRatesSpecificTimeFull(1,3,100,'2018-11-8 13:00:00');

        $response = $adxEvents->adxAboveThreshold($rates, 14, 20);

        $this->assertTrue($response);

        $rates = $historicalRates->getRatesSpecificTimeFull(1,3,100,'2018-10-23 19:00:00');

        $response = $adxEvents->adxAboveThreshold($rates, 14, 20);

        $this->assertFalse($response);
    }
}
