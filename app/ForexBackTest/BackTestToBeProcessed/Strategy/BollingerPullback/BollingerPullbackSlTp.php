<?php namespace App\ForexBackTest\BackTestToBeProcessed\Strategy\Bollinger;

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

use \App\ForexStrategy\Bollinger\AdxBelowBollinger;

use \Log;

class BollingerSlTp extends \App\ForexBackTest\BackTestToBeProcessed\Base
{
    public function callProcess() {
        /******************************
         * SET BACK TEST
         ******************************/
        //Set
        $backTest = new TakeProfitStopLossTest('Ema Momentum 15 Minutes');

        $backTest->strategyRunName = 'Bollinger Pullback';
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
        if ($this->server->strategy_iteration == 'BOLLINGER_ADX_PULLBACK_ASK_BID') {
            $strategy = new AdxBelowBollingerPullback(7827172, 'BackTestABC', true);
            $backTest->rateLevel = 'both';
        }

        $strategy->strategyId = 5;
        $strategy->strategyDesc = 'Ema Momentum';
        $strategy->positionMultiplier = 5;

        //Most Back Tests
        $strategy->stopLossPipAmount = $this->backTestToBeProcessed->stop_loss_pips;
        $strategy->takeProfitPipAmount = $this->backTestToBeProcessed->take_profit_pips;

        //Unique Strategy Variables
        $strategy->bollingerLength = intval($this->backTestToBeProcessed->variable_1);
        $strategy->bollingerSdMultiplier = floatval($this->backTestToBeProcessed->variable_2);
        $strategy->adxCutOff = intval($this->backTestToBeProcessed->variable_3);


        //ALL
        $strategy->exchange = $fullExchange;
        $strategy->maxPositions = 5;

        $backTest->strategy = $strategy;

        //Values for Getting Rates
        $backTest->rateCount = intval($this->backTestToBeProcessed->variable_1)*10;
        $backTest->rateIndicatorMin = intval($this->backTestToBeProcessed->variable_1)*5;
        $backTest->currentRatesProcessed = $backTest->rateCount;

        $backTest->run();
    }
}