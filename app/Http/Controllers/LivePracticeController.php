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

class LivePracticeController extends Controller {

    public $macdShortPeriod;
    public $macdLongPeriod;

    public $macdIndicatorPeriod;

    public $rsiPeriods;
    public $rsiUpperCutoff;
    public $rsiLowerCutoff;

    public $bollingerPeriods;

    public $currentPositionStatus;

    public $currentLongPrice;
    public $currentShortPrice;

    public $currentLoss;

    public $accountValue;

    public $smaShortPeriod;
    public $smaLongPeriod;

    public $rateOfChangePeriod;

    public $transactions;

    public $lossCutoff;

    public $currentShortMin;
    public $currentLongMax;

    public $fivePeriodsSincePositionRate;
    public $tenPeriodsSincePositionRate;

    public $maxGain;

    public $periodsSincePosition;
    public $biggestGainPeriodSince;

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

    public function thirtyMinute() {
        Log::info('30Min: START LivePracticeController->thirtyMinute');

        //Need to Change
        $exchanges = \App\Model\Exchange::get();

        foreach ($exchanges as $exchange) {

            $logPrefix = "30Min-".$exchange->exchange."-".uniqid();

            $hmaAdxConfirmStrategy = new EmaMomentumDifferenceSlope('101-001-7608904-008', $logPrefix);

            /*** LOGGING ***/
            $strategyLogger = new StrategyLogger();
            $strategyLogger->exchange_id = $exchange->id;
            $strategyLogger->method = 'thirtyMinute';
            $strategyLogger->oanda_account_id = 7;

            $strategyLogger->newStrategyLog();
            $hmaAdxConfirmStrategy->setLogger($strategyLogger);

            if ($exchange->exchange == 'EUR_USD') {
                $hmaAdxConfirmStrategy->logDbRates = true;
            }

            $hmaAdxConfirmStrategy->exchange = $exchange;
            $hmaAdxConfirmStrategy->oanda->frequency = 'M30';

            $hmaAdxConfirmStrategy->rateCount = 200;

            $hmaAdxConfirmStrategy->rates = $hmaAdxConfirmStrategy->getRates('both');

            $hmaAdxConfirmStrategy->positionMultiplier = 25;

            $hmaAdxConfirmStrategy->maxPositions = 3;
            $hmaAdxConfirmStrategy->stopLossPipAmount = 5;
            $hmaAdxConfirmStrategy->takeProfitPipAmount = 20;

            //Most Back Tests
            $hmaAdxConfirmStrategy->stopLossPipAmount = 5;
            $hmaAdxConfirmStrategy->takeProfitPipAmount = 50;

            //Unique Strategy Variables
            $hmaAdxConfirmStrategy->hmaFastLength = 5;
            $hmaAdxConfirmStrategy->hmaSlowLength = 10;
            $hmaAdxConfirmStrategy->adxCutoff = 20;
            $hmaAdxConfirmStrategy->adxPeriodLength = 14;
            $hmaAdxConfirmStrategy->slopeDiffLength = 3;

            $hmaAdxConfirmStrategy->checkForNewPosition();
            $strategyLogger->logStrategyEnd();
        }
        Log::info('HmaAdxStayInFourHour: END');
    }


    public function hmaHourlyAfterHour() {
        Log::info('hmaHourlyAfterHour: START LivePracticeController->hmaHourlyAfterHour');

        //Need to Change
        $exchanges = \App\Model\Exchange::get();

        foreach ($exchanges as $exchange) {

            $logPrefix = "hmaHourlyAfterHour-".$exchange->exchange."-".uniqid();

            $twoTierStrategy = new HmaStayInDifferentEntryExitAdx('101-001-7608904-010', $logPrefix);

            /*** LOGGING ***/
            $strategyLogger = new StrategyLogger();
            $strategyLogger->exchange_id = $exchange->id;
            $strategyLogger->method = 'hmaHourlyAfterHour';
            $strategyLogger->oanda_account_id = 6;

            $strategyLogger->newStrategyLog();
            $twoTierStrategy->setLogger($strategyLogger);

            if ($exchange->exchange == 'EUR_USD') {
                $twoTierStrategy->logDbRates = true;
            }

            $twoTierStrategy->exchange = $exchange;
            $twoTierStrategy->oanda->frequency = 'H1';

            $twoTierStrategy->rateCount = 200;
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
        Log::info('hmaHourlyAfterHour: END');
    }


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

    public function fifteenEmaFiveTenAfter() {
        Log::info('fifteenEmaFiveTenAfter: START LivePracticeController->emaMomentumAdx15MinutesTPSL');

        $strategy = new Strategy();
        //Need to Change
        $exchanges = \App\Model\Exchange::get();

        foreach ($exchanges as $exchange) {

            $logPrefix = "fifteenEmaFiveTenAfter-".$exchange->exchange."-".uniqid();

            $fiftyOneHundredEma = new EmaMomentumDifferenceSlope('101-001-7608904-012', $logPrefix);

            $strategyLogger = new StrategyLogger();
            $strategyLogger->exchange_id = $exchange->id;
            $strategyLogger->method = 'fifteenEmaFiveTenAfter';
            $strategyLogger->oanda_account_id = 14;

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
        Log::info('fifteenEmaFiveTenAfter: END');
    }

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
        Log::info('emaXAdxConfirmWithMarketIfTouched: START LivePracticeController->emaXAdxConfirmWithMarketIfTouched');

        $strategy = new Strategy();
        //Need to Change
        $exchanges = \App\Model\Exchange::get();

        foreach ($exchanges as $exchange) {
            $logPrefix = "emaXAdxConfirmWithMarketIfTouched-".$exchange->exchange."-".uniqid();

            $systemStrategy = new EmaXAdxConfirmWithMarketIfTouched('101-001-7608904-008', $logPrefix);

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
            $systemStrategy->oanda->frequency = 'H1';

            $systemStrategy->rateCount = 1000;

            $systemStrategy->rates = $systemStrategy->getRates('both');
            $systemStrategy->setCurrentPrice();

            $systemStrategy->exchange = $exchange;
            $systemStrategy->strategyId = 5;
            $systemStrategy->strategyDesc = 'emaXAdxConfirmWithMarketIfTouched';
            $systemStrategy->positionMultiplier = 5;

            $systemStrategy->maxPositions = 3;

            //Specific Strategy Variables
            $systemStrategy->fastEma = 5;
            $systemStrategy->slowEma = 10;

            $systemStrategy->takeProfitTrueRangeMultiplier = 3;
            $systemStrategy->stopLossTrueRangeMultiplier = 2;

            $systemStrategy->orderType = 'MARKET_IF_TOUCHED';

            $systemStrategy->checkForNewPosition();
        }
        Log::info('hourlyStochPullback: END');
    }
}