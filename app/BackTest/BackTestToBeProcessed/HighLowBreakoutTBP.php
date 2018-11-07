<?php namespace App\BackTest\BackTestToBeProcessed;

use \App\Model\HistoricalRates;
use \DB;
use \App\Strategy\FiftyOneHundredMomentum;
use App\Model\Exchange;
use App\Model\DecodeFrequency;
use \App\BackTest\HighLowBreakoutTest;
use \App\Model\BackTestToBeProcessed;
use \Log;

class HighLowBreakoutTBP extends \App\BackTest\BackTestToBeProcessed\Base
{
    public function callProcess() {
        $this->groupId = $this->server->current_back_test_group_id;

        //Clear Log Files to Prevent Storage Overload
        $highLowBreakout = new HighLowBreakoutTest('High Low Breakout');

        $highLowBreakout->strategyRunName = 'High Low Breakout';
        $highLowBreakout->strategyId = 9;

        //All Back Tests
        $highLowBreakout->currencyId = $this->backTestToBeProcessed->exchange_id;
        $fullExchange = Exchange::find($this->backTestToBeProcessed->exchange_id);
        $highLowBreakout->exchange = $fullExchange;
        $highLowBreakout->frequencyId = $this->backTestToBeProcessed->frequency_id;

        //Strategy Unique Variables
        $highLowBreakout->entryPeriodCount = intval($this->backTestToBeProcessed->variable_1);
        $highLowBreakout->exitPeriodCount = intval($this->backTestToBeProcessed->variable_2);
        $highLowBreakout->slowEma = intval($this->backTestToBeProcessed->variable_3);

        $highLowBreakout->recordBackTestStart($this->backTestToBeProcessed->id);

        //Starting Unix Time to Run Strategy
        $highLowBreakout->rateUnixStart = $this->backTestToBeProcessed->variable_5;

        $highLowBreakout->run();
    }
}