<?php namespace App\Http\Controllers\Equity;


use \DB;
use \Log;
use Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ServersController;
use App\Http\Controllers\Equity\StockFundamentalDataController;
use App\Http\Controllers\Equity\StocksFinancialsController;
use App\Http\Controllers\Equity\StocksCompanyProfileController;
use App\Broker\IexTrading;
use Illuminate\Support\Facades\Config;

use App\Model\Stocks\ServerTasksSecondary;
use App\Model\Stocks\Stocks;
use App\Model\Stocks\StocksCompanyFinancialsAnnual;
use App\Model\Stocks\StocksCompanyFinancialsQuarter;
use App\Model\Servers;
use App\Services\Csv;

class StocksInformationController extends Controller {

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

    public function runNextTask() {
        Log::emergency('Getting Next StocksInformationController Task');

        $nextTask = ServerTasksSecondary::orderBy('last_run_time')->first();

        Log::emergency('Current StocksInformationController Task is '.$nextTask->name);

        $nextTask->last_run_time = time();

        $nextTask->save();

        $controller = new StockFundamentalDataController();
        $controller->keepRunning();

//        if ($nextTask->id == 1) {
//            $controller = new StocksCompanyProfileController();
//            $controller->keepRunning();
//        }
//        elseif ($nextTask->id == 2) {
//            $controller = new StocksFinancialsController();
//            $controller->keepRunning();
//        }
//        elseif ($nextTask->id == 3) {
//            $controller = new StockFundamentalDataController();
//            $controller->keepRunning();
//        }
    }
}