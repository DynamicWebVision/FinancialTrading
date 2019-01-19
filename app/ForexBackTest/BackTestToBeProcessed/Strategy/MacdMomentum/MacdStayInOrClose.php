<?php namespace App\ForexBackTest\BackTestToBeProcessed\Strategy\MacdMomentum;

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
use \App\ForexBackTest\StopLossOrStrategyClose;

use \App\ForexStrategy\MacdMomentum\MacdMomentumAdxConfirm;
use \App\ForexStrategy\MacdMomentum\MacdMomentumAdxWHmaSlow;

use \Log;

class MacdStayInOrClose extends \App\ForexBackTest\BackTestToBeProcessed\Base
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

        $backTest->recordBackTestStart($this->backTestToBeProcessed->id);

        //Starting Unix Time to Run Strategy
        $backTest->rateUnixStart = $this->server->rate_unix_time_start;

        /******************************
         * SET STRATEGY
         ******************************/
        //Set Strategy
        if ($this->server->strategy_iteration == 'MACD_ADX_CONFIRM') {
            $strategy = new MacdMomentumAdxConfirm(7827172, 'BackTestABC', true);
            $backTest->rateLevel = 'both';
            $strategy->fastLength = intval($this->backTestToBeProcessed->variable_1);
            $strategy->slowLength = intval($this->backTestToBeProcessed->variable_2);

            $strategy->adxPeriodLength = round(intval($this->backTestToBeProcessed->variable_1)*intval($this->backTestToBeProcessed->variable_3));
            $strategy->adxCutoff = 20;

            $strategy->macdHistogramSlopeLengthEntry = intval($this->backTestToBeProcessed->variable_4);
            $strategy->macdHistogramSlopeLengthExit = intval($this->backTestToBeProcessed->variable_5);;

            //Values for Getting Rates
            $backTest->rateCount = intval($this->backTestToBeProcessed->variable_2)*10;
            $backTest->rateIndicatorMin = intval($this->backTestToBeProcessed->variable_2)*3;
        }
        elseif ($this->server->strategy_iteration == 'MACD_ADX_CONFIRM_HMA_SLOW') {
            $strategy = new MacdMomentumAdxWHmaSlow(7827172, 'BackTestABC', true);
            $backTest->rateLevel = 'both';
            $strategy->fastLength = intval($this->backTestToBeProcessed->variable_1);
            $strategy->slowLength = intval($this->backTestToBeProcessed->variable_2);

            $strategy->adxPeriodLength = round(intval($this->backTestToBeProcessed->variable_1)*intval($this->backTestToBeProcessed->variable_3));
            $strategy->adxCutoff = 20;

            $strategy->slowHmaLength = round(intval($this->backTestToBeProcessed->variable_4)*intval($this->backTestToBeProcessed->variable_2));
            $strategy->slowHmaSlopeConfirm = intval($this->backTestToBeProcessed->variable_5);

            //Values for Getting Rates
            $backTest->rateCount = $strategy->slowHmaLength*10;
            $backTest->rateIndicatorMin = $strategy->slowHmaLength*3;
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

        $backTest->currentRatesProcessed = $backTest->rateCount;

        $backTest->run();
    }
}