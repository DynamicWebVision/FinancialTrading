<?php namespace App\Services;

use \App\Services\Utility;
use \Log;

class CurrencyIndicators  {
    //Simple Moving Average Calculation
    //NOT TESTED
    public function calculateSMA($numberPeriods, $currencyArray, $key) {
        $relativeKey = $key - $numberPeriods;
        $sumAmounts = 0;

        while ($relativeKey < $key) {
            $sumAmounts = $sumAmounts + $currencyArray[$relativeKey]['amount'];
            $relativeKey++;
        }

        return ($sumAmounts/$numberPeriods);
    }

    //Simple Moving Average Calculation
    //NOT TESTED
    public function calculateSMALive($numberPeriods, $currencyArray, $key) {
        $relativeKey = $key - $numberPeriods;
        $sumAmounts = 0;

        while ($relativeKey < $key) {
            $sumAmounts = $sumAmounts + $currencyArray[$relativeKey];
            $relativeKey++;
        }

        return ($sumAmounts/$numberPeriods);
    }

    //Exponential Moving Average
    public function ema($rates, $numberOfPeriods){
        $emaValues = [];

        //calculate K
        $k = 2 / ($numberOfPeriods + 1);

        $smaValues = array_slice($rates,0,$numberOfPeriods);

        $yesterdayEma = $this->average($smaValues);

        $emaRates = array_slice($rates, $numberOfPeriods);

        foreach($emaRates as $rate) {
            $yesterdayEma = (($rate * $k) + $yesterdayEma * (1 - $k));
            $emaValues[] = $yesterdayEma;
        }
        return $emaValues;
    }

    //Simple Slope/Rate of Change Calculation
    public function calculateRateOfChange($rates, $key, $period) {
        $startROCKey = $key - $period;

        return ($rates[$key]['amount'] - $rates[$startROCKey]['amount'])/$period;
    }

    public function sdSquare($x, $mean) { return pow($x - $mean,2); }

    //Calculate Standard deviation of Array
    public function standardDeviation($array) {
        if (sizeof($array) < 3) {
            return 0;
        }

        try {
            return sqrt(array_sum(array_map(array($this, 'sdSquare'), $array, array_fill(0,count($array), (array_sum($array) / count($array)) ) ) ) / (count($array)-1) );
        }
        catch (\Exception $e) {
            Log::critical(json_encode($e));
            Log::critical('Standard Deveiation Error array'.PHP_EOL.$array);
        }
    }

    //Has Test
    public function median ($arr) {
        if (sizeof($arr) > 2) {
            sort($arr);
            $count = count($arr); //total numbers in array
            $middleval = floor(($count-1)/2); // find the middle value, or the lowest middle value
            if($count % 2) { // odd number, middle is the median
                $median = $arr[$middleval];
            } else { // even number, calculate avg of 2 medians
                $low = $arr[$middleval];
                $high = $arr[$middleval+1];
                $median = (($low+$high)/2);
            }
            return $median;
        }
        else {
            return 0;
        }
    }

    //Has Test
    public function average($arr) {
        if (sizeof($arr) > 2) {
            $total = 0;
            $count = count($arr); //total numbers in array
            foreach ($arr as $value) {
                $total = $total + $value; // total value of array numbers
            }
            $average = ($total/$count); // get average value
            return $average;
        }
        else {
            return 0;
        }
    }

    public function getClosestLonger($search, $arr) {
    $closest = null;
    foreach ($arr as $item) {
        if ($closest === null || abs($search - $closest['tradeUnixTime']) > abs($item['tradeUnixTime'] - $search)) {
            $closest = $item;
        }
    }
    return $closest;
    }

    public function checkCrossover($short, $long) {
        $shortPeriodBefore = $short[count($short)-2];
        $shortCurrent = $short[count($short)-1];

        $longPeriodBefore = $long[count($long)-2];
        $longCurrent = $long[count($long)-1];

        if ($shortPeriodBefore < $longPeriodBefore && $shortCurrent > $longCurrent) {
            return "crossedAbove";
        }
        elseif ($shortPeriodBefore > $longPeriodBefore && $shortCurrent < $longCurrent) {
            return "crossedBelow";
        }
        else {
            return "none";
        }
    }

    public function checkCrossoverGoBackTwo($short, $long) {
        $shortPeriodBefore = $short[count($short)-2];
        $shortCurrent = $short[count($short)-1];

        $longPeriodBefore = $long[count($long)-2];
        $longCurrent = $long[count($long)-1];

        if ($shortPeriodBefore < $longPeriodBefore && $shortCurrent > $longCurrent) {
            return "crossedAbove";
        }
        elseif ($shortPeriodBefore > $longPeriodBefore && $shortCurrent < $longCurrent) {
            return "crossedBelow";
        }


        $shortPeriodBefore = $short[count($short)-3];
        $shortCurrent = $short[count($short)-2];

        $longPeriodBefore = $long[count($long)-3];
        $longCurrent = $long[count($long)-2];

        if ($shortPeriodBefore < $longPeriodBefore && $shortCurrent > $longCurrent) {
            return "crossedAbove";
        }
        elseif ($shortPeriodBefore > $longPeriodBefore && $shortCurrent < $longCurrent) {
            return "crossedBelow";
        }
        else {
            return "none";
        }
    }

    public function stochastic($rates, $kLength, $smoothingSlow, $smoothingFull) {

        $responseValues = [];

        foreach ($rates as $index => $rate) {
            if (($index +1) >= $kLength) {
                $averageValuesArray = $rates;

                $arrayIndexStart = $index - $kLength + 1;

                $averageValuesArray = array_slice($averageValuesArray, $arrayIndexStart, $kLength);

                $minValue = min($averageValuesArray);
                $maxValue = max($averageValuesArray);

                if ($maxValue - $minValue == 0) {
                    $responseValues['fast']['k'][] = 100;
                }
                else {
                    $responseValues['fast']['k'][] = (($rate - $minValue)/($maxValue - $minValue))*100;
                }
            }
        }

        $responseValues['fast']['d'] = $this->sma($responseValues['fast']['k'], $smoothingSlow);

        $responseValues['slow']['k'] = $this->sma($responseValues['fast']['d'], $smoothingSlow);
        $responseValues['slow']['d'] = $this->sma($responseValues['slow']['k'], $smoothingSlow);

        $responseValues['full']['k'] = $this->sma($responseValues['slow']['d'], $smoothingFull);
        $responseValues['full']['d'] = $this->sma($responseValues['full']['k'], $smoothingFull);

        return $responseValues;
    }


    //Has Test
    public function positiveNegativeIncreaseValueCheck($values) {

        $secondToLastValue = $values[count($values)-2];
        $lastValue = end($values);

        if ($secondToLastValue < 0 && $secondToLastValue < $lastValue) {
            return "passedLong";
        }
        elseif ($secondToLastValue > 0 && $secondToLastValue > $lastValue) {
            return "passedShort";
        }
        else {
            return "none";
        }
    }

    public function rsi($rates, $period) {
        $rateCutoff = sizeof($rates)-$period;

        $ratesSlice = $rates;
        $gains = [];
        $losses = [];

        $endRates = array_splice($ratesSlice, $rateCutoff);

        foreach ($endRates as $index=>$rate) {
            if (isset($endRates[$index+1])) {

                $diff = $endRates[$index+1] - $rate;

                if ($diff > 0) {
                    $gains[] = $diff;
                }
                elseif ($diff < 0) {
                    $losses[] = abs($diff);
                }
            }
        }

        if (array_sum($losses) == 0) {
            $rsi = 100;
        }
        else {
            $rs = (array_sum($gains)/$period)/(array_sum($losses)/$period);

            $rsi = 100 - (100/(1+$rs));
        }

        return $rsi;
    }

    public function sma($rates, $periods) {
        $smaValues = [];

        foreach ($rates as $index=>$rate) {
            if (($index +1) >= $periods) {
                $averageValuesArray = $rates;

                $arrayIndexStart = $index - $periods + 1;

                $averageValuesArray = array_slice($averageValuesArray, $arrayIndexStart, $periods);

                $smaValues[] = array_sum($averageValuesArray)/$periods;
            }
        }
        return $smaValues;
    }

    public function macd($rates, $shortPeriod, $longPeriod, $signalPeriod) {
        $shortEma = $this->ema($rates, $shortPeriod);
        $longEma = $this->ema($rates, $longPeriod);

        $shortPortionWithLong = array_slice($shortEma, sizeof($shortEma) - sizeof($longEma));

        $macdLine = [];

        foreach ($longEma as $index=>$value) {
            $macdLine[] = $shortPortionWithLong[$index] - $value;
        }

        $signalLine = $this->ema($macdLine, $signalPeriod);

        $macdWithSignal = array_slice($macdLine, sizeof($macdLine) - sizeof($signalLine));

        $histogram = [];

        foreach ($signalLine as $index=>$value) {
            $histogram[] = $macdWithSignal[$index] - $value;
        }

        return [
            'macd'=>$macdWithSignal,
            'signal'=>$signalLine,
            'histogram'=>$histogram
        ];
    }

    public function macdHma($rates, $shortPeriod, $longPeriod, $signalPeriod) {
        $shortEma = $this->hma($rates, $shortPeriod);
        $longEma = $this->hma($rates, $longPeriod);

        $shortPortionWithLong = array_slice($shortEma, sizeof($shortEma) - sizeof($longEma));

        $macdLine = [];

        foreach ($longEma as $index=>$value) {
            $macdLine[] = $shortPortionWithLong[$index] - $value;
        }

        $signalLine = $this->ema($macdLine, $signalPeriod);

        $macdWithSignal = array_slice($macdLine, sizeof($macdLine) - sizeof($signalLine));

        $histogram = [];

        foreach ($signalLine as $index=>$value) {
            $histogram[] = $macdWithSignal[$index] - $value;
        }

        return [
            'macd'=>$macdWithSignal,
            'signal'=>$signalLine,
            'histogram'=>$histogram
        ];
    }

    public function linearRegression($rates, $periods = false) {
        if ($periods == 2) {
            $utility = new Utility();
            $lastTwo = $utility->getLastXElementsInArray($rates, 2);
            $arrayDiff = $this->arrayDiff($lastTwo);
            return ['m'=>$arrayDiff[0], 100];
        }

        if ($periods) {
            $arrayStart = sizeof($rates) - $periods;
            $rates = array_slice($rates, $arrayStart);
        }

        $x = [];
        $y = $rates;

        foreach ($rates as $index=>$rate) {
            $x[] = $index+1;
        }

        // calculate number points
        $n = count($x);

        // calculate sums
        $x_sum = array_sum($x);
        $y_sum = array_sum($y);

        $xx_sum = 0;
        $xy_sum = 0;

        for($i = 0; $i < $n; $i++) {

            $xy_sum+=($x[$i]*$y[$i]);
            $xx_sum+=($x[$i]*$x[$i]);

        }
        $quotient = ($n * $xx_sum) - ($x_sum * $x_sum);
        // calculate slope

        if ((($n * $xx_sum) - ($x_sum * $x_sum)) == 0) {
            echo "alsdjflaskdf";
        }

        $m = (($n * $xy_sum) - ($x_sum * $y_sum)) / (($n * $xx_sum) - ($x_sum * $x_sum));

        // calculate intercept
        $b = ($y_sum - ($m * $x_sum)) / $n;

        // return result
        return array("m"=>$m, "b"=>$b);
    }

    public function getMostRecentCrossover($fast, $slow) {

        if (end($fast) > end($slow)) {
            $key = key($fast);
            $lastBelowIndex = false;

            while (!$lastBelowIndex) {
                $key = $key - 1;
                if ($fast[$key] < $slow[$key]) {
                    $lastBelowIndex = $key;
                }
            }
            return $lastBelowIndex;
        }
        elseif (end($fast) < end($slow)) {
            $key = key($fast);
            $lastBelowIndex = false;

            while (!$lastBelowIndex) {
                $key = $key - 1;
                if ($fast[$key] < $slow[$key]) {
                    $lastBelowIndex = $key;
                }
            }
            return $lastBelowIndex;
        }
        else {
            $key = key($fast);
            $lastUnequal = false;

            while (!$lastUnequal) {
                $key = $key - 1;
                if ($fast[$key] != $slow[$key]) {
                    $lastUnequal = $key;
                }
            }
            return $lastUnequal;
        }
    }

    //Hull Moving Average
    public function hma($rates, $numberOfPeriods) {
        $halfMovingAverageTimes2 = $this->ema($rates, round($numberOfPeriods/2));
        $fullEma = $this->ema($rates, $numberOfPeriods);

        $differenceLength = sizeof($fullEma)-1;

        $halfToSubtract = array_slice($halfMovingAverageTimes2, sizeof($halfMovingAverageTimes2) - $differenceLength);
        $fullEma = array_slice($fullEma, sizeof($fullEma) - $differenceLength);

        $differenceEma = [];

        foreach ($halfToSubtract as $index=>$value) {
            $differenceEma[] = ($halfToSubtract[$index]*2) - $fullEma[$index];
        }

        $squareRootPeriods = round(sqrt($numberOfPeriods));

        return $this->ema($differenceEma, $squareRootPeriods);
    }

    //H
    public function periodCountMaxMin($rates, $numberOfPeriods) {
        $rates = array_slice($rates, -$numberOfPeriods);

        $min = min($rates);
        $max = max($rates);

        $last = end($rates);

        if ($last == $max) {
            return "upperBreakthrough";
        }
        elseif ($last == $min) {
            return "lowerBreakthrough";
        }
        else {
            return "none";
        }
    }

    //HMA
    public function linearRegressionSet($array, $count, $linRegLength) {
        $arraySets = [];
        $linearRegressionSet = [];

        while ($count > 0) {
            $start = (sizeof($array) - ($count + $linRegLength)) + 1;
            $arraySets[] = array_slice($array, $start, $linRegLength);
            $count--;
        }
        foreach ($arraySets as $set) {
            $linearRegressionSet[] = $this->linearRegression($set, $linRegLength);
        }
        return $linearRegressionSet;
    }

    public function arrayDiff($array) {
        $diffValues = [];
        foreach ($array as $index=>$value) {
            if ($index > 0) {
                $diffValues[] = $array[$index] - $array[$index-1];
            }
        }
        return $diffValues;
    }

    public function arrayDiffSubtractCurrent($array) {
        $diffValues = [];
        foreach ($array as $index=>$value) {
            if ($index > 0) {
                $diffValues[] = $array[$index-1] - $array[$index];
            }
        }
        return $diffValues;
    }

    public function rateOfChange($array, $periods) {
        $firstLinRegSetCount = $periods + 1;

        $currentArrayStart = ($firstLinRegSetCount + $periods)*-1;

        $arrayLinRegValues = [];
        while (($currentArrayStart*-1)+1 != $firstLinRegSetCount) {
            $currentValues = array_slice($array, $currentArrayStart+1, $periods);
            $lingReg = $this->linearRegression($currentValues, $periods);
            $arrayLinRegValues[] = $lingReg['m'];

            $currentArrayStart++;
        }

        $linearRegLinReg = $this->linearRegression($arrayLinRegValues, $periods);
        return $linearRegLinReg['m'];
    }

    public function reverseDirection($array, $pipMin = false) {
        $arrayValues = array_slice($array, -3, 3);

        $arrayDiff = $this->arrayDiff($arrayValues);

        $yesterdayDirection = $arrayDiff[0];
        $todayDirection = $arrayDiff[1];

        if ($yesterdayDirection <= 0 && $todayDirection > 0) {
            if ($pipMin) {
                if ($todayDirection > $pipMin) {
                    return 'crossedUp';
                }
                else {
                    return 'none';
                }
            }
            else {
                return 'crossedUp';
            }
        }
        elseif ($yesterdayDirection >= 0 && $todayDirection < 0) {
            if ($pipMin) {
                if ($todayDirection*-1 > $pipMin) {
                    return 'crossedDown';
                }
                else {
                    return 'none';
                }
            }
            else {
                return 'crossedDown';
            }
        }
        else {
            return 'none';
        }
    }

    public function hmaMinRegRequirement() {
        $this->decisionIndicators['hmaLong'] = $this->indicators->hma($this->ratesPips, $this->hullLongLength);
        $this->decisionIndicators['hmaLongLinReg'] = $this->indicators->linearRegression($this->decisionIndicators['hmaLong'], $this->hullLinearRegressionLength);
    }

    public function trueRange($rates, $periods) {
        $trueRangeValues = [];
        foreach ($rates as $index=>$rate) {
            if ($index > 0) {
                $trueRangeOptions = [];
                $yesterdayRate = $rates[$index-1];
                $trueRangeOptions[] = $rate->highMid - $rate->lowMid;
                $trueRangeOptions[] = abs($rate->highMid - $yesterdayRate->closeMid);
                $trueRangeOptions[] = abs($rate->lowMid - $yesterdayRate->closeMid);
                $trueRangeValues[] = max($trueRangeOptions);
            }
        }
        return $this->wilderSmoothingTechnique($trueRangeValues,$periods);
    }

    public function averageTrueRange($rates, $periods) {
        $trueRangeValues = [];
        foreach ($rates as $index=>$rate) {
            if ($index > 0) {
                $trueRangeOptions = [];
                $yesterdayRate = $rates[$index-1];
                $trueRangeOptions[] = $rate->highMid - $rate->lowMid;
                $trueRangeOptions[] = abs($rate->highMid - $yesterdayRate->closeMid);
                $trueRangeOptions[] = abs($rate->lowMid - $yesterdayRate->closeMid);
                $trueRangeValues[] = max($trueRangeOptions);
            }
        }
        $utility = new Utility();

        $trueRangeValues = $utility->getLastXElementsInArray($trueRangeValues, $periods);
        return ((array_sum($trueRangeValues))/$periods);
    }

    public function trueRangeArray($rates, $periods) {
        $trueRangeValues = [];
        foreach ($rates as $index=>$rate) {
            if ($index > 0) {
                $trueRangeOptions = [];
                $yesterdayRate = $rates[$index-1];
                $trueRangeOptions[] = $rate['highMid'] - $rate['lowMid'];
                $trueRangeOptions[] = abs($rate['highMid'] - $yesterdayRate['closeMid']);
                $trueRangeOptions[] = abs($rate['lowMid'] - $yesterdayRate['closeMid']);
                $trueRangeValues[] = max($trueRangeOptions);
            }
        }
        return $this->wilderSmoothingTechnique($trueRangeValues,$periods);
    }

    public function wilderSmoothingTechnique($values, $periods) {
        $wilderSmoothedValues = [];

        $wilderSmoothedValues[] = array_sum(array_slice($values, 0, $periods));

        $remainingValuesForSmoothing = array_slice($values, $periods);

        $currentWilderIndex = 0;
        foreach ($remainingValuesForSmoothing as $value) {
            $priorSmoothedValue = $wilderSmoothedValues[$currentWilderIndex];
            $wilderSmoothedValues[] = ($priorSmoothedValue - ($priorSmoothedValue/$periods)) + $value;
            $currentWilderIndex++;
        }
        return $wilderSmoothedValues;
    }

    public function wilderSmoothingForAverage($values, $periods) {
        $wilderSmoothedValues = [];

        $wilderSmoothedValues[] = array_sum((array_slice($values, 0, $periods)))/$periods;

        $remainingValuesForSmoothing = array_slice($values, $periods);

        $currentWilderIndex = 0;
        foreach ($remainingValuesForSmoothing as $value) {
            $priorSmoothedValue = $wilderSmoothedValues[$currentWilderIndex];
            $wilderSmoothedValues[] = (($priorSmoothedValue*($periods-1)) + $value)/$periods;
            $currentWilderIndex++;
        }
        return $wilderSmoothedValues;
    }

    public function adx($rates, $periods) {

        $highs = array_column($rates,'highMid');
        $lows = array_column($rates,'lowMid');

        $highDiffs = $this->arrayDiff($highs);
        $lowDiffs = $this->arrayDiffSubtractCurrent($lows);

        $plusDi = [];
        $minusDi = [];

        //dd(['highDiffs'=>$highDiffs, 'lowDiffs'=>$lowDiffs]);

        foreach ($highDiffs as $index=>$highDiff) {
            $lowDiff = $lowDiffs[$index];

            if ($highDiff > $lowDiff && $highDiff > 0) {
                $plusDi[] = $highDiff;
            }
            else {
                $plusDi[] = 0;
            }

            if ($lowDiff > $highDiff && $lowDiff > 0) {
                $minusDi[] = $lowDiff;
            }
            else {
                $minusDi[] = 0;
            }
        }

        $trueRange = $this->trueRange($rates, $periods);

        $plusDiEma = $this->wilderSmoothingTechnique($plusDi, $periods);
        $minusDiEma = $this->wilderSmoothingTechnique($minusDi, $periods);

        $plusDiIndicator = [];

        foreach ($plusDiEma as $index=>$plusDiEmaValue) {
            if ($trueRange[$index] == 0) {
                dd($index);
            }

            $plusDiIndicator[] = $plusDiEmaValue/$trueRange[$index];
        }

        $plusDiIndicator = array_map(function($value) {
            return $value*100;
        }, $plusDiIndicator);

        $minusDiIndicator = [];

        foreach ($minusDiEma as $index=>$minusDiEmaValue) {
            $minusDiIndicator[] = $minusDiEmaValue/$trueRange[$index];
        }

        $minusDiIndicator = array_map(function($value) {
            return $value*100;
        }, $minusDiIndicator);

        $dxIndicator = [];

        foreach ($plusDiIndicator as $index=>$plusIndicator) {
            if (($plusIndicator + $minusDiIndicator[$index]) == 0) {
                $dxIndicator[] = 0;
            }
            else {
                $dxIndicator[] = ((abs($plusIndicator - $minusDiIndicator[$index]))/($plusIndicator + $minusDiIndicator[$index]))*100;
            }
        }
        $adx = $this->wilderSmoothingForAverage($dxIndicator, $periods);
        return $adx;
    }

    public function bollingerBands($array, $length, $standardDeviationMultiplier) {
        $utility = new Utility();

        $arrayValuesNeeded = $utility->getLastXElementsInArray($array, $length);

        $average = array_sum($arrayValuesNeeded)/$length;

        $standardDeviation = $this->standardDeviation($arrayValuesNeeded);

        $high = $average + ($standardDeviation*$standardDeviationMultiplier);
        $low = $average - ($standardDeviation*$standardDeviationMultiplier);

        return [
            'average'=> $average,
            'high'=> $high,
            'low'=> $low
        ];
    }

    public function upwardSlopeOrHardCutoff($array, $hardCutoffPoint, $upwardSlopeCutoffPoint, $slopeMin) {
        $currentValue = end($array);

        if ($currentValue >= $hardCutoffPoint) {
            return true;
        }
        elseif ($currentValue >= $upwardSlopeCutoffPoint) {
            $slope = $this->linearRegression($array, 2);

            if ($slope['m'] >= $slopeMin) {
                return true;
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }

    public function differenceSlope($array1, $array2, $periods) {
        $utility = new Utility();

        $firstArrayRelevantValues = $utility->getLastXElementsInArray($array1, $periods);
        $secondArrayRelevantValues = $utility->getLastXElementsInArray($array2, $periods);

        $differences = [];

        foreach ($firstArrayRelevantValues as $index=>$value) {
            $differences[] = $secondArrayRelevantValues[$index] - $value;
        }
        $linearRegression = $this->linearRegression($differences, $periods);

        return abs($linearRegression['m']);
    }

    public function pivotPoints($previousRate) {
        $high = $previousRate->highMid;
        $low = $previousRate->lowMid;
        $close = $previousRate->closeMid;

        $response = [];

        $response['pivot'] = ($high + $low + $close)/3;

        $response['r1'] = ($response['pivot']*2) - $low;
        $response['r2'] = $response['pivot'] + ($high - $low);
        $response['s1'] = ($response['pivot']*2) - $high;
        $response['s2'] = $response['pivot'] - ($high - $low);

        return $response;
    }


    public function stochastics($rates, $kLength, $smoothingSlow, $smoothingFull) {

        $utility = new Utility();
        $rates = $utility->getLastXElementsInArray($rates, $kLength*2);

        $highs = array_column($rates,'highMid');
        $lows = array_column($rates,'lowMid');

        $responseValues = [];

        foreach ($rates as $index => $rate) {
            if (($index +1) >= $kLength) {

                $arrayIndexStart = $index - $kLength + 1;

                $currentHighs = array_slice($highs, $arrayIndexStart, $kLength);
                $currentLows = array_slice($lows, $arrayIndexStart, $kLength);

                $lowValue = min($currentLows);
                $highValue = max($currentHighs);

                if ($highValue - $lowValue == 0) {
                    $responseValues['fast']['k'][] = 100;
                }
                else {
                    $responseValues['fast']['k'][] = (($rate->closeMid - $lowValue)/($highValue - $lowValue))*100;
                }
            }
        }

        $responseValues['fast']['d'] = $this->sma($responseValues['fast']['k'], $smoothingSlow);

        $responseValues['slow']['k'] = $this->sma($responseValues['fast']['d'], $smoothingSlow);
        $responseValues['slow']['d'] = $this->sma($responseValues['slow']['k'], $smoothingSlow);

        $responseValues['full']['k'] = $this->sma($responseValues['slow']['d'], $smoothingFull);
        $responseValues['full']['d'] = $this->sma($responseValues['full']['k'], $smoothingFull);

        return $responseValues;
    }
}
