<?php namespace App\ForexBackTest\BackTestToBeProcessed;

use \App\Model\HistoricalRates;
use \DB;
use \App\ForexStrategy\FiftyOneHundredMomentum;
use App\Model\Exchange;
use App\Model\DecodeFrequency;
use \App\ForexBackTest\StopLossWithTrailingStopTest;
use \App\Model\BackTestToBeProcessed;
use \Log;

class FiftyOneHundredEmaTBP extends \App\ForexBackTest\BackTestToBeProcessed\Base
{
    public function callProcess() {
        $this->groupId = $this->server->current_back_test_group_id;

        //Clear Log Files to Prevent Storage Overload
        $backTest = new StopLossWithTrailingStopTest('Fifty One Hundred Back Test');

        $backTest->strategyRunName = 'Fifty One Hundred EMA Momentum';
        $backTest->strategyId = 6;

        //All Back Tests
        $backTest->currencyId = $this->backTestToBeProcessed->exchange_id;
        $fullExchange = Exchange::find($this->backTestToBeProcessed->exchange_id);

        $backTest->exchange = $fullExchange;
        $backTest->frequencyId = $this->backTestToBeProcessed->frequency_id;

        //Most Back Tests
        $backTest->stopLoss = $this->backTestToBeProcessed->stop_loss_pips;
        $backTest->trailingStop = $this->backTestToBeProcessed->trailing_stop_pips;

        $backTest->recordBackTestStart($this->backTestToBeProcessed->id);

        //Starting Unix Time to Run Strategy
        $backTest->rateUnixStart = $this->backTestToBeProcessed->variable_5;

        //Set Strategy
        $strategy = new FiftyOneHundredMomentum(7827172, 'BackTestABC', true);
        $strategy->strategyId = 5;
        $strategy->strategyDesc = 'fiftyOneHundred';
        $strategy->positionMultiplier = 5;

        $strategy->exchange = $fullExchange->exchange;

        //Most Back Tests
        $strategy->stopLossPipAmount = $this->backTestToBeProcessed->stop_loss_pips;
        $strategy->optionalTrailingStopAmount = $this->backTestToBeProcessed->trailing_stop_pips;

        //Unique Strategy Variables
        $strategy->fastEmaLength = intval($this->backTestToBeProcessed->variable_1);
        $strategy->slowEmaLength = intval($this->backTestToBeProcessed->variable_2);
        $strategy->linearRegressionLength = intval($this->backTestToBeProcessed->variable_3);
        $strategy->takeProfitPipAmount = 1;

        $strategy->maxPositions = 5;

        $backTest->strategy = $strategy;

        //Values for Getting Rates
        $backTest->rateCount = intval($this->backTestToBeProcessed->variable_2)*10;
        $backTest->shortRateIndicatorMin = intval($this->backTestToBeProcessed->variable_2)*3;
        $backTest->currentRatesProcessed = $backTest->rateCount;

        $backTest->run();
    }
}