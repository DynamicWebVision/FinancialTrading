<?php

/********************************************
RsiPullbackEmaPriceAction Strategy System under RsiPullback Strategy
Created at: 01/09/19by Brian O'Neill
Description: This strategy is centered around an RSI pullback when price is above a very long ema and then exiting when price rises above a very short ema.
 ********************************************/

namespace App\ForexStrategy\MarketIfTouched;
use \Log;

class MarketIfTouchedReturnToHighLow extends \App\ForexStrategy\Strategy  {

    public function checkForNewPosition() {
        $lastClose = end($this->rates['full']);

        if ($this->currentPriceData->mid > $lastClose->highMid) {
            $this->marketIfTouchedOrderPrice = $lastClose->highMid;

            $this->strategyLogger->logMessage("this->currentPriceData->mid is greater than lastClose->closeMid, new short MIT");

            if ($this->oanda->noExistingOpenOrderOrPositionOnPair()) {
                $this->newShortPosition();
            }
        }
        elseif ($this->currentPriceData->mid < $lastClose->lowMid) {
            $this->marketIfTouchedOrderPrice = $lastClose->lowMid;
            $this->strategyLogger->logMessage("this->currentPriceData->mid is less than lastClose->closeMid, new long MIT");
            if ($this->oanda->noExistingOpenOrderOrPositionOnPair()) {
                $this->newLongPosition();
            }
        }
    }
}