<?php namespace App\BackTest;

use \DB;
use \Log;

class IndicatorRunThroughTest extends \App\BackTest\BackTest  {

    public $oanda;
    public $utility;

    public $passedOuter;

    public function run() {
        Log::debug('Started Running');

        $this->rateCount = 1000;
        $this->rateIndicatorMin = 300;

        $this->getInitialRates();

        $this->currentRatesProcessed = $this->rateCount;

        $this->setLastId();

        $this->startNewPeriod();

        //Start the Indexes


        //Calling Strategy
        while(!$this->lastIdCheck()) {
            //Rotate Rates Check
            $this->startNewPeriod();
            $this->strategy->checkForNewPosition();

        }
        Log::debug('BACK TEST END');
    }
}
