<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Model\Exchange;
use App\Services\BackTest;
use App\BackTest\TakeProfitStopLossTest;
use App\BackTest\IndicatorRunThroughTest;
use \App\Model\HistoricalRates;
use Tests\TestCase;

use \App\Strategy\EmaMomentum\EmaSimpleMomentum;

class BackTestClassTest extends TestCase
{

    public $rates = [1.12154,
        1.12151,
        1.12163,
        1.12186,
        1.12187,
        1.12184,
        1.12203,
        1.12223,
        1.12236,
        1.12213,
        1.12187,
        1.12200,
        1.12193,
        1.12200,
        1.12204,
        1.12218,
        1.12233,
        1.12226,
        1.12226,
        1.12200,
        1.12186,
        1.12165,
        1.12184,
        1.12161,
        1.12159,
        1.12131,
        1.12081,
        1.12082,
        1.12054,
        1.12102,
        1.12163,
        1.12138,
        1.12232,
        1.12443,
        1.12432,
        1.12440,
        1.12444,
        1.12418,
        1.12428,
        1.12292,
        1.12298,
        1.12355,
        1.12317,
        1.12262,
        1.12156,
        1.12113,
        1.12100,
        1.12025,
        1.12030,
        1.12074];

    public $frequencyId;
    public $currencyId;


    public function testCheckTakeProfitStopLossPositionCloseStopLossLong() {
        $backtest = new TakeProfitStopLossTest('abc');
        $backtest->strategy = new EmaSimpleMomentum(123, 123);

        $backtest->currentPriceData = json_decode('{"ask":"1.08779","bid":"1.08779","high":"1.08106","low":"1.08713","id":173209,"dateTime":"2015-12-04T08:15:00.000000Z","instrument":"EUR_USD"}');

        $backtest->strategy->backTestPositions = [];

        $backtest->strategy->backTestPositions = [['amount' => '1.08745','positionType' => 'long','takeProfit' => '1.08245','stopLoss' => '1.08725','dateTime' => '2015-12-04T08:00:00.000000Z','highestRate' => '1.08745','lowestRate' => '1.08745','highestRateDate' => '2015-12-04T08:00:00.000000Z','lowestRateDate' => '2015-12-04T08:00:00.000000Z']];

        //dd($backtest->strategy->backTestPositions);

        $loss = 1.08725 - 1.08745;

        $backtest->checkTakeProfitStopLossPositionClose();

        $transactionGainLoss = $backtest->strategy->backTestPositions[0]['profitLoss'];

        $this->assertEquals($loss, $transactionGainLoss);
    }


    public function testCheckTakeProfitStopLossPositionCloseTakeProfitLong() {
        $backtest = new TakeProfitStopLossTest('abc');
        $backtest->strategy = new EmaSimpleMomentum(123, 123);

        $backtest->currentPriceData = json_decode('{"ask":"1.08779","bid":"1.08779","high":"1.09945","low":"1.08726","id":173209,"dateTime":"2015-12-04T08:15:00.000000Z","instrument":"EUR_USD"}');

        $backtest->strategy->backTestPositions = [];

        $backtest->strategy->backTestPositions = [['amount' => '1.08745','positionType' => 'long','takeProfit' => '1.09245','stopLoss' => '1.08725','dateTime' => '2015-12-04T08:00:00.000000Z','highestRate' => '1.08745','lowestRate' => '1.08745','highestRateDate' => '2015-12-04T08:00:00.000000Z','lowestRateDate' => '2015-12-04T08:00:00.000000Z']];

        //dd($backtest->strategy->backTestPositions);

        $gain = 1.09245 - 1.08745;

        $backtest->checkTakeProfitStopLossPositionClose();

        $transactionGainLoss = $backtest->strategy->backTestPositions[0]['profitLoss'];

        $this->assertEquals($gain, $transactionGainLoss);
    }


    public function testCheckTakeProfitStopLossPositionCloseStopLossShort() {
        $backtest = new TakeProfitStopLossTest('abc');
        $backtest->strategy = new EmaSimpleMomentum(123, 123);

        $backtest->currentPriceData = json_decode('{"ask":"1.08779","bid":"1.08779","high":"1.2","low":"1.08713","id":173209,"dateTime":"2015-12-04T08:15:00.000000Z","instrument":"EUR_USD"}');

        $backtest->strategy->backTestPositions = [];

        $backtest->strategy->backTestPositions = [['amount' => '1.08745','positionType' => 'short','takeProfit' => '1.08245','stopLoss' => '1.08945','dateTime' => '2015-12-04T08:00:00.000000Z','highestRate' => '1.08745','lowestRate' => '1.08745','highestRateDate' => '2015-12-04T08:00:00.000000Z','lowestRateDate' => '2015-12-04T08:00:00.000000Z']];

        //dd($backtest->strategy->backTestPositions);

        $loss = 1.08745 - 1.08945;

        $backtest->checkTakeProfitStopLossPositionClose();

        $transactionGainLoss = $backtest->strategy->backTestPositions[0]['profitLoss'];

        $this->assertEquals($loss, $transactionGainLoss);
    }


    public function testCheckTakeProfitStopLossPositionCloseTakeProfitShort() {
        $backtest = new TakeProfitStopLossTest('abc');
        $backtest->strategy = new EmaSimpleMomentum(123, 123);

        $backtest->currentPriceData = json_decode('{"ask":"1.08779","bid":"1.08779","high":"1.00","low":"1.08","id":173209,"dateTime":"2015-12-04T08:15:00.000000Z","instrument":"EUR_USD"}');

        $backtest->strategy->backTestPositions = [];

        $backtest->strategy->backTestPositions = [['amount' => '1.08745','positionType' => 'short','takeProfit' => '1.08245','stopLoss' => '1.08945','dateTime' => '2015-12-04T08:00:00.000000Z','highestRate' => '1.08745','lowestRate' => '1.08745','highestRateDate' => '2015-12-04T08:00:00.000000Z','lowestRateDate' => '2015-12-04T08:00:00.000000Z']];

        $gain = (1.08745 - 1.08245);


        $backtest->checkTakeProfitStopLossPositionClose();

        $transactionGainLoss = $backtest->strategy->backTestPositions[0]['profitLoss'];
       // dd($transactionGainLoss);

        $this->assertEquals($gain, $transactionGainLoss);
    }

    public function getNextRate($previousPriceData, $frequencyId, $currencyId) {
        $nextRateId = HistoricalRates::where('frequency_id', '=', $frequencyId)->where('currency_id', '=', $currencyId)->where('rate_unix_time', '>', $previousPriceData->rateUnixTime)->min('id');
        return HistoricalRates::find($nextRateId);
    }

    public function testGetRatesLooping()
    {
        ini_set('memory_limit', '-1');

        $backtest = new IndicatorRunThroughTest('abc');
        $backtest->strategy = new EmaSimpleMomentum(123, 123);
        $backtest->strategy->backtesting = true;
        $backtest->rateUnixStart = 1507769138;
        $backtest->rateIndicatorMin = 200;
        $backtest->currencyId = 1;
        $backtest->frequencyId = 1;

        $fullExchange = Exchange::find(1);

        $backtest->exchange = $fullExchange;
        $backtest->frequencyId = 1;

        $previousPriceData = false;
        $backtest->setLastId();

        $backtest->rateIndex = $backtest->rateIndicatorMin - 1;

        $backtest->getInitialRates();

        $backtest->currentRatesProcessed = $backtest->rateCount;

        $nextCheckIndex = $backtest->rateIndex + mt_rand(10,100);

        while (!$backtest->lastIdCheck()) {
            $backtest->startNewPeriod();

            if ($previousPriceData) {
                $dbNextRate = $this->getNextRate($previousPriceData, $backtest->frequencyId, $backtest->currencyId);

                $this->assertEquals($dbNextRate->id, $backtest->currentPriceData->id);
                $this->assertEquals($dbNextRate->close_mid, $backtest->currentPriceData->ask);

                if (isset($previousDbRate)) {
                    $this->assertEquals($previousDbRate->close_mid, end($backtest->currentRates));
                }
                $previousDbRate = $dbNextRate;
            }
            $previousPriceData = $backtest->currentPriceData;
        }
    }

//    public function testGetRatesTwoTierLooping()
//    {
//        $backtest = new IndicatorRunThroughTest('abc');
//        $backtest->strategy = new EmaSimpleMomentum(123, 123);
//        $backtest->strategy->backtesting = true;
//        $backtest->rateUnixStart = 1451606400;
//        $backtest->slowRateUnixStart = $backtest->rateUnixStart;
//        $backtest->rateIndicatorMin = 20;
//        $backtest->currencyId = 3;
//        $backtest->frequencyId = 1;
//
//        $backtest->slowFrequencyId = 4;
//        $backtest->rateCount = $backtest->rateIndicatorMin*2;
//        $backtest->slowRateIndicatorMin = 15;
//        $backtest->slowRateIndex = $backtest->slowRateIndicatorMin - 1;
//        $backtest->slowRateCount = $backtest->slowRateIndicatorMin*2;
//
//        $backtest->currentSlowRatesProcessed = $backtest->slowRateCount;
//
//        $fullExchange = Exchange::find(1);
//
//        $backtest->exchange = $fullExchange;
//        $backtest->frequencyId = 1;
//
//        $backtest->twoTierRates = true;
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
//        $nextCheckIndex = $backtest->rateIndex + mt_rand(10,100);
//
//        $count = 0;
//
//
//        while (!$backtest->lastIdCheck()) {
//
//            $backtest->startNewPeriod();
//            $count++;
//
//            if (isset($backtest->slowRates[$backtest->slowRateIndex + 1])) {
//                //Fast Rate Should be Equal to or Greater than Slow Rate, Second value should be the greater one
//                $this->assertGreaterThanOrEqual((int) $backtest->slowRates[$backtest->slowRateIndex]['rate_unix_time'], (int) $backtest->rates[$backtest->rateIndex]['rate_unix_time']);
//
//                //The Next Slow Rate Should be Greater than the current Fast Rate, Second value should be the greater one
//                $this->assertGreaterThan((int) $backtest->rates[$backtest->rateIndex]['rate_unix_time'], (int) $backtest->slowRates[$backtest->slowRateIndex + 1]['rate_unix_time']);
//
//            }
//        }
//    }
}
