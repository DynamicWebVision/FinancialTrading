<?php namespace App\ForexStrategy;

use \Log;
use \App\ForexBackTest\BackTestHelpers;
use \App\Services\TransactionAmountHelpers;
use \App\Model\DecisionInputRatesLogs;
use \App\Model\OandaOpenPositions;
use \App\Model\DecodeFrequency;

abstract class EquityBaseStrategy  {

    public $broker;

    public function __construct($broker) {
        $this->broker = $broker;
    }

    public function getAvailableMargin() {
        $this->accountInfo = $this->oanda->accountInfo();
        return $this->accountInfo->marginAvailable;
    }

    public function calculatePositionAmount() {
        if (!$this->backtesting) {
            $this->positionAmount = round(round($this->accountAvailableMargin)*$this->positionMultiplier);
            $this->oanda->positionAmount = $this->positionAmount;

            $this->strategyLogger->logMessage('Margin Available: '.$this->accountAvailableMargin.'Position Multiplier '.$this->positionMultiplier.' Position Amount: '.$this->positionAmount, 1);
        }
    }

    public function checkOpenPositionsThreshold() {
        if (!$this->backtesting) {
            $count = $this->oanda->checkTotalOpenPositions();

            if ($count <= $this->maxPositions) {
                return true;
            }
            else {
                return false;
            }
        }
        else {
            return true;
        }
    }

    public function newLongPosition() {

    }

    public function newShortPosition() {

    }

    public function calculateLongStopLoss($buyPrice) {
        return $buyPrice - ($this->exchange->pip * $this->stopLossPipAmount);
    }

    public function calculateShortStopLoss($price) {
        return $price + ($this->exchange->pip * $this->stopLossPipAmount);
    }

    public function calculateLongTakeProfit($price) {
        return $price + ($this->exchange->pip * $this->takeProfitPipAmount);
    }

    public function calculateShortTakeProfit($price) {
        return $price - ($this->exchange->pip * $this->takeProfitPipAmount);
    }

    public function calculateLimitEndTime() {
        $frequency = DecodeFrequency::where('oanda_code', '=', $this->oanda->frequency)->first();
        return time() + ($frequency->frequency_seconds - 60);
    }

    public function modifyLongStopLoss($openPosition) {
        if (!$this->backtesting) {
            $this->oanda->stopLoss = $this->calculateLongStopLoss($this->currentPriceData->mid);

            foreach ($openPosition['positionTradeIds'] as $tradeId) {
                $this->oanda->modifyStopLoss($tradeId);
            }
        }
        else {
            $this->backTestPositions[sizeof($this->backTestPositions)-1]['stopLoss'] = $this->calculateLongStopLoss($this->currentPriceData->open);
        }
    }

    public function modifyStopLoss($newPricePoint) {
        if (!$this->backtesting) {
            $this->oanda->stopLoss = $this->oanda->getOandaPrecisionPrice($newPricePoint, $this->exchange->pip);
            foreach ($this->openPosition['positionTradeIds'] as $tradeId) {
                $this->oanda->modifyStopLoss($tradeId);
            }

        }
        else {
            $this->backTestPositions[sizeof($this->backTestPositions)-1]['stopLoss'] = $newPricePoint;
        }
    }

    public function modifyShortStopLoss($openPosition) {
        if (!$this->backtesting) {
            $this->oanda->stopLoss = $this->calculateShortStopLoss($this->currentPriceData->mid);
            $this->oanda->modifyStopLoss($openPosition['stopLossId']);
        }
        else {
            $this->backTestPositions[sizeof($this->backTestPositions)-1]['stopLoss'] = $this->calculateShortStopLoss($this->currentPriceData->open);
        }
    }

    public function addTrailingStopToPosition($tsPipAmount) {

    }

    public function getCurrentAccountInfo() {

    }

    public function calculateAmountOfSharesToBuy($moneyToSpend, $sharePrice) {
        return floor($moneyToSpend/$sharePrice);
    }
}
