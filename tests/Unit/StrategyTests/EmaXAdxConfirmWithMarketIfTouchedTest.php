<?php

namespace Tests\Unit\StrategyTests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;


use App\IndicatorEvents\Bollinger;
use App\Model\HistoricalRates;
use App\ForexStrategy\Bollinger\BollingerBreakBandRetreatPullback;
use App\Services\StrategyLogger;

class EmaXAdxConfirmWithMarketIfTouched extends TestCase
{

    public function testEntryDecision() {
        $historicalRates = new \App\Model\HistoricalRates();
        $bollingerStrategy = new BollingerBreakBandRetreatPullback();
        $bollingerStrategy->strategyLogger = new StrategyLogger();
        $bollingerStrategy->bollingerLength = 20;
        $bollingerStrategy->bollingerSdMultiplier = 2;
        $bollingerStrategy->rsiLowerThreshold = 25;
        $bollingerStrategy->rsiUpperThreshold = 75;

        $bollingerStrategy->rsiPeriodsBack = 5;


    }
}
