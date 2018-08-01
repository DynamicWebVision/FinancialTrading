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
use \App\BackTest\StopLossOrStrategyClose;

use \App\Strategy\HmaCrossover\HmaXAdxSC;
use \App\Services\StrategyLogger;

use \Log;

class HmaCrossoverStayIn extends \App\BackTest\BackTestToBeProcessed\Base
{
    public function callProcess() {

        /******************************
         * SET BACK TEST
         ******************************/
        //Set
        $backTest = new StopLossOrStrategyClose('Fifty One Hundred Back Test');

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
        if ($this->server->strategy_iteration == 'HMA_CROSS_ADX_SC') {
            $strategy = new HmaXAdxSC(7827172, 'BackTestABC', true);
            $backTest->rateLevel = 'both';

            $strategy->hmaFastLength = intval($this->backTestToBeProcessed->variable_1);
            $strategy->hmaSlowLength = round(intval($this->backTestToBeProcessed->variable_1)*floatval($this->backTestToBeProcessed->variable_2));

            $strategy->adxCutoff = intval($this->backTestToBeProcessed->variable_3);
            $strategy->adxPeriodLength = round(($strategy->hmaFastLength + $strategy->hmaSlowLength)/2);

            $strategy->backTesting = true;

            //Values for Getting Rates
            $backTest->rateCount = $strategy->hmaSlowLength*10;
            $backTest->rateIndicatorMin = $strategy->hmaSlowLength*3;
        }

        //Most Back Tests
        $strategy->stopLossPipAmount = $this->backTestToBeProcessed->stop_loss_pips;

        $strategy->strategyLogger = new StrategyLogger();

        $strategy->backTestHelpers->exchange = $fullExchange;

        //ALL
        $strategy->exchange = $fullExchange;

        $backTest->strategy = $strategy;

        $backTest->currentRatesProcessed = $backTest->rateCount;

        $backTest->run();
    }
}