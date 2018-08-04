<?php

namespace Tests\Unit\IndicatorEventTests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\CurrencyIndicators;
use App\Model\HistoricalRates;
use App\Model\TmpTestRates;

class AdxEventsTest extends TestCase
{

    public function testAdx() {
        $currencyIndicators = new CurrencyIndicators();

        $historicalRates = new \App\Model\HistoricalRates();
        $rates = $historicalRates->getRatesSpecificTimeFull(1,3,100,'2018-06-27 9:00:00');

        $response = $currencyIndicators->adx($rates, 14);

        $this->assertEquals(round(end($response), 1), 29.8);
    }

    //Test from
    public function testSimpleAdx() {
        $currencyIndicators = new CurrencyIndicators();

        $tmpTestRates = TmpTestRates::where('test', '=', 'adx')->orderBy('id')->get()->toArray();

        $rates = $fullRates = array_map(function($rate) {

            $stdRate = new \StdClass();

            $stdRate->highMid = (float) $rate['high_mid'];
            $stdRate->closeMid = (float) $rate['close_mid'];
            $stdRate->lowMid = (float) $rate['low_mid'];
            $stdRate->openMid = (float) $rate['open_mid'];
            //$stdRate->volume = (float) $rate['volume'];

            return $stdRate;
        }, $tmpTestRates);

        $adx = $currencyIndicators->adx($rates, 14);

        $this->assertEquals(round(end($adx), 1), 16.7);
    }
}
