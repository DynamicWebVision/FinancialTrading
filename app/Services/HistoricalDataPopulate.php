<?php namespace App\Services;

use App\Broker\OandaV20;
use \App\Model\HistoricalDataTracker;
use \App\Model\DecodeFrequency;
use \App\Model\Exchange;
use \App\Model\HistoricalRates;
use \Log;
use \App\Model\HistoricalRateSpotChecks;
use Illuminate\Support\Facades\DB;

class HistoricalDataPopulate  {

    public $broker;
    public $frequencyInfo;
    public $currencyInfo;

    public function __construct() {
        $this->broker = new OandaV20();
        $this->broker->accountId= '3577742';
    }

    public function getHistoricalData($data = false) {
       Log::info('Get Historical Data START');

       if (!$data) {
           $tracker = HistoricalDataTracker::orderBy('last_run_time')
               ->take(1)
               ->get()[0];
       }
       else {
           $tracker = HistoricalDataTracker::where('current_frequency_id', '=', $data['frequency_id'])
               ->where('current_currency_id', '=', $data['currency_id'])
               ->first();
       }

        $this->frequencyInfo = DecodeFrequency::find($tracker->current_frequency_id);
        $this->currencyInfo = Exchange::find($tracker->current_currency_id);

        $minDate = DB::select('select max(rate_unix_time) as minimumDate from historical_rates where currency_id = ?
                    and frequency_id = ? ;', [$this->currencyInfo->id, $this->frequencyInfo->id]);

        $minUnixDate = $minDate[0]->minimumDate;
        $nextDateUnix = strtotime ( '-1 day' , $minUnixDate);

        $this->broker->exchange = $this->currencyInfo->exchange;
        $this->broker->frequency = $this->frequencyInfo->oanda_code;

        $this->broker->startDate = $nextDateUnix;

        $historicalRates = $this->broker->fullRates();

        $this->saveHistoricalRates($historicalRates);

        $updateHistoricalDataTracker = HistoricalDataTracker::find($tracker->id);

        $updateHistoricalDataTracker->last_run_time = time();

        $updateHistoricalDataTracker->save();

        Log::info('Get Historical Data END');
    }

    public function populateIssueData() {

        $historicalDataCheck = HistoricalRateSpotChecks::where('processed', '=', 0)
            ->take(1)
            ->get()[0];

        $this->frequencyInfo = DecodeFrequency::find($historicalDataCheck->frequency_id);
        $this->currencyInfo = Exchange::find($historicalDataCheck->currency_id);

        Log::info('Get Historical Data START');
        echo time();


        $this->oanda->exchange = $this->currencyInfo->exchange;
        $this->oanda->timePeriod = $this->frequencyInfo->oanda_code;

        $startDateTime = $historicalDataCheck->start_date_time;

        $historicalRates = $this->oanda->getHistoricalDataStart($startDateTime);

        $processedRates = HistoricalRates::where('currency_id', '=', $historicalDataCheck->frequency_id)
            ->where('frequency_id', '=', $historicalDataCheck->currency_id)
            ->where('rate_unix_time', '>', strtotime($startDateTime) - 500)
            ->orderBy('id', 'desc')
            ->take(10000)
            ->get(['rate_date_time']);

        $processedRates = $processedRates->toArray();

        $processedRates = array_map(array($this, 'getRate'), $processedRates);

        if (!isset($historicalRates->candles)) {
            Log::info('Historical Candles Not Set'.json_encode($historicalRates));
        }

        foreach($historicalRates->candles as $rate) {
            if (!in_array($rate->time , $processedRates)) {
                $newRate = new HistoricalRates();

                $newRate->high_mid = $rate->highMid;
                $newRate->close_mid = $rate->closeMid;
                $newRate->low_mid = $rate->lowMid;
                $newRate->open_mid = $rate->openMid;

                $newRate->currency_id = $historicalDataCheck->currency_id;
                $newRate->frequency_id = $historicalDataCheck->frequency_id;

                $newRate->rate_date_time = $rate->time;

                $timeStamp = strtotime(date($newRate->rate_date_time));
                $mysql_date_time = date('Y-m-d H:i:s',strtotime($newRate->rate_date_time));

                $newRate->rate_unix_time = $timeStamp;

                $newRate->rate_dt = $mysql_date_time;

                $newRate->rate_date_time = $rate->time;

                $newRate->save();
            }
        }

        $historicalDataTrackerUpdate = HistoricalRateSpotChecks::find($historicalDataCheck['id']);

        $historicalDataTrackerUpdate->processed = 1;

        $historicalDataTrackerUpdate->save();

        Log::info('Get Historical Data END');
    }

    public function getRate($e) {
            return $e['rate_date_time'];
    }

    public function initialBigLoad() {

        $currency = env('HISTORICAL_DATA_CURRENCY');

        if ($currency) {
            $tracker = HistoricalDataTracker::where('current_currency_id', '=', $currency)
                ->where('process_complete', '=', 0)
                ->take(1)
                ->get()[0];
        }
        else {
            $tracker = HistoricalDataTracker::where('process_complete', '=', 0)
                ->take(1)
                ->get()[0];
        }

        $this->frequencyInfo = DecodeFrequency::find($tracker->current_frequency_id);
        $this->currencyInfo = Exchange::find($tracker->current_currency_id);

        $recordCount = DB::select('select count(*) as record_count from historical_rates where currency_id = ?
                    and frequency_id = ? ;', [$this->currencyInfo->id, $this->frequencyInfo->id]);

        if ($recordCount[0]->record_count > 0) {
            $minDate = DB::select('select max(rate_unix_time) as minimumDate from historical_rates where currency_id = ?
                    and frequency_id = ? ;', [$this->currencyInfo->id, $this->frequencyInfo->id]);

            $minUnixDate = $minDate[0]->minimumDate;
            $nextDateUnix = strtotime ( '-1 day' , $minUnixDate);
        }
        else {
            $nextDateUnix = 1262304000;
        }

        $this->broker->exchange = $this->currencyInfo->exchange;
        $this->broker->frequency = $this->frequencyInfo->oanda_code;

        $this->broker->startDate = $nextDateUnix;

        $historicalRates = $this->broker->fullRates();

        $lastTimeStamp = $this->saveHistoricalRates($historicalRates);

        if ((time()-(60*60*24)) < $lastTimeStamp) {
            $historicalDataTrack = HistoricalDataTracker::find($tracker->id);
            $historicalDataTrack->process_complete = 1;
            $historicalDataTrack->save();
        }

        Log::info('Get Historical Data END');
    }

    public function saveHistoricalRates($rates) {
        foreach($rates as $rate) {

            $timeStamp = (int) $rate->time;

            $newRate = HistoricalRates::firstOrNew(['rate_unix_time'=> $timeStamp, 'currency_id'=>$this->currencyInfo->id, 'frequency_id'=>$this->frequencyInfo->id]);

            $newRate->high_mid = $rate->highMid;
            $newRate->close_mid = $rate->closeMid;
            $newRate->low_mid = $rate->lowMid;
            $newRate->open_mid = $rate->openMid;
            $newRate->volume = $rate->volume;

            $newRate->currency_id = $this->currencyInfo->id;
            $newRate->frequency_id = $this->frequencyInfo->id;

            $newRate->rate_date_time = $rate->time;

            $mysql_date_time = date('Y-m-d H:i:s',$timeStamp);
            $newRate->rate_unix_time = $timeStamp;
            $newRate->rate_dt = $mysql_date_time;

            $newRate->rate_date_time = date("c", $timeStamp);

            $newRate->save();

            $lastTimeStamp = $timeStamp;
        }
        return $lastTimeStamp;
    }
}
