<?php

namespace Tests\Unit\IndicatorEventTests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\CurrencyIndicators;
use App\IndicatorEvents\IchimokuKinkoHyo;
use App\Model\HistoricalRates;
use App\Model\TmpTestRates;

class IchimokuKinkoHyoTest extends TestCase
{

    public function testTargetRsi() {
        $historicalRates = new \App\Model\HistoricalRates();
        $rates = $historicalRates->getRatesSpecificTimeSimple(1,3,100,'2018-06-19 5:00:00');

        $count = 1;
        $text = '[';
        while ($count <= 100) {
            $text = $text.$count.', ';
            $count++;
        }
        $text = $text.'];';
        dd($text);

        $rsiEvents = new IchimokuKinkoHyo();
    }
}