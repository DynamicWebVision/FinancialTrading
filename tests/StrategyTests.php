<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Services\CurrencyIndicators;
use App\Services\StrategyLogger;
use App\Model\Exchange;
use App\Strategy\Stochastic\StochFastOppositeSlow;

class StrategyTest extends TestCase
{

    public function testTwoLimitOrders() {

        $exchange = Exchange::find(1);

        $systemStrategy = new StochFastOppositeSlow('101-001-7608904-002', 'test');

        $strategyLogger = new StrategyLogger();
        $strategyLogger->exchange_id = $exchange->id;
        $strategyLogger->method = 'testTwoLimitOrders';
        $strategyLogger->oanda_account_id = 4;

        $strategyLogger->newStrategyLog();
        $systemStrategy->setLogger($strategyLogger);

        $systemStrategy->exchange = $exchange;
        $systemStrategy->oanda->frequency = 'M15';

        $systemStrategy->rateCount = 1000;

        $systemStrategy->rates = $systemStrategy->getRates('both');
        $systemStrategy->setCurrentPrice();

        $systemStrategy->exchange = $exchange;
        $systemStrategy->strategyId = 5;
        $systemStrategy->strategyDesc = 'StochFastOppositeSlow';
        $systemStrategy->positionMultiplier = 20;

        $systemStrategy->maxPositions = 3;
        $systemStrategy->stopLossPipAmount = 10;
        $systemStrategy->takeProfitPipAmount = 50;

        //Specific Strategy Variables
        $systemStrategy->hmaLength = 200;
        $systemStrategy->hmaSlopeMin = .1;

        $systemStrategy->fastStochOverboughtCutoff = 30;
        $systemStrategy->slowStochOverboughtCutoff = 30;

        $systemStrategy->orderType = 'MARKET_IF_TOUCHED';

        $systemStrategy->limitOrderPrice = 1.16777;
        $systemStrategy->newLongPosition();

        $systemStrategy->limitOrderPrice = 1.16682;
        $systemStrategy->newShortPosition();
    }
}
