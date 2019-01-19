<?php

namespace Tests\Unit\StrategyTests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;


use App\IndicatorEvents\Bollinger;
use App\Model\HistoricalRates;
use App\ForexStrategy\Bollinger\BollingerBreakBandRetreatPullback;
use App\Services\StrategyLogger;

class BollingerPullbackTest extends TestCase
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

        $bollingerStrategy->rates = $historicalRates->getRatesSpecificTimeBoth(1,1,1000,'2018-08-15 2:00:00');

        $test = 1;

        /****NOT WORKING*****/

        //CROSSED ABOVE EVENT
//        $bollingerStrategy->rates = $historicalRates->getRatesSpecificTimeBoth(1,3,1000,'2018-06-04 07:00:00');
//
//        $decision = $bollingerStrategy->getEntryDecision();
//
//        $this->assertEquals($decision, 'short');
//
//        //CROSSED ABOVE EVENT
//        $bollingerStrategy->rates = $historicalRates->getRatesSpecificTimeBoth(1,3,1000,'2018-05-31 7:00:00');
//
//        $decision = $bollingerStrategy->getEntryDecision();
//
//        $this->assertEquals($decision, 'long');
//
//        //CROSSED ABOVE EVENT
//        $bollingerStrategy->rates = $historicalRates->getRatesSpecificTimeBoth(1,3,1000,'2018-05-28 1:00:00');
//
//        $decision = $bollingerStrategy->getEntryDecision();
//
//        $this->assertEquals($decision, 'none');
//
//        //CROSSED ABOVE EVENT
//        $bollingerStrategy->rates = $historicalRates->getRatesSpecificTimeBoth(1,3,1000,'2018-06-04 8:00:00');
//
//        $decision = $bollingerStrategy->getEntryDecision();
//
//        $this->assertEquals($decision, 'none');
    }
}
