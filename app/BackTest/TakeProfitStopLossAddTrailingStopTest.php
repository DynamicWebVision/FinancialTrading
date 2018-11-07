<?php namespace App\BackTest;

use \DB;
use \Log;

class TakeProfitStopLossAddTrailingStopTest extends \App\BackTest\BackTest  {
    public $oanda;
    public $utility;

    public $passedOuter;

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

            //Check to see if a stop loss was it
            if (sizeof($this->strategy->backTestPositions) > 0) {
                $this->checkTakeProfit();
            }

            if (($this->strategy->backTestCurrentPosition == "long" || $this->strategy->backTestCurrentPosition == "short") && !$this->strategy->backTestTrailingStop) {

                $this->strategy->checkToAddTrailingStop($this->currentRates);

                if ($this->strategy->backTestClosedAllPositions) {
                    $this->recordClosedPositionPossibleNewPositionOrNoOpen($this->currentPriceData);
                }
            }
            elseif (($this->strategy->backTestCurrentPosition == "long" || $this->strategy->backTestCurrentPosition == "short") && $this->strategy->backTestTrailingStop) {
                if ($this->strategy->backTestCurrentPosition == "long") {
                    $this->processLongTrailingStop();
                }
                else {
                    $this->processShortTrailingStop();
                }
            }
            else {
                $this->strategy->checkForNewPosition($this->currentRates);
            }
        }
        $this->endBackTest();

        Log::critical('BACK TEST END');
    }
}
