<?php namespace App\Http\Controllers;



//END OF Backtest Declarations
use \Log;

use App\Model\BackTest;
use App\Model\BackTestToBeProcessed;
use App\Model\BackTestGroup;
use App\Http\Controllers\ServersController;
use Illuminate\Support\Facades\Config;
use App\Model\Servers;


class TestController extends Controller {

    public function testLog() {
        \Log::emergency('test log abc');
    }
}