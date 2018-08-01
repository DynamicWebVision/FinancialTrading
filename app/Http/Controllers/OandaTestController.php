<?php namespace App\Http\Controllers;

use \App\Model\Servers;
use \App\Services\OandaV20;
use \App\Services\Oanda;

use \DB;
use \Log;
use Request;

class OandaTestController extends Controller {

    public function test() {
        $oanda = new OandaV20();
        $oanda->accountId = '101-001-7608904-002';
        $oanda->exchange = 'EUR_USD';
        $oanda->timePeriod = 'M15';

        $test = $oanda->getInstrumentCandles();
    }

    public function testAdx() {
        $oanda = new OandaV20();
        $oanda->accountId = '101-001-7608904-002';
        $oanda->exchange = 'EUR_USD';
        $oanda->timePeriod = 'M15';

        $test = $oanda->getInstrumentCandles();
    }
}