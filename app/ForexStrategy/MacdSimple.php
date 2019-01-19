<?php namespace App\ForexStrategy;

class MacdSimple extends \App\ForexStrategy\Strategy  {

    public $oanda;
    public $utility;

    public $fifteenMinuteRates;

    public $passedOuter;

    //Whether you will enter a new position with Phantom
    public function decision($indicators) {

        //Long
        //Macd Crossed Above
        if ($this->decisionIndicators['macdCrossover'] == "crossedAbove") {
            return "long";
        }
        //Short
        //Macd Crossed Below
        elseif ($this->decisionIndicators['macdCrossover'] == "crossedBelow") {
            return "short";
        }
        else {
            return "none";
        }
    }

    public function runStrategy($rates) {
        $this->utility = new \App\Services\Utility();

        $this->oanda->accountId = $this->accountId;

        $this->oanda->strategyId = 2;

        $this->oanda->positionAmount = $this->positionAmount;

        $this->oanda->exchange = $this->exchange->exchange;

        $this->livePosition = $this->checkOpenPosition();

        $this->decisionIndicators['macd'] = $this->indicators->macd($rates, 12, 26, 9);

        $this->decisionIndicators['macdCrossover'] = $this->indicators->checkCrossover($this->decisionIndicators['macd']['macd'], $this->decisionIndicators['macd']['signal']);

        $this->decision = $this->decision($indicators);

        if ($this->livePosition != 0) {
            $this->oanda->closePosition();
        }

        if ($this->checkOpenPositionsThreshold()) {
            $this->calculatePositionAmount();
            if ($this->decision == 'long') {
                $this->closePosition();
                $this->newLongPosition();
            }
            elseif ($this->decision == 'short') {
                $this->closePosition();
                $this->newShortPosition();
            }
        }
    }
}
