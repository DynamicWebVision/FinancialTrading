<?php namespace App\Http\Controllers;

use \Log;
use View;

class CurrencyController extends Controller {

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

    public function __construct() {
		set_time_limit(0);
	}

    public function graph() {
        return view('currencies');
    }

	public function getCurrencyData()
	{
        $currencyProcessing = new \App\Services\CurrencyProcessing();

        $currencyProcessing->macdShortPeriod = 5;
        $currencyProcessing->macdLongPeriod = 10;

        $currencyProcessing->macdIndicatorPeriod = 8;

        $currencyProcessing->rsiPeriods = 10;
        $currencyProcessing->rsiUpperCutoff = 80;
        $currencyProcessing->rsiLowerCutoff = 20;

        $currencyProcessing->bollingerPeriods = 12;

        $currencyProcessing->smaLongPeriod = 8;
        $currencyProcessing->smaShortPeriod = 4  ;

        $currencyProcessing->rateOfChangePeriod = 3;

        $currencyProcessing->accountValue = 10000;

        $currencyProcessing->rocFactorPeriods = 45;

        $currencyProcessing->outerPeriodShortEma = 50;

        $currencies = \App\Model\Currency::where('currency_exchange', '=', 2)->get();

		$currencyData = $currencyProcessing->runStrategy($currencies);

		echo json_encode($currencyData);
	}

    public function saveHistoricalDataDb($exchange, $period) {
        $oanada = new \App\Services\Oanda();
        $oanada->exchange = $exchange;
        $oanada->timePeriod = $period;

        $oanada->saveHistoricalData();
    }

    public function runMacdAnalyzation() {

        $processRuns = \App\Model\CurrencyStrategyRun::where("run", "=", 1)->get();

        foreach ($processRuns as $run) {

            $date = \Carbon\Carbon::now();

            $strategy = \App\Model\CurrencyStrategyRun::find($run->id);

            $strategy->run_start = $date;
            $strategy->run = 0;
            $strategy->save();

            $currencyProcessing = new \App\Services\CurrencyProcessing();

            $currencyProcessing->strategyId = $run->id;
            $currencyProcessing->outerCurrencyExchange = $run->currency_long;
            $currencyProcessing->mainCurrencyExchange = $run->currency_main;

            $currencyProcessing->analyzeMacdVariables();

            $date = \Carbon\Carbon::now();

            $runcheck = \App\Model\CurrencyStrategyRun::find($run->id);
            $runcheck->run_end = $date;
            $runcheck->save();
        }
    }

    public function storeHourlyRates() {

        $cronJob = new \App\Services\CronJobs(5);

        $cronJob->cronId = 5;
        $cronJob->start();

        $automatedSystem = new \App\Services\AutomatedSystem();

        $ratesSaved = $automatedSystem->storeHourlyRates();

        $cronJob->end($ratesSaved);
    }

    public function quickDecision() {
        $cronJob = new \App\Services\CronJobs();

        $cronJob->cronId = 6;
        $cronJob->start();

        $automatedSystem = new \App\Services\AutomatedSystem();

        $automatedSystem->emaLongPeriod = 10;
        $automatedSystem->emaShortPeriod = 5;

        $automatedSystem->rsiPeriods = 10;

        $ratesSaved = $automatedSystem->quickDecision();

        $cronJob->end($ratesSaved);
    }

    public function macdDivCheck() {
        $automated = new \App\Services\AutomatedSystem();

        $automated->macdDivergenceStrategy();
    }

    public function phantomCrossoverTest() {
        $autoTrading = new \App\Services\AutomatedSystem();

        $autoTrading->phantomCrossover(['asdf']);
    }

    public function traderTest() {
        $array = [1.23, 1.26, 1.22, 1.55, 1.88];

        $shortEma = trader_ema($array, 5);

        dd($shortEma);
    }

    public function storeFifteenMinuteRates() {

        $cronJob = new \App\Services\CronJobs();

        $cronJob->cronId = 5;
        $cronJob->start();

        $automatedSystem = new \App\Services\AutomatedSystem();

        $ratesSaved = $automatedSystem->storeHourlyRates();

        $cronJob->end($ratesSaved);
    }

    public function fiveMinuteSystems() {
        $exchanges = \App\Model\Exchange::get();
        $Oanda = new \App\Services\Oanda();

        $Oanda->historicalCount = 100;
        $Oanda->accountId = '3577742';

        foreach ($exchanges as $exchange) {
            $Oanda->exchange = $exchange->exchange;

            $Oanda->historicalCount = 52;
            $Oanda->timePeriod = 'D';

            $dailyRates = $Oanda->getHistoricalData();
            $dailyRates = json_decode($dailyRates);
            $dailyRates = $dailyRates->candles;

            $Oanda->timePeriod = 'H1';

            $rates = $Oanda->getHistoricalData();
            $rates = json_decode($rates);

            if (isset($rates->candles)) {
                $rates = $rates->candles;

                $currentPriceData = $Oanda->getCurrentPrice();

                $dailyRates = array_map(function($rate) {
                    return $rate->closeMid;
                }, $dailyRates);

                $rates = array_map(function($rate) {
                    return $rate->closeMid;
                }, $rates);

                $rates[] = $currentPriceData->ask;

                $cowabunga = new \App\Strategy\Cowabunga(548695);
                $cowabunga->exchange = $exchange;
                $cowabunga->strategyId = 2;
                $cowabunga->strategyDesc = 'Cowabunga';
                $cowabunga->currentPriceData = $currentPriceData;
                $cowabunga->positionMultiplier = 5;
                $cowabunga->maxPositions = 5;
                $cowabunga->takeProfitPipAmount = 150;
                $cowabunga->stopLossPipAmount = 50;

                $cowabunga->runStrategy($dailyRates, $rates);

                //Go Back to Phantom, Research This!!!
//                $phantom = new \App\Strategy\Phantom(3577742);
//                $phantom->exchange = $exchange;
//                $phantom->strategyId = 1;
//                $phantom->strategyDesc = 'Phantom';
//                $phantom->exchange = $exchange;
//                $phantom->currentPriceData = $currentPriceData;
//                $phantom->runStrategy($rates);
            }
        }
    }

    public function oneMinuteSystem() {
        $exchanges = \App\Model\Exchange::get();
        $Oanda = new \App\Services\Oanda();
        $Oanda->accountId = '7827172';

        foreach ($exchanges as $exchange) {
            $Oanda->exchange = $exchange->exchange;

            //4 Hour Rates
            $Oanda->historicalCount = 52;
            $Oanda->timePeriod = 'H4';

            $fourHourRates = $Oanda->getHistoricalData();
            $fourHourRates = json_decode($fourHourRates);

            if (isset($fourHourRates->candles)) {
                $fourHourRates = $fourHourRates->candles;

                $Oanda->timePeriod = 'M15';

                $fifteenMinuteRates = $Oanda->getHistoricalData();
                $fifteenMinuteRates = json_decode($fifteenMinuteRates);

                $fifteenMinuteRates = $fifteenMinuteRates->candles;

                $currentPriceData = $Oanda->getCurrentPrice();

                $fourHourRates = array_map(function($rate) {
                    return $rate->closeMid;
                }, $fourHourRates);

                $fifteenMinuteRates = array_map(function($rate) {
                    return $rate->closeMid;
                }, $fifteenMinuteRates);

                $fifteenMinuteRates[] = $currentPriceData->ask;

                $cowabunga = new \App\Strategy\Cowabunga(7827172);
                $cowabunga->exchange = $exchange;
                $cowabunga->strategyId = 2;
                $cowabunga->strategyDesc = 'Cowabunga';
                $cowabunga->currentPriceData = $currentPriceData;
                $cowabunga->positionMultiplier = 10;
                $cowabunga->maxPositions = 3;
                $cowabunga->takeProfitPipAmount = 50;
                $cowabunga->stopLossPipAmount = 15;

                $cowabunga->runStrategy($fourHourRates, $fifteenMinuteRates);
            }


        }
    }

    public function fifteenMinuteSystem() {
        $exchanges = \App\Model\Exchange::get();
        $Oanda = new \App\Services\Oanda();
        $Oanda->accountId = 3257917;

        foreach ($exchanges as $exchange) {
            $Oanda->exchange = $exchange->exchange;

            //4 Hour Rates
            $Oanda->historicalCount = 52;
            $Oanda->timePeriod = 'H4';

            $fourHourRates = $Oanda->getHistoricalData();
            $fourHourRates = json_decode($fourHourRates);

            if (isset($fourHourRates->candles)) {
                $fourHourRates = $fourHourRates->candles;

                $Oanda->timePeriod = 'M15';

                $fifteenMinuteRates = $Oanda->getHistoricalData();
                $fifteenMinuteRates = json_decode($fifteenMinuteRates);

                $fifteenMinuteRates = $fifteenMinuteRates->candles;

                $currentPriceData = end($fifteenMinuteRates);

                $fourHourRates = array_map(function($rate) {
                    return $rate->closeMid;
                }, $fourHourRates);

                $fifteenMinuteRates = array_map(function($rate) {
                    return $rate->closeMid;
                }, $fifteenMinuteRates);

                $cowabunga = new \App\Strategy\Cowabunga(3257917);
                $cowabunga->exchange = $exchange;
                $cowabunga->strategyId = 2;
                $cowabunga->strategyDesc = 'Cowabunga';
                $cowabunga->currentPriceData = $Oanda->getCurrentPrice();

                $cowabunga->positionMultiplier = 10;
                $cowabunga->maxPositions = 3;
                $cowabunga->takeProfitPipAmount = 50;
                $cowabunga->stopLossPipAmount = 15;

                $cowabunga->runStrategy($fourHourRates, $fifteenMinuteRates);


                //Try 30 Take Profit
                $cowabunga = new \App\Strategy\Cowabunga(3577742);
                $cowabunga->exchange = $exchange;
                $cowabunga->strategyId = 2;
                $cowabunga->strategyDesc = 'Cowabunga';
                $cowabunga->currentPriceData = $Oanda->getCurrentPrice();

                $cowabunga->positionMultiplier = 10;
                $cowabunga->maxPositions = 3;
                $cowabunga->takeProfitPipAmount = 26;
                $cowabunga->stopLossPipAmount = 15;

                $cowabunga->runStrategy($fourHourRates, $fifteenMinuteRates);

            }
        }
    }

    public function testGetStart() {

        $Oanda = new \App\Services\Oanda();

        $Oanda->historicalCount = 100;
        $Oanda->accountId = '3577742';

        $response = json_decode($Oanda->getHistoricalDataStart());

    }

    public function populateHDTracker() {
        $frequencies = \App\Model\DecodeFrequency::all();
        $currencies = \App\Model\Exchange::all();

        $originalDate = "2009-01-01";
        $newDate = date("Y-m-d H:i:s", strtotime($originalDate));

        foreach ($frequencies as $frequency) {
            foreach ($currencies as $currency) {
                $hdTracker = new \App\Model\HistoricalDataTracker();

                $hdTracker->current_currency_id = $currency->id;
                $hdTracker->current_frequency_id = $frequency->id;

                $hdTracker->last_date_time = $newDate;

                $hdTracker->save();
            }
        }
    }

    public function populateHistoricalData() {
        $cronJob = new \App\Services\CronJobs();

        $cronJob->cronId = 9;
        $cronJob->start();

        $historicalDataPopulate = new \App\Services\HistoricalDataPopulate();
        $historicalDataPopulate->getHistoricalData();

        $cronJob->end(1);
    }

    public function populateHistoricalTimeStamp() {
        Log::info('Populate Historical Timestamp Start');
        set_time_limit(0);

        $historicalRates = \App\Model\HistoricalRates::where('rate_dt', '=', null)
            ->orderby('frequency_id')
            ->orderby('currency_id')
            ->take(20000)->get();

        foreach ($historicalRates as $rate) {
            $timeStamp = strtotime(date($rate->rate_date_time));
            $mysql_date_time = date('Y-m-d H:i:s',strtotime($rate->rate_date_time));

            $updatedRate = \App\Model\HistoricalRates::find($rate->id);
            $updatedRate->rate_unix_time = $timeStamp;
            $updatedRate->rate_dt = $mysql_date_time;

            $updatedRate->save();
        }

        Log::info('Populate Historical Timestamp End');
    }
}