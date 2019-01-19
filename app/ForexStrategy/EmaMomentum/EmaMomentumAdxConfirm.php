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


namespace App\ForexStrategy\EmaMomentum;
use \Log;

class EmaMomentumAdxConfirm extends \App\ForexStrategy\Strategy  {

    public $oanda;

    public $fifteenMinuteRates;
    public $passedOuter;

    public $adxCutoff;
    public $adxPeriodLength;

    //Whether you will enter a new position
    public function decision() {
        $this->logIndicators();

        //Simple MACD Crossover
        if ($this->decisionIndicators['emaCrossover'] == "crossedAbove" && end($this->decisionIndicators['adx']) >= $this->adxCutoff) {
            $this->strategyLogger->logDecisionMade('new_long');
            return "long";
        }
        elseif ($this->decisionIndicators['emaCrossover'] == "crossedBelow" && end($this->decisionIndicators['adx']) >= $this->adxCutoff) {
            $this->strategyLogger->logDecisionMade('new_short');
            return "short";
        }
        else {
            $this->strategyLogger->logDecisionMade('none');
            return "none";
        }
    }

    public function checkForNewPosition() {
        $this->utility = new \App\Services\Utility();

        if ($this->recentOpenPositionCheck()) {
            $this->strategyLogger->logDecisionType('recent_open');
            return 'recent_open_position';
        }
        $this->strategyLogger->logDecisionType('check_new');

        $this->decisionIndicators['fastEma'] = $this->indicators->ema($this->rates['simple'], $this->fastEmaLength);
        $this->decisionIndicators['slowEma'] = $this->indicators->ema($this->rates['simple'], $this->slowEmaLength);

        $this->decisionIndicators['adx'] = $this->indicators->adx($this->rates['full'], $this->adxPeriodLength);

        $this->decisionIndicators['emaCrossover'] = $this->indicators->checkCrossoverGoBackTwo($this->decisionIndicators['fastEma'], $this->decisionIndicators['slowEma']);

        $this->decision = $this->decision();

        if ($this->checkOpenPositionsThreshold()) {
            $this->calculatePositionAmount();
            if ($this->decision == 'long') {
                $this->newLongOrStayInPosition();
            }
            elseif ($this->decision == 'short') {
                $this->newShortOrStayInPosition();
            }
        }
    }
}
