<?php namespace App\BackTest\BackTestToBeProcessed\Strategy\HmaCrossover;

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

use \App\Strategy\HmaCrossover\HmaXAdxPointConfirm;

use \Log;

class HmaCrossTPSL extends \App\BackTest\BackTestToBeProcessed\Base
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
        if ($this->server->strategy_iteration == 'HMA_CROSSOVER_ADX') {
            $strategy = new HmaXAdxPointConfirm(7827172, 'BackTestABC', true);
        }

        $strategy->strategyId = 5;
        $strategy->strategyDesc = 'HMA X';
        $strategy->positionMultiplier = 5;

        //Most Back Tests
        $strategy->stopLossPipAmount = $this->backTestToBeProcessed->stop_loss_pips;
        $strategy->takeProfitPipAmount = $this->backTestToBeProcessed->take_profit_pips;

        //Unique Strategy Variables
        $strategy->hmaFastLength = intval($this->backTestToBeProcessed->variable_1);
        $strategy->hmaSlowLength = floatval($this->backTestToBeProcessed->variable_2);
        $strategy->adxCutoff = intval($this->backTestToBeProcessed->variable_3);
        $strategy->adxPeriodLength = 14;

        //ALL
        $strategy->exchange = $fullExchange;
        $strategy->backTestHelpers->exchange = $fullExchange;

        $strategy->maxPositions = 5;

        $backTest->strategy = $strategy;

        //Values for Getting Rates
        $backTest->rateCount = intval($this->backTestToBeProcessed->variable_2)*60;
        $backTest->rateIndicatorMin = intval($this->backTestToBeProcessed->variable_2)*30;
        $backTest->currentRatesProcessed = $backTest->rateCount;
        $backTest->rateLevel = 'both';

        $backTest->run();
    }
}