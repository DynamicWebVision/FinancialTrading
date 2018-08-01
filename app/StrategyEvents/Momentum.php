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
use App\Services\Utility;
use \Log;
use \App\Services\CurrencyIndicators;
use \App\StrategyEvents\EventHelpers;

class Momentum {
    public $utility;
    public $indicators;
    public $strategyLogger;

    public function __construct() {
        $this->utility = new Utility();
        $this->indicators = new CurrencyIndicators();
        $this->eventHelpers = new EventHelpers();
    }

    public function threeMovingAverageInline($movingAverageFast, $movingAverageMedium, $movingAverageSlow) {
        $fastEnd = end($movingAverageFast);
        $mediumEnd = end($movingAverageMedium);
        $slowEnd = end($movingAverageSlow);


        if ($fastEnd > $mediumEnd && $mediumEnd > $slowEnd) {
            return 'long';
        }
        elseif ($fastEnd < $mediumEnd && $mediumEnd < $slowEnd) {
            return 'short';
        }
        else {
            return 'none';
        }
    }

    public function threeMASimple($fastLength, $mediumLength, $slowLength, $rates) {
        $indicators = new CurrencyIndicators();

        $fastMa = $indicators->sma($rates, $fastLength);
        $mediumMa = $indicators->sma($rates, $mediumLength);
        $slowMa = $indicators->sma($rates, $slowLength);

        return $this->threeMovingAverageInline($fastMa, $mediumMa, $slowMa);
    }

    public function threeMAExponential($fastLength, $mediumLength, $slowLength, $rates) {
        $indicators = new CurrencyIndicators();

        $fastMa = $indicators->ema($rates, $fastLength);
        $mediumMa = $indicators->ema($rates, $mediumLength);
        $slowMa = $indicators->ema($rates, $slowLength);

        return $this->threeMovingAverageInline($fastMa, $mediumMa, $slowMa);
    }

    public function threeMAHull($fastLength, $mediumLength, $slowLength, $rates) {
        $indicators = new CurrencyIndicators();

        $fastMa = $indicators->hma($rates, $fastLength);
        $mediumMa = $indicators->hma($rates, $mediumLength);
        $slowMa = $indicators->hma($rates, $slowLength);

        return $this->threeMovingAverageInline($fastMa, $mediumMa, $slowMa);
    }

    public function hmaMeetsCutoffPipSlope($length, $slopeCutoff, $ratePips) {
        $indicators = new CurrencyIndicators();

        $fastMa = $indicators->hma($ratePips, $length);
        $linReg = $indicators->linearRegression($fastMa, 2);

        $this->strategyLogger->logMessage('hmaMeetsCutoffPipSlope Slope Cutoff '.$slopeCutoff.' Lin Reg Slope: '.$linReg['m'], 1);

        if ($linReg['m'] >= $slopeCutoff) {
            $this->strategyLogger->logMessage('hmaMeetsCutoffPipSlope Decision: LONG', 1);
            return 'long';
        }
        elseif ($linReg['m'] <= ($slopeCutoff*-1)) {
            $this->strategyLogger->logMessage('hmaMeetsCutoffPipSlope Decision: SHORT', 1);
            return 'short';
        }
        else {
            $this->strategyLogger->logMessage('hmaMeetsCutoffPipSlope Decision: NONE', 1);
            return 'none';
        }
    }

    public function emaMeetsCutoffPipSlope($length, $slopeCutoff, $ratePips) {
        $indicators = new CurrencyIndicators();

        $fastMa = $indicators->ema($ratePips, $length);
        $linReg = $indicators->linearRegression($fastMa, 2);

        if ($linReg['m'] >= $slopeCutoff) {
            return 'long';
        }
        elseif ($linReg['m'] <= ($slopeCutoff*-1)) {
            return 'short';
        }
        else {
            return 'none';
        }
    }

    public function crossOrHistogramTrendingUp($macd, $indicators, $histogramSlopeLength) {
        $crossCheck = $indicators->checkCrossover($macd['macd'], $macd['signal']);

        $histogramLinReg = $indicators->linearRegression($macd['histogram'], $histogramSlopeLength);

        if ($crossCheck == 'crossedAbove' || (end($macd['histogram']) > 0 && $histogramLinReg['m'] > 0)) {
            return 'long';
        }
        elseif ($crossCheck == 'crossedBelow' || (end($macd['histogram']) < 0 && $histogramLinReg['m'] < 0)) {
            return 'short';
        }
        else {
            return 'none';
        }
    }

    public function macdCrossOrHistogramTrendingUp($rates, $fastLength, $slowLength, $signalLength, $histogramSlopeLength) {
        $indicators = new CurrencyIndicators();

        $macd = $indicators->macd($rates, $fastLength, $slowLength, $signalLength);

        return $this->crossOrHistogramTrendingUp($macd, $indicators, $histogramSlopeLength);
    }

    public function macdHmaCrossOrHistogramTrendingUp($rates, $fastLength, $slowLength, $signalLength, $histogramSlopeLength) {
        $indicators = new CurrencyIndicators();

        $macd = $indicators->macdHma($rates, $fastLength, $slowLength, $signalLength);

        return $this->crossOrHistogramTrendingUp($macd, $indicators, $histogramSlopeLength);
    }

    public function macdNegativeHistogramExitCheck($macd, $slopeLength, $openPositionDirection) {
        $indicators = new CurrencyIndicators();

        $linReg = $indicators->linearRegression($macd['histogram'], $slopeLength);

        if ($linReg['m'] < 0 && $openPositionDirection == 1) {
            return 'close';
        }
        elseif ($linReg['m'] > 0 && $openPositionDirection == -1) {
            return 'close';
        }
        else {
            return 'nothing';
        }
    }

    public function hmaSlopeMinCheck($rates, $hmaLength, $linRegLength, $slopeCutoff) {
        $indicators = new CurrencyIndicators();

        $hma = $indicators->hma($rates, $hmaLength);

        $linReg = $indicators->linearRegression($hma, $linRegLength);

        if ($linReg['m'] >= $slopeCutoff) {
            return 'long';
        }
        elseif ($linReg['m'] <= $slopeCutoff*-1) {
            return 'short';
        }
        else {
            return 'none';
        }
    }

    public function macdExitCrossover($rates, $fastLength, $slowLength, $signalLength, $openPositionDirection) {
        $indicators = new CurrencyIndicators();

        $macd = $indicators->macd($rates, $fastLength, $slowLength, $signalLength);

        $crossCheck = $indicators->checkCrossover($macd['macd'], $macd['signal']);

        if ($crossCheck == 'crossedAbove' && $openPositionDirection == -1) {
            return 'close';
        }
        elseif ($crossCheck == 'crossedBelow' && $openPositionDirection == 1) {
            return 'close';
        }
        else {
            return 'none';
        }
    }

    public function lineSwitchedDirectionsAndMeetsMinSlope($line, $minSlope, $periodsBack) {
        $line = $this->utility->getLastXElementsInArray($line, $periodsBack + 2);

        $lineSlopes = [];

        foreach ($line as $index => $value) {
            if ($index != (count($line) - 1) && $index != (count($line) - 2)) {
                $currentValues = array_slice($line, $index, 2);
                $currentLinReg = $this->indicators->linearRegression($currentValues, 2);
                $lineSlopes[] = $currentLinReg['m'];
            }
        }

        $currentSlope = $this->indicators->linearRegression($line, 2);

        if ($this->utility->hasNegativeCheck($lineSlopes) && $currentSlope['m'] >= $minSlope) {
            return 'long';
        }
        elseif ($this->utility->hasPositiveCheck($lineSlopes) && $currentSlope['m'] <= $minSlope*-1) {
            return 'short';
        }
        else {
            return 'none';
        }
    }

    //
    public function hmaSwitchesDirectionAndMeetsMinimumSlope($rates, $length, $minSlope, $periodsBack) {
        $hma = $this->indicators->hma($rates, $length);

        return $this->lineSwitchedDirectionsAndMeetsMinSlope($hma, $minSlope, $periodsBack);
    }

    //See if Hma is Moving in the Opposite Direction of Current Open Position
    public function hmaWrongDirection($rates,$length, $openPositionDirection) {
        $hma = $this->indicators->hma($rates, $length);

        $hmaLinReg = $this->indicators->linearRegression($hma, 2);

        if ($hmaLinReg['m'] < 0 && $openPositionDirection == 1) {
            return 'close';
        }
        elseif ($hmaLinReg['m'] > 0 && $openPositionDirection == -1) {
            return 'close';
        }
        else {
            return 'nothing';
        }
    }

    public function hmaCrossover($rates, $fastLength, $slowLength) {
        $fastHma = $this->indicators->hma($rates, $fastLength);
        $slowHma = $this->indicators->hma($rates, $slowLength);
       // dd($slowHma);

        return $this->indicators->checkCrossoverGoBackTwo($fastHma, $slowHma);
    }

    public function fastSlowPlacement($rates, $fastLength, $slowLength) {
        $fastHma = $this->indicators->hma($rates, $fastLength);
        $slowHma = $this->indicators->hma($rates, $slowLength);

        $lastFast = end($fastHma);
        $lastSlow = end($slowHma);

        if ($lastFast > $lastSlow) {
            return 'fastAbove';
        }
        elseif ($lastFast < $lastSlow) {
            return 'fastBelow';
        }
    }

    public function hmaSlopeDirection($rates, $length) {
        $hma = $this->indicators->hma($rates, $length);

        $hmaLinReg = $this->indicators->linearRegression($hma, 2);

        if ($hmaLinReg['m'] > 0) {
            return 'up';
        }
        elseif ($hmaLinReg['m'] < 0) {
            return 'down';
        }
    }

    public function emaCrossover($rates, $fastLength, $slowLength) {
        $fastEma = $this->indicators->ema($rates, $fastLength);
        $slowEma = $this->indicators->ema($rates, $slowLength);

        return $this->indicators->checkCrossover($fastEma, $slowEma);
    }

    public function emaCrossoverHard($rates, $fastLength, $slowLength) {
        $fastEma = $this->indicators->ema($rates, $fastLength);
        $slowEma = $this->indicators->ema($rates, $slowLength);

        return $this->indicators->checkCrossover($fastEma, $slowEma);
    }

    public function emaCrossoverGoBackX($rates, $fastLength, $slowLength, $periodsBack) {
        $arraySets = $this->utility->getMultipleArraySets($rates, $slowLength*2, $periodsBack);

        $emaXEvents = [];

        foreach ($arraySets as $array) {
            $emaXEvents[] = $this->emaCrossover($array, $fastLength, $slowLength);
        }
        return $this->eventHelpers->periodsBackGetLastResultEvent($emaXEvents, ['crossedAbove', 'crossedBelow']);
    }
}
