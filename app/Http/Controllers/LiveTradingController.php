<?php namespace App\Http\Controllers;

use App\Model\Strategy;
use App\Services\Utility;
use View;
use \App\Services\CronJobs;
use \App\Services\StrategyLogger;
use \Log;


//Strategies
use \App\ForexStrategy\HullMovingAverage\TwoTier;
use \App\ForexStrategy\HullMovingAverage\HmaAdxStayIn;
use \App\ForexStrategy\EmaMomentum\EmaMomentumAdxConfirm;
use \App\ForexStrategy\HmaCrossover\HmaXAdxPointConfirm;
use \App\ForexStrategy\HullMovingAverage\HmaStayInDifferentEntryExitAdx;
use \App\ForexStrategy\TwoTier\EmaFastHmaSlow;
use \App\ForexStrategy\EmaMomentum\EmaMomentumDifferenceSlope;
use \App\ForexStrategy\Stochastic\SingleHmaMomentumTpSl;
use \App\ForexStrategy\Stochastic\StochFastOppositeSlow;
use \App\ForexStrategy\EmaMomentum\EmaXAdxConfirmWithMarketIfTouched;
use \App\ForexStrategy\HullMovingAverage\HmaSimple;
use \App\ForexStrategy\PreviousCandlePriceHighLow\HighLowSuperSimpleHoldOnePeriod;

class LiveTradingController extends Controller {

    public $utility;

    public function __construct() {
        set_time_limit(0);

        $this->utility = new Utility();
    }

    public function hmaFifteenMinutes() {
        //
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
            $strategyLogger->oanda_account_id = 15;

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


    public function marketIfTouchedReturnToOpen() {

        $this->utility->sleepUntilAtLeastFiveSeconds();

        $strategy = new MarketIfTouchedReturnToOpen('001-001-2369711-001', 'initialload');
        $logger = new ProcessLogger('lp_return_open_mkt_touch');

        $marginAvailable = $strategy->getAvailableMargin();

        //Need to Change
        $exchanges = \App\Model\Exchange::get();

        foreach ($exchanges as $exchange) {
            $logger->logMessage('Starting Exchange '.$exchange->exchange);
            $logPrefix = "MarketIfTouchedReturnToOpen-".$exchange->exchange."-".uniqid();

            $systemStrategy = new MarketIfTouchedReturnToOpen('001-001-2369711-001', $logPrefix);
            $systemStrategy->accountAvailableMargin = $marginAvailable;

            $strategyLogger = new StrategyLogger();
            $strategyLogger->exchange_id = $exchange->id;
            $strategyLogger->method = 'marketIfTouchedReturnToOpen';
            $strategyLogger->oanda_account_id = 15;

            $strategyLogger->newStrategyLog();
            $systemStrategy->setLogger($strategyLogger);

            $systemStrategy->exchange = $exchange;
            $systemStrategy->oanda->frequency = 'D';

            $systemStrategy->rateCount = 1000;

            $systemStrategy->positionMultiplier = 1;

            $systemStrategy->orderType = 'MARKET_IF_TOUCHED';

            $systemStrategy->rates = $systemStrategy->getRates('both', true);
            $systemStrategy->setCurrentPrice();
            $logger->logMessage('Checking for New Position '.$exchange->exchange);

            $systemStrategy->checkForNewPosition();
        }
    }
}