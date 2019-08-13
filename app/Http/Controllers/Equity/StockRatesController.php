<?php namespace App\Http\Controllers\Equity;


use \DB;
use \Log;
use Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ServersController;
use App\Broker\TDAmeritrade;
use Illuminate\Support\Facades\Config;

use App\Broker\IexTrading;
use App\Model\Stocks\Stocks;
use App\Model\Stocks\StockIexDailyRates;
use App\Model\Stocks\VwStocksWithRateCount;

class StockRatesController extends Controller {
    public function getChart($symbol, $chartPeriod) {
        $iexTrading = new IexTrading();

        $ratesResponse = $iexTrading->getChart($symbol, $chartPeriod);

        $rates = [];
        $labels = [];

        foreach ($ratesResponse as $rate) {
            $rates[] = $rate->close;
            if ($chartPeriod == '1D') {
                $labels[] = $rate->label;
            }
            else {
                $labels[] = $rate->date;
            }
        }
        return ['rates'=>$rates, 'labels'=>$labels];
    }

    public function ratesProfile() {
        $stocks = VwStocksWithRateCount::get()->toArray();

        $stocks = array_map(function($stock) {
            $stock['selected'] = false;
            $stock['market_cap'] = number_format($stock['market_cap']);
            $stock['rate_count'] = number_format($stock['rate_count']);
            return $stock;
        }, $stocks);
        return $stocks;
    }
}