<?php namespace App\BackTest\BackTestToBeProcessed\Strategy\Hma;

/**
 * database.php
 *
 * HMA With Take Profit and Stop Loss Test
 *
 * @variable_1   HMA Length Short
 * @variable_2   HMA Lin Reg Length
 *
 * @variable_3   HMA Confirm Slope Requirement
 * @variable_4   HMA Long Confirm Length
 * @variable_5   HMA Long Lin Reg Length
 *
 */

use \DB;

use App\Model\Exchange;
use \App\BackTest\TakeProfitStopLossTest;

use \App\Strategy\HullMovingAverage\HmaLongHmaConfirm;
use \App\Strategy\HullMovingAverage\HmaNoConfirm;
use \App\Strategy\HullMovingAverage\HmaWithArrayDiff;
use \App\Strategy\HullMovingAverage\ArrayDiffWithLinRegLongConfirm;

use \Log;

class HmaTpSlTBP extends \App\BackTest\BackTestToBeProcessed\Base
{
    public function callProcess() {
        /******************************
         * RECORD START
         ******************************/
        $this->groupId = $this->server->current_back_test_group_id;

        /******************************
         * SET BACK TEST
         ******************************/
        //Set
        $backTest = new TakeProfitStopLossTest('Fifty One Hundred Back Test');

        $backTest->strategyRunName = 'Fifty One Hundred EMA Momentum';
        $backTest->strategyId = 6;

        //All Back Tests
        $backTest->currencyId = $this->backTestToBeProcessed->exchange_id;
        $fullExchange = Exchange::find($this->backTestToBeProcessed->exchange_id);

        $backTest->exchange = $fullExchange;
        $backTest->frequencyId = $this->backTestToBeProcessed->frequency_id;

        //Most Back Tests
        $backTest->stopLoss = $this->backTestToBeProcessed->stop_loss_pips;
        $backTest->takeProfit = $this->backTestToBeProcessed->take_profit_pips;

        $backTest->recordBackTestStart($this->backTestToBeProcessed->id);

        //Starting Unix Time to Run Strategy
        $backTest->rateUnixStart = $this->server->rate_unix_time_start;


        /******************************
         * SET STRATEGY
         ******************************/
        //Set Strategy
        if ($this->server->strategy_iteration == 'HMA_LONG_CONFIRM') {
            $strategy = new HmaLongHmaConfirm(7827172, 'BackTestABC', true);
        }
        elseif ($this->server->strategy_iteration == 'HMA_SIMPLE') {
            $strategy = new HmaNoConfirm(7827172, 'BackTestABC', true);
        }
        elseif ($this->server->strategy_iteration == 'HMA_ARRAY_DIFF') {
            $strategy = new HmaWithArrayDiff(7827172, 'BackTestABC', true);
        }
        elseif ($this->server->strategy_iteration == 'ARRAY_DIFF_WITH_LONG_HMA_CONFIRM') {
            $strategy = new ArrayDiffWithLinRegLongConfirm(7827172, 'BackTestABC', true);
        }

        $strategy->strategyId = 5;
        $strategy->strategyDesc = 'fiftyOneHundred';
        $strategy->positionMultiplier = 5;

        //Most Back Tests
        $strategy->stopLossPipAmount = $this->backTestToBeProcessed->stop_loss_pips;
        $strategy->takeProfitPipAmount = $this->backTestToBeProcessed->take_profit_pips;

        //Unique Strategy Variables
        $strategy->hullLength = intval($this->backTestToBeProcessed->variable_1);
        $strategy->hullLinearRegressionLength = floatval($this->backTestToBeProcessed->variable_2);
        $strategy->hullLinearRegressionSlopeRequirement = intval($this->backTestToBeProcessed->variable_3);
        $strategy->hullLongLength = intval($this->backTestToBeProcessed->variable_4);
        $strategy->hullLongLengthLinRegLength = intval($this->backTestToBeProcessed->variable_5);

        //ALL
        $strategy->exchange = $fullExchange;
        $strategy->maxPositions = 5;

        $backTest->strategy = $strategy;

        //Values for Getting Rates
        $backTest->rateCount = intval($this->backTestToBeProcessed->variable_2)*10;
        $backTest->rateIndicatorMin = intval($this->backTestToBeProcessed->variable_2)*3;

        if ($this->server->strategy_iteration == 'ARRAY_DIFF_WITH_LONG_HMA_CONFIRM') {
            $backTest->rateCount = intval($this->backTestToBeProcessed->variable_4)*10;
            $backTest->rateIndicatorMin = intval($this->backTestToBeProcessed->variable_4)*3;
        }

        $backTest->currentRatesProcessed = $backTest->rateCount;

        $backTest->run();
    }
}