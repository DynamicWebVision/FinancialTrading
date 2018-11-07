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
use \App\BackTest\StopLossOrStrategyClose;

use \App\Strategy\HullMovingAverage\TwoTier;
use \App\Strategy\HullMovingAverage\HmaAdxStayIn;
use \App\Strategy\HullMovingAverage\HmaStayInDifferentEntryExitAdx;
use \App\Strategy\HullMovingAverage\SimpleUntilStochOverbought;

use \Log;

class HmaStayInStopLoss extends \App\BackTest\BackTestToBeProcessed\Base
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
        if ($this->server->strategy_iteration == 'HMA_TWO_TIER') {
            $strategy = new TwoTier(7827172, 'BackTestABC', true);
            $backTest->rateLevel = 'both';
            $strategy->fastHullLength = intval($this->backTestToBeProcessed->variable_1);
            $strategy->fastHullLinearRegressionLength = 2;
            $strategy->fastHullLinearRegressionSlopeRequirement = intval($this->backTestToBeProcessed->variable_3);

            $strategy->slowHullLength = intval($this->backTestToBeProcessed->variable_2);
            $strategy->slowHullLinearRegressionLength = 3;
            $strategy->slowHullLinearRegressionSlopeRequirement = intval($this->backTestToBeProcessed->variable_4);

            $strategy->adxPeriodLength = 14;
            $strategy->adxCutOffForDownwardSlope = 35;
            $strategy->adxCutOffForUpwardSlope = 20;

        }
        elseif ($this->server->strategy_iteration == 'HMA_ADX_STAY_IN') {
            $strategy = new HmaAdxStayIn(7827172, 'BackTestABC', true);
            $backTest->rateLevel = 'both';

            $strategy->hullLength = intval($this->backTestToBeProcessed->variable_1);
            $strategy->adxPeriodLength = 14;
            $strategy->adxCutOff = intval($this->backTestToBeProcessed->variable_2);
            $strategy->hmaLinRegCutoff = intval($this->backTestToBeProcessed->variable_3);
        }
        elseif ($this->server->strategy_iteration == 'HMA_ADX_STAY_IN_ENTRY_CLOSE_DIFFERENT') {
            $strategy = new HmaStayInDifferentEntryExitAdx(7827172, 'BackTestABC', true);
            $backTest->rateLevel = 'both';

            $strategy->hullLength = intval($this->backTestToBeProcessed->variable_3);
            $strategy->adxPeriodLength = 14;
            $strategy->adxCutOff = 20;
            $strategy->hmaLinRegCutoff = intval($this->backTestToBeProcessed->variable_1);
            $strategy->hmaCloseLinRegCutoff = intval($this->backTestToBeProcessed->variable_2);
        }
        elseif ($this->server->strategy_iteration == 'HMA_STOCH_OVERBOUGHT_CLOSE') {
            $strategy = new SimpleUntilStochOverbought(7827172, 'BackTestABC', true);
            $backTest->rateLevel = 'both';

            $strategy->hmaLength = intval($this->backTestToBeProcessed->variable_1);


            $strategy->hmaMinSlope = floatval($this->backTestToBeProcessed->variable_2);
            $strategy->hmaPeriodsBackReversalCheck = intval($this->backTestToBeProcessed->variable_3);

            $strategy->kLength = round(intval($this->backTestToBeProcessed->variable_1)*floatval($this->backTestToBeProcessed->variable_4));
            $strategy->smoothingSlow = 3;
            $strategy->smoothingFull = 3;
            $strategy->stochOverboughtCutoff = intval($this->backTestToBeProcessed->variable_5);
        }

        $strategy->strategyId = 5;
        $strategy->strategyDesc = 'fiftyOneHundred';
        $strategy->positionMultiplier = 5;

        //Most Back Tests
        $strategy->stopLossPipAmount = $this->backTestToBeProcessed->stop_loss_pips;

        //ALL
        $strategy->exchange = $fullExchange;
        $strategy->maxPositions = 5;

        $backTest->strategy = $strategy;

        //Values for Getting Rates
        $backTest->rateCount = intval($this->backTestToBeProcessed->variable_2)*10;
        $backTest->rateIndicatorMin = intval($this->backTestToBeProcessed->variable_2)*3;

        $backTest->currentRatesProcessed = $backTest->rateCount;

        if ($this->server->strategy_iteration == 'HMA_STOCH_OVERBOUGHT_CLOSE') {
            $rateMultiplier = $this->returnMax([$this->backTestToBeProcessed->variable_1, $this->backTestToBeProcessed->variable_1*$this->backTestToBeProcessed->variable_4]);

            //Values for Getting Rates
            $backTest->rateCount = $rateMultiplier*10;
            $backTest->rateIndicatorMin = $rateMultiplier*5;
        }

        $backTest->run();
    }
}