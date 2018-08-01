<?php namespace App\BackTest\BackTestToBeProcessed\Strategy\TwoTier;

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

use \App\Strategy\TwoTier\EmaFastHmaSlow;

use \Log;

class EmaFastHmaSlowBT extends \App\BackTest\BackTestToBeProcessed\Base
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
        $backTest = new TakeProfitStopLossTest('Ema Momentum 15 Minutes');

        $backTest->strategyRunName = 'BACKTESTING';
        $backTest->strategyId = 6;

        //All Back Tests
        $backTest->currencyId = $this->backTestToBeProcessed->exchange_id;
        $fullExchange = Exchange::find($this->backTestToBeProcessed->exchange_id);

        $backTest->exchange = $fullExchange;
        $backTest->frequencyId = $this->backTestToBeProcessed->frequency_id;

        //Most Back Tests
        $backTest->stopLoss = $this->backTestToBeProcessed->stop_loss_pips;
        $backTest->takeProfit = $this->backTestToBeProcessed->take_profit_pips;
        $backTest->rateLevel = 'both';

        $backTest->recordBackTestStart($this->backTestToBeProcessed->id);

        //Starting Unix Time to Run Strategy
        $backTest->rateUnixStart = $this->server->rate_unix_time_start;


        /******************************
         * SET STRATEGY
         ******************************/
        //Set Strategy
        if ($this->server->strategy_iteration == 'EMA_CROSS_FAST_HMA_SLOW_SIMPLE') {
            $strategy = new EmaFastHmaSlow(7827172, 'BackTestABC', true);

            $strategy->strategyId = 5;
            $strategy->strategyDesc = 'bt';
            $strategy->positionMultiplier = 5;
        }

        //Most Back Tests
        $strategy->stopLossPipAmount = $this->backTestToBeProcessed->stop_loss_pips;
        $strategy->takeProfitPipAmount = $this->backTestToBeProcessed->take_profit_pips;

        //Unique Strategy Variables
        $strategy->fastEmaLength = intval($this->backTestToBeProcessed->variable_1);
        $strategy->slowEmaLength = floatval($this->backTestToBeProcessed->variable_2);
        $strategy->slowHullLength = floatval($this->backTestToBeProcessed->variable_3);

        $strategy->adxCutoff = 20;
        $strategy->adxPeriodLength = 14;


        //ALL
        $strategy->exchange = $fullExchange;
        $strategy->maxPositions = 5;

        $backTest->strategy = $strategy;

        //Values for Getting Rates
        $backTest->rateCount = intval($this->backTestToBeProcessed->variable_2)*10;
        $backTest->rateIndicatorMin = intval($this->backTestToBeProcessed->variable_2)*5;
        $backTest->currentRatesProcessed = $backTest->rateCount;

        //Because It is Two Tier
        $backTest->slowFrequencyId = $this->backTestToBeProcessed->slow_frequency_id;
        $backTest->slowRateIndicatorMin = floatval($this->backTestToBeProcessed->variable_3)*5;
        $backTest->slowRateIndex = $backTest->slowRateIndicatorMin - 1;
        $backTest->slowRateCount = floatval($this->backTestToBeProcessed->variable_3)*10;
        $backTest->twoTierRates = true;

        $backTest->run();
    }
}