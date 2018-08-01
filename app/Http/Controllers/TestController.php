<?php namespace App\Http\Controllers;

use \App\Services\CurrencyIndicators;
use App\Services\PositionHelpers;
use App\Broker\OandaV20;
use \App\Model\DecodeFrequency;
use \App\Model\Exchange;
use App\Services\BackTest;
use App\BackTest\TakeProfitStopLossTest;
use App\BackTest\IndicatorRunThroughTest;
use \App\Model\HistoricalRates;
use Twilio;
use Illuminate\Support\Facades\Mail;

use App\Services\Utility;
use \App\Strategy\EmaMomentum\EmaSimpleMomentum;

use \Log;

class TestController extends Controller {

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

    public function rsi() {

        $indicators = new CurrencyIndicators();

        $rsi = $indicators->rsi($this->rates, 10);

        dd($rsi);
    }

    public function linearRegression() {
        $indicators = new CurrencyIndicators();

        $upwardRates = [88.23,
                        75.2,
                        78,
                        65,
                        60,
                        49];

        $sma = $indicators->linearRegression($upwardRates);

        echo "asdfasdf";
    }

    public function macd() {
        $indicators = new CurrencyIndicators();

        $macd = $indicators->macd($this->rates, 12, 26, 9);

        echo "asdfasdf";
    }

    public function testLastUpSwing() {


            $exchanges = \App\Model\Exchange::get();
            $oanda = new \App\Services\Oanda();

            $oanda->historicalCount = 100;
            $oanda->accountId = '3577742';

            foreach ($exchanges as $exchange) {
                $oanda->exchange = $exchange->exchange;

                $oanda->historicalCount = 52;
                $oanda->timePeriod = 'H4';

                $rates = $oanda->getHistoricalData();
                $rates = json_decode($rates);

                $rates = array_map(function($rate) {
                    return $rate->closeMid;
                }, $rates->candles);

                $positionHelpers = new PositionHelpers();
                $positionHelpers->rates = $rates;

                $currentPriceData = $oanda->getCurrentPrice();

                $positionHelpers->mostRecentDownSwing($exchange->pip, $currentPriceData->ask, 10, 30);

            }
    }

    public function testABC() {
        $positionHelpers = new PositionHelpers();

        $test = $positionHelpers->closestFiftyOneHundredUp(1.215658, .0001, 50);
        $test2 = $positionHelpers->closestFiftyOneHundredDown(1.215658, .0001, 50);

        echo "asdfasdf";
    }

    public function testHma() {
        $indicators = new CurrencyIndicators();

        $hma = $indicators->hma($this->rates, 12);

        echo "asdfasdf";
    }

    public function testGetRates() {
        $oandaTwenty = new OandaV20();

        $oandaTwenty->frequency = 'H4';
        $oandaTwenty->exchange = 'USD_CAD';
        $oandaTwenty->startDate = date('01-01-2018');

        $rates = $oandaTwenty->fullRates();

        echo "asdfl";
    }

    public function testCreateOrder() {
        $oandaTwenty = new OandaV20();

        $oandaTwenty->frequency = 'H1';
        $oandaTwenty->exchange = 'EUR_USD';
        $oandaTwenty->accountId = '101-001-7608904-002';
        $oandaTwenty->positionAmount = 10000;
        $oandaTwenty->runId = 'ABCDEG';
        $oandaTwenty->stopLoss = 1.22;

        $orderRequest = $oandaTwenty->newOrder('buy');

        echo "asdfl";
    }

    public function testTransactions() {
        $oandaTwenty = new OandaV20();

        $oandaTwenty->frequency = 'H1';
        $oandaTwenty->exchange = 'EUR_USD';
        $oandaTwenty->accountId = '101-001-7608904-002';

        $transacionts = $oandaTwenty->transactions();

        echo "asdfl";
    }


    public function testGetRatesTwoTierLooping()
    {
        $backtest = new IndicatorRunThroughTest('abc');
        $backtest->strategy = new EmaSimpleMomentum(123, 123);
        $backtest->strategy->backtesting = true;
        $backtest->rateUnixStart = 1451606400;
        $backtest->slowRateUnixStart = $backtest->rateUnixStart;
        $backtest->rateIndicatorMin = 20;
        $backtest->currencyId = 3;
        $backtest->frequencyId = 1;

        $backtest->slowFrequencyId = 4;
        $backtest->rateCount = $backtest->rateIndicatorMin*2;
        $backtest->slowRateIndicatorMin = 15;
        $backtest->slowRateIndex = $backtest->slowRateIndicatorMin - 1;
        $backtest->slowRateCount = $backtest->slowRateIndicatorMin*3;

        $backtest->currentSlowRatesProcessed = $backtest->slowRateCount;

        $fullExchange = Exchange::find(1);

        $backtest->exchange = $fullExchange;
        $backtest->frequencyId = 1;

        $backtest->twoTierRates = true;

        $previousPriceData = false;
        $backtest->setLastId();

        $backtest->rateIndex = $backtest->rateIndicatorMin - 1;

        $backtest->getInitialRates();

        $backtest->currentRatesProcessed = $backtest->rateCount;

        $nextCheckIndex = $backtest->rateIndex + mt_rand(10,100);

        $count = 0;

        while (!$backtest->lastIdCheck()) {

            $backtest->startNewPeriod();

            if (! (int) $backtest->rates[$backtest->rateIndex]['rate_unix_time'] >= (int) $backtest->slowRates[$backtest->slowRateIndex]['rate_unix_time']) {
                dd(
                    ['level'=>1,
                        'fastRate'=>$backtest->rates[$backtest->rateIndex]['rate_dt'],
                        'slowRate'=>$backtest->slowRates[$backtest->slowRateIndex]['rate_dt']]
                );
            }

            if (isset($backtest->slowRates[$backtest->slowRateIndex + 1])) {

                if ((int) $backtest->rates[$backtest->rateIndex]['rate_unix_time'] > (int) $backtest->slowRates[$backtest->slowRateIndex + 1]['rate_unix_time']) {
                    dd(
                        ['level'=>2,
                         'fastRate'=>$backtest->rates[$backtest->rateIndex]['rate_dt'],
                         'slowRate'=>$backtest->slowRates[$backtest->slowRateIndex]['rate_dt'],
                         'slowRateNext'=>$backtest->slowRates[$backtest->slowRateIndex + 1]['rate_dt']]
                    );
                }

//                $this->assertTrue((int) $backtest->rates[$backtest->rateIndex]['rate_unix_time'] >= (int) $backtest->slowRates[$backtest->slowRateIndex]['rate_unix_time']);
//                $this->assertTrue((int) $backtest->rates[$backtest->rateIndex]['rate_unix_time'] <= (int) $backtest->slowRates[$backtest->slowRateIndex + 1]['rate_unix_time']);
            }
        }
    }

    public function testDecision() {
        $json = '{"fastEma":[1.404745037292,1.4048233581947,1.4047622387964,1.4049081591976,1.4049987727984],"slowEma":[1.4047966859533,1.40483001578,1.4047954674563,1.4048690188279,1.4049255608592],"adx":[12.073177952176,11.868183887572,11.875017309033,11.935083095394,11.990858468443],"emaCrossover":"crossedAbove","rates":{"full":[{"highMid":1.40527,"closeMid":1.40516,"lowMid":1.40423,"openMid":1.40423,"volume":1533},{"highMid":1.40524,"closeMid":1.40498,"lowMid":1.40458,"openMid":1.40514,"volume":1774},{"highMid":1.40543,"closeMid":1.40464,"lowMid":1.40452,"openMid":1.40497,"volume":1170},{"highMid":1.40548,"closeMid":1.4052,"lowMid":1.40452,"openMid":1.40457,"volume":1735},{"highMid":1.40518,"closeMid":1.40518,"lowMid":1.40518,"openMid":1.40518,"volume":1}],"simple":["1.40516","1.40498","1.40464","1.40520","1.40518"]}}';

        $indicators = json_decode($json);

        $strategy = new \App\Strategy\EmaMomentum\EmaMomentumAdxConfirm('234', '234');
        $strategy->adxCutoff = 18;

        $strategy->decisionIndicators['fastEma'] = $indicators->fastEma;
        $strategy->decisionIndicators['slowEma'] = $indicators->slowEma;
        $strategy->decisionIndicators['adx'] = $indicators->adx;
        $strategy->decisionIndicators['emaCrossover'] = $indicators->emaCrossover;

        $test = $strategy->decision();

        return $test;
    }

    public function testOandaPricing() {
        $oandaTwenty = new OandaV20();

        $oandaTwenty->exchange = 'EUR_USD';
        $oandaTwenty->accountId = '101-001-7608904-002';

        $response = $oandaTwenty->price();

        return $response;
    }

    public function testFifteenEarlyCronJob() {
        $utility = new Utility();

        $utility->waitUntilSeconds(50);

        Log::info('testFifteenEarlyCronJob ran');
    }

    public function testMail() {
       // $twilio = new Twilio($accountId, $token, $fromNumber);

        $data = ['serverName'=>'TEEST',
                 'errorLog'=>'asdf'];

        $test = Mail::send('mail.error_log', $data, function($message) {
            $message->to('brian.oneill.tx@gmail.com')->subject('Welcome to the Laravel 4 Auth App!');
        });

        echo "asdfasdf";
    }

    public function testTwilio() {
        Twilio::message('2817967601', 'Hey Brian');
    }


    public function testSpecificTimeFrameFromOanda() {
        $oanda = new OandaV20();

        $frequency = DecodeFrequency::find(3);
        $exchange = Exchange::find(1);

        $oanda->frequency = $frequency->oanda_code;
        $oanda->exchange = $exchange->exchange;
        $oanda->startDate = '1296255600';

        $apiRates = $oanda->fullRates();

        foreach($apiRates as $apiRate) {
            $unix = (int) $apiRate->time;

            $dbRate = HistoricalRates::where('currency_id', '=', $exchange->id)
                ->where('frequency_id', '=', $frequency->id)
                ->where('rate_unix_time', '=', $unix)
                ->first();
        }
    }
}