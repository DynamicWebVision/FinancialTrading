<?php namespace App\Http\Controllers\Equity;


use \DB;
use \Log;
use Request;
use App\Http\Controllers\Controller;

use App\Model\Stocks\StocksDump;
use App\Services\Csv;

class ExchangeDataController extends Controller {

    public function refreshDumpTable() {
        StocksDump::truncate();
    }

    public function nasdaqDump() {
        $file = fopen('/Users/boneill/Documents/Reference/Financial/StockDataDumps/nasdaq_dump.csv',"r");
        $count = 0;

        while (($data = fgetcsv($file)) !== FALSE) {
            if ($count > 0) {
                $stockDump = new StocksDump();

                $stockDump->symbol = $data[0];
                $stockDump->name = $data[1];

                if (is_float($data[2])) {
                    $stockDump->last_sale = $data[2];
                }

                if (is_float($data[3])) {
                    $stockDump->market_cap = $data[3];
                }

                $stockDump->ipo_year = $data[5];
                $stockDump->sector = trim($data[6]);
                $stockDump->industry = trim($data[7]);

                $stockDump->save();
            }

            $count++;
        }
    }
    public function nyseDump() {
        $file = fopen('/Users/boneill/Documents/Reference/Financial/StockDataDumps/nyse_dump.csv',"r");
        $count = 0;

        while (($data = fgetcsv($file)) !== FALSE) {
            if ($count > 0) {
                $stockDump = new StocksDump();

                $stockDump->symbol = $data[0];
                $stockDump->name = $data[1];

                if (is_float($data[2])) {
                    $stockDump->last_sale = $data[2];
                }

                if (is_float($data[3])) {
                    $stockDump->market_cap = $data[3];
                }

                $stockDump->ipo_year = $data[5];
                $stockDump->sector = trim($data[6]);
                $stockDump->industry = trim($data[7]);

                $stockDump->save();
            }

            $count++;
        }
    }



}