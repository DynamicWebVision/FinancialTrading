<?php namespace App\BackTest;

use \App\Model\HistoricalRates;
use App\Strategy\EmaSimpleMomentum;
use \DB;
use \App\Strategy\FiftyOneHundredMomentum;
use \Log;

class TakeProfitStopLossTest extends \App\BackTest\BackTest  {

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

            $this->strategy->checkForNewPosition($this->currentRates);

            //Close the Position if the in the position it closed
//            if ($this->strategy->backTestClosedAllPositions) {
//                $this->closeDueToStrategyRules();
//            }
            $this->endPeriod();
        }
        $this->endBackTest();

        Log::critical('BACK TEST END');
    }
}
