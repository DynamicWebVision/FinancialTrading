<?php
/**
 * Created by PhpStorm.
 * User: brian
 * Date: 10/12/2017
 * Time: 5:11 PM
 * Description: This is a strategy that tries to follow the Hull Momentum Average HMA trend.
 *
 * Decisions:
 * BUY      ---> HMA meets positive up slope linear regression requirement
 * Short    ---> HMA meets negative up slope linear regression requirement
 */


namespace App\Strategy\HullMovingAverage;
use App\StrategyEvents\Momentum;
use \App\StrategyEvents\StochasticEvents;
use \Log;
use Illuminate\Support\Facades\DB;

class SimpleUntilStochOverbought extends \App\Strategy\Strategy  {

    public $hmaLength;
    public $hmaMinSlope;
    public $hmaPeriodsBackReversalCheck;

    public $kLength;
    public $smoothingSlow;
    public $smoothingFull;
    public $stochOverboughtCutoff = 20;

    //Whether you will enter a new position
    public function decision() {
        Log::info($this->runId.': Decision Indicators '.json_encode($this->decisionIndicators));

        //if (!$this->fullPositionInfo['open']) {
        Log::info($this->runId.': Decision Completely Open.');

        if ($this->decisionIndicators['hma'] == 'long' && $this->decisionIndicators['stochOverboughtCheck'] != 'overBoughtLong') {
            Log::warning($this->runId.': NEW LONG POSITION');
            return 'long';
        }
        elseif ($this->decisionIndicators['hma'] == 'short' && $this->decisionIndicators['stochOverboughtCheck'] != 'overBoughtShort') {
            Log::warning($this->runId.': NEW SHORT POSITION');
            return 'short';
        }
//        }
    }

    public function entryCheck() {

        $momentum = new Momentum();
        $stochEvents = new StochasticEvents();

        $ratePips = $this->getRatesInPips($this->rates);

        $this->decisionIndicators['hma'] = $momentum->hmaSwitchesDirectionAndMeetsMinimumSlope($ratePips, $this->hmaLength, $this->hmaMinSlope, $this->hmaPeriodsBackReversalCheck);

        $this->decisionIndicators['stochOverboughtCheck'] = $stochEvents->overBoughtCheck($this->rates['full'], $this->kLength, $this->smoothingSlow, $this->smoothingFull, $this->stochOverboughtCutoff);

        $this->decision = $this->decision();

        if ($this->decision == 'long') {
            $this->newLongOrStayInPosition();
        }
        elseif ($this->decision == 'short') {
            $this->newShortOrStayInPosition();
        }
    }

    public function exitCheck() {
        $momentum = new Momentum();
        $stochEvents = new StochasticEvents();

        $this->checkOpenPosition();

        $this->decisionIndicators['stochOverboughtCheck'] = $stochEvents->overBoughtCheck($this->rates['full'], $this->kLength, $this->smoothingSlow, $this->smoothingFull, $this->stochOverboughtCutoff);

        $this->decisionIndicators['hmaWrongDirection'] = $momentum->hmaWrongDirection($this->rates['simple'], $this->hmaLength, $this->currentPosition['current_position']);

        if ($this->decisionIndicators['hmaWrongDirection'] == 'close' || $this->decisionIndicators['stochOverboughtCheck'] == 'overBoughtLong' && $this->currentPosition['current_position'] == 1) {
            $this->closePosition();
        }
        elseif ($this->decisionIndicators['hmaWrongDirection'] == 'close' || $this->decisionIndicators['stochOverboughtCheck'] == 'overBoughtShort' && $this->currentPosition['current_position'] == -1) {
            $this->closePosition();
        }
        else {
            return 'nothing';
            }
    }

    public function checkForNewPosition() {
        if (!$this->fullPositionInfo['open']) {
            $this->entryCheck();
        }
        else {
            $this->exitCheck();
        }
    }
}