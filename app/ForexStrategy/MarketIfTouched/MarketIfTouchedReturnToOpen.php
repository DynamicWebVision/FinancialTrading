<?php

/********************************************
RsiPullbackEmaPriceAction Strategy System under RsiPullback Strategy
Created at: 01/09/19by Brian O'Neill
Description: This strategy is centered around an RSI pullback when price is above a very long ema and then exiting when price rises above a very short ema.
 ********************************************/

namespace App\ForexStrategy\MarketIfTouched;
use \Log;

class MarketIfTouchedReturnToOpen extends \App\ForexStrategy\Strategy  {

    public function getEntryDecision() {
        $lastClose = end($this->rates['full']);
        $this->marketIfTouchedOrderPrice = $lastClose->closeMid;

        if ($this->currentPriceData->mid > $lastClose->closeMid) {
            $this->strategyLogger->logMessage("this->currentPriceData->mid is greater than lastClose->closeMid, new short MIT");

            $this->newShortPosition();
        }
        elseif ($this->currentPriceData->mid < $lastClose->closeMid) {
            $this->strategyLogger->logMessage("this->currentPriceData->mid is less than lastClose->closeMid, new long MIT");
            $this->newLongPosition();
        }
    }

    public function inPositionDecision() {
        $this->closePosition();

        $lastClose = end($this->rates['full']);
        $this->marketIfTouchedOrderPrice = $lastClose->closeMid;

        if ($this->currentPriceData->mid > $lastClose->closeMid) {
            $this->strategyLogger->logMessage("this->currentPriceData->mid is greater than lastClose->closeMid, new short MIT");
            $this->newShortPosition();
        }
        elseif ($this->currentPriceData->mid < $lastClose->closeMid) {
            $this->strategyLogger->logMessage("this->currentPriceData->mid is less than lastClose->closeMid, new long MIT");
            $this->newLongPosition();
        }
    }
    public function checkForNewPosition() {
        $this->setOpenPosition();

        if (!$this->openPosition) {
            $this->decision = $this->getEntryDecision();
        }
        else {
            $this->inPositionDecision();
        }
    }
}