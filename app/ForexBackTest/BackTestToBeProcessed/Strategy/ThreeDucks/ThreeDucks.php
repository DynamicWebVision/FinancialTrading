<?php namespace App\ForexBackTest\BackTestToBeProcessed\Strategy\ThreeDucks;

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
use \App\ForexBackTest\TakeProfitStopLossTest;
use \App\Services\StrategyLogger;
use \App\ForexStrategy\ThreeDucks\ThreeDucksEntryPTExitClosedCross;
use \App\ForexStrategy\ThreeDucks\ThreeDucksEntryOpenCloseExitClosedCross;

use \Log;

class ThreeDucks extends \App\ForexBackTest\BackTestToBeProcessed\Base
{
    public function callProcess() {
        /******************************
         * SET BACK TEST
         ******************************/
        //Set
        $backTest = new TakeProfitStopLossTest('Three Ducks');

        $backTest->strategyRunName = 'Three Ducks';
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
        if ($this->server->strategy_iteration == 'TD_ENTRY_PT_EXIT_CLOSED_CROSS') {
            $backTest->rateLevel = 'both';

            $strategy = new ThreeDucksEntryPTExitClosedCross(1,1,true);
            $strategy->orderType = 'MARKET_IF_TOUCHED';

            $strategy->fastMa = intval($this->backTestToBeProcessed->variable_1);
            $strategy->mediumMa = intval($this->backTestToBeProcessed->variable_1)*$this->backTestToBeProcessed->variable_2;
            $strategy->slowMa = $strategy->mediumMa*$this->backTestToBeProcessed->variable_3;

            $strategy->stopLossTrueRangeMultiplier = floatval($this->backTestToBeProcessed->variable_4);

            //Strategy Will Determine Exit
            $strategy->takeProfitPipAmount = 0;

            //Values for Getting Rates
            $backTest->rateCount = intval($strategy->slowMa)*5;
            $backTest->rateIndicatorMin = intval($strategy->slowMa)*2;
            $backTest->currentRatesProcessed = $backTest->rateCount;
        }
        elseif ($this->server->strategy_iteration == 'THREE_DUCKS_BAR_CLOSES_ENTRY') {
            $backTest->rateLevel = 'both';

            $strategy = new ThreeDucksEntryOpenCloseExitClosedCross(1,1,true);

            $strategy->fastMa = intval($this->backTestToBeProcessed->variable_1);
            $strategy->mediumMa = intval($this->backTestToBeProcessed->variable_1)*$this->backTestToBeProcessed->variable_2;
            $strategy->slowMa = $strategy->mediumMa*$this->backTestToBeProcessed->variable_3;

            $strategy->stopLossTrueRangeMultiplier = floatval($this->backTestToBeProcessed->variable_4);

            //Strategy Will Determine Exit
            $strategy->takeProfitPipAmount = 0;

            //Values for Getting Rates
            $backTest->rateCount = intval($strategy->slowMa)*5;
            $backTest->rateIndicatorMin = intval($strategy->slowMa)*2;
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