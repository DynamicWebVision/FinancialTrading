<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Services\Utility;
use App\StrategyEvents\Momentum;
use App\Model\HistoricalRates;

class MomentumTest extends TestCase
{
    public function testLineSwitchedDirectionsAndMeetsMinSlope()
    {
        $momentum = new Momentum();

        $array = [-1, -2, 3, 4, 6, 5.5, 7, 6.5, 9, 10];

        $response = $momentum->lineSwitchedDirectionsAndMeetsMinSlope($array, 1, 3);

        $this->assertEquals('long', $response);

        $array = [-1, -2, 3, 4, 6, 5.5, 7,7, 9, 10];

        $response = $momentum->lineSwitchedDirectionsAndMeetsMinSlope($array, 1, 3);

        $this->assertEquals('none', $response);

        $array = [-1, -2, 3, 4, 6, -5.5, -7,-6.5, -9, -10];

        $response = $momentum->lineSwitchedDirectionsAndMeetsMinSlope($array, 1, 3);

        $this->assertEquals('short', $response);

        $array = [-1, -2, 3, 4, 6, -5.5, -7,-7, -9, -10];

        $response = $momentum->lineSwitchedDirectionsAndMeetsMinSlope($array, 1, 3);

        $this->assertEquals('none', $response);
    }

//    public function testHmaSwitchesDirectionAndMeetsMinimumSlope() {
//        $momentum = new Momentum();
//
//        $historicalRates = new App\Model\HistoricalRates();
//        $rates = $historicalRates->getRatesSpecificTimeSimple(1,1,100,'2018-05-22 12:00:00');
//
//        $test = $momentum->hmaSwitchesDirectionAndMeetsMinimumSlope($rates, 9, .1, 2);
//
//        dd($test);
//    }

//    public function testHmaCrossover() {
//        $historicalRates = new App\Model\HistoricalRates();
//        $rates = $historicalRates->getRatesSpecificTimeSimple(1,1,1000,'2018-05-02 04:45:00');
//
//        $momentum = new Momentum();
//
//        $crossover = $momentum->hmaCrossover($rates, 20, 40);
//
//        dd($crossover);
//    }

    public function testEmaCrossoverEmaCrossoverHard() {
        $historicalRates = new App\Model\HistoricalRates();
        $rates = $historicalRates->getRatesSpecificTimeSimple(1,3,1000,'2018-06-21 12:00:00');

        $momentum = new Momentum();

        $crossover = $momentum->emaCrossover($rates, 5, 10);

        dd($crossover);
    }
}
