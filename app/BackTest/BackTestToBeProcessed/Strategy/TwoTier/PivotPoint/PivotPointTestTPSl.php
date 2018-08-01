<?php namespace App\BackTest\BackTestToBeProcessed\Strategy\TwoTier\PivotPoint;

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

use \App\Strategy\TwoTier\PivotPoints\HmaMomentumConfirm;

use \Log;

class PivotPointTestTPSl extends \App\BackTest\BackTestToBeProcessed\Base
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
        if ($this->server->strategy_iteration == 'PIVOT_HMA_MOMENTUM') {
            $strategy = new HmaMomentumConfirm(7827172, 'BackTestABC', true);

            //Unique Strategy Variables
            $strategy->hullLength = intval($this->backTestToBeProcessed->variable_1);
            $strategy->hmaSlopeMin = floatval($this->backTestToBeProcessed->variable_2);

            $backTest->rateCount = intval($this->backTestToBeProcessed->variable_1)*10;
            $backTest->rateIndicatorMin = intval($this->backTestToBeProcessed->variable_1)*5;
        }

        $strategy->strategyId = 5;
        $strategy->strategyDesc = 'Ema Momentum';
        $strategy->positionMultiplier = 5;

        //Most Back Tests
        $strategy->stopLossPipAmount = $this->backTestToBeProcessed->stop_loss_pips;
        $strategy->takeProfitPipAmount = $this->backTestToBeProcessed->take_profit_pips;


        //$strategy->linearRegressionLength = intval($this->backTestToBeProcessed->variable_3);


        //ALL
        $strategy->exchange = $fullExchange;
        $strategy->maxPositions = 5;

        $backTest->strategy = $strategy;

        //Values for Getting Rates

        $backTest->currentRatesProcessed = $backTest->rateCount;

        $backTest->slowFrequencyId = $this->backTestToBeProcessed->slow_frequency_id;
        $backTest->slowRateIndicatorMin = floatval($this->backTestToBeProcessed->variable_1)*5;
        $backTest->slowRateIndex = $backTest->slowRateIndicatorMin - 1;
        $backTest->slowRateCount = floatval($this->backTestToBeProcessed->variable_1)*10;
        $backTest->twoTierRates = true;

        $backTest->slowRateLevel = 'both';

        $backTest->run();
    }
}