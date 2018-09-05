<?php

namespace Tests\Unit\IndicatorEventTests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\CurrencyIndicators;
use App\IndicatorEvents\RsiEvents;
use App\Model\HistoricalRates;
use App\Model\TmpTestRates;

class RsiEventsTest extends TestCase
{

    public function testTargetRsi() {
        $historicalRates = new \App\Model\HistoricalRates();
        $rates = $historicalRates->getRatesSpecificTimeSimple(1,3,100,'2018-06-19 5:00:00');

        $rsiEvents = new RsiEvents();

        $rsiTargetShort = $rsiEvents->getCrossLevelPricePoint($rates, 14, 50);

        $ratesWithTargetPrice = $rates;
        $ratesWithTargetPrice[] = $rsiTargetShort['targetPrice'];

        $rsi = $rsiEvents->rsi($ratesWithTargetPrice, 14);

        $this->assertEquals($rsi, 50);



        $historicalRates = new \App\Model\HistoricalRates();
        $rates = $historicalRates->getRatesSpecificTimeSimple(1,3,100,'2018-06-21 11:00:00');

        $rsiEvents = new RsiEvents();

        $rsiTargetShort = $rsiEvents->getCrossLevelPricePoint($rates, 14, 51);

        $ratesWithTargetPrice = $rates;
        $ratesWithTargetPrice[] = $rsiTargetShort['targetPrice'];

        $rsi = $rsiEvents->rsi($ratesWithTargetPrice, 14);

        $this->assertEquals($rsi, 51);
    }
}
