<?php namespace App\Http\Controllers;

use App\Model\Strategy;
use App\Services\Utility;
use View;
use \App\Services\CronJobs;
use \App\Services\StrategyLogger;
use \Log;


//Strategies
use \App\Strategy\HullMovingAverage\TwoTier;
use \App\Strategy\HullMovingAverage\HmaAdxStayIn;
use \App\Strategy\EmaMomentum\EmaMomentumAdxConfirm;
use \App\Strategy\HmaCrossover\HmaXAdxPointConfirm;
use \App\Strategy\HullMovingAverage\HmaStayInDifferentEntryExitAdx;
use \App\Strategy\TwoTier\EmaFastHmaSlow;
use \App\Strategy\EmaMomentum\EmaMomentumDifferenceSlope;
use \App\Strategy\Stochastic\SingleHmaMomentumTpSl;
use \App\Strategy\Stochastic\StochFastOppositeSlow;
use \App\Strategy\EmaMomentum\EmaXAdxConfirmWithMarketIfTouched;
use \App\Strategy\HullMovingAverage\HmaSimple;
use \App\Strategy\PreviousCandlePriceHighLow\HighLowSuperSimpleHoldOnePeriod;

class LiveTradingController extends Controller {

    public $utility;

    public function __construct() {
        set_time_limit(0);

        $this->utility = new Utility();
    }

    public function hmaFifteenMinutes() {

        $this->utility->sleepUntilAtLeastFiveSeconds();

        $strategy = new HmaSimple('001-001-2369711-001', 'initialload');

        $marginAvailable = $strategy->getAvailableMargin();

        //Need to Change
        $exchanges = \App\Model\Exchange::get();

        foreach ($exchanges as $exchange) {
            $logPrefix = "hmaFifteenMinutes-".$exchange->exchange."-".uniqid();

            $systemStrategy = new HmaSimple('001-001-2369711-001', $logPrefix);
            $systemStrategy->accountAvailableMargin = $marginAvailable;

            $strategyLogger = new StrategyLogger();
            $strategyLogger->exchange_id = $exchange->id;
            $strategyLogger->method = 'hmaFifteenMinutes';
            $strategyLogger->oanda_account_id = 4;

            $strategyLogger->newStrategyLog();
            $systemStrategy->setLogger($strategyLogger);

            if ($exchange->exchange == 'EUR_USD') {
                $systemStrategy->logDbRates = true;
            }

            $systemStrategy->exchange = $exchange;
            $systemStrategy->oanda->frequency = 'M15';

            $systemStrategy->rateCount = 1000;

            $systemStrategy->rates = $systemStrategy->getRates('both', true);
            $systemStrategy->setCurrentPrice();

            $systemStrategy->exchange = $exchange;
            $systemStrategy->strategyId = 5;
            $systemStrategy->strategyDesc = 'hmaSimple';
            $systemStrategy->positionMultiplier = 5;

            $systemStrategy->maxPositions = 3;

            //Specific Strategy Variables
            $systemStrategy->fastHma = 200;

            $systemStrategy->adxLength = 14;
            $systemStrategy->adxUndersoldThreshold = 20;

            $systemStrategy->takeProfitTrueRangeMultiplier = 10;
            $systemStrategy->stopLossTrueRangeMultiplier = 2;

            $systemStrategy->orderType = 'MARKET_IF_TOUCHED';

            $systemStrategy->checkForNewPosition();
        }
    }


}