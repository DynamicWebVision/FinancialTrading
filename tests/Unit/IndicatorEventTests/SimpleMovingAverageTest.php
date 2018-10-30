<?php

namespace Tests\Unit\IndicatorEventTests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\CurrencyIndicators;
use App\IndicatorEvents\SimpleMovingAverage;
use App\Model\HistoricalRates;
use App\Model\TmpTestRates;

class SimpleMovingAverageTest extends TestCase
{

    public function testPriceTarget() {
        $simpleMovingAverage = new SimpleMovingAverage();

        $array = [1, 4, 6, 7, 9, 12, 14, 17, 22, 4, 7];

        $test = $simpleMovingAverage->getCrossPriceTarget($array, 5);

        $de=1;
    }
}
