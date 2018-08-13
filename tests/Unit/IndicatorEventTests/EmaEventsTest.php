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

//    public function testPriceCrossEmaRate() {
//        $historicalRates = new \App\Model\HistoricalRates();
//        $rates = $historicalRates->getRatesSpecificTimeSimple(1,3,1000,'2018-07-11 13:00:00');
//
//        $emaEvents = new EmaEvents();
//
//        $rate = $emaEvents->priceCrossEmaRate($rates, 20);
//
//        $rates[] = $rate;
//
//        $newEma = $emaEvents->ema($rates, 20);
//
//        $this->assertEquals(end($newEma), $rate);
//
//    }
//
//    public function testCalculateEmaNextPeriodCrossoverRate2() {
//        $historicalRates = new \App\Model\HistoricalRates();
//        $rates = $historicalRates->getRatesSpecificTimeSimple(1,3,1000,'2018-07-02 23:00:00');
//
//        $emaEvents = new EmaEvents();
//
//        $rate = $emaEvents->calculateEmaNextPeriodCrossoverRate($rates, 5, 10);
//
//        $rates[] = $rate;
//
//        $newEma = $emaEvents->ema($rates, 20);
//
//        $this->assertEquals(end($newEma), $rate);
//
//    }
}
