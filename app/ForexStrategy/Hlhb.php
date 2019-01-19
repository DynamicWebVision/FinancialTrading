<?php namespace App\ForexStrategy;
use \Log;
class Hlhb extends \App\ForexStrategy\Strategy  {

    public $oanda;
    public $utility;

    public $fifteenMinuteRates;

    public $passedOuter;


    public function log($indicators, $newDecision) {

    }

    public function logOpenPosition() {
        $log = new \App\Model\Logs\HlhbLog;

        $log->rate_date_time = date('Y-m-d H:i:s');

        $log->current_position_status = $this->livePosition;

        $log->save();
    }

    //Whether you will enter a new position with Phantom
    public function decision($indicators) {

        //Long
        //EMA Most Cross Above and RSI must go from below 50 to above 50
        if ($this->decisionIndicators['emaCrossOver'] == "crossedAbove" && $this->decisionIndicators['rsiPrevious'] <= 50 && $this->decisionIndicators['rsi'] >= 50) {
            Log::warning($this->runId.': NEW DECISION LONG Indicators:  \n'.json_encode($indicators));
            return "long";
        }
        //Short
        //EMA Most Cross Below and RSI must go from above 50 to below 50
        elseif ($this->decisionIndicators['emaCrossOver'] == "crossedBelow" && $this->decisionIndicators['rsiPrevious'] >= 50 && $this->decisionIndicators['rsi'] <= 50) {
            Log::warning($this->runId.': NEW DECISION SHORT Indicators:  \n'.json_encode($indicators));
            return "short";
        }
        else {
            Log::info($this->runId.': no decision : '.json_encode($indicators));
            return "none";
        }
    }

    public function runStrategy($rates) {
        $this->utility = new \App\Services\Utility();

        $this->checkDbPosition();

        $this->livePosition = $this->checkOpenPosition();

        if ($this->livePosition == 0) {
            //Four Hour Stuff
            $this->decisionIndicators['ema5'] = $this->indicators->ema($rates, 5);
            $this->decisionIndicators['ema10'] = $this->indicators->ema($rates, 10);

            $this->decisionIndicators['rsi'] = $this->indicators->rsi($rates, 10);

            $lastHourRates = $rates;
            array_pop($lastHourRates);

            $this->decisionIndicators['rsiPrevious'] = $this->indicators->rsi($lastHourRates, 10);

            $this->decisionIndicators['emaCrossOver'] = $this->indicators->checkCrossover($this->decisionIndicators['ema5'], $this->decisionIndicators['ema10']);

            $this->decision = $this->decision($indicators);

            if ($this->livePosition != 0) {
                $this->oanda->closePosition();
            }

            if ($this->checkOpenPositionsThreshold()) {
                $this->calculatePositionAmount();
                if ($this->decision == 'long') {
                    $this->newLongPosition();
                }
                elseif ($this->decision == 'short') {
                    $this->newShortPosition();
                }
            }
        }
    }
}
