<?php namespace App\Http\Controllers;



//END OF Backtest Declarations
use \Log;


use App\Model\BackTestToBeProcessed;
use App\Model\BackTestGroup;
use App\Http\Controllers\ServersController;
use Illuminate\Support\Facades\Config;
use App\Model\Forex\BackTest;


class TestController extends Controller {

    public function testLog() {
        $model = \App\Model\Forex\BackTest::find(1);
        dd($model);
    }
}