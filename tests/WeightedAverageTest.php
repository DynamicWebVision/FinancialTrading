<?php
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\StrategyEvents\WeightedMovingAverage;
use App\Model\HistoricalRates;
use App\Strategy\Hlhb\HlhbOnePeriodCrossover;
use App\Services\StrategyLogger;
use App\Services\CurrencyIndicators;

class WeightedAverageTest extends TestCase
{

    public function testWeightedMovingAverage() {
        $weightedMA = new WeightedMovingAverage();

        $rates = [1.12154,
                    1.12151,
                    1.12163,
                    1.12186,
                    1.12187,
                    1.12184,
                    1.12203,
                    1.12223,
                    1.12236,
                    1.12213,
                    1.12187];

        $wma = $weightedMA->weightedMovingAverage($rates, 11);

        $this->assertEquals($wma, 1.122006667);
    }
}