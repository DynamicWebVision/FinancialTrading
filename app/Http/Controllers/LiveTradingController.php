<?php namespace App\Http\Controllers;

use App\Model\Strategy;
use App\Services\Utility;
use View;
use \App\Services\CronJobs;
use \App\Services\StrategyLogger;
use \Log;


//Strategies
use \App\ForexStrategy\MarketIfTouched\MarketIfTouchedReturnToOpen;

class LiveTradingController extends Controller {

    public $utility;

    public function __construct() {
        set_time_limit(0);

        $this->utility = new Utility();
    }

    public function marketIfTouchedReturnToOpenWeekly() {

        $strategy = new MarketIfTouchedReturnToOpen('001-001-2369711-001', 'initialload');

        $marginAvailable = $strategy->getAvailableMargin();

        //Need to Change
        $exchanges = \App\Model\Exchange::get();

        foreach ($exchanges as $exchange) {
            $logPrefix = "MarketIfTouchedReturnToOpen-".$exchange->exchange."-".uniqid();

            $systemStrategy = new MarketIfTouchedReturnToOpen('001-001-2369711-001', $logPrefix);
            $systemStrategy->accountAvailableMargin = $marginAvailable;

            $strategyLogger = new StrategyLogger();
            $strategyLogger->exchange_id = $exchange->id;
            $strategyLogger->method = 'marketIfTouchedReturnToOpenWeekly';
            $strategyLogger->oanda_account_id = 15;

            $strategyLogger->newStrategyLog();
            $systemStrategy->setLogger($strategyLogger);

            $systemStrategy->exchange = $exchange;
            $systemStrategy->oanda->frequency = 'W';

            $systemStrategy->rateCount = 1000;
            $systemStrategy->limitEndSeconds = 258000;

            $systemStrategy->positionMultiplier = 3;
            $systemStrategy->stopLossPipAmount = 100;

            $systemStrategy->orderType = 'MARKET_IF_TOUCHED';

            $systemStrategy->rates = $systemStrategy->getRates('both', true);
            $systemStrategy->setCurrentPrice();

            $systemStrategy->checkForNewPosition();
        }
    }
}