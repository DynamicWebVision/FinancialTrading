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


namespace App\ForexStrategy\TwoTier\SlowOverBoughtFastMomentum;
use \Log;
use \App\IndicatorEvents\Momentum;
use \App\IndicatorEvents\StochasticEvents;

class SlowHmaStochRevFastHmaCross extends \App\ForexStrategy\Strategy  {

    public $oanda;

    public $slowHmaLength;
    public $slowHmaPipSlopeMin;

    public $slowRatesKLength = 14;
    public $slowRatesSmoothingSlow = 3;
    public $slowRatesSmoothingFull = 3;
    public $slowRatesStochOverboughtCutoff = 20;

    public $fastFastHmaLength;
    public $fastSlowHmaLength;

    //Whether you will enter a new position
    public function decision() {
        Log::info($this->runId.': New Position Decision Indicators: '.PHP_EOL.' '.$this->logIndicators());

        //Simple MACD Crossover
        if ($this->decisionIndicators['slowHmaSlopePass'] == "long" && $this->decisionIndicators['slowStochOverboughtReversal'] == 'long'
            &&  $this->decisionIndicators['fastHmaCrossover'] == 'crossedAbove') {
            Log::warning($this->runId.': NEW LONG POSITION');
            return "long";
        }
        elseif ($this->decisionIndicators['slowHmaSlopePass'] == "short" && $this->decisionIndicators['slowStochOverboughtReversal'] == 'short'
            &&  $this->decisionIndicators['fastHmaCrossover'] == 'crossedBelow') {
            Log::warning($this->runId.': NEW SHORT POSITION');
            return "short";
        }
        else {
            Log::info($this->runId.': Failed Ema Breakthrough');
            return "none";
        }
    }

    public function checkForNewPosition() {
        $momentum = new Momentum();
        $momentum->strategyLogger = $this->strategyLogger;
        $stochasticEvents = new StochasticEvents();
        $stochasticEvents->strategyLogger = $this->strategyLogger;

        $slowRatePips = $this->getRatesInPips($this->slowRates['simple']);

        //Hma has Upward Slope
        $this->decisionIndicators['slowHmaSlopePass'] = $momentum->hmaMeetsCutoffPipSlope($this->slowHmaLength, $this->slowHmaPipSlopeMin, $slowRatePips);

        //Stoch Overbought
        $this->decisionIndicators['slowStochOverboughtReversal'] = $stochasticEvents->fastKMovesOutOfOverboughtForReversal($this->rates['full'], $this->slowRatesKLength, $this->slowRatesSmoothingSlow, $this->slowRatesSmoothingFull, $this->slowRatesStochOverboughtCutoff);

        //Hma Crossover for Fast Rates
        $this->decisionIndicators['fastHmaCrossover'] = $momentum->hmaCrossover($this->rates['simple'], $this->fastFastHmaLength, $this->fastSlowHmaLength);

        $this->decision = $this->decision();

        if ($this->checkOpenPositionsThreshold()) {
            if ($this->decision == 'long') {
                $this->newLongOrStayInPosition();
            }
            elseif ($this->decision == 'short') {
                $this->newShortOrStayInPosition();
            }
        }
    }
}