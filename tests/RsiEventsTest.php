<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Services\Utility;
use App\StrategyEvents\RsiEvents;

class RsiEventsTest extends TestCase
{
    public function testCrossedLevel()
    {
        $rsiEvents = new RsiEvents();

        $historicalRates = new App\Model\HistoricalRates();
        $rates = $historicalRates->getRatesSpecificTimeSimple(1,3,1000,'2018-06-21 13:00:00');

        $crossAbove = $rsiEvents->crossedLevel($rates, 14, 50);

        $this->assertEquals($crossAbove, 'crossedAbove');

        $rates = $historicalRates->getRatesSpecificTimeSimple(1,3,1000,'2018-06-21 12:00:00');

        $crossAbove = $rsiEvents->crossedLevel($rates, 14, 50);

        $this->assertEquals($crossAbove, 'crossedBelow');
    }
    public function testCrossedAbc()
    {
        $rsiEvents = new RsiEvents();

        $historicalRates = new App\Model\HistoricalRates();
        $rates = $historicalRates->getRatesSpecificTimeSimple(1,3,1000,'2018-06-15 10:00:00');

        $crossAbove = $rsiEvents->crossedLevel($rates, 14, 50);

        $this->assertEquals($crossAbove, 'crossedAbove');


    }

    public function testGetLastTwoRsi() {
        $rsiEvents = new RsiEvents();

        $historicalRates = new App\Model\HistoricalRates();

        $rates = $historicalRates->getRatesSpecificTimeSimple(1,3,1000,'2018-06-04 03:00:00');

        $test = $rsiEvents->getLastTwoRsi($rates, 14);

    }

    public function testRsi() {
        $rsiEvents = new RsiEvents();

        $historicalRates = new App\Model\HistoricalRates();

        $rates = $historicalRates->getRatesSpecificTimeSimple(1,3,1000,'2018-06-04 04:00:00');

        $test = $rsiEvents->rsi($rates, 14);

        $test = 1;
    }

    public function testRsiJson() {
        $rsiEvents = new RsiEvents();

        $historicalRates = new App\Model\HistoricalRates();

        $rates = [44.34,
44.09,
44.15,
43.61,
44.33,
44.83,
45.10,
45.42,
45.84,
46.08,
45.89,
46.03,
45.61,
46.28,
46.28];

        $test = $rsiEvents->rsi($rates, 14);

        $test = 1;
    }
}
