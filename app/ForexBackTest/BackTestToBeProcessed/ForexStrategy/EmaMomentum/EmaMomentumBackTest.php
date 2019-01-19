<?php namespace App\ForexBackTest\BackTestToBeProcessed\ForexStrategy\EmaMomentum;

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
use \App\ForexStrategy\EmaMomentum\EmaPriceCross;
use \App\ForexStrategy\EmaMomentum\EmaXAdxConfirmWithMarketIfTouched;

use \Log;

class EmaMomentumBackTest extends \App\ForexBackTest\BackTestToBeProcessed\Base
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
        if ($this->server->strategy_iteration == 'EMA_PRICE_CROSS') {
            $backTest->rateLevel = 'simple';

            $strategy = new EmaPriceCross(1,1,true);

            $strategy->emaLength = intval($this->backTestToBeProcessed->variable_1);

            //Strategy Will Determine Exit
            $strategy->takeProfitPipAmount = 0;

            //Values for Getting Rates
            $backTest->rateCount = intval($this->backTestToBeProcessed->variable_1)*10;
            $backTest->rateIndicatorMin = intval($this->backTestToBeProcessed->variable_1)*3;
            $backTest->currentRatesProcessed = $backTest->rateCount;

            $strategy->strategyLogger = new StrategyLogger();
        }
        elseif ($this->server->strategy_iteration == 'EMA_ADX_CONFIRM_MARKET_TOUCHED') {
            $backTest->rateLevel = 'both';

            $strategy = new EmaXAdxConfirmWithMarketIfTouched(1,1,true);
            $strategy->orderType = 'MARKET_IF_TOUCHED';

            $strategy->fastEma = intval($this->backTestToBeProcessed->variable_1);
            $strategy->slowEma = intval($this->backTestToBeProcessed->variable_2);

            $strategy->adxUndersoldThreshold = 25;

            if (intval($this->backTestToBeProcessed->variable_3) > 0) {
                $strategy->trueRangeLength = intval($this->backTestToBeProcessed->variable_3);
            }

            $rateMultiplier = max([$this->backTestToBeProcessed->variable_1, $this->backTestToBeProcessed->variable_2, $this->backTestToBeProcessed->variable_3, 14]);

            $strategy->takeProfitTrueRangeMultiplier = floatval($this->backTestToBeProcessed->variable_4);
            $strategy->stopLossTrueRangeMultiplier = floatval($this->backTestToBeProcessed->variable_5);

            //Values for Getting Rates
            $backTest->rateCount = intval($rateMultiplier)*10;
            $backTest->rateIndicatorMin = intval($rateMultiplier)*3;
            $backTest->currentRatesProcessed = $backTest->rateCount;

            $strategy->strategyLogger = new StrategyLogger();
        }

        $strategy->strategyId = 5;
        $strategy->strategyDesc = 'EMA Momentum';
        $strategy->positionMultiplier = 5;

        //Most Back Tests
        $strategy->stopLossPipAmount = $this->backTestToBeProcessed->stop_loss_pips;
        // $strategy->takeProfitPipAmount = $this->backTestToBeProcessed->take_profit_pips;

        //ALL
        $strategy->exchange = $fullExchange;
        $strategy->backTestHelpers->exchange = $fullExchange;
        $strategy->maxPositions = 5;

        $backTest->strategy = $strategy;

        $backTest->run();
    }
}