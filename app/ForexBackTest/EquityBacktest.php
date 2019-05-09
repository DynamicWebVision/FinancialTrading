<?php namespace App\ForexBackTest;

use \App\Model\HistoricalRates;
use App\ForexStrategy\EmaSimpleMomentum;
use \DB;
use App\EquityBacktest\EquityBackTestBroker;
use \Log;

class EquityBacktest extends \App\ForexBackTest\BackTest  {

    public function run() {
        $this->getInitialRates();

        //Calling Strategy
        while(!$this->lastIdCheck()) {

            $this->startNewPeriod();

            $this->strategy->checkForNewPosition($this->currentRates);

            $this->endPeriod();
        }
        $this->endBackTest();

        Log::critical('BACK TEST END');
    }
}
