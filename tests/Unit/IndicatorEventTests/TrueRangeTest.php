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

    public function testGetSLOpenTrUntilCurrentTrProfOrNumberOfPeriods()
    {
        $historicalRates = new \App\Model\HistoricalRates();

        $rates = $historicalRates->getRatesSpecificTimeFull(1, 3, 1000, '2018-8-28 14:00:00');

        $openPosition = [];

        $openPosition['side'] = 'short';
        $openPosition['openPrice'] = 1.17343;
        $openPosition['periodsOpen'] = 10;

        $trueRange = new TrueRange();

        $stopLoss = $trueRange->getSLOpenTrUntilCurrentTrProfOrNumberOfPeriods($rates, 14, .5, .0001, $openPosition, 4);

        $openPosition['openPrice'] = 1.16254;
        $stopLoss = $trueRange->getSLOpenTrUntilCurrentTrProfOrNumberOfPeriods($rates, 14, .5, .0001, $openPosition, 4);

    }
}
