<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
}