<?php namespace App\ForexBackTest\BackTestToBeProcessed\Strategy\RsiPullback;

/**********************
RsiPullback Backtest Variable Definitions
Created at: 01/09/19by Brian O'Neill
***********************/

use \DB;
use App\Model\Exchange;
use \App\ForexBackTest\TakeProfitStopLossTest;
use \App\Services\StrategyLogger;

use \App\ForexStrategy\RsiPullback\RsiPullbackEmaPriceAction;
use \App\ForexStrategy\RsiPullback\RsiPbPriceActionHma;
use \App\ForexStrategy\RsiPullback\RsiPbEmaTrSl;
use \App\ForexStrategy\RsiPullback\RsiPbHmaTrSl;
//END STRATEGY DECLARATIONS

class RsiPullbackBackTestToBeProcessed extends \App\ForexBackTest\BackTestToBeProcessed\Base
{

    public function callProcess() {
        /******************************
        * SET BACK TEST
        ******************************/

        //Set
        $backTest = new TakeProfitStopLossTest('Automated Backtest');

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
        if (1==2) {

        }
        elseif ($this->server->strategy_iteration == 'RSI_PB_EMA_PRICE') {
            $backTest->rateLevel = 'both';
        
            $strategy = new RsiPullbackEmaPriceAction(1,1,true);
        
            //$strategy->orderType = 'MARKET_IF_TOUCHED';
            $strategy->emaLengthSlow = intval($this->backTestToBeProcessed->variable_1);
            $strategy->rsiLength = intval($this->backTestToBeProcessed->variable_2);
            $strategy->rsiCutoff = intval($this->backTestToBeProcessed->variable_3);
            $strategy->emaLengthFast = intval($this->backTestToBeProcessed->variable_4);

            $strategy->takeProfitPipAmount = 0;
            $multiplyValue = max([intval($this->backTestToBeProcessed->variable_1), intval($this->backTestToBeProcessed->variable_2), 
        intval($this->backTestToBeProcessed->variable_3), intval($this->backTestToBeProcessed->variable_4), intval($this->backTestToBeProcessed->variable_5)]);
        
            //Values for Getting Rates
            $backTest->rateCount = intval($multiplyValue)*10;
            $backTest->rateIndicatorMin = intval($multiplyValue)*3;
            $backTest->currentRatesProcessed = $backTest->rateCount;
        }
        elseif ($this->server->strategy_iteration == 'RSI_PB_PA_HMA') {
            $backTest->rateLevel = 'both';
        
            $strategy = new RsiPbPriceActionHma(1,1,true);
        
            //$strategy->orderType = 'MARKET_IF_TOUCHED';
            $strategy->hmaLength = intval($this->backTestToBeProcessed->variable_1);
            $strategy->rsiLength = intval($this->backTestToBeProcessed->variable_2);
            $strategy->rsiCutoff = intval($this->backTestToBeProcessed->variable_3);
            $strategy->emaLength = intval($this->backTestToBeProcessed->variable_4);
        
            $strategy->takeProfitPipAmount = 0;
            $multiplyValue = max([intval($this->backTestToBeProcessed->variable_1), intval($this->backTestToBeProcessed->variable_2), 
        intval($this->backTestToBeProcessed->variable_3), intval($this->backTestToBeProcessed->variable_4), intval($this->backTestToBeProcessed->variable_5)]);
        
            //Values for Getting Rates
            $backTest->rateCount = intval($multiplyValue)*10;
            $backTest->rateIndicatorMin = intval($multiplyValue)*3;
            $backTest->currentRatesProcessed = $backTest->rateCount;
        }
        elseif ($this->server->strategy_iteration == 'RSI_PB_EMA_TRSL') {
            $backTest->rateLevel = 'both';
        
            $strategy = new RsiPbEmaTrSl(1,1,true);
        
            //$strategy->orderType = 'MARKET_IF_TOUCHED';
            $strategy->emaLengthSlow = intval($this->backTestToBeProcessed->variable_1);
            $strategy->rsiLength = intval($this->backTestToBeProcessed->variable_2);
            $strategy->rsiCutoff = intval($this->backTestToBeProcessed->variable_3);
            $strategy->emaLengthFast = intval($this->backTestToBeProcessed->variable_4);
            $strategy->trueRangeLength = 14;
            $strategy->trueRangeMultiplier = intval($this->backTestToBeProcessed->variable_5);
        
            $strategy->takeProfitPipAmount = 0;
            $multiplyValue = max([intval($this->backTestToBeProcessed->variable_1), intval($this->backTestToBeProcessed->variable_2), 
        intval($this->backTestToBeProcessed->variable_3), intval($this->backTestToBeProcessed->variable_4), intval($this->backTestToBeProcessed->variable_5)]);
        
            //Values for Getting Rates
            $backTest->rateCount = intval($multiplyValue)*10;
            $backTest->rateIndicatorMin = intval($multiplyValue)*3;
            $backTest->currentRatesProcessed = $backTest->rateCount;
        }
        elseif ($this->server->strategy_iteration == 'RSI_PB_HMA_TRSL') {
            $backTest->rateLevel = 'both';
        
            $strategy = new RsiPbHmaTrSl(1,1,true);
        
            //$strategy->orderType = 'MARKET_IF_TOUCHED';
            $strategy->hmaLength = intval($this->backTestToBeProcessed->variable_1);
            $strategy->rsiLength = intval($this->backTestToBeProcessed->variable_2);
            $strategy->rsiCutoff = intval($this->backTestToBeProcessed->variable_3);
            $strategy->trueRangeMultiplier = intval($this->backTestToBeProcessed->variable_4);
            $strategy->emaLength = intval($this->backTestToBeProcessed->variable_5);
        
            $strategy->takeProfitPipAmount = 0;
            $multiplyValue = max([intval($this->backTestToBeProcessed->variable_1), intval($this->backTestToBeProcessed->variable_2), 
        intval($this->backTestToBeProcessed->variable_3), intval($this->backTestToBeProcessed->variable_4), intval($this->backTestToBeProcessed->variable_5)]);
        
            //Values for Getting Rates
            $backTest->rateCount = intval($multiplyValue)*10;
            $backTest->rateIndicatorMin = intval($multiplyValue)*3;
            $backTest->currentRatesProcessed = $backTest->rateCount;
        }
        //END OF SYSTEM STRATEGY IFS

        $strategy->strategyLogger = new StrategyLogger();

        $strategy->strategyId = 6;
        $strategy->strategyDesc = 'Automated Test';
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