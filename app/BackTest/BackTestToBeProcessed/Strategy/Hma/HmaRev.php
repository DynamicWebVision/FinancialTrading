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
use \App\Services\StrategyLogger;
use \App\Strategy\HullMovingAverage\HmaSwitchShortConfirmLongAdx;

use \Log;

class HmaRev extends \App\BackTest\BackTestToBeProcessed\Base
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
        if ($this->server->strategy_iteration == 'HMA_REV_SLOW_ADX') {
            $backTest->rateLevel = 'both';

            $strategy = new HmaSwitchShortConfirmLongAdx(1,1,true);

            $strategy->fastHma = intval($this->backTestToBeProcessed->variable_1);
            $strategy->slowHma = intval($this->backTestToBeProcessed->variable_2);

            $strategy->adxLength = 14;
            $strategy->adxUndersoldThreshold = intval($this->backTestToBeProcessed->variable_5);

            $strategy->slowHmaSlopeMin = floatval($this->backTestToBeProcessed->variable_3);

            $strategy->stopLossTrueRangeMultiplier = floatval($this->backTestToBeProcessed->variable_4);

            //Strategy Will Determine Exit
            $strategy->takeProfitPipAmount = 0;

            //Values for Getting Rates
            $backTest->rateCount = intval($this->backTestToBeProcessed->variable_2)*10;
            $backTest->rateIndicatorMin = intval($this->backTestToBeProcessed->variable_2)*3;
            $backTest->currentRatesProcessed = $backTest->rateCount;
        }

        $strategy->strategyLogger = new StrategyLogger();

        $strategy->strategyId = 5;
        $strategy->strategyDesc = 'HMA Rev';
        $strategy->positionMultiplier = 5;

        //Most Back Tests
        $strategy->stopLossPipAmount = $this->backTestToBeProcessed->stop_loss_pips;

        //ALL
        $strategy->exchange = $fullExchange;
        $strategy->backTestHelpers->exchange = $fullExchange;
        $strategy->maxPositions = 5;

        $backTest->strategy = $strategy;

        $backTest->run();
    }
}