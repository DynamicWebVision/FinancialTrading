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
use \Log;
use Illuminate\Support\Facades\DB;

class EmaMomentumDifferenceSlope extends \App\Strategy\Strategy  {
    public $slopeDiffLength;
    public $slopeCutoff;

    //Whether you will enter a new position
    public function decision() {
        $this->logIndicators();
        //Simple MACD Crossover
        if ($this->decisionIndicators['emaCrossover'] == "crossedAbove" && $this->decisionIndicators['emaDifferenceSlope'] >= $this->slopeCutoff) {
            $this->strategyLogger->logDecisionMade('new_long');
            return "long";
        }
        elseif ($this->decisionIndicators['emaCrossover'] == "crossedBelow" && $this->decisionIndicators['emaDifferenceSlope'] >= $this->slopeCutoff) {
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
        $this->strategyLogger->logDecisionType('check_new');

        if ($this->recentOpenPositionCheck()) {
            return 'recent_open_position';
        }

        $this->ratesPips = $this->getRatesInPips($this->rates['simple']);

        $this->decisionIndicators['fastEma'] = $this->indicators->ema($this->ratesPips, $this->fastEmaLength);
        $this->decisionIndicators['slowEma'] = $this->indicators->ema($this->ratesPips, $this->slowEmaLength);

        $this->decisionIndicators['emaDifferenceSlope'] = abs($this->indicators->differenceSlope($this->decisionIndicators['fastEma'], $this->decisionIndicators['slowEma'], $this->slopeDiffLength));

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
