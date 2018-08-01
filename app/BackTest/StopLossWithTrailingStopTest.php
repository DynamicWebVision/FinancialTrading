<?php namespace App\BackTest;

use \App\Model\HistoricalRates;
use \DB;
use \App\Strategy\FiftyOneHundredMomentum;
use \Log;

class StopLossWithTrailingStopTest extends \App\BackTest\BackTest  {

    public $fastEma;
    public $slowEma;
    public $linearRegressionLength;

    public function run() {
        Log::debug('Started Running');

        $this->getInitialRates();

        $this->setLastId();

        $this->startNewPeriod();

        //Calling Strategy
        while(!$this->lastIdCheck()) {

            //Due all the tasks for starting a new period, getting current rates, price data, etc.
            $this->startNewPeriod();

            if (($this->strategy->backTestCurrentPosition == "long" || $this->strategy->backTestCurrentPosition == "short") && !$this->strategy->backTestTrailingStop) {

                $this->strategy->checkToAddTrailingStop($this->currentRates);

                if ($this->strategy->backTestClosedAllPositions) {
                    $this->recordClosedPositionAfterNewPositionSkippingTrailingStop($this->currentPriceData);
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
