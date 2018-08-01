<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Model\HistoricalRates;
use App\StrategyEvents\Momentum;

class StrategyEventsTest extends TestCase
{

    public function testEmaThreeInLine()
    {
        $historicalRates = new HistoricalRates();

        $momentum = new Momentum();

        $rates = $historicalRates->getRatesSpecificTimeSimple(1,1,250,'2018-04-06 17:00:00');

        $longValidation = $momentum->threeMAExponential(20,40,80, $rates);

        $this->assertEquals($longValidation, 'long');

        $rates = $historicalRates->getRatesSpecificTimeSimple(1,1,250,'2018-04-05 14:15:00');

        $shortValidation = $momentum->threeMAExponential(20,40,80, $rates);

        $this->assertEquals($shortValidation, 'short');

        $rates = $historicalRates->getRatesSpecificTimeSimple(1,1,250,'2018-04-06 13:15:00');

        $noneValidation = $momentum->threeMAExponential(20,40,80, $rates);

        $this->assertEquals($noneValidation, 'none');
    }

    public function testSmaThreeInLine()
    {
        $historicalRates = new HistoricalRates();

        $momentum = new Momentum();

        $rates = $historicalRates->getRatesSpecificTimeSimple(1,1,1000,'2018-04-05 22:00:00');

        $longValidation = $momentum->threeMAHull(20,40,80, $rates);

        $this->assertEquals($longValidation, 'long');

        $rates = $historicalRates->getRatesSpecificTimeSimple(1,1,1000,'2018-03-27 10:45:00');

        $shortValidation = $momentum->threeMAHull(20,40,80, $rates);

        $this->assertEquals($shortValidation, 'short');

        $rates = $historicalRates->getRatesSpecificTimeSimple(1,1,1000,'2018-03-27 17:30:00');

        $noneValidation = $momentum->threeMAHull(20,40,80, $rates);

       $this->assertEquals($noneValidation, 'none');
    }
}
