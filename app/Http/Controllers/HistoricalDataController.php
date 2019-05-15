<?php namespace App\Http\Controllers;

use App\Services\Oanda;
use App\Broker\OandaV20;
use \Log;
use Illuminate\Support\Facades\DB;

use App\Model\DecodeFrequency;
use App\Model\Exchange;
use \App\Model\HistoricalRates;
use \App\Model\HistoricalRateSpotChecks;
use \App\Model\OandaAccounts;
use \App\Model\Strategy;
use \App\Services\ProcessLogger;
use \App\Model\HistoricalRatesRandomTesting;

class HistoricalDataController extends Controller {

    public $Oanda;
    public $logger;

    public function __construct() {
        $this->Oanda = new Oanda();
    }

    public function populateHistoricalData() {
        $this->logger = new ProcessLogger('historical_fx_rates');

        $historicalDataPopulate = new \App\Services\HistoricalDataPopulate();
        $historicalDataPopulate->logger = $this->logger;
        $historicalDataPopulate->getHistoricalData();

        $this->logger->processEnd();
    }

    public function populateHistoricalDataSpecific($frequencyId,$currencyId) {
        $historicalDataPopulate = new \App\Services\HistoricalDataPopulate();
        $historicalDataPopulate->getHistoricalData(['frequency_id'=>$frequencyId, 'currency_id'=>$currencyId]);

        $currentRateDT = HistoricalRates::where('frequency_id', '=', $frequencyId)->where('currency_id', '=', $currencyId)->max('rate_dt');
        return $currentRateDT;
    }

    public function initialLoad() {
        Log::emergency('START Historical Data Populate');

        $startTime = time();
        $currentTime = time();

        while (($currentTime - $startTime) < 900) {
            $historicalDataPopulate = new \App\Services\HistoricalDataPopulate();
            $historicalDataPopulate->initialBigLoad();

            Log::info('END Historical Data Populate');
            $currentTime = time();
        }
    }

    public function populateSpotCheckHistoricalData() {
        Log::info('START Historical Data Populate');

        $historicalDataPopulate = new \App\Services\HistoricalDataPopulate();
        $historicalDataPopulate->populateIssueData();

        Log::info('END Historical Data Populate');
    }

    public function evaluateHistoricalData($frequencyId, $exchangeId) {
        $months = [1, 2, 3, 4, 5 ,6 ,7 , 8, 9, 10, 11, 12];

        $years = [2009, 2010, 2011, 2012, 2013, 2014, 2015, 2016, 2017];

        $monthSets = [];

        $monthData = DB::select("select distinct DATE_FORMAT(rate_dt, '%m/%Y') as transaction_month, count(*) as rate_count
                              from historical_rates where currency_id = ? and frequency_id = ? group by transaction_month order by max(id);",
            [$exchangeId, $frequencyId]);

        foreach ($years as $year) {
            foreach ($months as $month) {
                if ($month > 1 || $year > 2009) {
                    $monthSets[] = $month."/".$year;
                }
            }
        }
        return ['monthYearSets'=>$monthSets, 'rateMonthYears'=>$monthData];
    }

    public function getFrequenciesExchanges() {
        $frequencies = DecodeFrequency::get()->toArray();
        $exchanges = Exchange::get()->toArray();
        $strategies = Strategy::get()->toArray();
        $oandaAccounts = OandaAccounts::get()->toArray();

        return ['frequencies'=>$frequencies, 'exchanges'=>$exchanges, 'strategies'=>$strategies, 'oandaAccounts'=>$oandaAccounts];
    }

    public function checkCurrencyIssues() {
        ini_set('memory_limit','512M');

        $exchanges = Exchange::get()->toArray();
        $frequencies = DecodeFrequency::get()->toArray();

        foreach ($exchanges as $exchange) {
            foreach ($frequencies as $frequency) {
                $skipAmount = 0;

                $rates = HistoricalRates::where('frequency_id', '=', $frequency['id'])->where('currency_id','=', $exchange['id'])
                    ->skip(0)
                    ->take(1000)
                    ->orderBy('id')->get()->toArray();

                echo '<table>';
                echo '<tr><th>ID</th><th>Previous Date</th><th>Rate Date</th></tr>';

                while(sizeof($rates) > 500) {
                    foreach ($rates as $index=>$rate) {
                        if ($index - 1 >= 0) {
                            $yesterdayRate = $rates[$index-1];

                            $difference = $rate['rate_unix_time'] - $yesterdayRate['rate_unix_time'];

                            if ($difference > $frequency['frequency_seconds'] && ((date('l', strtotime($rate['rate_dt'])) != 'Sunday' && date('l', strtotime($rate['rate_dt'])) != 'Saturday')
                                    && date('l', strtotime($rate['rate_dt'])) != 'Friday')) {
                                echo '<tr><td>'.$rate['id'].'</td><td>'.date('l Y-m-d H:i:s', strtotime($yesterdayRate['rate_dt'])).'</td><td>'.date('l Y-m-d H:i:s', strtotime($rate['rate_dt'])).'</td></tr>';

                                $newHistoricalRateSpotCheck = new HistoricalRateSpotChecks();

                                $newHistoricalRateSpotCheck->frequency_id = $frequency['id'];

                                $newHistoricalRateSpotCheck->currency_id = $exchange['id'];

                                $newHistoricalRateSpotCheck->start_date_time = date('Y-m-d', $yesterdayRate['rate_unix_time'])."T15%3A47%3A40Z";

                                $newHistoricalRateSpotCheck->save();
                            }
                        }
                    }
                    $skipAmount = $skipAmount + 1000;

                    $rates = HistoricalRates::where('frequency_id', '=', $frequency['id'])->where('currency_id','=',$exchange['id'])
                        ->skip($skipAmount -1)
                        ->take(1000)
                        ->orderBy('id')->get()->toArray();
                }

                echo '</table>';
            }
        }
    }

    public function historicalDataPull() {
        $this->Oanda->exchange = 'EUR_USD';
        $this->Oanda->historicalCount = 20;
        $this->Oanda->timePeriod = 'D';
        $this->Oanda->accountId = 548695;

        $responseNormal = $this->Oanda->getHistoricalData();

        $responseCancel = $this->Oanda->getHistoricalData(true);

        $abc = false;
    }

    public function test() {
        $this->historicalDataTest(1, 1);
    }

    public function historicalDataTest($frequencyId, $currencyId) {
        $historical_rates_max = HistoricalRates::where('frequency_id', '=', $frequencyId)->where('currency_id', '=', $currencyId)->max('id');

        $historicalRate = null;

        while ($historicalRate == null) {
            $startRateId = mt_rand(1, $historical_rates_max);
            $historicalRate = HistoricalRates::where('frequency_id', '=', $frequencyId)->where('currency_id', '=', $currencyId)->where('id', '=', $startRateId)->first();
        }

        $frequencyInfo = DecodeFrequency::find($historicalRate->frequency_id);
        $currencyInfo = Exchange::find($historicalRate->currency_id);

        $oanda = new OandaV20();
        $oanda->strategyLogger = $this->logger;

        $oanda->exchange = $currencyInfo->exchange;
        $oanda->frequency = $frequencyInfo->oanda_code;

        $oanda->startDate = $historicalRate->rate_unix_time;

        $historicalRates = $oanda->fullRates();

        foreach ($historicalRates as $oandaRate) {
            try {
                $dbRate = HistoricalRates::where('currency_id','=', $historicalRate->currency_id)
                    ->where('frequency_id', '=', $historicalRate->frequency_id)
                    ->where('rate_date_time', '=', $oandaRate->time)
                    ->first();

                if ($dbRate->high_mid != $oandaRate->highMid || $dbRate->close_mid != $oandaRate->closeMid || $dbRate->low_mid != $oandaRate->lowMid
                    || $dbRate->open_mid != $oandaRate->openMid) {
                    $testValues = [];
                    $testValues['dbRate'] = $dbRate;
                    $testValues['oandaRate'] = $oandaRate;

                    $testRecord = new HistoricalRatesRandomTesting();

                    $testRecord->currency_id = $historicalRate->currency_id;
                    $testRecord->frequency_id = $historicalRate->frequency_id;
                    $testRecord->rate_date_time = $oandaRate->time;
                    $testRecord->test_values = json_encode($testValues);
                    $testRecord->status = 'MISMATCH';

                    $testRecord->save();
                }
                else {
                    $testRecord = new HistoricalRatesRandomTesting();

                    $testRecord->currency_id = $historicalRate->currency_id;
                    $testRecord->frequency_id = $historicalRate->frequency_id;
                    $testRecord->rate_date_time = $oandaRate->time;
                    $testRecord->status = 'VALID';

                    $testRecord->save();
                }
            }
            catch (\Exception $e) {

//                $newRate = new HistoricalRates();
//
//                $newRate->high_mid = $oandaRate->highMid;
//                $newRate->close_mid = $oandaRate->closeMid;
//                $newRate->low_mid = $oandaRate->lowMid;
//                $newRate->open_mid = $oandaRate->openMid;
//                $newRate->volume = $oandaRate->volume;
//
//                $newRate->currency_id = $historicalRate->currency_id;
//                $newRate->frequency_id = $historicalRate->frequency_id;
//
//                $newRate->rate_date_time = $oandaRate->time;
//
//                $timeStamp = strtotime(date($newRate->rate_date_time));
//                $mysql_date_time = date('Y-m-d H:i:s',strtotime($newRate->rate_date_time));
//
//                $newRate->rate_unix_time = $timeStamp;
//
//                $newRate->rate_dt = $mysql_date_time;
//
//                $newRate->save();

                $testRecord = new HistoricalRatesRandomTesting();

                $testRecord->currency_id = $historicalRate->currency_id;
                $testRecord->frequency_id = $historicalRate->frequency_id;
                $testRecord->rate_date_time = $oandaRate->time;
                $testRecord->status = 'NOT_FOUND';

                $testRecord->save();
            }

        }
    }
}
