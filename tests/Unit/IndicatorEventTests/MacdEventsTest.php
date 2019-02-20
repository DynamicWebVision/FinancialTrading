<?php

namespace Tests\Unit\IndicatorEventTests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\CurrencyIndicators;
use App\IndicatorEvents\Macd;
use App\Model\HistoricalRates;
use App\Model\TmpTestRates;

class MacdEventsTest extends TestCase
{
    public function testMacd() {
        $historicalRates = new \App\Model\HistoricalRates();
        //$rates = $historicalRates->getRatesSpecificTimeSimpleInPips(1,3,1000,'2018-10-09 3:00:00');
        $rates = $historicalRates->getRatesSpecificTimeSimple(1,3,1000,'2018-12-24 8:00:00');

        $macd = new Macd();

        $values = $macd->macd($rates, 12, 26, 9);

        dd($values);
    }
}