<?php namespace App\Http\Controllers;

use \App\Model\Servers;
use \App\Services\OandaV20;
use \App\Services\Oanda;

use \DB;
use \Log;
use Request;

class TdAmeritradeController extends Controller {

    public function getCode() {
        $request = Request::all();

        $debug = 1;
    }
}