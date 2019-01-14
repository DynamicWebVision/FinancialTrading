<?php

namespace Tests\Unit\IndicatorEventTests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\CurrencyIndicators;
use App\IndicatorEvents\TrueRange;
use App\Model\HistoricalRates;
use App\Model\TmpTestRates;

class TrueRangeTest extends TestCase
{

    public function testHullChangeDirectionCheck() {
        $historicalRates = new \App\Model\HistoricalRates();
        //$rates = $historicalRates->getRatesSpecificTimeSimpleInPips(1,3,1000,'2018-10-09 3:00:00');
        $rates = $historicalRates->getRatesSpecificTimeFull(1,3,1000,'2018-12-27 18:00:00');

        $trueRange = new TrueRange();

        $averageTrueRange = $trueRange->currentAverageTrueRange($rates, 14);
        $atr = round($averageTrueRange, 4);

        $this->assertEquals(.0022, $atr);
        //$rates = $historicalRates->getRatesSpecificTimeSimpleInPips(1,3,1000,'2018-10-09 3:00:00');
        $rates = $historicalRates->getRatesSpecificTimeFull(1,3,1000,'2018-12-27 2:00:00');

        $trueRange = new TrueRange();

        $averageTrueRange = $trueRange->currentAverageTrueRange($rates, 14);
        $atr = round($averageTrueRange, 4);

        $this->assertEquals(.0014, $atr);
    }

    public function testAverageTrueRangePips() {
        $historicalRates = new \App\Model\HistoricalRates();
        //$rates = $historicalRates->getRatesSpecificTimeSimpleInPips(1,3,1000,'2018-10-09 3:00:00');
        $rates = $historicalRates->getRatesSpecificTimeFull(1,3,1000,'2018-12-27 18:00:00');

        $trueRange = new TrueRange();

        $averageTrueRange = $trueRange->averageTrueRangePips($rates, 14, .0001);
    }

    public function testGoogleSpreadsheet() {
        $line = 2;

        $test = '=CONCATENATE(';

        while ($line < 32) {
            $test = $test.', J'.$line;
            $line++;
        }
        $test = $test.')';

        dd($test);
    }

    public function testGetStopLossPipValue() {
        $historicalRates = new \App\Model\HistoricalRates();
        //$rates = $historicalRates->getRatesSpecificTimeSimpleInPips(1,3,1000,'2018-10-09 3:00:00');
        $rates = $historicalRates->getRatesSpecificTimeFull(1,3,1000,'2018-12-27 18:00:00');

        $trueRange = new TrueRange();

        $averageTrueRange = $trueRange->getStopLossPipValue($rates, 14, .0001, 1);

        dd($averageTrueRange);
    }
}
