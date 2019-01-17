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

    public function testGetStopLossTrueRangeOrBreakEvenMostProfitable()
    {
        $historicalRates = new \App\Model\HistoricalRates();

        $rates = $historicalRates->getRatesSpecificTimeFull(1, 3, 1000, '2018-12-28 2:00:00');

        $openPosition = [];

        $openPosition['side'] = 'long';
        $openPosition['openPrice'] = 1.13673;
        $openPosition['periodsOpen'] = 10;

        $trueRange = new TrueRange();

        $averageTrueRange = $trueRange->getStopLossTrueRangeOrBreakEvenMostProfitable($rates, 14, .5, .0001, $openPosition);

        $rates = $historicalRates->getRatesSpecificTimeFull(1, 3, 1000, '2019-1-2 14:00:00');

        $openPosition = [];

        $openPosition['side'] = 'short';
        $openPosition['openPrice'] = 1.14779;
        $openPosition['periodsOpen'] = 10;

        $trueRange = new TrueRange();

        $test = $trueRange->getStopLossTrueRangeOrBreakEvenMostProfitable($rates, 14, .5, .0001, $openPosition);

        dd($test);
    }

    public function testGetStopLossTrueRangeOrBreakEven()
    {
        $historicalRates = new \App\Model\HistoricalRates();

        $rates = $historicalRates->getRatesSpecificTimeFull(1, 3, 1000, '2018-12-28 2:00:00');

        $openPosition = [];

        $openPosition['side'] = 'long';
        $openPosition['openPrice'] = 1.13673;
        $openPosition['periodsOpen'] = 10;

        $trueRange = new TrueRange();

        $averageTrueRange = $trueRange->getStopLossTrueRangeOrBreakEven($rates, 14, .5, .0001, $openPosition);

        //dd($averageTrueRange);

        $rates = $historicalRates->getRatesSpecificTimeFull(1, 3, 1000, '2019-1-2 14:00:00');

        $openPosition = [];

        $openPosition['side'] = 'short';
        $openPosition['openPrice'] = 1.14779;
        $openPosition['periodsOpen'] = 10;

        $trueRange = new TrueRange();

        $test = $trueRange->getStopLossTrueRangeOrBreakEven($rates, 14, .5, .0001, $openPosition);

        dd($test);
    }
}
