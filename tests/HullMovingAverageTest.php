<?php
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Services\CurrencyIndicators;
use App\Model\HistoricalRates;
use App\Strategy\Hlhb\HlhbOnePeriodCrossover;
use App\StrategyEvents\HullMovingAverage;
use App\Services\StrategyLogger;

class HullMovingAverageTest extends TestCase
{

    public function testEntryDecision() {
        $historicalRates = new App\Model\HistoricalRates();
        $rates = $historicalRates->getRatesSpecificTimeSimple(1,3,1000,'2018-07-06 13:00:00');

        $hullMovingAverage = new HullMovingAverage();

        $hullEndPoint = $hullMovingAverage->endHullPoint($rates, 9);

        $this->assertEquals(1.1733, round($hullEndPoint,4));

    }

    public function testHullLine() {
        $historicalRates = new App\Model\HistoricalRates();
        $rates = $historicalRates->getRatesSpecificTimeSimple(1,3,1000,'2018-07-09 14:00:00');

        $hullMovingAverage = new HullMovingAverage();

        $hullMovingAverageLine = $hullMovingAverage->hullLineLastThree($rates, 9);

        $test = 1;
    }

    public function testHullChangeDirection() {
        $historicalRates = new App\Model\HistoricalRates();
        $rates = $historicalRates->getRatesSpecificTimeSimple(1,3,1000,'2018-07-03 10:00:00');

        $hullMovingAverage = new HullMovingAverage();

        $changeDirection = $hullMovingAverage->hullChangeDirectionCheck($rates, 9);

        $this->assertEquals('reversedDown', $changeDirection);

        $rates = $historicalRates->getRatesSpecificTimeSimple(1,3,1000,'2018-07-04 13:00:00');

        $hullMovingAverage = new HullMovingAverage();

        $changeDirection = $hullMovingAverage->hullChangeDirectionCheck($rates, 9);

        $this->assertEquals('reversedUp', $changeDirection);
    }
}