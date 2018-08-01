<?php
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Services\CurrencyIndicators;
use App\Model\HistoricalRates;
use App\Strategy\Hlhb\HlhbOnePeriodCrossover;
use App\Strategy\Hlhb\HlhbAdx;
use App\Services\StrategyLogger;

class HlhbTest extends TestCase
{

    public function testEntryDecision() {
        $historicalRates = new App\Model\HistoricalRates();
        $rates = $historicalRates->getRatesSpecificTimeSimple(1,2,1000,'2018-06-25 7:30:00');

        $hlhbStrategy = new HlhbOnePeriodCrossover();
        $hlhbStrategy->rates = $rates;
        $hlhbStrategy->strategyLogger = new StrategyLogger();

        $hlhbStrategy->fastEma = 20;
        $hlhbStrategy->slowEma = 35;

        $hlhbStrategy->rsiLength = 14;
        $hlhbStrategy->rsiBreakthroughLevel = 50;

        $decision = $hlhbStrategy->getEntryDecision();

        $this->assertEquals('long', $decision);

    }

    public function testAdx() {
        $historicalRates = new App\Model\HistoricalRates();
        $rates = $historicalRates->getRatesSpecificTimeBoth(1,3,1000,'2018-06-14 11:00:00');

        $hlhbStrategy = new HlhbAdx();
        $hlhbStrategy->rates = $rates;
        $hlhbStrategy->strategyLogger = new StrategyLogger();

        $hlhbStrategy->fastEma = 5;
        $hlhbStrategy->slowEma = 10;

        $hlhbStrategy->rsiLength = 14;
        $hlhbStrategy->rsiBreakthroughLevel = 50;

        $decision = $hlhbStrategy->getEntryDecision();

        $this->assertEquals('short', $decision);

    }
}