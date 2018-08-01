<?php namespace App\BackTest\BackTestToBeProcessed\Strategy\Stochastic;

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
use \App\Strategy\Stochastic\SingleHmaMomentumTpSl;
use \App\Strategy\Stochastic\StochFastOppositeSlow;
use \App\Strategy\Stochastic\StochRevLowAdx;

use \Log;

class StochasticTPSl extends \App\BackTest\BackTestToBeProcessed\Base
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
        if ($this->server->strategy_iteration == 'STOCH_HMA_MOMENTUM_TPSL') {
            $strategy = new SingleHmaMomentumTpSl(7827172, 'BackTestABC', true);

            $strategy->hmaLength = round(intval($this->backTestToBeProcessed->variable_1)*intval($this->backTestToBeProcessed->variable_3));
            $strategy->hmaSlopeMin = intval($this->backTestToBeProcessed->variable_4);

            $strategy->kLength = intval($this->backTestToBeProcessed->variable_1);
            $strategy->smoothingSlow = intval($this->backTestToBeProcessed->variable_2);
            $strategy->smoothingFull = intval($this->backTestToBeProcessed->variable_2);

            $backTest->rateCount = round(intval($this->backTestToBeProcessed->variable_1)*intval($this->backTestToBeProcessed->variable_3)*10);
            $backTest->rateIndicatorMin = round(intval($this->backTestToBeProcessed->variable_1)*intval($this->backTestToBeProcessed->variable_3)*5);

        }
        //Set Strategy
        elseif ($this->server->strategy_iteration == 'STOCH_FAST_OPP_SLOW') {
            $strategy = new StochFastOppositeSlow(7827172, 'BackTestABC', true);

            $strategy->hmaLength = intval($this->backTestToBeProcessed->variable_1);
            $strategy->hmaSlopeMin = intval($this->backTestToBeProcessed->variable_2);

            $strategy->fastStochOverboughtCutoff = intval($this->backTestToBeProcessed->variable_3);
            $strategy->slowStochOverboughtCutoff = intval($this->backTestToBeProcessed->variable_4);

            $backTest->rateCount = intval($this->backTestToBeProcessed->variable_1)*10;
            $backTest->rateIndicatorMin = intval($this->backTestToBeProcessed->variable_1)*5;
        }
        elseif ($this->server->strategy_iteration == 'STOCH_REV_LOW_ADX') {
            $strategy = new StochRevLowAdx(7827172, 'BackTestABC', true);

            $strategy->stochOverboughtCutoff = intval($this->backTestToBeProcessed->variable_1);
            $strategy->adxUndersoldThreshold = intval($this->backTestToBeProcessed->variable_2);
            $strategy->adxLength = intval($this->backTestToBeProcessed->variable_3);
            $strategy->stochKLength = intval($this->backTestToBeProcessed->variable_3);

            $backTest->rateCount = intval($this->backTestToBeProcessed->variable_3)*10;
            $backTest->rateIndicatorMin = intval($this->backTestToBeProcessed->variable_3)*5;
        }


        $strategy->strategyId = 5;
        $strategy->strategyDesc = 'Stoch';
        $strategy->positionMultiplier = 5;

        //Most Back Tests
        $strategy->stopLossPipAmount = $this->backTestToBeProcessed->stop_loss_pips;
        $strategy->takeProfitPipAmount = $this->backTestToBeProcessed->take_profit_pips;


        //ALL
        $strategy->exchange = $fullExchange;
        $strategy->backTestHelpers->exchange = $fullExchange;
        $strategy->maxPositions = 5;
        $strategy->strategyLogger = new StrategyLogger();

        $backTest->strategy = $strategy;
        $backTest->rateLevel = 'both';
        //Values for Getting Rates

        $backTest->currentRatesProcessed = $backTest->rateCount;

        $backTest->run();

    }
}