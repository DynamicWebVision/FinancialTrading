<?php namespace App\BackTest\BackTestToBeProcessed\Strategy\TwoTier\SlowOverBoughtFastMomentum;

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

use \App\Strategy\TwoTier\SlowOverBoughtFastMomentum\SlowHmaStochFastHmaCross;
use \App\Strategy\TwoTier\SlowOverBoughtFastMomentum\SlowHmaStochRevFastHmaCross;
use \App\Services\StrategyLogger;
use \Log;

class SlowOverboughtFastMomentumTpSL extends \App\BackTest\BackTestToBeProcessed\Base
{
    public function callProcess() {
        /******************************
         * SET BACK TEST
         ******************************/
        //Set
        $backTest = new TakeProfitStopLossTest('Ema Momentum 15 Minutes');

        $backTest->strategyRunName = 'Ema Momentum 15 Minutes';
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
        if ($this->server->strategy_iteration == 'STOCH_OVBT_HMA_CROSS' || $this->server->strategy_iteration == 'STOCH_OVBT_REV_HMA_CROSS') {
            if ($this->server->strategy_iteration == 'STOCH_OVBT_HMA_CROSS') {
                $strategy = new SlowHmaStochFastHmaCross(7827172, 'BackTestABC', true);
            }
            elseif ($this->server->strategy_iteration == 'STOCH_OVBT_REV_HMA_CROSS') {
                $strategy = new SlowHmaStochRevFastHmaCross(7827172, 'BackTestABC', true);
                $backTest->rateLevel = 'both';
            }

            /**************Unique Strategy Variables**************/
            //Slow Variables For Strategy
            $strategy->slowHmaLength = intval($this->backTestToBeProcessed->variable_3);
            $strategy->slowHmaPipSlopeMin = floatval($this->backTestToBeProcessed->variable_4);
            $strategy->slowRatesStochOverboughtCutoff = intval($this->backTestToBeProcessed->variable_5);

            //Slow Variables for Running Backtest
            $backTest->slowRateCount = floatval($this->backTestToBeProcessed->variable_3)*10;
            $backTest->slowRateIndicatorMin = floatval($this->backTestToBeProcessed->variable_3)*5;
            $backTest->slowRateLevel = 'both';

            //Fast Variables For Strategy
            $strategy->fastFastHmaLength = intval($this->backTestToBeProcessed->variable_1);
            $strategy->fastSlowHmaLength = intval($this->backTestToBeProcessed->variable_2);

            //Use Highest Variable for Rate Count and Indicator Min
            $backTest->rateCount = intval($this->backTestToBeProcessed->variable_2)*10;
            $backTest->rateIndicatorMin = intval($this->backTestToBeProcessed->variable_2)*5;
        }

        //Most Back Tests
        $strategy->stopLossPipAmount = $this->backTestToBeProcessed->stop_loss_pips;
        $strategy->takeProfitPipAmount = $this->backTestToBeProcessed->take_profit_pips;
        $strategy->strategyLogger = new StrategyLogger();

        //ALL
        $strategy->exchange = $fullExchange;
        $strategy->backTestHelpers->exchange = $fullExchange;
        $backTest->strategy = $strategy;

        //Values for Getting Rates
        $backTest->currentRatesProcessed = $backTest->rateCount;
        $backTest->slowFrequencyId = $this->backTestToBeProcessed->slow_frequency_id;
        $backTest->slowRateIndex = $backTest->slowRateIndicatorMin - 1;
        $backTest->twoTierRates = true;

        $backTest->run();
    }
}