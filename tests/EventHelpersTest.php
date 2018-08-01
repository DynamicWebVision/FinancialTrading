<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Services\Utility;
use App\StrategyEvents\EventHelpers;

class EventHelpersTest extends TestCase
{
    public function testCrossedLevel()
    {
        $eventHelpers = new EventHelpers();

        $events = ['crossedUp', 'none'];

        $result = $eventHelpers->periodsBackGetLastResultEvent($events, ['crossedUp', 'crossedBelow']);

        $this->assertEquals('crossedUp', $result);

        $events = ['none', 'none'];

        $result = $eventHelpers->periodsBackGetLastResultEvent($events, ['crossedUp', 'crossedBelow']);

        $this->assertEquals('none', $result);

        $events = ['crossedUp', 'crossedBelow'];

        $result = $eventHelpers->periodsBackGetLastResultEvent($events, ['crossedUp', 'crossedBelow']);

        $this->assertEquals('crossedBelow', $result);
    }

    public function testRatesAverageHighLow() {
        $historicalRates = new App\Model\HistoricalRates();
        $rates = $historicalRates->getRatesSpecificTimeFull(1,3,50,'2018-06-21 12:00:00');

        $eventHelpers = new EventHelpers();
        $rates = $eventHelpers->ratesAverageHighLow($rates);

        $this->assertEquals(1.15718, end($rates));
    }
}
