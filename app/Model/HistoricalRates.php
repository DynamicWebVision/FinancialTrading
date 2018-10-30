<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Model\Exchange;

//1	EUR_USD	0.00010
//2	USD_JPY	0.01000
//3	USD_CAD	0.00010
//4	GBP_USD	0.00010
//5	USD_CHF	0.00010
//6	NZD_USD	0.00010
//7	AUD_USD	0.00010

//1	15 Minutes	M15	40	900
//2	30 Minutes	M30	90	1800
//3	Hourly	H1	180	3600
//4	Four Hour	H4	700	14400
//5	Daily	D	4000	86400

class HistoricalRates extends Model {
    protected $table = 'historical_rates';

    protected $fillable = ['rate_date_time', 'currency_id', 'frequency_id'];

    public function getRatesSpecificTimeSimple($currencyId, $frequencyId, $count, $startDate) {
        $dateCutoff = strtotime($startDate);

        $rates = $this->where('currency_id', '=', $currencyId)
                ->where('frequency_id', '=', $frequencyId)
                ->where('rate_unix_time', '<=',$dateCutoff)
                ->orderBy('rate_unix_time', 'desc')
                ->take($count)
                ->get()
                ->toArray();

        $rates = array_map(function($rate) {
            return (float) $rate['close_mid'];
        }, $rates);
        return array_reverse($rates);
    }

    public function getRatesSpecificTimeFull($currencyId, $frequencyId, $count, $startDate) {
        $dateCutoff = strtotime($startDate);

        $rates = $this->where('currency_id', '=', $currencyId)
                ->where('frequency_id', '=', $frequencyId)
                ->where('rate_unix_time', '<=',$dateCutoff)
                ->orderBy('rate_unix_time', 'desc')
                ->take($count)
                ->get()
                ->toArray();

        $fullRates = array_map(function($rate) {

            $stdRate = new \StdClass();

            $stdRate->highMid = (float) $rate['high_mid'];
            $stdRate->closeMid = (float) $rate['close_mid'];
            $stdRate->lowMid = (float) $rate['low_mid'];
            $stdRate->openMid = (float) $rate['open_mid'];
            $stdRate->volume = (float) $rate['volume'];

            return $stdRate;
        }, $rates);

        return array_reverse($fullRates);
    }

    public function getRatesSpecificTimeBoth($currencyId, $frequencyId, $count, $startDate) {
        $dateCutoff = strtotime($startDate);

        $rates = $this->where('currency_id', '=', $currencyId)
            ->where('frequency_id', '=', $frequencyId)
            ->where('rate_unix_time', '<=',$dateCutoff)
            ->orderBy('rate_unix_time', 'desc')
            ->take($count)
            ->get()
            ->toArray();

        $fullRates = array_map(function($rate) {

            $stdRate = new \StdClass();

            $stdRate->highMid = (float) $rate['high_mid'];
            $stdRate->closeMid = (float) $rate['close_mid'];
            $stdRate->lowMid = (float) $rate['low_mid'];
            $stdRate->openMid = (float) $rate['open_mid'];
            $stdRate->volume = (float) $rate['volume'];

            return $stdRate;
        }, $rates);

        $rates = array_map(function($rate) {
            return (float) $rate['close_mid'];
        }, $rates);

        return ['full'=> array_reverse($fullRates), 'simple' => array_reverse($rates)];
    }

    public function getRatesSpecificTimeSimpleInPips($currencyId, $frequencyId, $count, $startDate) {
        $dateCutoff = strtotime($startDate);

        $exchange = Exchange::find($currencyId);

        $rates = $this->where('currency_id', '=', $currencyId)
            ->where('frequency_id', '=', $frequencyId)
            ->where('rate_unix_time', '<=',$dateCutoff)
            ->orderBy('rate_unix_time', 'desc')
            ->take($count)
            ->get()
            ->toArray();

        $rates = array_map(function($rate) use ($exchange) {
            return (float) $rate['close_mid']/$exchange->pip;
        }, $rates);
        return array_reverse($rates);
    }
}