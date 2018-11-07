<?php namespace App\BackTest;

use \App\Model\HistoricalRates;
use App\Strategy\EmaSimpleMomentum;
use \DB;
use \App\Strategy\FiftyOneHundredMomentum;
use \Log;

class StopLossOrStrategyClose extends \App\BackTest\BackTest  {

    public function run() {
        Log::debug('Started Running');

        $this->getInitialRates();

        $this->currentRatesProcessed = $this->rateCount;

        $this->setLastId();

        $this->startNewPeriod();

        //Calling Strategy
        while(!$this->lastIdCheck()) {
            //Due all the tasks for starting a new period, getting current rates, price data, etc.
            $this->startNewPeriod();

            $this->strategy->setOpenPosition();
            $this->strategy->checkForNewPosition($this->currentRates);
            $this->endPeriod();
        }
        $this->endBackTest();

        Log::critical('BACK TEST END');
    }
}
