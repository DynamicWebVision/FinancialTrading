<?php namespace App\ForexBackTest\BackTestToBeProcessed;

use \App\Model\HistoricalRates;
use \DB;

use App\Model\Exchange;
use App\Model\DecodeFrequency;
use \App\ForexBackTest\HmaTrendBackTest;
use \App\Model\BackTestToBeProcessed;
use \Log;

class HmaTrendTBP extends \App\ForexBackTest\BackTestToBeProcessed\Base
{
    public function callProcess() {
        $this->groupId = $this->server->current_back_test_group_id;

        //Clear Log Files to Prevent Storage Overload
        $hmaSimpleTrend = new HmaTrendBackTest('Fifty One Hundred Back Test');

        $hmaSimpleTrend->strategyRunName = 'HMA Momentum';
        $hmaSimpleTrend->strategyId = 6;

        //All Back Tests
        $hmaSimpleTrend->currencyId = $this->backTestToBeProcessed->exchange_id;
        $fullExchange = Exchange::find($this->backTestToBeProcessed->exchange_id);
        $hmaSimpleTrend->exchange = $fullExchange;
        $hmaSimpleTrend->frequencyId = $this->backTestToBeProcessed->frequency_id;

        //Most Back Tests
        //$hmaSimpleTrend->stopLoss = $this->backTestToBeProcessed->stop_loss_pips;

        //Strategy Unique Variables
        $hmaSimpleTrend->hullLength = intval($this->backTestToBeProcessed->variable_1);
        $hmaSimpleTrend->hullLinearRegressionSlopeRequirement = $this->backTestToBeProcessed->variable_2;
        $hmaSimpleTrend->hullLinearRegressionLength = intval($this->backTestToBeProcessed->variable_3);

        $hmaSimpleTrend->recordBackTestStart($this->backTestToBeProcessed->id);

        //Starting Unix Time to Run Strategy
        $hmaSimpleTrend->rateUnixStart = $this->backTestToBeProcessed->variable_5;

        $hmaSimpleTrend->run();
    }
}