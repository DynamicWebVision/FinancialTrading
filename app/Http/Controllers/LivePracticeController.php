<?php namespace App\Http\Controllers;

use App\Model\OandaAccounts;
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
use \App\ForexStrategy\MarketIfTouched\MarketIfTouchedReturnToOpen;
use \App\ForexStrategy\AmazingCrossover\AmazingCrossoverTS;
use \App\ForexStrategy\HmaReversal\HmaRevAfterPeriodsHold;
Use App\Services\ProcessLogger;

class LivePracticeController extends Controller {

    public $utility;

    public function __construct() {
        set_time_limit(0);

        $this->utility = new Utility();
    }

    public function closeCowabunga() {
        Log::info('START: LivePracticeController->closeCowabunga');
        $oanda = new \App\Services\Oanda();
        $oanda->accountId = 548695;

        $oanda->closeAllPositions();

        Log::info('END: LivePracticeController->closeCowabunga');
    }

    public function closeHlhb() {
        Log::info('START: LivePracticeController->closeHlhb');
        $oanda = new \App\Services\Oanda();
        $oanda->accountId = 3257917;

        $oanda->closeAllPositions();
        Log::info('END: LivePracticeController->closeHlhb');
    }

    public function twoLevelHmaDaily() {
        Log::info('HmaTwoTierDaily: START LivePracticeController->fiftyOneHundredEMACrossover');

        $strategy = new Strategy();
        //Need to Change
        $exchanges = \App\Model\Exchange::get();

        foreach ($exchanges as $exchange) {

            $logPrefix = "HmaTwoTierDaily-".$exchange->exchange."-".uniqid();

            $twoTierStrategy = new TwoTier('101-001-7608904-003', $logPrefix);

            /*** LOGGING ***/
            $strategyLogger = new StrategyLogger();
            $strategyLogger->exchange_id = $exchange->id;
            $strategyLogger->method = 'twoLevelHmaDaily';
            $strategyLogger->oanda_account_id = 2;

            $strategyLogger->newStrategyLog();
            $twoTierStrategy->setLogger($strategyLogger);

            if ($exchange->exchange == 'EUR_USD') {
                $twoTierStrategy->logDbRates = true;
            }

            $twoTierStrategy->exchange = $exchange;
            $twoTierStrategy->oanda->frequency = 'D';

            $twoTierStrategy->rateCount = 200;
            $twoTierStrategy->rates = $twoTierStrategy->getRates('both');

            $twoTierStrategy->positionMultiplier = 10;

            $twoTierStrategy->maxPositions = 3;
            $twoTierStrategy->stopLossPipAmount = 200;

            $twoTierStrategy->fastHullLength = 10;
            $twoTierStrategy->fastHullLinearRegressionLength = 2;
            $twoTierStrategy->fastHullLinearRegressionSlopeRequirement = 25;

            $twoTierStrategy->slowHullLength = 20;
            $twoTierStrategy->slowHullLinearRegressionLength = 3;
            $twoTierStrategy->slowHullLinearRegressionSlopeRequirement = 7;

            $twoTierStrategy->adxPeriodLength = 14;
            $twoTierStrategy->adxCutOffForDownwardSlope = 35;
            $twoTierStrategy->adxCutOffForUpwardSlope = 20;

            $twoTierStrategy->checkForNewPosition();
            $strategyLogger->logStrategyEnd();

        }
        Log::info('HmaTwoTierDaily: END');
    }

//    public function thirtyMinute() {
//        Log::info('30Min: START LivePracticeController->thirtyMinute');
//
//        //Need to Change
//        $exchanges = \App\Model\Exchange::get();
//
//        foreach ($exchanges as $exchange) {
//
//            $logPrefix = "30Min-".$exchange->exchange."-".uniqid();
//
//            $hmaAdxConfirmStrategy = new EmaMomentumDifferenceSlope('101-001-7608904-008', $logPrefix);
//
//            /*** LOGGING ***/
//            $strategyLogger = new StrategyLogger();
//            $strategyLogger->exchange_id = $exchange->id;
//            $strategyLogger->method = 'thirtyMinute';
//            $strategyLogger->oanda_account_id = 7;
//
//            $strategyLogger->newStrategyLog();
//            $hmaAdxConfirmStrategy->setLogger($strategyLogger);
//
//            if ($exchange->exchange == 'EUR_USD') {
//                $hmaAdxConfirmStrategy->logDbRates = true;
//            }
//
//            $hmaAdxConfirmStrategy->exchange = $exchange;
//            $hmaAdxConfirmStrategy->oanda->frequency = 'M30';
//
//            $hmaAdxConfirmStrategy->rateCount = 200;
//
//            $hmaAdxConfirmStrategy->rates = $hmaAdxConfirmStrategy->getRates('both');
//
//            $hmaAdxConfirmStrategy->positionMultiplier = 25;
//
//            $hmaAdxConfirmStrategy->maxPositions = 3;
//            $hmaAdxConfirmStrategy->stopLossPipAmount = 5;
//            $hmaAdxConfirmStrategy->takeProfitPipAmount = 20;
//
//            //Most Back Tests
//            $hmaAdxConfirmStrategy->stopLossPipAmount = 5;
//            $hmaAdxConfirmStrategy->takeProfitPipAmount = 50;
//
//            //Unique Strategy Variables
//            $hmaAdxConfirmStrategy->hmaFastLength = 5;
//            $hmaAdxConfirmStrategy->hmaSlowLength = 10;
//            $hmaAdxConfirmStrategy->adxCutoff = 20;
//            $hmaAdxConfirmStrategy->adxPeriodLength = 14;
//            $hmaAdxConfirmStrategy->slopeDiffLength = 3;
//
//            $hmaAdxConfirmStrategy->checkForNewPosition();
//            $strategyLogger->logStrategyEnd();
//        }
//        Log::info('HmaAdxStayInFourHour: END');
//    }



    public function hmaHourlyBeforeHour() {
        Log::info('hmaHourlyBeforeHour: START LivePracticeController->hmaHourlyAfterHour');

        //Need to Change
        $exchanges = \App\Model\Exchange::get();

        foreach ($exchanges as $exchange) {

            $logPrefix = "hmaHourlyBeforeHour-".$exchange->exchange."-".uniqid();

            $twoTierStrategy = new HmaStayInDifferentEntryExitAdx('101-001-7608904-011', $logPrefix);

            /*** LOGGING ***/
            $strategyLogger = new StrategyLogger();
            $strategyLogger->exchange_id = $exchange->id;
            $strategyLogger->method = 'hmaHourlyBeforeHour';
            $strategyLogger->oanda_account_id = 12;

            $strategyLogger->newStrategyLog();
            $twoTierStrategy->setLogger($strategyLogger);

            if ($exchange->exchange == 'EUR_USD') {
                $twoTierStrategy->logDbRates = true;
            }

            $twoTierStrategy->exchange = $exchange;
            $twoTierStrategy->oanda->frequency = 'H1';

            $twoTierStrategy->rateCount = 200;
            $twoTierStrategy->addCurrentPriceToRates = true;

            $twoTierStrategy->rates = $twoTierStrategy->getRates('both');

            $twoTierStrategy->positionMultiplier = 5;

            $twoTierStrategy->maxPositions = 3;
            $twoTierStrategy->stopLossPipAmount = 200;

            $twoTierStrategy->hullLength = 9;
            $twoTierStrategy->adxPeriodLength = 14;
            $twoTierStrategy->adxCutOff = 20;

            $twoTierStrategy->hmaLinRegCutoff = 10;
            $twoTierStrategy->hmaCloseLinRegCutoff = 8;

            $twoTierStrategy->orderType = 'LIMIT';

            $twoTierStrategy->checkForNewPosition();
            $strategyLogger->logStrategyEnd();
        }
        Log::info('hmaHourlyBeforeHour: END');
    }

//    public function fifteenEarly() {
//        Log::info('EmaMomentum15TPSL: START LivePracticeController->emaMomentumAdx15MinutesTPSL');
//
//        $strategy = new Strategy();
//        //Need to Change
//        $exchanges = \App\Model\Exchange::get();
//
//        $this->utility->waitUntilSeconds(30);
//
//        foreach ($exchanges as $exchange) {
//
//            $logPrefix = "EmaMomentum15TPSL-".$exchange->exchange."-".uniqid();
//
//            $fiftyOneHundredEma = new EmaMomentumAdxConfirm('101-001-7608904-009', $logPrefix);
//
//            /*** LOGGING ***/
//            $strategyLogger = new StrategyLogger();
//            $strategyLogger->exchange_id = $exchange->id;
//            $strategyLogger->method = 'fifteenEarly';
//            $strategyLogger->oanda_account_id = 8;
//
//            $strategyLogger->newStrategyLog();
//            $fiftyOneHundredEma->setLogger($strategyLogger);
//
//            if ($exchange->exchange == 'EUR_USD') {
//                $fiftyOneHundredEma->logDbRates = true;
//            }
//
//            $fiftyOneHundredEma->exchange = $exchange;
//            $fiftyOneHundredEma->oanda->frequency = 'M15';
//
//            $fiftyOneHundredEma->rateCount = 200;
//            $fiftyOneHundredEma->addCurrentPriceToRates = true;
//
//            $fiftyOneHundredEma->rates = $fiftyOneHundredEma->getRates('both');
//
//            $fiftyOneHundredEma->exchange = $exchange;
//            $fiftyOneHundredEma->strategyId = 5;
//            $fiftyOneHundredEma->strategyDesc = 'EmaMomentumFifteenMinutes';
//            $fiftyOneHundredEma->positionMultiplier = 5;
//
//            $fiftyOneHundredEma->maxPositions = 3;
//            $fiftyOneHundredEma->stopLossPipAmount = 5;
//            $fiftyOneHundredEma->takeProfitPipAmount = 50;
//
//            $fiftyOneHundredEma->fastEmaLength = 20;
//            $fiftyOneHundredEma->slowEmaLength = 35;
//
//            $fiftyOneHundredEma->adxCutoff = 20;
//            $fiftyOneHundredEma->adxPeriodLength = 14;
//
//            $fiftyOneHundredEma->linearRegressionLength = 3;
//
//            $fiftyOneHundredEma->checkForNewPosition();
//            $strategyLogger->logStrategyEnd();
//
//        }
//        Log::info('EmaMomentum15TPSL: END');
//    }

//    public function fifteenEmaFiveTenAfter() {
//        Log::info('fifteenEmaFiveTenAfter: START LivePracticeController->emaMomentumAdx15MinutesTPSL');
//
//        $strategy = new Strategy();
//        //Need to Change
//        $exchanges = \App\Model\Exchange::get();
//
//        foreach ($exchanges as $exchange) {
//
//            $logPrefix = "fifteenEmaFiveTenAfter-".$exchange->exchange."-".uniqid();
//
//            $fiftyOneHundredEma = new EmaMomentumDifferenceSlope('101-001-7608904-012', $logPrefix);
//
//            $strategyLogger = new StrategyLogger();
//            $strategyLogger->exchange_id = $exchange->id;
//            $strategyLogger->method = 'fifteenEmaFiveTenAfter';
//            $strategyLogger->oanda_account_id = 14;
//
//            $strategyLogger->newStrategyLog();
//            $fiftyOneHundredEma->setLogger($strategyLogger);
//
//            if ($exchange->exchange == 'EUR_USD') {
//                $fiftyOneHundredEma->logDbRates = true;
//            }v
//
//            $fiftyOneHundredEma->exchange = $exchange;
//            $fiftyOneHundredEma->oanda->frequency = 'M15';
//
//            $fiftyOneHundredEma->rateCount = 200;
//            $fiftyOneHundredEma->addCurrentPriceToRates = true;
//
//            $fiftyOneHundredEma->rates = $fiftyOneHundredEma->getRates('both');
//
//            $fiftyOneHundredEma->exchange = $exchange;
//            $fiftyOneHundredEma->strategyId = 5;
//            $fiftyOneHundredEma->strategyDesc = 'EmaMomentumFifteenMinutes';
//            $fiftyOneHundredEma->positionMultiplier = 10;
//
//            $fiftyOneHundredEma->maxPositions = 3;
//            $fiftyOneHundredEma->stopLossPipAmount = 15;
//            $fiftyOneHundredEma->takeProfitPipAmount = 30;
//
//            $fiftyOneHundredEma->fastEmaLength = 20;
//            $fiftyOneHundredEma->slowEmaLength = 35;
//
//            $fiftyOneHundredEma->adxCutoff = 20;
//            $fiftyOneHundredEma->adxPeriodLength = 14;
//
//            $fiftyOneHundredEma->slopeDiffLength = 3;
//            $fiftyOneHundredEma->slopeCutoff = 1.25;
//
//            $fiftyOneHundredEma->linearRegressionLength = 3;
//
//            $fiftyOneHundredEma->orderType = 'LIMIT';
//
//            $fiftyOneHundredEma->checkForNewPosition();
//            $strategyLogger->logStrategyEnd();
//
//        }
//        Log::info('fifteenEmaFiveTenAfter: END');
//    }

    public function fifteenEmaFiveTenBefore() {
        Log::info('fifteenEmaFiveTenBefore: START LivePracticeController->emaMomentumAdx15MinutesTPSL');

        $strategy = new Strategy();
        //Need to Change
        $exchanges = \App\Model\Exchange::get();

        $this->utility->waitUntilSeconds(48);

        foreach ($exchanges as $exchange) {

            $logPrefix = "fifteenEmaFiveTenBefore-".$exchange->exchange."-".uniqid();

            $fiftyOneHundredEma = new EmaMomentumDifferenceSlope('101-001-7608904-013', $logPrefix);

            $strategyLogger = new StrategyLogger();
            $strategyLogger->exchange_id = $exchange->id;
            $strategyLogger->method = 'fifteenEmaFiveTenBefore';
            $strategyLogger->oanda_account_id = 13;

            $strategyLogger->newStrategyLog();
            $fiftyOneHundredEma->setLogger($strategyLogger);

            if ($exchange->exchange == 'EUR_USD') {
                $fiftyOneHundredEma->logDbRates = true;
            }

            $fiftyOneHundredEma->exchange = $exchange;
            $fiftyOneHundredEma->oanda->frequency = 'M15';

            $fiftyOneHundredEma->rateCount = 200;
            $fiftyOneHundredEma->addCurrentPriceToRates = true;

            $fiftyOneHundredEma->rates = $fiftyOneHundredEma->getRates('both');

            $fiftyOneHundredEma->exchange = $exchange;
            $fiftyOneHundredEma->strategyId = 5;
            $fiftyOneHundredEma->strategyDesc = 'EmaMomentumFifteenMinutes';
            $fiftyOneHundredEma->positionMultiplier = 10;

            $fiftyOneHundredEma->maxPositions = 3;
            $fiftyOneHundredEma->stopLossPipAmount = 15;
            $fiftyOneHundredEma->takeProfitPipAmount = 30;

            $fiftyOneHundredEma->fastEmaLength = 20;
            $fiftyOneHundredEma->slowEmaLength = 35;

            $fiftyOneHundredEma->adxCutoff = 20;
            $fiftyOneHundredEma->adxPeriodLength = 14;

            $fiftyOneHundredEma->slopeDiffLength = 3;
            $fiftyOneHundredEma->slopeCutoff = 1.25;

            $fiftyOneHundredEma->linearRegressionLength = 3;

            $fiftyOneHundredEma->orderType = 'LIMIT';

            $fiftyOneHundredEma->checkForNewPosition();

            $strategyLogger->logStrategyEnd();
        }
        Log::info('fifteenEmaFiveTenBefore: END');
    }

//    public function hourlyStochPullback() {
//        Log::info('hourlyStochPullback: START LivePracticeController->hourlyStochPullback');
//
//        $strategy = new Strategy();
//        //Need to Change
//        $exchanges = \App\Model\Exchange::get();
//
//        foreach ($exchanges as $exchange) {
//
//            $logPrefix = "hourlyStochPullback-".$exchange->exchange."-".uniqid();
//
//            $systemStrategy = new SingleHmaMomentumTpSl('101-001-7608904-008', $logPrefix);
//
//            $strategyLogger = new StrategyLogger();
//            $strategyLogger->exchange_id = $exchange->id;
//            $strategyLogger->method = 'hourlyStochPullback';
//            $strategyLogger->oanda_account_id = 7;
//
//            $strategyLogger->newStrategyLog();
//            $systemStrategy->setLogger($strategyLogger);
//
//            if ($exchange->exchange == 'EUR_USD') {
//                $systemStrategy->logDbRates = true;
//            }
//
//            $systemStrategy->exchange = $exchange;
//            $systemStrategy->oanda->frequency = 'H1';
//
//            $systemStrategy->rateCount = 350;
//
//            $systemStrategy->rates = $systemStrategy->getRates('both');
//
//            $systemStrategy->exchange = $exchange;
//            $systemStrategy->strategyId = 5;
//            $systemStrategy->strategyDesc = 'hourlyStochPullback';
//            $systemStrategy->positionMultiplier = 45;
//
//            $systemStrategy->maxPositions = 3;
//            $systemStrategy->stopLossPipAmount = 10;
//            $systemStrategy->takeProfitPipAmount = 25;
//
//            //Specific Strategy Variables
//            $systemStrategy->hmaLength = 70;
//            $systemStrategy->hmaSlopeMin = 0;
//
//            $systemStrategy->kLength = 14;
//            $systemStrategy->smoothingSlow = 3;
//            $systemStrategy->smoothingFull = 3;
//
//            $systemStrategy->orderType = 'LIMIT';
//
//            $systemStrategy->checkForNewPosition();
//        }
//        Log::info('hourlyStochPullback: END');
//    }

    public function fifteenMinuteStochPullback() {
        Log::info('fifteenMinuteStochPullback: START LivePracticeController->fifteenMinuteStochPullback');

        $strategy = new Strategy();
        //Need to Change
        $exchanges = \App\Model\Exchange::get();

        foreach ($exchanges as $exchange) {

            $logPrefix = "fifteenMinuteStochPullback-".$exchange->exchange."-".uniqid();

            $systemStrategy = new SingleHmaMomentumTpSl('101-001-7608904-005', $logPrefix);

            $strategyLogger = new StrategyLogger();
            $strategyLogger->exchange_id = $exchange->id;
            $strategyLogger->method = 'fifteenMinuteStochPullback';
            $strategyLogger->oanda_account_id = 3;

            $strategyLogger->newStrategyLog();
            $systemStrategy->setLogger($strategyLogger);

            if ($exchange->exchange == 'EUR_USD') {
                $systemStrategy->logDbRates = true;
            }

            $systemStrategy->exchange = $exchange;
            $systemStrategy->oanda->frequency = 'M15';

            $systemStrategy->rateCount = 625;

            $systemStrategy->rates = $systemStrategy->getRates('both');
            $systemStrategy->setCurrentPrice();

            $systemStrategy->exchange = $exchange;
            $systemStrategy->strategyId = 5;
            $systemStrategy->strategyDesc = 'fifteenMinuteStochPullback';
            $systemStrategy->positionMultiplier = 15;

            $systemStrategy->maxPositions = 3;
            $systemStrategy->stopLossPipAmount = 10;
            $systemStrategy->takeProfitPipAmount = 20;

            //Specific Strategy Variables
            $systemStrategy->hmaLength = 125;
            $systemStrategy->hmaSlopeMin = 0;

            $systemStrategy->kLength = 25;
            $systemStrategy->smoothingSlow = 3;
            $systemStrategy->smoothingFull = 3;

            $systemStrategy->orderType = 'LIMIT';

            $systemStrategy->checkForNewPosition();
        }
        Log::info('hourlyStochPullback: END');
    }

    //StochFastOppositeSlow-Hourly 101-001-7608904-007
    public function hourStochFastOppositeSlow() {
        Log::info('hourStochFastOppositeSlow: START LivePracticeController->fifteenMinuteStochPullback');

        $strategy = new Strategy();
        //Need to Change
        $exchanges = \App\Model\Exchange::get();

        foreach ($exchanges as $exchange) {

            $logPrefix = "hourStochFastOppositeSlow-".$exchange->exchange."-".uniqid();

            $systemStrategy = new StochFastOppositeSlow('101-001-7608904-007', $logPrefix);

            $strategyLogger = new StrategyLogger();
            $strategyLogger->exchange_id = $exchange->id;
            $strategyLogger->method = 'StochFastOppositeSlow';
            $strategyLogger->oanda_account_id = 4;

            $strategyLogger->newStrategyLog();
            $systemStrategy->setLogger($strategyLogger);

            if ($exchange->exchange == 'EUR_USD') {
                $systemStrategy->logDbRates = true;
            }

            $systemStrategy->exchange = $exchange;
            $systemStrategy->oanda->frequency = 'H1';

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

//            $backTest->rateCount = intval($this->backTestToBeProcessed->variable_1)*10;
//            $backTest->rateIndicatorMin = intval($this->backTestToBeProcessed->variable_1)*5;

            $systemStrategy->orderType = 'LIMIT';

            $systemStrategy->checkForNewPosition();
        }
        Log::info('hourlyStochPullback: END');
    }

    public function testSandBoxAccount() {

        $strategy = new Strategy();
        //Need to Change
        $exchanges = \App\Model\Exchange::get();

        foreach ($exchanges as $exchange) {

            $logPrefix = "fifteenMinuteStochPullback-".$exchange->exchange."-".uniqid();

            $systemStrategy = new SingleHmaMomentumTpSl('101-001-7608904-002', $logPrefix);

            $strategyLogger = new StrategyLogger();
            $strategyLogger->exchange_id = $exchange->id;
            $strategyLogger->method = 'fifteenMinuteStochPullback';
            $strategyLogger->oanda_account_id = 3;

            $strategyLogger->newStrategyLog();
            $systemStrategy->setLogger($strategyLogger);

            if ($exchange->exchange == 'EUR_USD') {
                $systemStrategy->logDbRates = true;
            }

            $systemStrategy->exchange = $exchange;
            $systemStrategy->oanda->frequency = 'M15';

            $systemStrategy->rateCount = 625;

            $systemStrategy->rates = $systemStrategy->getRates('both');
            $systemStrategy->setCurrentPrice();

            $systemStrategy->exchange = $exchange;
            $systemStrategy->strategyId = 5;
            $systemStrategy->strategyDesc = 'fifteenMinuteStochPullback';
            $systemStrategy->positionMultiplier = 15;

            $systemStrategy->maxPositions = 3;
            $systemStrategy->stopLossPipAmount = 10;
            $systemStrategy->takeProfitPipAmount = 20;

            //Specific Strategy Variables
            $systemStrategy->hmaLength = 125;
            $systemStrategy->hmaSlopeMin = 0;

            $systemStrategy->kLength = 25;
            $systemStrategy->smoothingSlow = 3;
            $systemStrategy->smoothingFull = 3;

            $systemStrategy->orderType = 'LIMIT';

            $systemStrategy->newLongPosition();
        }
        Log::info('hourlyStochPullback: END');
    }

    public function emaXAdxConfirmWithMarketIfTouched() {
        //Back Test Group 206
        Log::info('emaXAdxConfirmWithMarketIfTouched: START LivePracticeController->emaXAdxConfirmWithMarketIfTouched');
        $this->utility->sleepUntilAtLeastFiveSeconds();

        $strategy = new EmaXAdxConfirmWithMarketIfTouched('101-001-7608904-008', 'initialload');

        $marginAvailable = $strategy->getAvailableMargin();

        //Need to Change
        $exchanges = \App\Model\Exchange::get();

        foreach ($exchanges as $exchange) {
            $logPrefix = "emaXAdxConfirmWithMarketIfTouched-".$exchange->exchange."-".uniqid();

            $systemStrategy = new EmaXAdxConfirmWithMarketIfTouched('101-001-7608904-008', $logPrefix);
            $systemStrategy->accountAvailableMargin = $marginAvailable;

            $strategyLogger = new StrategyLogger();
            $strategyLogger->exchange_id = $exchange->id;
            $strategyLogger->method = 'emaXAdxConfirmWithMarketIfTouched';
            $strategyLogger->oanda_account_id = 7;

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
            $systemStrategy->strategyDesc = 'emaXAdxConfirmWithMarketIfTouched';
            $systemStrategy->positionMultiplier = 5;

            $systemStrategy->maxPositions = 3;

            //Specific Strategy Variables
            $systemStrategy->fastEma = 75;
            $systemStrategy->slowEma = 150;
            $systemStrategy->trueRangeLength = 20;

            $systemStrategy->takeProfitTrueRangeMultiplier = 10;
            $systemStrategy->stopLossTrueRangeMultiplier = 2;

            $systemStrategy->orderType = 'MARKET_IF_TOUCHED';

            $systemStrategy->checkForNewPosition();
        }
        Log::info('hourlyStochPullback: END');
    }

    public function emaXAdxConfirmWithMarketIfTouchedHr() {
        Log::info('emaXAdxConfirmWithMarketIfTouched: START LivePracticeController->emaXAdxConfirmWithMarketIfTouched');
        $this->utility->sleepUntilAtLeastFiveSeconds();

        $strategy = new EmaXAdxConfirmWithMarketIfTouched('101-001-7608904-009', 'initialload');

        $marginAvailable = $strategy->getAvailableMargin();

        //Need to Change
        $exchanges = \App\Model\Exchange::get();

        foreach ($exchanges as $exchange) {
            $logPrefix = "emaXAdxConfirmWithMarketIfTouchedHr-".$exchange->exchange."-".uniqid();

            $systemStrategy = new EmaXAdxConfirmWithMarketIfTouched('101-001-7608904-009', $logPrefix);
            $systemStrategy->accountAvailableMargin = $marginAvailable;

            $strategyLogger = new StrategyLogger();
            $strategyLogger->exchange_id = $exchange->id;
            $strategyLogger->method = 'emaXAdxConfirmWithMarketIfTouched';
            $strategyLogger->oanda_account_id = 8;

            $strategyLogger->newStrategyLog();
            $systemStrategy->setLogger($strategyLogger);

            if ($exchange->exchange == 'EUR_USD') {
                $systemStrategy->logDbRates = true;
            }

            $systemStrategy->exchange = $exchange;
            $systemStrategy->oanda->frequency = 'H1';

            $systemStrategy->rateCount = 1000;

            $systemStrategy->rates = $systemStrategy->getRates('both', true);
            $systemStrategy->setCurrentPrice();

            $systemStrategy->exchange = $exchange;
            $systemStrategy->strategyId = 5;
            $systemStrategy->strategyDesc = 'emaXAdxConfirmWithMarketIfTouched';
            $systemStrategy->positionMultiplier = 5;

            $systemStrategy->maxPositions = 3;

            //Specific Strategy Variables
            $systemStrategy->fastEma = 50;
            $systemStrategy->slowEma = 100;
            $systemStrategy->trueRangeLength = 30;

            $systemStrategy->takeProfitTrueRangeMultiplier = 10;
            $systemStrategy->stopLossTrueRangeMultiplier = 2;

            $systemStrategy->orderType = 'MARKET_IF_TOUCHED';

            $systemStrategy->checkForNewPosition();
        }
        Log::info('hourlyStochPullback: END');
    }

    public function hmaFifteenMinutes() {

        $this->utility->sleepUntilAtLeastFiveSeconds();

        $strategy = new HmaSimple('101-001-7608904-007', 'initialload');

        $marginAvailable = $strategy->getAvailableMargin();

        //Need to Change
        $exchanges = \App\Model\Exchange::get();

        foreach ($exchanges as $exchange) {
            $logPrefix = "emaXAdxConfirmWithMarketIfTouchedHr-".$exchange->exchange."-".uniqid();

            $systemStrategy = new HmaSimple('101-001-7608904-007', $logPrefix);
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
            $systemStrategy->fastHma = 250;

            $systemStrategy->adxLength = 14;
            $systemStrategy->adxUndersoldThreshold = 20;

            $systemStrategy->takeProfitTrueRangeMultiplier = 10;
            $systemStrategy->stopLossTrueRangeMultiplier = 2;

            $systemStrategy->orderType = 'MARKET_IF_TOUCHED';

            $systemStrategy->checkForNewPosition();
        }
    }

    public function hmaThirty() {

        $this->utility->sleepUntilAtLeastFiveSeconds();

        $strategy = new HmaSimple('101-001-7608904-003', 'initialload');

        $marginAvailable = $strategy->getAvailableMargin();

        //Need to Change
        $exchanges = \App\Model\Exchange::get();

        foreach ($exchanges as $exchange) {
                $logPrefix = "hmaThirty-".$exchange->exchange."-".uniqid();

            $systemStrategy = new HmaSimple('101-001-7608904-003', $logPrefix);
            $systemStrategy->accountAvailableMargin = $marginAvailable;

            $strategyLogger = new StrategyLogger();
            $strategyLogger->exchange_id = $exchange->id;
            $strategyLogger->method = 'hmaThirty';
            $strategyLogger->oanda_account_id = 2;

            $strategyLogger->newStrategyLog();
            $systemStrategy->setLogger($strategyLogger);

            if ($exchange->exchange == 'EUR_USD') {
                $systemStrategy->logDbRates = true;
            }

            $systemStrategy->exchange = $exchange;
            $systemStrategy->oanda->frequency = 'M30';

            $systemStrategy->rateCount = 1000;

            $systemStrategy->rates = $systemStrategy->getRates('both', true);
            $systemStrategy->setCurrentPrice();

            $systemStrategy->exchange = $exchange;
            $systemStrategy->strategyId = 5;
            $systemStrategy->strategyDesc = 'hmaSimple';
            $systemStrategy->positionMultiplier = 5;

            $systemStrategy->maxPositions = 3;

            //Specific Strategy Variables
            $systemStrategy->fastHma = 125;

            $systemStrategy->adxLength = 14;
            $systemStrategy->adxUndersoldThreshold = 20;

            $systemStrategy->takeProfitTrueRangeMultiplier = 10;
            $systemStrategy->stopLossTrueRangeMultiplier = 2;

            $systemStrategy->orderType = 'MARKET_IF_TOUCHED';

            $systemStrategy->checkForNewPosition();
        }
    }

    public function hmaHour() {

        $this->utility->sleepUntilAtLeastFiveSeconds();

        $strategy = new HmaSimple('101-001-7608904-004', 'initialload');

        $marginAvailable = $strategy->getAvailableMargin();

        //Need to Change
        $exchanges = \App\Model\Exchange::get();

        foreach ($exchanges as $exchange) {
            $logPrefix = "hmaHour-".$exchange->exchange."-".uniqid();

            $systemStrategy = new HmaSimple('101-001-7608904-004', $logPrefix);
            $systemStrategy->accountAvailableMargin = $marginAvailable;

            $strategyLogger = new StrategyLogger();
            $strategyLogger->exchange_id = $exchange->id;
            $strategyLogger->method = 'hmaHour';
            $strategyLogger->oanda_account_id = 1;

            $strategyLogger->newStrategyLog();
            $systemStrategy->setLogger($strategyLogger);

            if ($exchange->exchange == 'EUR_USD') {
                $systemStrategy->logDbRates = true;
            }

            $systemStrategy->exchange = $exchange;
            $systemStrategy->oanda->frequency = 'H1';

            $systemStrategy->rateCount = 1000;

            $systemStrategy->rates = $systemStrategy->getRates('both', true);
            $systemStrategy->setCurrentPrice();

            $systemStrategy->exchange = $exchange;
            $systemStrategy->strategyId = 5;
            $systemStrategy->strategyDesc = 'hmaSimple';
            $systemStrategy->positionMultiplier = 5;

            $systemStrategy->maxPositions = 3;

            //Specific Strategy Variables
            $systemStrategy->fastHma = 50;

            $systemStrategy->adxLength = 14;
            $systemStrategy->adxUndersoldThreshold = 20;

            $systemStrategy->takeProfitTrueRangeMultiplier = 10;
            $systemStrategy->stopLossTrueRangeMultiplier = 2;

            $systemStrategy->orderType = 'MARKET_IF_TOUCHED';

            $systemStrategy->checkForNewPosition();
        }
    }

    public function dailyPreviousPriceBreakout() {

        $this->utility->sleepUntilAtLeastFiveSeconds();

        $strategy = new HighLowSuperSimpleHoldOnePeriod('101-001-7608904-013', 'initialload');

        $marginAvailable = $strategy->getAvailableMargin();

        //Need to Change
        $exchanges = \App\Model\Exchange::get();

        foreach ($exchanges as $exchange) {
            $logPrefix = "dailyPreviousPriceBreakout-".$exchange->exchange."-".uniqid();

            $systemStrategy = new HighLowSuperSimpleHoldOnePeriod('101-001-7608904-013', $logPrefix);
            $systemStrategy->accountAvailableMargin = $marginAvailable;
            $systemStrategy->stopLossPipAmount = 15;

            $strategyLogger = new StrategyLogger();
            $strategyLogger->exchange_id = $exchange->id;
            $strategyLogger->method = 'hmaHour';
            $strategyLogger->oanda_account_id = 13;

            $strategyLogger->newStrategyLog();
            $systemStrategy->setLogger($strategyLogger);

            if ($exchange->exchange == 'EUR_USD') {
                $systemStrategy->logDbRates = true;
            }

            $systemStrategy->exchange = $exchange;
            $systemStrategy->oanda->frequency = 'D';

            $systemStrategy->rateCount = 1000;

            $systemStrategy->orderType = 'MARKET_IF_TOUCHED';

            $systemStrategy->rates = $systemStrategy->getRates('both', true);
            $systemStrategy->setCurrentPrice();

            $systemStrategy->checkForNewPosition();
        }
    }

    public function dailyPreviousPriceBreakoutTpSl() {

        $this->utility->sleepUntilAtLeastFiveSeconds();

        $strategy = new HighLowSuperSimpleHoldOnePeriod('101-001-7608904-003', 'initialload');

        $marginAvailable = $strategy->getAvailableMargin();

        //Need to Change
        $exchanges = \App\Model\Exchange::get();

        foreach ($exchanges as $exchange) {
            $logPrefix = "dailyPreviousPriceBreakout-".$exchange->exchange."-".uniqid();

            $systemStrategy = new HighLowSuperSimpleHoldOnePeriod('101-001-7608904-003', $logPrefix);
            $systemStrategy->accountAvailableMargin = $marginAvailable;
            $systemStrategy->stopLossPipAmount = 15;
            $systemStrategy->takeProfitPipAmount = 25;

            $strategyLogger = new StrategyLogger();
            $strategyLogger->exchange_id = $exchange->id;
            $strategyLogger->method = 'hmaHour';
            $strategyLogger->oanda_account_id = 2;

            $strategyLogger->newStrategyLog();
            $systemStrategy->setLogger($strategyLogger);

            if ($exchange->exchange == 'EUR_USD') {
                $systemStrategy->logDbRates = true;
            }

            $systemStrategy->exchange = $exchange;
            $systemStrategy->oanda->frequency = 'D';

            $systemStrategy->rateCount = 1000;

            $systemStrategy->orderType = 'MARKET_IF_TOUCHED';

            $systemStrategy->rates = $systemStrategy->getRates('both', true);
            $systemStrategy->setCurrentPrice();

            $systemStrategy->checkForNewPosition();
        }
    }

    public function marketIfTouchedReturnToOpen() {

        $this->utility->sleepUntilAtLeastFiveSeconds();

        $strategy = new MarketIfTouchedReturnToOpen('101-001-7608904-011', 'initialload');
        $logger = new ProcessLogger('lp_return_open_mkt_touch');

        $marginAvailable = $strategy->getAvailableMargin();

        //Need to Change
        $exchanges = \App\Model\Exchange::get();

        foreach ($exchanges as $exchange) {
            $logger->logMessage('Starting Exchange '.$exchange->exchange);
            $logPrefix = "MarketIfTouchedReturnToOpen-".$exchange->exchange."-".uniqid();

            $systemStrategy = new MarketIfTouchedReturnToOpen('101-001-7608904-011', $logPrefix);
            $systemStrategy->accountAvailableMargin = $marginAvailable;

            $strategyLogger = new StrategyLogger();
            $strategyLogger->exchange_id = $exchange->id;
            $strategyLogger->method = 'marketIfTouchedReturnToOpen';
            $strategyLogger->oanda_account_id = 12;

            $strategyLogger->newStrategyLog();
            $systemStrategy->setLogger($strategyLogger);

            $systemStrategy->exchange = $exchange;
            $systemStrategy->oanda->frequency = 'D';

            $systemStrategy->rateCount = 1000;

            $systemStrategy->positionMultiplier = 3;

            $systemStrategy->orderType = 'MARKET_IF_TOUCHED';

            $systemStrategy->rates = $systemStrategy->getRates('both', true);
            $systemStrategy->setCurrentPrice();
            $logger->logMessage('Checking for New Position '.$exchange->exchange);

            $systemStrategy->checkForNewPosition();
        }
    }

    public function marketIfTouchedReturnToOpenTpSl() {

        $this->utility->sleepUntilAtLeastFiveSeconds();

        $strategy = new MarketIfTouchedReturnToOpen('101-001-7608904-004', 'initialload');
        $logger = new ProcessLogger('lp_return_open_mkt_touch');

        $marginAvailable = $strategy->getAvailableMargin();

        //Need to Change
        $exchanges = \App\Model\Exchange::get();

        foreach ($exchanges as $exchange) {
            $logger->logMessage('Starting Exchange '.$exchange->exchange);
            $logPrefix = "MarketIfTouchedReturnToOpen-".$exchange->exchange."-".uniqid();

            $systemStrategy = new MarketIfTouchedReturnToOpen('101-001-7608904-004', $logPrefix);
            $systemStrategy->accountAvailableMargin = $marginAvailable;

            $strategyLogger = new StrategyLogger();
            $strategyLogger->exchange_id = $exchange->id;
            $strategyLogger->method = 'marketIfTouchedReturnToOpen';
            $strategyLogger->oanda_account_id = 1;

            $strategyLogger->newStrategyLog();
            $systemStrategy->setLogger($strategyLogger);

            $systemStrategy->exchange = $exchange;
            $systemStrategy->oanda->frequency = 'D';

            $systemStrategy->rateCount = 1000;
            $systemStrategy->stopLossPipAmount = 15;
            $systemStrategy->takeProfitPipAmount = 25;

            $systemStrategy->positionMultiplier = 5;

            $systemStrategy->orderType = 'MARKET_IF_TOUCHED';

            $systemStrategy->rates = $systemStrategy->getRates('both', true);
            $systemStrategy->setCurrentPrice();
            $logger->logMessage('Checking for New Position '.$exchange->exchange);

            $systemStrategy->checkForNewPosition();
        }
    }

    public function amazingCrossoverTrailingStop() {

        $this->utility->sleepUntilAtLeastFiveSeconds();

        $strategy = new AmazingCrossoverTS('101-001-7608904-010', 'initialload');
        $logger = new ProcessLogger('lp_amazing_x_ts');

        $marginAvailable = $strategy->getAvailableMargin();

        //Need to Change
        $exchanges = \App\Model\Exchange::get();

        foreach ($exchanges as $exchange) {
            $logger->logMessage('Starting Exchange '.$exchange->exchange);
            $logPrefix = "amazingCrossoverTrailingStop-".$exchange->exchange."-".uniqid();

            $systemStrategy = new AmazingCrossoverTS('101-001-7608904-010', $logPrefix);
            $systemStrategy->accountAvailableMargin = $marginAvailable;

            $strategyLogger = new StrategyLogger();
            $strategyLogger->exchange_id = $exchange->id;
            $strategyLogger->method = 'amazingCrossoverTrailingStop';
            $strategyLogger->oanda_account_id = 6;

            $strategyLogger->newStrategyLog();
            $systemStrategy->setLogger($strategyLogger);

            $systemStrategy->exchange = $exchange;
            $systemStrategy->oanda->frequency = 'H1';

            $systemStrategy->rateCount = 1000;
            $systemStrategy->trailingStopPipAmount = 25;
            $systemStrategy->takeProfitPipAmount = 100;
            $systemStrategy->stopLossPipAmount = 25;

            $systemStrategy->positionMultiplier = 5;

            $systemStrategy->orderType = 'LIMIT';

            $systemStrategy->rates = $systemStrategy->getRates('both', true);
            $systemStrategy->setCurrentPrice();
            $logger->logMessage('Checking for New Position '.$exchange->exchange);

            $systemStrategy->checkForNewPosition();
        }
    }


    public function marketIfTouchedReturnToOpenHour() {

        $this->utility->sleepUntilAtLeastFiveSeconds();

        $strategy = new MarketIfTouchedReturnToOpen('101-001-7608904-014', 'initialload');
        $logger = new ProcessLogger('lp_return_open_mkt_touch');

        $marginAvailable = $strategy->getAvailableMargin();

        //Need to Change
        $exchanges = \App\Model\Exchange::get();

        foreach ($exchanges as $exchange) {
            $logger->logMessage('Starting Exchange '.$exchange->exchange);
            $logPrefix = "MarketIfTouchedReturnToOpen-".$exchange->exchange."-".uniqid();

            $systemStrategy = new MarketIfTouchedReturnToOpen('101-001-7608904-014', $logPrefix);
            $systemStrategy->accountAvailableMargin = $marginAvailable;

            $strategyLogger = new StrategyLogger();
            $strategyLogger->exchange_id = $exchange->id;
            $strategyLogger->method = 'marketIfTouchedReturnToOpen';
            $strategyLogger->oanda_account_id = 17;

            $strategyLogger->newStrategyLog();
            $systemStrategy->setLogger($strategyLogger);

            $systemStrategy->exchange = $exchange;
            $systemStrategy->oanda->frequency = 'H1';

            $systemStrategy->rateCount = 1000;

            $systemStrategy->positionMultiplier = 5;
            $systemStrategy->stopLossPipAmount = 5;

            $systemStrategy->orderType = 'MARKET_IF_TOUCHED';

            $systemStrategy->rates = $systemStrategy->getRates('both', true);
            $systemStrategy->setCurrentPrice();
            $logger->logMessage('Checking for New Position '.$exchange->exchange);

            $systemStrategy->checkForNewPosition();
        }
    }

    public function hma4HSetHoldPeriods() {
        $this->utility->sleepUntilAtLeastFiveSeconds();

        $strategy = new HmaRevAfterPeriodsHold('101-001-7608904-015', 'initialload');
        $logger = new ProcessLogger('lp_hma4h_period_hold');

        $marginAvailable = $strategy->getAvailableMargin();

        //Need to Change
        $exchanges = \App\Model\Exchange::get();

        foreach ($exchanges as $exchange) {
            $logger->logMessage('Starting Exchange '.$exchange->exchange);
            $logPrefix = "hma4HSetHoldPeriods-".$exchange->exchange."-".uniqid();

            $systemStrategy = new HmaRevAfterPeriodsHold('101-001-7608904-015', $logPrefix);
            $systemStrategy->accountAvailableMargin = $marginAvailable;

            $strategyLogger = new StrategyLogger();
            $strategyLogger->exchange_id = $exchange->id;
            $strategyLogger->method = 'hma4HSetHoldPeriods';
            $strategyLogger->oanda_account_id = 18;

            $strategyLogger->newStrategyLog();
            $systemStrategy->setLogger($strategyLogger);

            $systemStrategy->exchange = $exchange;
            $systemStrategy->oanda->frequency = 'H4';

            $systemStrategy->rateCount = 1000;
            $systemStrategy->stopLossPipAmount = 100;
            $systemStrategy->hmaLength = 5;
            $systemStrategy->hmaChangeDirPeriods = 2;
            $systemStrategy->periodsOpenMultiplier = 4;
            $systemStrategy->hmaSlopeMin = 0;

            $systemStrategy->positionMultiplier = 5;
            $systemStrategy->orderType = 'LIMIT';

            $systemStrategy->rates = $systemStrategy->getRates('both', true);
            $systemStrategy->setCurrentPrice();
            $logger->logMessage('Checking for New Position '.$exchange->exchange);

            $systemStrategy->checkForNewPosition();
        }
    }


    public function priceBreakoutHourly() {

        $this->utility->sleepUntilAtLeastFiveSeconds();

        $strategy = new HighLowSuperSimpleHoldOnePeriod('101-001-7608904-012', 'initialload');

        $marginAvailable = $strategy->getAvailableMargin();

        //Need to Change
        $exchanges = \App\Model\Exchange::get();

        foreach ($exchanges as $exchange) {
            $logPrefix = "priceBreakoutHourly-".$exchange->exchange."-".uniqid();

            $systemStrategy = new HighLowSuperSimpleHoldOnePeriod('101-001-7608904-012', $logPrefix);
            $systemStrategy->accountAvailableMargin = $marginAvailable;
            $systemStrategy->trailingStopPipAmount = 10;

            $strategyLogger = new StrategyLogger();
            $strategyLogger->exchange_id = $exchange->id;
            $strategyLogger->method = 'priceBreakoutHourly';
            $strategyLogger->oanda_account_id = 14;

            $strategyLogger->newStrategyLog();
            $systemStrategy->setLogger($strategyLogger);

            $systemStrategy->exchange = $exchange;
            $systemStrategy->oanda->frequency = 'H1';

            $systemStrategy->rateCount = 1000;

            $systemStrategy->orderType = 'MARKET_IF_TOUCHED';

            $systemStrategy->rates = $systemStrategy->getRates('both', true);
            $systemStrategy->setCurrentPrice();

            $systemStrategy->checkForNewPosition();
        }
    }


    public function weeklyPriceBreakout() {

        $strategy = new HighLowSuperSimpleHoldOnePeriod('101-001-7608904-017', 'initialload');

        $marginAvailable = $strategy->getAvailableMargin();

        //Need to Change
        $exchanges = \App\Model\Exchange::get();

        foreach ($exchanges as $exchange) {
            $logPrefix = "priceBreakoutWeekly-".$exchange->exchange."-".uniqid();

            $systemStrategy = new HighLowSuperSimpleHoldOnePeriod('101-001-7608904-017', $logPrefix);
            $systemStrategy->accountAvailableMargin = $marginAvailable;
            $systemStrategy->trailingStopPipAmount = 10;

            $strategyLogger = new StrategyLogger();
            $strategyLogger->exchange_id = $exchange->id;
            $strategyLogger->method = 'priceBreakoutWeekly';
            $strategyLogger->oanda_account_id = 20;
            $systemStrategy->limitEndSeconds = 340000;

            $strategyLogger->newStrategyLog();
            $systemStrategy->setLogger($strategyLogger);

            $systemStrategy->exchange = $exchange;
            $systemStrategy->oanda->frequency = 'W';
            $systemStrategy->stopLossPipAmount = 100;

            $systemStrategy->rateCount = 1000;

            $systemStrategy->orderType = 'MARKET_IF_TOUCHED';

            $systemStrategy->rates = $systemStrategy->getRates('both', true);
            $systemStrategy->setCurrentPrice();

            $systemStrategy->checkForNewPosition();
        }
    }

    public function hourlyRatesCheck() {
        $strategy = new HmaRevAfterPeriodsHold('101-001-7608904-015', 'initialload');

        $exchanges = \App\Model\Exchange::get();

        $exchange = $exchanges[0];

        $strategy->exchange = $exchange;
        $strategy->oanda->frequency = 'H1';

        $ratesCount = 0;

        $startTime = time();

        $strategyLogger = new StrategyLogger();
        $strategyLogger->exchange_id = $exchange->id;
        $strategyLogger->method = 'priceBreakoutHourly';
        $strategyLogger->oanda_account_id = 14;

        $strategy->strategyLogger = $strategyLogger;

        while ((time() - $startTime) < 180) {
            $rates = $strategy->getRates('both', true);

            $lastRate = end($rates['full']);

            $testDayRate = new \App\Model\DailyRatesDebug();

            $testDayRate->low_mid = $lastRate->lowMid;
            $testDayRate->open_mid = $lastRate->openMid;
            $testDayRate->high_mid = $lastRate->highMid;
            $testDayRate->close_mid = $lastRate->closeMid;

            $testDayRate->rate_date_time = $lastRate->dateTime;
            $testDayRate->volume = $lastRate->volume;

            $testDayRate->save();

            $ratesCount = $ratesCount + 1;
            sleep(5);
        }
    }

    public function closeWeeklyAccounts() {
        $weeklyAccounts = [19, 20];

        foreach ($weeklyAccounts as $weeklyAccount) {
            $account = OandaAccounts::find($weeklyAccount);

            $exchanges = \App\Model\Exchange::get();

            foreach ($exchanges as $exchange) {
                $logPrefix = "priceBreakoutWeekly-".$exchange->exchange."-".uniqid();

                $systemStrategy = new HighLowSuperSimpleHoldOnePeriod($account->oanda_id, $logPrefix);

                $strategyLogger = new StrategyLogger();
                $strategyLogger->exchange_id = $exchange->id;
                $strategyLogger->method = 'WeeklyCloseouts';
                $strategyLogger->oanda_account_id = $weeklyAccount;

                $systemStrategy->strategyLogger = $strategyLogger;
                $systemStrategy->oanda->strategyLogger = $strategyLogger;

                $systemStrategy->exchange = $exchange;

                $systemStrategy->closeIfOpen();
            }
        }
    }

    public function marketIfTouchedReturnToOpenWeekly() {

        $strategy = new MarketIfTouchedReturnToOpen('101-001-7608904-016', 'initialload');

        $marginAvailable = $strategy->getAvailableMargin();

        //Need to Change
        $exchanges = \App\Model\Exchange::get();

        foreach ($exchanges as $exchange) {
            $logPrefix = "MarketIfTouchedReturnToOpen-".$exchange->exchange."-".uniqid();

            $systemStrategy = new MarketIfTouchedReturnToOpen('101-001-7608904-016', $logPrefix);
            $systemStrategy->accountAvailableMargin = $marginAvailable;

            $strategyLogger = new StrategyLogger();
            $strategyLogger->exchange_id = $exchange->id;
            $strategyLogger->method = 'marketIfTouchedReturnToOpen';
            $strategyLogger->oanda_account_id = 19;

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

    public function marketIfTouchedHighLowDaily() {

        $this->utility->sleepUntilAtLeastFiveSeconds();

        $strategy = new MarketIfTouchedReturnToOpen('101-001-7608904-011', 'initialload');
        $logger = new ProcessLogger('lp_return_open_mkt_touch');

        $marginAvailable = $strategy->getAvailableMargin();

        //Need to Change
        $exchanges = \App\Model\Exchange::get();

        foreach ($exchanges as $exchange) {
            $logger->logMessage('Starting Exchange '.$exchange->exchange);
            $logPrefix = "MarketIfTouchedReturnToOpen-".$exchange->exchange."-".uniqid();

            $systemStrategy = new MarketIfTouchedReturnToOpen('101-001-7608904-011', $logPrefix);
            $systemStrategy->accountAvailableMargin = $marginAvailable;

            $strategyLogger = new StrategyLogger();
            $strategyLogger->exchange_id = $exchange->id;
            $strategyLogger->method = 'marketIfTouchedReturnToOpen';
            $strategyLogger->oanda_account_id = 12;

            $strategyLogger->newStrategyLog();
            $systemStrategy->setLogger($strategyLogger);

            $systemStrategy->exchange = $exchange;
            $systemStrategy->oanda->frequency = 'D';

            $systemStrategy->rateCount = 1000;

            $systemStrategy->positionMultiplier = 3;

            $systemStrategy->orderType = 'MARKET_IF_TOUCHED';

            $systemStrategy->rates = $systemStrategy->getRates('both', true);
            $systemStrategy->setCurrentPrice();
            $logger->logMessage('Checking for New Position '.$exchange->exchange);

            $systemStrategy->checkForNewPosition();
        }
    }

    //
}