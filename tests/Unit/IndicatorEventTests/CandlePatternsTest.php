<?php

namespace Tests\Unit\IndicatorEventTests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\CurrencyIndicators;
use App\Model\HistoricalRates;
use App\Model\TmpTestRates;
use \App\IndicatorEvents\CandlePatterns;

class CandlePatternsTest extends TestCase
{

    public function testCalculateEmaNextPeriodCrossoverRate() {
        $historicalRates = new \App\Model\HistoricalRates();
        $rates = $historicalRates->getRatesSpecificTimeFull(1,3,1000,'2019-01-23 2:00');

        $candlePatterns = new CandlePatterns();

        $rate = $candlePatterns->haramiCheck($rates);

        $this->assertTrue($rate);

        $rates = $historicalRates->getRatesSpecificTimeFull(1,3,1000,'2019-01-22 14:00');

        $candlePatterns = new CandlePatterns();

        $rate = $candlePatterns->haramiCheck($rates);

        $this->assertFalse($rate);
    }

    public function testalsdfjlasjkdf() {
        $historicalRates = new \App\Model\HistoricalRates();
        $rates = $historicalRates->getRatesSpecificTimeFull(1,3,1000,'2018-12-20 13:00');

        $candlePatterns = new CandlePatterns();

        $rate = $candlePatterns->hammerShootingStarCheck($rates);

        $this->assertEquals($rate, 'shooting-star');
        $rates = $historicalRates->getRatesSpecificTimeFull(1,3,1000,'2018-12-20 13:00');

        $candlePatterns = new CandlePatterns();

        $rate = $candlePatterns->hammerShootingStarCheck($rates);

        $this->assertEquals($rate, 'hammer');
    }

}