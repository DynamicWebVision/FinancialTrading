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


namespace App\StrategyEvents;
use \Log;
use \App\Services\CurrencyIndicators;
use \App\Services\Utility;

class StochasticEvents {
    public $utility;
    public $indicators;

    public $strategyLogger;

    public function __construct() {
        $this->indicators = new CurrencyIndicators();
        $this->utility = new Utility();
    }

    public function fastKMovesOutOfOverbought($rates, $klength, $smoothingSlow, $smoothingFull, $overBoughtThreshold) {
        $stochastic = $this->indicators->stochastics($rates, $klength, $smoothingSlow, $smoothingFull);

        $upperThreshold = 100 - $overBoughtThreshold;

        $secondToLastFastK = $this->utility->getXFromLastValue($stochastic['fast']['k'], 1);
        $secondToLastFastD = $this->utility->getXFromLastValue($stochastic['fast']['d'], 1);

        $currentKValue = end($stochastic['fast']['k']);

        //************LOGGING************/
        $this->strategyLogger->logMessage('fastKMovesOutOfOverbought Threshold: '.$overBoughtThreshold.' Current Fast K: '.$currentKValue.' Previous K: '.$secondToLastFastK.' Previous D: '.$secondToLastFastD, 1);


        if ($currentKValue < $upperThreshold && $secondToLastFastK >= $upperThreshold && $secondToLastFastD >= $upperThreshold) {
            $this->strategyLogger->logMessage('fastKMovesOutOfOverbought Decision: LONG', 1);
            return 'long';
        }
        elseif ($currentKValue > $overBoughtThreshold && $secondToLastFastK <= $overBoughtThreshold && $secondToLastFastD <= $overBoughtThreshold) {
            $this->strategyLogger->logMessage('fastKMovesOutOfOverbought Decision: SHORT', 1);
            return 'short';
        }
        else {
            $this->strategyLogger->logMessage('fastKMovesOutOfOverbought Decision: NONE', 1);
            return 'none';
        }
    }

    public function fastKMovesOutOfOverboughtForReversal($rates, $klength, $smoothingSlow, $smoothingFull, $overBoughtThreshold) {
        $stochastic = $this->indicators->stochastics($rates, $klength, $smoothingSlow, $smoothingFull);

        $upperThreshold = 100 - $overBoughtThreshold;

        $secondToLastFastK = $this->utility->getXFromLastValue($stochastic['fast']['k'], 1);
        $secondToLastFastD = $this->utility->getXFromLastValue($stochastic['fast']['d'], 1);

        $currentKValue = end($stochastic['fast']['k']);

        if ($currentKValue < $upperThreshold && $secondToLastFastK >= $upperThreshold && $secondToLastFastD >= $upperThreshold) {
            return 'short';
        }
        elseif ($currentKValue > $overBoughtThreshold && $secondToLastFastK <= $overBoughtThreshold && $secondToLastFastD <= $overBoughtThreshold) {
            return 'long';
        }
        else {
            return 'none';
        }
    }

    public function overBoughtCheck($rates,$klength, $smoothingSlow, $smoothingFull, $overBoughtThreshold) {
        $stochastic = $this->indicators->stochastics($rates, $klength, $smoothingSlow, $smoothingFull);

        $upperThreshold = 100 - $overBoughtThreshold;

        $currentKValue = end($stochastic['fast']['k']);

        if ($currentKValue < $overBoughtThreshold) {
            return 'overBoughtShort';
        }
        elseif ($currentKValue > $upperThreshold) {
            return 'overBoughtLong';
        }
        else {
            return 'none';
        }
    }
}
