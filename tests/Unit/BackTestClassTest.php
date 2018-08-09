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

class BackTestClassTest extends TestCase
{

    public $frequencyId;
    public $currencyId;

    public $backtest;


    public function setUpStandardBackTestStrategy() {
        $this->backtest = new TakeProfitStopLossTest('abc');
        $this->backtest->strategy = new EmaXAdxConfirmWithMarketIfTouched(123, 123);

        $this->backtest->strategy->optionalTrailingStopAmount = 10;
        $this->backtest->strategy->takeProfitPipAmount = 20;
        $this->backtest->exchange = new \stdClass();
        $this->backtest->exchange->pip = .0001;
    }

    public function setCurrentPriceData($price) {
        $priceData = new \stdClass();
        $priceData->ask = $price;
        $priceData->bid = $price;
        $priceData->high = $price + .0005;
        $priceData->open = $price;
        $priceData->low = $price - .0005;
        $priceData->id = 1;
        $priceData->dateTime = '2015-12-10T12:00:00+00:00';
        $priceData->rateUnixTime = '2015-12-10T12:00:00+00:00';
        $priceData->instrument = 'EUR_USD';

        $this->backtest->currentPriceData = $priceData;
    }

    public function setPositionLong() {
        $this->backtest->strategy->backTestCurrentPosition = 'long';

        $this->backtest->strategy->backTestPositions[] = [
            "amount" =>   1.2500,
            "positionType" =>   "long",
            "takeProfit" =>   1.2520,
            "stopLoss" =>   1.249,
            "dateTime" => '2015-12-10T12:00:00+00:00'
        ];
    }

    public function setPositionShort() {
        $this->backtest->strategy->backTestCurrentPosition = 'short';

        $this->backtest->strategy->backTestPositions[] = [
            "amount" =>   1.2500,
            "positionType" =>   "short",
            "takeProfit" =>   1.248,
            "stopLoss" =>   1.2510,
            "dateTime" => '2015-12-10T12:00:00+00:00'
        ];
    }

    public function testCheckStopLoss() {
        $this->setUpStandardBackTestStrategy();

        //Long When Nothing Should Happen
        $this->setCurrentPriceData(1.26);
        $this->setPositionLong();

        $this->backtest->checkStopLoss();

        $lastPosition = $this->backtest->getLastPosition();

        $this->assertFalse(isset($lastPosition['profitLoss']));

        //Long With Loss
        $this->setCurrentPriceData(1.248);
        $this->setPositionLong();

        $this->backtest->checkStopLoss();

        $lastPosition = $this->backtest->getLastPosition();

        $this->assertEquals(-.001, $lastPosition['profitLoss']);

        //Short When Nothing Should Happen
        $this->setCurrentPriceData(1.248);
        $this->setPositionShort();

        $this->backtest->checkStopLoss();

        $lastPosition = $this->backtest->getLastPosition();

        $this->assertFalse(isset($lastPosition['profitLoss']));

        //Short With Loss

        $this->setPositionShort();
        $this->setCurrentPriceData(1.26);
        $this->backtest->checkStopLoss();

        $lastPosition = $this->backtest->getLastPosition();

        $this->assertEquals(-.001, $lastPosition['profitLoss']);
    }

    public function testProcessShortTrailingStop() {
        $this->setUpStandardBackTestStrategy();

        $this->backtest->strategy->backTestTrailingStop = true;

        //Long
        $this->setCurrentPriceData(1.2515);
        $this->setPositionLong();

        $this->backtest->checkTrailingStop();

        $this->setCurrentPriceData(1.2516);

        $this->backtest->checkTrailingStop();

        $lastPosition = $this->backtest->getLastPosition();

        $this->assertFalse(isset($lastPosition['profitLoss']));

        $this->setCurrentPriceData(1.2514);

        $this->backtest->checkTrailingStop();

        $lastPosition = $this->backtest->getLastPosition();

        $this->assertEquals(.001, $lastPosition['profitLoss']);

        //Short
        $this->setCurrentPriceData(1.2488);
        $this->setPositionShort();
        $this->backtest->strategy->backTestTrailingStop = true;

        $this->backtest->checkTrailingStop();

        $this->setCurrentPriceData(1.2487);

        $this->backtest->checkTrailingStop();

        $lastPosition = $this->backtest->getLastPosition();

        $this->assertFalse(isset($lastPosition['profitLoss']));

        $this->setCurrentPriceData(1.255);

        $this->backtest->checkTrailingStop();

        $lastPosition = $this->backtest->getLastPosition();

        $this->assertEquals(.0007, $lastPosition['profitLoss']);
    }

    public function testCheckTakeProfit() {
        $this->setUpStandardBackTestStrategy();
        //Long
        $this->setCurrentPriceData(1.2508);
        $this->setPositionLong();

        $this->backtest->checkTakeProfit();

        $lastPosition = $this->backtest->getLastPosition();

        $this->assertFalse(isset($lastPosition['profitLoss']));

        $this->setCurrentPriceData(1.2522);

        $this->backtest->checkTakeProfit();

        $lastPosition = $this->backtest->getLastPosition();

        $this->assertEquals(.002, $lastPosition['profitLoss']);


        $this->setPositionShort();

        $this->setCurrentPriceData(1.2495);

        $this->backtest->checkTakeProfit();

        $lastPosition = $this->backtest->getLastPosition();

        $this->assertFalse(isset($lastPosition['profitLoss']));

        $this->setCurrentPriceData(1.2479);

        $this->backtest->checkTakeProfit();

        $lastPosition = $this->backtest->getLastPosition();

        $this->assertEquals(.002, $lastPosition['profitLoss']);
    }

    public function getNextRate($previousPriceData, $frequencyId, $currencyId) {
        $nextRateId = HistoricalRates::where('frequency_id', '=', $frequencyId)->where('currency_id', '=', $currencyId)->where('rate_unix_time', '>', $previousPriceData->rateUnixTime)->min('id');
        return HistoricalRates::find($nextRateId);
    }

//    public function testGetRatesLooping()
//    {
//        ini_set('memory_limit', '-1');
//
//        $backtest = new IndicatorRunThroughTest('abc');
//        $backtest->strategy = new EmaXAdxConfirmWithMarketIfTouched(123, 123);
//        $backtest->strategy->backtesting = true;
//        $backtest->rateUnixStart = 1507769138;
//        $backtest->rateIndicatorMin = 200;
//        $backtest->currencyId = 1;
//        $backtest->frequencyId = 1;
//
//        $fullExchange = Exchange::find(1);
//
//        $backtest->exchange = $fullExchange;
//        $backtest->frequencyId = 1;
//
//        $previousPriceData = false;
//        $backtest->setLastId();
//
//        $backtest->rateIndex = $backtest->rateIndicatorMin - 1;
//
//        $backtest->getInitialRates();
//
//        $backtest->currentRatesProcessed = $backtest->rateCount;
//
//        while (!$backtest->lastIdCheck()) {
//            $backtest->startNewPeriod();
//
//            if ($previousPriceData) {
//                $dbNextRate = $this->getNextRate($previousPriceData, $backtest->frequencyId, $backtest->currencyId);
//
//                $this->assertEquals($dbNextRate->id, $backtest->currentPriceData->id);
//                $this->assertEquals($dbNextRate->open_mid, $backtest->currentPriceData->ask);
//
//                if (isset($previousDbRate)) {
//                    $this->assertEquals($previousDbRate->close_mid, end($backtest->currentRates));
//                }
//                $previousDbRate = $dbNextRate;
//            }
//            $previousPriceData = $backtest->currentPriceData;
//        }
//    }
}
