<?php namespace App\Http\Controllers\Equity;


use \DB;
use \Log;
use Request;
use App\Http\Controllers\Controller;
use App\Broker\TDAmeritrade;

use App\Model\Stocks\StocksDump;
use App\Services\Csv;

class StocksHistoricalDataController extends Controller {

    public function getStockData() {
        $tdAmeritrade = new TDAmeritrade();

        $symbol = 'HD';

        $params = [
            'periodType'=>'month',
            'period'=>1,
            'frequencyType'=>'daily',
            'frequency'=>1,

        ];

        $startDate = strtotime('1/1/2010')*1000;
        $endDate = strtotime('12/10/2010')*1000;

        $params = [
            'frequencyType'=>'daily',
            'frequency'=>1,
            'periodType'=>'year',
            'startDate'=>$startDate,
            'endDate'=>$endDate

        ];

        $response = $tdAmeritrade->getStockPriceHistory('HD', $params);

        foreach ($response->candles as $candle) {
            $dateTime = date('Y-m-d',($candle->datetime/1000));
            echo $dateTime."<BR><BR>";
        }
    }
}