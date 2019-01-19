<?php namespace App\ForexBackTest\BackTestToBeProcessed\ForexStrategy\TwoTier\ThreeMaSystem;

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
use App\ForexStrategy\TwoTier\ThreeMa\SimpleMaNoOtherConfirm;
use App\ForexStrategy\TwoTier\ThreeMa\ExponentialMaNoOtherConfirm;
use App\ForexStrategy\TwoTier\ThreeMa\HullMaNoOtherConfirm;

use \App\ForexBackTest\StopLossOrStrategyClose;

use \Log;

class StayIn extends \App\ForexBackTest\BackTestToBeProcessed\Base
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
        $backTest = new StopLossOrStrategyClose('Ema Momentum 15 Minutes');

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
        if ($this->server->strategy_iteration == 'SIMPLE_MA_NO_OTHER_CONFIRM') {
            $strategy = new SimpleMaNoOtherConfirm(7827172, 'BackTestABC', true);

            $strategy->strategyId = 5;
            $strategy->strategyDesc = 'bt';
            $strategy->positionMultiplier = 5;
        }
        if ($this->server->strategy_iteration == 'EXPONENTIAL_MA_NO_OTHER_CONFIRM') {
            $strategy = new ExponentialMaNoOtherConfirm(7827172, 'BackTestABC', true);

            $strategy->strategyId = 5;
            $strategy->strategyDesc = 'bt';
            $strategy->positionMultiplier = 5;
        }
        if ($this->server->strategy_iteration == 'HULL_MA_NO_OTHER_CONFIRM') {
            $strategy = new HullMaNoOtherConfirm(7827172, 'BackTestABC', true);

            $strategy->strategyId = 5;
            $strategy->strategyDesc = 'bt';
            $strategy->positionMultiplier = 5;
        }

        //Most Back Tests
        $strategy->stopLossPipAmount = $this->backTestToBeProcessed->stop_loss_pips;

        //Unique Strategy Variables
        $strategy->fastMaLength = intval($this->backTestToBeProcessed->variable_1);
        $strategy->mediumMaLength = intval($this->backTestToBeProcessed->variable_2);
        $strategy->slowMaLength = intval($this->backTestToBeProcessed->variable_3);

        //ALL
        $strategy->exchange = $fullExchange;
        $strategy->maxPositions = 5;

        $backTest->strategy = $strategy;

        //Values for Getting Rates
        $backTest->rateCount = intval($this->backTestToBeProcessed->variable_3)*10;
        $backTest->rateIndicatorMin = intval($this->backTestToBeProcessed->variable_3)*5;
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