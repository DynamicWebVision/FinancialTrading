<?php
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\StrategyEvents\Bollinger;
use App\Model\HistoricalRates;
use App\Strategy\Bollinger\BollingerPriceAction;
use App\Services\StrategyLogger;

class BollingerPriceActionTest extends TestCase
{

    public function testEntryDecision() {
        $historicalRates = new App\Model\HistoricalRates();
        $bollingerStrategy = new BollingerPriceAction();
        $bollingerStrategy->strategyLogger = new StrategyLogger();
        $bollingerStrategy->bollingerLength = 20;
        $bollingerStrategy->bollingerSdMultiplier = 2;

        //CROSSED ABOVE EVENT
        $bollingerStrategy->rates = $historicalRates->getRatesSpecificTimeBoth(1,3,1000,'2018-05-29 6:00:00');

        $decision = $bollingerStrategy->getEntryDecision();

        $this->assertEquals($decision, 'short');

        //CROSSED ABOVE EVENT
        $bollingerStrategy->rates = $historicalRates->getRatesSpecificTimeBoth(1,3,1000,'2018-05-31 7:00:00');

        $decision = $bollingerStrategy->getEntryDecision();

        $this->assertEquals($decision, 'long');

        //CROSSED ABOVE EVENT
        $bollingerStrategy->rates = $historicalRates->getRatesSpecificTimeBoth(1,3,1000,'2018-05-28 1:00:00');

        $decision = $bollingerStrategy->getEntryDecision();

        $this->assertEquals($decision, 'none');
    }
}