<?php namespace App\BackTest\BackTestToBeProcessed\Strategy\ThreeDucks;

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
use \App\Strategy\PreviousCandlePriceHighLow\HighLowSuperSimpleHoldOnePeriod;

use \Log;

class PreviousPeriodPriceBreakout extends \App\BackTest\BackTestToBeProcessed\Base
{
    public function callProcess() {
        /******************************
         * SET BACK TEST
         ******************************/
        //Set
        $backTest = new TakeProfitStopLossTest('PreviousCandlePriceHighLow');

        $backTest->strategyRunName = 'PreviousCandlePriceHighLow';
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
        if ($this->server->strategy_iteration == 'PREVIOUS_PRICE_LOW_HIGH') {
            $backTest->rateLevel = 'both';

            $strategy = new HighLowSuperSimpleHoldOnePeriod(1,1,true);
            $strategy->orderType = 'MARKET_IF_TOUCHED';

            //Values for Getting Rates
            $backTest->rateCount = 10;
            $backTest->rateIndicatorMin = 1;
            $backTest->currentRatesProcessed = $backTest->rateCount;
        }

        $strategy->strategyLogger = new StrategyLogger();

        $strategy->strategyId = 5;
        $strategy->strategyDesc = 'Three Ducks';
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