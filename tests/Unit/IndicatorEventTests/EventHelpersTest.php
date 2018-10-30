<?php

namespace Tests\Unit\IndicatorEventTests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\CurrencyIndicators;
use App\IndicatorEvents\HullMovingAverage;
use App\Model\HistoricalRates;
use App\Model\TmpTestRates;

class EventHelpersTest extends TestCase
{

    public function testAverageSlopeLastXPeriods() {
        $historicalRates = new \App\Model\HistoricalRates();
        $rates = $historicalRates->getRatesSpecificTimeSimple(1,3,100,'2018-08-08 1:00:00');

        $hullEvents = new HullMovingAverage();

        $xPoint = $hullEvents->hullChangeDirectionPoint($rates, 9, .0001);

        $rates[] = $xPoint;
    }
}
