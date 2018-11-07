<?php
/**
 * Created by PhpStorm.
 * User: diego.rodriguez
 * Date: 8/31/17
 * Time: 5:11 PM
 * Description: This is a basic strategy that uses an EMA break through with a longer (100 EMA to start) and short EMA (50 to start).
 *
 * Decisions:
 * BUY      ---> Short EMA crosses above Long EMA
 * Short    ---> Short EMA crosses below Long EMA
 *
 *During Open Position:
 * -If another breakthrouch occurs we will close the current position and open a new, opposite one
 * -If the linear regression slope of the fast EMA is opposite of the position direction, a tighter stop loss will be added.
 */


namespace App\Strategy\EmaMomentum;
namespace App\Strategy\EmaMomentum;
use \Log;

class EmaSimpleMomentum extends \App\Strategy\Strategy  {

    public $oanda;

    public $fifteenMinuteRates;

    public $passedOuter;

    //Whether you will enter a new position
    public function decision() {
        Log::info($this->runId.': New Position Decision Indicators: '.PHP_EOL.' '.$this->logIndicators());

        //Simple MACD Crossover
        if ($this->decisionIndicators['emaCrossover'] == "crossedAbove") {
            Log::warning($this->runId.': NEW LONG POSITION');
            return "long";
        }
        elseif ($this->decisionIndicators['emaCrossover'] == "crossedBelow") {
            Log::warning($this->runId.': NEW SHORT POSITION');
            return "short";
        }
        else {
            Log::info($this->runId.': Failed Ema Breakthrough');
            return "none";
        }
    }

    public function openPositionDecision() {
        Log::info($this->runId.': Trailing Stop Decision Indicators '.PHP_EOL.' '.$this->logIndicators());

        if ($this->decisionIndicators['emaCrossover'] == "crossedAbove" && $this->fullPositionInfo['side'] == "sell") {
            Log::warning($this->runId.': NEW LONG POSITION, BREAKTHROUGH DURING OPEN POSITION');
            return "long";
        }
        elseif ($this->decisionIndicators['emaCrossover'] == "crossedBelow" && $this->fullPositionInfo['side'] == "buy") {
            Log::warning($this->runId.': NEW SHORT POSITION, BREAKTHROUGH DURING OPEN POSITION');
            return "short";
        }
        else {
            if ($this->fullPositionInfo['side'] == "buy") {
                if ($this->decisionIndicators['shortEmaLinearRegression']['m'] < 0) {
                    Log::warning($this->runId.': ADD TRAILING STOP TO LONG POSITION PASS');
                    return "addStop";
                }
                else {
                    Log::info($this->runId.': add trailing stop to long position fail');
                    return "nothing";
                }
            }
            else {
                if ($this->decisionIndicators['shortEmaLinearRegression']['m'] > 0) {
                    Log::warning($this->runId.': ADD TRAILING STOP TO SHORT POSITION PASS');
                    return "addStop";
                }
                else {
                    Log::info($this->runId.': Add trailing stop to short position fail');
                    return "nothing";
                }
            }
        }
    }

    public function checkForNewPosition($rates) {
        $this->utility = new \App\Services\Utility();

        $this->decisionIndicators['fastEma'] = $this->indicators->ema($rates, $this->fastEmaLength);

        $this->decisionIndicators['slowEma'] = $this->indicators->ema($rates, $this->slowEmaLength);

        $this->decisionIndicators['emaCrossover'] = $this->indicators->checkCrossover($this->decisionIndicators['fastEma'], $this->decisionIndicators['slowEma']);

        $this->decision = $this->decision();

        if ($this->checkOpenPositionsThreshold()) {
            $this->calculatePositionAmount();
            if ($this->decision == 'long') {
                $this->closePosition();
                $this->newLongPosition();
            }
            elseif ($this->decision == 'short') {
                $this->closePosition();
                $this->newShortPosition();
            }
        }
    }

    public function checkToAddTrailingStop($rates) {
        $this->decisionIndicators['fastEma'] = $this->indicators->ema($rates, $this->fastEmaLength);
        $this->decisionIndicators['slowEma'] = $this->indicators->ema($rates, $this->slowEmaLength);

        $pipFastEmaArray = array_map(function($v) {
            return $v/$this->exchange->pip;
        }, $this->decisionIndicators['fastEma']);

        $this->decisionIndicators['shortEmaLinearRegression'] = $this->indicators->linearRegression($pipFastEmaArray, $this->linearRegressionLength);

        $this->decisionIndicators['emaCrossover'] = $this->indicators->checkCrossoverGoBackTwo($this->decisionIndicators['fastEma'], $this->decisionIndicators['slowEma']);

        $this->livePosition = $this->checkOpenPosition();

        $openPositionTask = $this->openPositionDecision();

        if ($openPositionTask == 'long') {
            $this->closePosition();
            $this->newLongPosition();
        }
        elseif ($openPositionTask == 'short') {
            $this->closePosition();
            $this->newShortPosition();
        }
        elseif ($openPositionTask == 'addStop') {
            $this->trailingStopPipAmount = $this->optionalTrailingStopAmount;
            $this->addTrailingStopToPosition($this->optionalTrailingStopAmount);
        }
    }
}
