<?php namespace App\Http\Controllers\Equity;

use App\Model\Stocks\StocksCompanyProfile;
use \DB;
use \Log;
use Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ServersController;
use App\Broker\IexTrading;
use Illuminate\Support\Facades\Config;

use App\Model\Stocks\Stocks;
use App\Model\Stocks\StocksDailyPrice;
use App\Model\Stocks\StocksTags;
use App\Model\Stocks\StocksIndustry;

class StockRateMaintenanceController extends Controller {

    public $symbol = false;
    public $stockId = false;
    public $year = false;
    public $keepRunningCheck = true;
    public $lastPullTime = 0;
    public $keepRunningStartTime = 0;

    public function __construct() {
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $serverController = new ServersController();

        $serverController->setServerId();
    }

    public function markFirstDateOfCompanyStocks() {
        $stocks = Stocks::get();

        foreach($stocks as $stock) {
            $priceCount = StocksDailyPrice::where('stock_id', '=', $stock->id)->count();

            if ($priceCount > 0) {
                 $minDate = StocksDailyPrice::where('stock_id', '=', $stock->id)->min('price_date_time');

                $currentStock = Stocks::find($stock->id);

                $currentStock->first_daily_rates_date = $minDate;

                $currentStock->save();
            }
        }
    }
}