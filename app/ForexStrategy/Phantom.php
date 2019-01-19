<?php namespace App\ForexStrategy;

class Phantom extends \App\ForexStrategy\Strategy  {

    public $oanda;

    public function logFiveMinuteCheck($this->decisionInput, $newDecision) {
//        $fiveMinuteLog = new \App\Model\FiveMinuteCheckLog;
//
//        $fiveMinuteLog->strategy_id = 1;
//
//        $fiveMinuteLog->rate_date_time = date('Y-m-d H:i:s');;
//
//        $fiveMinuteLog->rsi = $this->decisionInput['rsi'];
//
//        $fiveMinuteLog->current_position_status = 0;
//
//        $fiveMinuteLog->ema_crossover = $this->decisionInput['emaCrossover'];
//
//        $fiveMinuteLog->rsi_linear_regression = $this->decisionInput['rsiLinearRegression'];
//
//        $fiveMinuteLog->decision = $newDecision;
//
//        $fiveMinuteLog->exchange = $this->exchange->id;
//
//        $fiveMinuteLog->save();
    }

    //Whether you will enter a new position with Phantom
    public function decision($indicators) {
        if ($this->decisionIndicators['emaCrossover'] == 'crossedAbove' &&  ($this->decisionIndicators['rsi'] > 45 && $this->decisionIndicators['rsi'] < 55) && $this->decisionIndicators['rsiLinearRegression'] >= 1) {
            return 'long';
        }
        elseif ($this->decisionIndicators['emaCrossover'] == 'crossedBelow' &&  ($this->decisionIndicators['rsi'] > 45 && $this->decisionIndicators['rsi'] < 55) && $this->decisionIndicators['rsiLinearRegression'] <= -1) {
            return 'short';
        }
        else {
            return 'none';
        }
    }

    public function runStrategy($rates) {
        $this->oanda->exchange = $this->exchange->exchange;

        $this->takeProfitPipAmount = 200;
        $this->stopLossPipAmount = 100;

        $this->checkDbPosition();

        $livePosition = $this->checkOpenPosition();

        if ($livePosition != $this->currentPosition) {
            $this->updatePositionToNone();

            $this->updateOutstandingPosition();
        }

        if ($livePosition == 0) {

            $this->oanda = new \App\Services\Oanda();
            $this->oanda->accountId = $this->accountId;

            $this->oanda->strategyId = 1;

            $this->oanda->positionAmount = 100000;

            $shortEma = $this->indicators->calculateEMA($rates, 5);
            $longEma = $this->indicators->calculateEMA($rates, 10);

            $rsi = trader_rsi($rates, 10);

            $linearRegression = trader_linearreg_slope($rsi, 5);

            $crossover = $this->indicators->checkCrossover($shortEma, $longEma);

            $this->decisionInput = [
                'emaCrossover' => $crossover,
                'rsi' => end($rsi),
                'rsiLinearRegression' => end($linearRegression)
            ];
            $this->decision = $this->decision($this->decisionInput, 0);

            $this->logFiveMinuteCheck($this->decisionInput, $this->decision);

            if ($this->decision == 'long') {
                $this->newLongPosition();
            }
            elseif ($this->decision == 'short') {
                $this->newShortPosition();
            }
        }
    }
}
