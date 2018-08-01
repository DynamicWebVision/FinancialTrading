<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use \App\Model\DecodeFrequency;
use \App\Model\Exchange;
use \App\Model\HistoricalRates;
use \App\Broker\OandaV20;

use App\Services\CurrencyIndicators;
use App\Http\Controllers\HistoricalDataController;

class HistoricalRatesTest extends TestCase
{

//    public function testDuplicates()
//    {
//        $frequencyInfo = DecodeFrequency::get();
//        $exchanges = Exchange::get();
//
//        foreach ($frequencyInfo as $frequency) {
//            foreach ($exchanges as $exchange) {
//                $recordCount = DB::select('select rate_date_time, count(*) as record_count from historical_rates
//                  where currency_id = ? and frequency_id = ? group by rate_date_time order by record_count desc;;', [$exchange->id, $frequency->id]);
//
//                $this->assertEquals($recordCount[0]->record_count, 1);
//            }
//        }
//    }

//    public function testIntervals()
//    {
//        $frequencyInfo = DecodeFrequency::get();
//        $exchanges = Exchange::get();
//
//        $oanda = new OandaV20();
//
//        foreach ($frequencyInfo as $frequency) {
//            foreach ($exchanges as $exchange) {
//                $minHistoricalRate = HistoricalRates::where('currency_id', '=', $exchange->id )->where('frequency_id', '=', $frequency->id)->min('rate_unix_time');
//
//                $maxHistoricalRate = HistoricalRates::where('currency_id', '=', $exchange->id )->where('frequency_id', '=', $frequency->id)->max('rate_unix_time');
//
//                $maxHistoricalRate = $maxHistoricalRate - 10000;
//
//                $startUnix = mt_rand($minHistoricalRate, $maxHistoricalRate);
//
//                $rates = HistoricalRates::where('currency_id', '=', $exchange->id )->where('frequency_id', '=', $frequency->id)
//                    ->where('rate_unix_time', '>=', $startUnix)->take(100)->get();
//
//                foreach ($rates as $index=>$rate) {
//                    if ($index > 0) {
//                        $lastIndex = $index-1;
//
//                        $rateTimeDifference = $rate->rate_unix_time - $rates[$lastIndex]->rate_unix_time;
//
//                        if ($rateTimeDifference == $frequency->frequency_seconds) {
//                            $this->assertEquals($rateTimeDifference, $frequency->frequency_seconds);
//                        }
//                        else {
//                            $oanda->frequency = $frequency->oanda_code;
//                            $oanda->exchange = $exchange->exchange;
//                            $oanda->startDate = $rates[$lastIndex]->rate_unix_time;
//
//                            $apiRates = $oanda->fullRates();
//
//                            $current = (int) $apiRates[0]->time;
//                            $next = (int) $apiRates[1]->time;
//
//                            if (($next - $current) == $rateTimeDifference) {
//                                $this->assertEquals(($next - $current), $rateTimeDifference);
//                            }
//                            else {
//                                dd(
//                                    [
//                                        'exchange'=>$exchange->exchange,
//                                        'frequency'=>$frequency->oanda_code,
//                                        'time'=>$rates[$lastIndex]->rate_unix_time,
//                                    ]
//                                );
//                            }
//                        }
//                    }
//                }
//            }
//        }
//    }
//
//    public function testRealDataSpotCheck()
//    {
//        $frequencyInfo = DecodeFrequency::get();
//        $exchanges = Exchange::get();
//
//        $historicalDataController = new HistoricalDataController();
//
//        foreach ($frequencyInfo as $frequency) {
//            foreach ($exchanges as $exchange) {
//                $historicalDataController->historicalDataTest($frequency->id, $exchange->id);
//            }
//        }
//    }

//    public function testSpecificTimeFrameFromOanda() {
//        $oanda = new OandaV20();
//
//        $frequency = DecodeFrequency::find(3);
//        $exchange = Exchange::find(1);
//
//        $oanda->frequency = $frequency->oanda_code;
//        $oanda->exchange = $exchange->exchange;
//        $oanda->startDate = '1531436442';
//
//        $apiRates = $oanda->fullRates();
//
//        foreach($apiRates as $apiRate) {
//            $unix = (int) $apiRate->time;
//
//            $dbRate = HistoricalRates::where('currency_id', '=', $exchange->id)
//                        ->where('frequency_id', '=', $frequency->id)
//                        ->where('rate_unix_time', '=', $unix)
//                        ->first();
//
//            $this->assertEquals($dbRate->high_mid, $apiRate->highMid);
//            $this->assertEquals($dbRate->close_mid, $apiRate->closeMid);
//            $this->assertEquals($dbRate->open_mid, $apiRate->openMid);
//            $this->assertEquals($dbRate->low_mid, $apiRate->lowMid);
//        }
//    }
}
