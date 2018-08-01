<?php
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\StrategyEvents\EmaEvents;
use App\Model\HistoricalRates;
use App\Strategy\Hlhb\HlhbOnePeriodCrossover;
use App\Services\StrategyLogger;

class EmaEventsTest extends TestCase
{

    public function testCalculateEmaNextPeriodCrossoverRate() {
        $historicalRates = new App\Model\HistoricalRates();
        $rates = $historicalRates->getRatesSpecificTimeSimple(1,3,1000,'2018-06-20 2:00:00');

        $emaEvents = new EmaEvents();

        $rate = $emaEvents->calculateEmaNextPeriodCrossoverRate($rates, 5, 10, .001);
    }

    public function testPriceCrossEmaRate() {
        $historicalRates = new App\Model\HistoricalRates();
        $rates = $historicalRates->getRatesSpecificTimeSimple(1,3,1000,'2018-07-11 13:00:00');

        $emaEvents = new EmaEvents();

        $rate = $emaEvents->priceCrossEmaRate($rates, 20);

        $rates[] = $rate;

        $newEma = $emaEvents->ema($rates, 20);

        $this->assertEquals(end($newEma), $rate);

    }

    public function testCalculateEmaNextPeriodCrossoverRate2() {
        $historicalRates = new App\Model\HistoricalRates();
        $rates = $historicalRates->getRatesSpecificTimeSimple(1,3,1000,'2018-07-02 23:00:00');

        $emaEvents = new EmaEvents();

        $rate = $emaEvents->calculateEmaNextPeriodCrossoverRate($rates, 5, 10);

        $rates[] = $rate;

        $newEma = $emaEvents->ema($rates, 20);

        $this->assertEquals(end($newEma), $rate);

    }
}