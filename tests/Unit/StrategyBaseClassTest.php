<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use \App\Model\Exchange;
use \App\Services\BackTest;
use \App\BackTest\TakeProfitStopLossTest;
use \App\BackTest\IndicatorRunThroughTest;
use \App\Model\HistoricalRates;
use \App\Model\TmpTestRates;
use \App\Strategy\EmaMomentum\EmaXAdxConfirmWithMarketIfTouched;
use \App\Http\Controllers\AutomatedBackTestController;
use \App\Services\StrategyLogger;

class StrategyBaseClassTest extends TestCase
{

    public $systemStrategy;
    public $testAccount = '101-001-7608904-002';
    public $tradingDays = ['Monday','Tuesday','Wednesday','Thursday'];

    public function setUpStrategy() {
        $exchange = \App\Model\Exchange::find(1);

        $this->systemStrategy = new EmaXAdxConfirmWithMarketIfTouched($this->testAccount, 'TESTING-ABC');

        $strategyLogger = new StrategyLogger();
        $strategyLogger->exchange_id = $exchange->id;
        $strategyLogger->method = 'emaXAdxConfirmWithMarketIfTouched';
        $strategyLogger->oanda_account_id = 7;

        $strategyLogger->newStrategyLog();
        $this->systemStrategy->setLogger($strategyLogger);

        $this->systemStrategy->exchange = $exchange;
        $this->systemStrategy->exchange = $exchange;
        $this->systemStrategy->oanda->frequency = 'H1';
        $this->systemStrategy->oanda->exchange = $exchange->exchange;

        $this->systemStrategy->rateCount = 1000;

        $this->systemStrategy->rates = $this->systemStrategy->getRates('both');
        $this->systemStrategy->setCurrentPrice();

        $this->systemStrategy->strategyId = 5;
        $this->systemStrategy->strategyDesc = 'emaXAdxConfirmWithMarketIfTouched';
        $this->systemStrategy->positionMultiplier = 5;

        $this->systemStrategy->maxPositions = 3;

        //Specific Strategy Variables
        $this->systemStrategy->fastEma = 5;
        $this->systemStrategy->slowEma = 10;

        $this->systemStrategy->takeProfitTrueRangeMultiplier = 3;
        $this->systemStrategy->stopLossTrueRangeMultiplier = 2;

        $this->systemStrategy->orderType = 'MARKET_IF_TOUCHED';
    }

    public function testMarketIfTouchedLong() {
        $this->setUpStrategy();

        $dayOfWeek = date('l');

        if (!in_array($dayOfWeek,$this->tradingDays)) {
            echo 'NOT TRADING DAY';
        }
        else {
            $this->systemStrategy->marketIfTouchedOrderPrice = $this->systemStrategy->currentPriceData->mid + .001;
            $this->systemStrategy->takeProfitPipAmount = 22.32434;
            $this->systemStrategy->stopLossPipAmount = 11.32434;
            $this->systemStrategy->newLongPosition();
        }
    }

    public function testMarketIfTouchedShort() {
        $this->setUpStrategy();

        $dayOfWeek = date('l');

        if (!in_array($dayOfWeek,$this->tradingDays)) {
            echo 'NOT TRADING DAY';
        }
        else {
            $this->systemStrategy->marketIfTouchedOrderPrice = $this->systemStrategy->currentPriceData->mid - .001;
            $this->systemStrategy->takeProfitPipAmount = 22.32434;
            $this->systemStrategy->stopLossPipAmount = 11.32434;
            $this->systemStrategy->newShortPosition();
        }
    }

    public function testModifyStopLossNoCurentStopLoss() {
        $this->setUpStrategy();

        $dayOfWeek = date('l');

        if (!in_array($dayOfWeek,$this->tradingDays)) {
            echo 'NOT TRADING DAY';
        }
        else {
            $this->systemStrategy->setOpenPosition();

            $this->systemStrategy->modifyStopLoss(1.05);
        }
    }
}
