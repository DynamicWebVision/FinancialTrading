<?php namespace App\ForexBackTest\BackTestToBeProcessed\Strategy\Hlhb;

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
use \App\ForexBackTest\BackTestHelpers;

use \App\ForexStrategy\Hlhb\BasicHlhb;
use \App\ForexStrategy\Hlhb\HlhbMoreExitRules;
use \App\ForexStrategy\Hlhb\HlhbOnePeriodCrossover;
use \App\ForexStrategy\Hlhb\HlhbAdx;
use \App\ForexStrategy\Hlhb\HlhbTargetPrice;
use \App\Services\StrategyLogger;

use \Log;

class HlhbTpWTrailingStop extends \App\ForexBackTest\BackTestToBeProcessed\Base
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
        if ($this->server->strategy_iteration == 'BASIC_HLHB' || $this->server->strategy_iteration == 'HLHB_EXIT_CROSSOVERS' || $this->server->strategy_iteration == 'HLHB_HARD_X' || $this->server->strategy_iteration == 'HLHB_ADX') {
            $backTest->rateLevel = 'simple';
            if ($this->server->strategy_iteration == 'BASIC_HLHB') {
                $strategy = new BasicHlhb(7827172, 'BackTestABC', true);
                $strategy->optionalTrailingStopAmount = round(intval($this->backTestToBeProcessed->stop_loss_pips)/2);
            }
            elseif ($this->server->strategy_iteration == 'HLHB_HARD_X') {
                $strategy = new HlhbOnePeriodCrossover(7827172, 'BackTestABC', true);
                $strategy->optionalTrailingStopAmount = round(intval($this->backTestToBeProcessed->stop_loss_pips)/2);
            }
            elseif ($this->server->strategy_iteration == 'HLHB_ADX') {
                $strategy = new HlhbAdx(7827172, 'BackTestABC', true);
                $strategy->trailingStopPipAmount = $this->backTestToBeProcessed->trailing_stop_pips;
                $strategy->trailingStopNewPosition = true;
                $strategy->adxLength = 14;
                $strategy->adxUndersoldThreshold = intval($this->backTestToBeProcessed->variable_5);
                $backTest->rateLevel = 'both';
                $strategy->optionalTrailingStopAmount = $this->backTestToBeProcessed->trailing_stop_pips;
            }
            else {
                $strategy = new HlhbMoreExitRules(7827172, 'BackTestABC', true);
            }
            $strategy->fastEma = intval($this->backTestToBeProcessed->variable_1);
            $strategy->slowEma = intval($this->backTestToBeProcessed->variable_2);

            $strategy->rsiLength = intval($this->backTestToBeProcessed->variable_3);
            $strategy->rsiBreakthroughLevel = intval($this->backTestToBeProcessed->variable_4);

            //Values for Getting Rates
            $backTest->rateCount = intval($this->backTestToBeProcessed->variable_2)*10;
            $backTest->rateIndicatorMin = intval($this->backTestToBeProcessed->variable_2)*3;
            $backTest->currentRatesProcessed = $backTest->rateCount;

            $strategy->trailingStopProtectBreakEven = true;


            if ($this->server->strategy_iteration == 'HLHB_HARD_X') {
                $backTest->rateCount = 14*10;
                $backTest->rateIndicatorMin = 14*3;

                $strategy->trailingStopProtectBreakEven = false;
            }

            $strategy->strategyLogger = new StrategyLogger();

            //Most Back Tests
            $strategy->stopLossPipAmount = $this->backTestToBeProcessed->stop_loss_pips;
            $strategy->takeProfitPipAmount = $this->backTestToBeProcessed->take_profit_pips;
        }
        elseif ($this->server->strategy_iteration == 'HLHB_TARGET_PRICE') {
            $strategy = new HlhbTargetPrice(7827172, 'BackTestABC', true);
            $strategy->orderType = 'MARKET_IF_TOUCHED';

            $strategy->fastEma = intval($this->backTestToBeProcessed->variable_1);
            $strategy->slowEma = intval($this->backTestToBeProcessed->variable_2);

            $strategy->rsiLength = intval($this->backTestToBeProcessed->variable_3);
            $strategy->adxLength = intval($this->backTestToBeProcessed->variable_3);
            $strategy->trueRangeLength = intval($this->backTestToBeProcessed->variable_3);

            $possibleRateMultipliers = [$strategy->rsiLength, $strategy->fastEma, $strategy->slowEma];

            $multiplierValue = max($possibleRateMultipliers);

            $backTest->rateCount = $multiplierValue*10;
            $backTest->rateIndicatorMin = $multiplierValue*3;
            $backTest->currentRatesProcessed = $backTest->rateCount;
            $strategy->strategyLogger = new StrategyLogger();

            $backTest->rateLevel = 'both';
        }

        $strategy->strategyId = 5;
        $strategy->strategyDesc = 'HMA X';
        $strategy->positionMultiplier = 5;

        //ALL
        $strategy->exchange = $fullExchange;
        $strategy->backTestHelpers->exchange = $fullExchange;
        $strategy->maxPositions = 5;

        $backTest->strategy = $strategy;

        $backTest->run();
    }
}