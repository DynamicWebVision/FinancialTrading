<?php namespace App\Services;

class CurrencyProcessing  {

    public $macdShortPeriod;
    public $macdLongPeriod;

    public $macdIndicatorPeriod;

    public $rsiPeriods;
    public $rsiUpperCutoff;
    public $rsiLowerCutoff;

    public $bollingerPeriods;

    public $currentPositionStatus;

    public $currentLongPrice;
    public $currentShortPrice;

    public $currentLoss;

    public $accountValue;

    public $smaShortPeriod;
    public $smaLongPeriod;

    public $rateOfChangePeriod;

    public $transactions;

    public $lossCutoff;

    public $currentShortMin;
    public $currentLongMax;

    public $fivePeriodsSincePositionRate;
    public $tenPeriodsSincePositionRate;

    public $maxGain;

    public $periodsSincePosition;
    public $biggestGainPeriodSince;

    public $rocPeriods;

    public $strategyId;

    public $tenPercentMarginAmount;

    public $longerIndicatorValues;

    public $outerPeriodShortEma;
    public $outerPeriodLongEma;

    public $outerCurrencyExchange;
    public $mainCurrencyExchange;

    /***************************************
      Run Strategy
     *
     * This is the method that processes and loops through a currency period instance.
     *
     * As it loops through it performs several calculations on the currency that will be
     * used later to decide whether any position will be entered.  The calculations are often
     * done by passing variables to the CurrencyIndicators service/class.
     *
     * At the bottom of the loop, the indicator values are passed to a decision method located in the
     * CurrencyPositionDecisions service/class, and a decision is made as to whether to enter a new position.
     ***************************************/
    public function runStrategy($currencies)
    {
        $indicators = new \App\Services\CurrencyIndicators();
        $positionDecisions = new \App\Services\CurrencyPositionDecisions();

        $positionDecisions->rsiUpperCutoff = 80;
        $positionDecisions->rsiLowerCutoff = 20;

        $positionDecisions->lossCutoff = .001;

        $this->transactions = [];

        $currencyResponse = [];

        $key = 0;
        $maxAmount = 0;
        $minAmount = 1000;

        $maxMacd = 0;
        $minMacd = 0;

        $transactionIndex = 0;

        $this->currentPositionStatus = 0;

        $absoluteRoc = [];

        $this->tenPercentMarginAmount = 10000;

        foreach ($currencies as $currency) {

            $currencyResponse['rates'][$key]['index'] = $key;

            //Formatting a readable date value
            $currencyResponse['rates'][$key]['tradeDate'] = str_replace("00:00:00", "", $currency->currency_date);
            $currencyResponse['rates'][$key]['tradeUnixTime'] = strtotime($currency->currency_date);

            $currencyResponse['rates'][$key]['amount'] = $currency->amount;

            if ($key > 0) {
                $yesterdayKey = $key - 1;

                //Calculate Change
                $currencyResponse['rates'][$key]['change'] = $currency->amount - $currencyResponse['rates'][$yesterdayKey]['amount'];
                $currencyResponse['rates'][$key]['change'] = $currency->amount - $currencyResponse['rates'][$yesterdayKey]['amount'];

                //Calculate Gain
                if ($currencyResponse['rates'][$key]['change'] > 0) {
                    $currencyResponse['rates'][$key]['gain'] = $currencyResponse['rates'][$key]['change'];
                }
                else {
                    $currencyResponse['rates'][$key]['gain'] = 0;
                }

                //Calculate Loss
                if ($currencyResponse['rates'][$key]['change'] < 0) {
                    $currencyResponse['rates'][$key]['loss'] = $currencyResponse['rates'][$key]['change']*-1;
                }
                else {
                    $currencyResponse['rates'][$key]['loss'] = 0;
                }
            }

            //Short and Long SMA can be used in a decision.  The idea is if the shorter SMA crosses over the longer SMA a position should be entered
            if ($key > $this->macdShortPeriod-1) {
                $currencyResponse['rates'][$key]['shortMacdSma'] = $indicators->calculateSMA($this->macdShortPeriod, $currencyResponse['rates'], $key);
            }

            if ($key > $this->macdLongPeriod-1) {
                $currencyResponse['rates'][$key]['longMacdSma'] = $indicators->calculateSMA($this->macdLongPeriod, $currencyResponse['rates'], $key);
            }

            //Sma Used for Position Decision
            if ($key > $this->smaShortPeriod-1) {
                $currencyResponse['rates'][$key]['shortSma'] = $indicators->calculateSMA($this->smaShortPeriod, $currencyResponse['rates'], $key);
            }

            if ($key > $this->smaLongPeriod-1) {
                $currencyResponse['rates'][$key]['longSma'] = $indicators->calculateSMA($this->smaLongPeriod, $currencyResponse['rates'], $key);
            }

            //Rate of Change Calculation
            if ($key > $this->rateOfChangePeriod) {
                $currencyResponse['rates'][$key]['roc'] = $indicators->calculateRateOfChange($currencyResponse['rates'], $key, $this->rateOfChangePeriod);

                if ($currencyResponse['rates'][$key]['roc'] >= 0) {
                    $absoluteRoc[$key] = $currencyResponse['rates'][$key]['roc'];
                    $currencyResponse['rates'][$key]['absoluteRoc'] = $currencyResponse['rates'][$key]['roc'];
                }
                else {
                    $absoluteRoc[$key] = $currencyResponse['rates'][$key]['roc']*-1;
                    $currencyResponse['rates'][$key]['absoluteRoc'] = $currencyResponse['rates'][$key]['roc']*-1;
                }

                if ($key > ($this->rocFactorPeriods + $this->rateOfChangePeriod)) {
                    //ROC Factor...Experimenting with this, the idea is to see if ROC is greater than the standard deviation of ROC over the last several periods
                    $rocFactorRelativeKey = $key - $this->rocFactorPeriods;
                    $previousRoc = [];
                    while ($rocFactorRelativeKey <= $key) {
                        $previousRoc[] = $absoluteRoc[$rocFactorRelativeKey];
                        $rocFactorRelativeKey++;
                    }

                   $rocStandardDeviation = $indicators->standardDeviation($previousRoc);
                   $rocAverage = $indicators->average($previousRoc);

                   $currencyResponse['rates'][$key]['rocFactor'] = $currencyResponse['rates'][$key]['absoluteRoc']/($rocAverage + $rocStandardDeviation);
                }

            }

           if ($key > $this->bollingerPeriods-1) {
                $currencyResponse['rates'][$key]['bollingerSma'] = $indicators->calculateSMA($this->bollingerPeriods, $currencyResponse['rates'], $key);
            }

            //Calculate Current Loss
            if ($this->currentPositionStatus == 1) {
                $currencyResponse['rates'][$key]['positionChange'] = $this->currentLongPrice - $currency->amount;
                $currencyResponse['rates'][$key]['positionChangePercent'] = ($currency->amount - $this->currentLongPrice)/$this->currentLongPrice;

                $this->periodsSincePosition = $this->periodsSincePosition + 1 ;

                if ($this->currentLongMax < $currency->amount) {
                    $this->currentLongMax = $currency->amount;
                    $this->maxGain = $this->currentLongMax - $this->currentLongPrice;
                    $this->biggestGainPeriodSince = $this->periodsSincePosition;
                }

                //Find the amount of change in 5 periods since new position
                if ($this->periodsSincePosition == 5) {
                    $this->fivePeriodsSincePositionRate = $currency->amount;
                }

                //Find the amount of change in 10 periods since new position
                if ($this->periodsSincePosition == 10) {
                    $this->tenPeriodsSincePositionRate = $currency->amount;
                }
            }
            elseif ($this->currentPositionStatus == -1) {
                $currencyResponse['rates'][$key]['positionChange'] = $currency->amount - $this->currentShortPrice;
                $currencyResponse['rates'][$key]['positionChangePercent'] = ($this->currentShortPrice - $currency->amount)/$this->currentShortPrice;

                $this->periodsSincePosition = $this->periodsSincePosition + 1;

                if ($this->currentShortMin > $currency->amount) {
                    $this->currentShortMin = $currency->amount;
                    $this->maxGain = $this->currentShortPrice - $currency->amount;
                    $this->biggestGainPeriodSince = $this->periodsSincePosition;
                }

                //Find the amount of change in 5 periods since new position
                if ($this->periodsSincePosition == 5) {
                    $this->fivePeriodsSincePositionRate = $currency->amount;
                }

                //Find the amount of change in 10 periods since new position
                if ($this->periodsSincePosition == 10) {
                    $this->tenPeriodsSincePositionRate = $currency->amount;
                }
            }
            else {
                $currencyResponse['rates'][$key]['currentLoss'] = 0;
            }

            //Calculate RSI Stuff
            if ($key > $this->rsiPeriods) {
                $relativeRsiKey = $key - $this->rsiPeriods;

                $totalGains = 0;
                $totalLosses = 0;

                //Average Gains & Losses
                while ($relativeRsiKey <= $key) {
                    $totalGains = $totalGains + $currencyResponse['rates'][$relativeRsiKey]['gain'];
                    $totalLosses = $totalLosses + $currencyResponse['rates'][$relativeRsiKey]['loss'];
                    $relativeRsiKey++;
                }

                $currencyResponse['rates'][$key]['averageGain'] = $totalGains/$this->rsiPeriods;
                $currencyResponse['rates'][$key]['averageLoss'] = $totalLosses/$this->rsiPeriods;

                if ($currencyResponse['rates'][$key]['averageLoss'] == 0 && $currencyResponse['rates'][$key]['averageGain'] > 0) {
                    $currencyResponse['rates'][$key]['rsi'] = 100;
                }
                elseif (($currencyResponse['rates'][$key]['averageLoss'] > 0 && $currencyResponse['rates'][$key]['averageGain'] == 0)) {
                    $currencyResponse['rates'][$key]['rsi'] = 0;
                }
                elseif (($currencyResponse['rates'][$key]['averageLoss'] == 0 && $currencyResponse['rates'][$key]['averageGain'] == 0)) {
                    $currencyResponse['rates'][$key]['rsi'] = 50;
                }
                else {
                    $rs = $currencyResponse['rates'][$key]['averageGain']/$currencyResponse['rates'][$key]['averageLoss'];
                    $currencyResponse['rates'][$key]['rsi'] = round(100 - (100 / (1 + ($rs))), 2);
                }

                //Figure Out this Shit
            }

            //Calculate Bollinger Bands
            if ($key > $this->bollingerPeriods) {
                $bollingerRelativeKey = $key - $this->bollingerPeriods;
                $previousValues = [];

                //Average Gains & Losses
                while ($bollingerRelativeKey <= $key) {
                    $previousValues[] = $currencyResponse['rates'][$bollingerRelativeKey]['amount'];
                    $bollingerRelativeKey++;
                }

                $standardDeviation = $indicators->standardDeviation($previousValues);

                $currencyResponse['rates'][$key]['upperBollinger'] = $currencyResponse['rates'][$key]['bollingerSma'] + ($standardDeviation*2);
                $currencyResponse['rates'][$key]['lowerBollinger'] = $currencyResponse['rates'][$key]['bollingerSma'] - ($standardDeviation*2);

                //Record where current rate is relative to Bollinger Bands
                if ($currency->amount > $currencyResponse['rates'][$key]['lowerBollinger'] && $currency->amount <  $currencyResponse['rates'][$key]['upperBollinger']) {
                    $currencyResponse['rates'][$key]['bollingerStatus'] = "between";
                }
                elseif ($currency->amount > $currencyResponse['rates'][$key]['upperBollinger']) {
                    $currencyResponse['rates'][$key]['bollingerStatus'] = "above";
                }
                elseif ($currency->amount < $currencyResponse['rates'][$key]['lowerBollinger']) {
                    $currencyResponse['rates'][$key]['bollingerStatus'] = "below";
                }

                //Cross Bollinger Moving Average
                if ($currency->amount > $currencyResponse['rates'][$key]['bollingerSma']) {
                    $currencyResponse['rates'][$key]['bollingerSmaStatus'] = "above";
                }
                elseif ($currency->amount < $currencyResponse['rates'][$key]['bollingerSma']) {
                    $currencyResponse['rates'][$key]['bollingerSmaStatus'] = "below";
                }

                //Records a Bollinger Event from one day to another...crossing a band
                if (isset($currencyResponse['rates'][$yesterdayKey]['bollingerStatus'])) {
                    if (($currencyResponse['rates'][$yesterdayKey]['bollingerStatus'] == "between" || $currencyResponse['rates'][$yesterdayKey]['bollingerStatus'] == "below")
                        && $currencyResponse['rates'][$key]['bollingerStatus'] == "above") {
                        $currencyResponse['rates'][$key]['bollingerEvent'] = "crossedAbove";
                    }
                    elseif (($currencyResponse['rates'][$yesterdayKey]['bollingerStatus'] == "between" || $currencyResponse['rates'][$yesterdayKey]['bollingerStatus'] == "above")
                        && $currencyResponse['rates'][$key]['bollingerStatus'] == "below") {
                        $currencyResponse['rates'][$key]['bollingerEvent'] = "crossedBelow";
                    }
                    elseif ($currencyResponse['rates'][$yesterdayKey]['bollingerStatus'] == "below" && $currencyResponse['rates'][$key]['bollingerStatus'] == "between") {
                        $currencyResponse['rates'][$key]['bollingerEvent'] = "crossedBelowToBetween";
                    }
                    elseif ($currencyResponse['rates'][$yesterdayKey]['bollingerStatus'] == "above" && $currencyResponse['rates'][$key]['bollingerStatus'] == "between") {
                        $currencyResponse['rates'][$key]['bollingerEvent'] = "crossedAboveToBetween";
                    }
                    else {
                        $currencyResponse['rates'][$key]['bollingerEvent'] = "none";
                    }
                }

                //bollinger SMA Event
//                if (isset($currencyResponse['rates'][$yesterdayKey]['bollingerSmaStatus'])) {
//                    if (($currencyResponse['rates'][$yesterdayKey]['bollingerSmaStatus'] == "above" && $currencyResponse['rates'][$key]['bollingerSmaStatus'] == "below") ||
//                        ($currencyResponse['rates'][$key]['bollingerSmaStatus'] == "above" && $currencyResponse['rates'][$yesterdayKey]['bollingerSmaStatus'] == "below")) {
//
//                        $currencyResponse['rates'][$key]['bollingerSmaEvent'] = "crossed";
//                    }
//                    else {
//                        $currencyResponse['rates'][$key]['bollingerSmaEvent'] = "none";
//                    }
//                }
            }


            if ($key > $this->macdLongPeriod) {

                //Calculated Exponential Moving Average for MACD
                if (isset($currencyResponse['rates'][$yesterdayKey]['shortEma'])) {
                    $yesterdayShortEma = $currencyResponse['rates'][$yesterdayKey]['shortEma'];
                } else {
                    $yesterdayShortEma = $currencyResponse['rates'][$yesterdayKey]['shortMacdSma'];
                }

                if (isset($currencyResponse['rates'][$yesterdayKey]['longEma'])) {
                    $yesterdayLongEma = $currencyResponse['rates'][$yesterdayKey]['longEma'];
                } else {
                    $yesterdayLongEma = $currencyResponse['rates'][$yesterdayKey]['longMacdSma'];
                }

                $currencyResponse['rates'][$key]['shortEma'] = $indicators->calculateEMA($this->macdShortPeriod, $key, $currency->amount, $yesterdayShortEma);
                $currencyResponse['rates'][$key]['longEma'] = $indicators->calculateEMA($this->macdLongPeriod, $key, $currency->amount, $yesterdayLongEma);

                //EMA Events
                if (isset($yesterdayLongEma)) {
                    if ($yesterdayShortEma > $yesterdayLongEma  && $currencyResponse['rates'][$key]['shortEma'] < $currencyResponse['rates'][$key]['longEma']) {
                        $currencyResponse['rates'][$key]['emaEvent'] = "crossedBelow";
                    }
                    elseif ($yesterdayShortEma < $yesterdayLongEma  && $currencyResponse['rates'][$key]['shortEma'] > $currencyResponse['rates'][$key]['longEma']) {
                        $currencyResponse['rates'][$key]['emaEvent'] = "crossedAbove";
                    }
                    else {
                        $currencyResponse['rates'][$key]['emaEvent'] = "none";
                    }
                }

                if ( $currencyResponse['rates'][$key]['longEma'] > 0) {
                    $currencyResponse['rates'][$key]['macd'] = ($currencyResponse['rates'][$key]['shortEma'] - $currencyResponse['rates'][$key]['longEma']);

                    if ($currencyResponse['rates'][$key]['macd'] > $maxMacd) {
                        $maxMacd = $currencyResponse['rates'][$key]['macd'];
                    }

                    if ($currencyResponse['rates'][$key]['macd'] < $minMacd) {
                        $minMacd = $currencyResponse['rates'][$key]['macd'];
                    }

                    if ($key > 100)
                    {
                        //MACD Signal Start ********** MACD Signal = EMA (Moving Average) of MACD
                        //Signal is EMA of MACD
                        $currencyResponse['rates'][$key]['macdSignalSma'] = $indicators->calculateMacdSMA($this->macdLongPeriod, $currencyResponse['rates'], $key);

                        if (isset($currencyResponse['rates'][$yesterdayKey]['macdSignal'])) {
                            $yesterdayMacd = $currencyResponse['rates'][$yesterdayKey]['macdSignal'];
                        }
                        elseif (isset($currencyResponse['rates'][$yesterdayKey]['macdSignalSma']))
                        {
                            $yesterdayMacd = $currencyResponse['rates'][$yesterdayKey]['macdSignalSma'];
                        }

                        if (isset($yesterdayMacd)) {
                            $currencyResponse['rates'][$key]['macdSignal'] = $indicators->calculateEMA($this->macdIndicatorPeriod, $key,
                                $currencyResponse['rates'][$key]['macd'], $yesterdayMacd);

                            //Macd Histogram
                            $currencyResponse['rates'][$key]['macdHistogram'] = $currencyResponse['rates'][$key]['macd'] - $currencyResponse['rates'][$key]['macdSignal'];
                            $previousHistoKey = $key - 4;

                            if (isset($currencyResponse['rates'][$previousHistoKey]['macdHistogram'])) {
                                $currencyResponse['rates'][$key]['prevHistogramValue'] = $currencyResponse['rates'][$previousHistoKey]['macdHistogram'];
                            }
                        }
                        //MACD Signal End

                        //Simple Moving Average Indicator Triggers Start
                        if ($currencyResponse['rates'][$key]['shortSma'] > $currencyResponse['rates'][$key]['longSma']) {
                            $currencyResponse['rates'][$key]['currentShortSmaAgainstLongSma'] = 'above';
                        }
                        else {
                            $currencyResponse['rates'][$key]['currentShortSmaAgainstLongSma'] = 'below';
                        }


                        if (isset($currencyResponse['rates'][$yesterdayKey]['currentShortSmaAgainstLongSma'])) {
                            if ($currencyResponse['rates'][$key]['currentShortSmaAgainstLongSma'] == 'above' &&
                                $currencyResponse['rates'][$yesterdayKey]['currentShortSmaAgainstLongSma'] == 'below'
                            ) {

                                $currencyResponse['rates'][$key]['smaCrossover'] = 'crossedAbove';
                            }
                            elseif ($currencyResponse['rates'][$key]['currentShortSmaAgainstLongSma'] == 'below' &&
                                $currencyResponse['rates'][$yesterdayKey]['currentShortSmaAgainstLongSma'] == 'above') {

                                $currencyResponse['rates'][$key]['smaCrossover'] = 'crossedBelow';
                            } else {
                                $currencyResponse['rates'][$key]['smaCrossover'] = 'none';
                            }
                        }



                        //Simple Moving Average Indicator Triggers End

                        if (isset($yesterdayMacd)) {
                            /********************************************************************************
                             See if MACD indicator is above or below, the assumption is this can be used to
                             confirm trends
                             ********************************************************************************/
                            if ($currencyResponse['rates'][$key]['macdSignal'] > $currencyResponse['rates'][$key]['macd']) {
                                $currencyResponse['rates'][$key]['macdStatus'] = "signalHigher";
                            }
                            else {
                                $currencyResponse['rates'][$key]['macdStatus'] = "signalLower";
                            }

                            /********************************************************************************
                             Check to see if an MACD crossover has occurred that is used to trigger a new
                             * position
                             ********************************************************************************/
                            if (isset($currencyResponse['rates'][$yesterdayKey]['macdStatus'])) {
                                //Check for macd crossover
                                if ($currencyResponse['rates'][$key]['macdStatus'] == "signalHigher" && $currencyResponse['rates'][$yesterdayKey]['macdStatus'] == "signalLower") {
                                    $currencyResponse['rates'][$key]['macdCrossOver'] = -1;
                                }
                                else if ($currencyResponse['rates'][$key]['macdStatus'] == "signalLower" && $currencyResponse['rates'][$yesterdayKey]['macdStatus'] == "signalHigher") {
                                    $currencyResponse['rates'][$key]['macdCrossOver'] = 1;
                                }
                                else {
                                    $currencyResponse['rates'][$key]['macdCrossOver'] = 0;
                                }


                                /********************************************************************************
                                 Position Decision - This determins if we want to buy/sell/close the currency
                                 ********************************************************************************/

                                //I use the code below to set a breakpoint to see what's going on with a position
                                if ($key == 130) {
                                        $test = "asdf";
                                }

                                if (isset($currencyResponse['rates'][$key]['rocFactor'])) {
                                    //Check Longer Macd
                              //      $currencyResponse['rates'][$key]['longerValues'] = $indicators->getClosestLonger($currencyResponse['rates'][$key]['tradeUnixTime'] , $this->longerIndicatorValues['rates']);

                                    $positionDecision = $positionDecisions->emaDecision($currencyResponse['rates'][$key], $this->currentPositionStatus);
                                }
                                else {
                                    $positionDecision = ['newPosition'=> "none", 'closePosition'=> "none"];
                                }

                                /********************************************************************************
                                 Closed Position Transaction Information For Analysis
                                 ********************************************************************************/
                                if ($positionDecision['closePosition'] == "closeLong") {

                                    $gainLoss = $currencyResponse['rates'][$key]['amount'] - $this->currentLongPrice;
                                    $gainLossPercent = round(($gainLoss/$this->currentLongPrice)*100, 2);
                                    $currencyResponse['rates'][$key]['gainLoss'] = $gainLoss;

                                    $currencyResponse['rates'][$key]['closePosition'] = 'closeLong';
                                    $this->currentPositionStatus = 0;

                                    $fivePeriodChange = $this->fivePeriodsSincePositionRate - $this->currentLongPrice;
                                    $fivePeriodChangePercent = round(($fivePeriodChange/$this->currentLongPrice)*100, 2);

                                    $tenPeriodChange = $this->tenPeriodsSincePositionRate - $this->currentLongPrice;
                                    $tenPeriodChangePercent = round(($tenPeriodChange/$this->currentLongPrice)*100, 2);

                                    $maxGainPercent  = round(($this->maxGain/$this->currentLongPrice)*100, 2);

                                    $previousIndex = $transactionIndex - 1;

                                    $oneLotGainLoss = ((10000/$this->currentLongPrice)*$currencyResponse['rates'][$key]['amount']) - 10000;

                                    $tradeAmount = $this->tenPercentMarginAmount*10;
                                    $this->tenPercentMarginAmount = $this->tenPercentMarginAmount + ((($tradeAmount/$this->currentLongPrice)*$currencyResponse['rates'][$key]['amount']) - $tradeAmount);
                                    $this->transactions[$transactionIndex]['tenPercentMarginAmount'] = $this->tenPercentMarginAmount;

                                    //Transaction History
                                    $this->transactions[$transactionIndex]['transactionType'] = 'closeLong';
                                    $this->transactions[$transactionIndex]['transactionDate'] = str_replace('00:00:00', '', $currency->currency_date);
                                    $this->transactions[$transactionIndex]['price'] = $currencyResponse['rates'][$key]['amount'];
                                    $this->transactions[$transactionIndex]['gainLoss'] = $gainLoss;
                                    $this->transactions[$transactionIndex]['maxGainPercent'] = $maxGainPercent;
                                    $this->transactions[$transactionIndex]['maxGainPeriod'] = $this->biggestGainPeriodSince;
                                    $this->transactions[$transactionIndex]['fiveDayChangePercent'] = $fivePeriodChangePercent;
                                    $this->transactions[$transactionIndex]['tenDayChangePercent'] = $tenPeriodChangePercent;
                                    $this->transactions[$transactionIndex]['gainLossPercent'] = $gainLossPercent;
                                    $this->transactions[$transactionIndex]['oneLotGainLoss'] = $oneLotGainLoss;
                                    $this->transactions[$transactionIndex]['rateIndex'] = $key;
                                    $transactionIndex++;

                                }
                                else if ($positionDecision['closePosition'] == "closeShort") {

                                    $gainLoss = $this->currentShortPrice - $currencyResponse['rates'][$key]['amount'];
                                    $currencyResponse['rates'][$key]['gainLoss'] = $gainLoss;
                                    $gainLossPercent = round(($gainLoss/$this->currentShortPrice)*100, 2);

                                    $currencyResponse['rates'][$key]['closePosition'] = 'closeShort';
                                    $this->currentPositionStatus = 0;

                                    if ($this->fivePeriodsSincePositionRate > 0) {
                                        $fivePeriodChange = $this->currentLongPrice - $this->fivePeriodsSincePositionRate;
                                        $fivePeriodChangePercent = round(($fivePeriodChange/$this->fivePeriodsSincePositionRate)*100, 2);
                                    }
                                    else {
                                        $fivePeriodChangePercent = 0;
                                    }


                                    if ($this->tenPeriodsSincePositionRate > 0) {
                                        $tenPeriodChange = $this->currentLongPrice - $this->tenPeriodsSincePositionRate;
                                        $tenPeriodChangePercent = round(($tenPeriodChange/$this->tenPeriodsSincePositionRate)*100, 2);
                                    }
                                    else {
                                        $tenPeriodChangePercent = 0;
                                    }

                                    if ($this->maxGain > 0) {
                                        $maxGainChange  = $this->currentShortPrice -  $this->maxGain;
                                        $maxGainPercent  = round(($maxGainChange/$this->maxGain)*100, 2);
                                    }
                                    else {
                                        $maxGainPercent  = 0;
                                    }

                                    $previousIndex = $transactionIndex - 1;

                                    //One Lot is $10,000

                                    $oneLotGainLoss = ($currencyResponse['rates'][$key]['amount']*(10000/$this->currentShortPrice) - 10000)*-1;

                                    $tradeAmount = $this->tenPercentMarginAmount*10;
                                    $tradeUnits = $tradeAmount/$this->currentShortPrice;
                                    $tradeDiff = $tradeUnits*$this->currentShortPrice - $tradeUnits*$currencyResponse['rates'][$key]['amount'];

                                    $this->tenPercentMarginAmount = $this->tenPercentMarginAmount + $tradeDiff;


                                    $this->transactions[$transactionIndex]['tenPercentMarginAmount'] = $this->tenPercentMarginAmount;

                                    //Transaction History
                                    $this->transactions[$transactionIndex]['transactionType'] = 'closeShort';
                                    $this->transactions[$transactionIndex]['transactionDate'] = $currency->currency_date;
                                    $this->transactions[$transactionIndex]['price'] = $currencyResponse['rates'][$key]['amount'];
                                    $this->transactions[$transactionIndex]['gainLoss'] = $gainLoss;
                                    $this->transactions[$transactionIndex]['maxGainPercent'] = $maxGainPercent;
                                    $this->transactions[$transactionIndex]['maxGainPeriod'] = $this->biggestGainPeriodSince;
                                    $this->transactions[$transactionIndex]['fiveDayChangePercent'] = $fivePeriodChangePercent;
                                    $this->transactions[$transactionIndex]['tenDayChangePercent'] = $tenPeriodChangePercent;
                                    $this->transactions[$transactionIndex]['gainLossPercent'] = $gainLossPercent;
                                    $this->transactions[$transactionIndex]['oneLotGainLoss'] = $oneLotGainLoss;
                                    $this->transactions[$transactionIndex]['rateIndex'] = $key;
                                    $transactionIndex++;

                                }
                                else {
                                    $currencyResponse['rates'][$key]['position'] = 'none';
                                    $currencyResponse['rates'][$key]['gainLoss'] = 0;
                                }



                                /********************************************************************************
                                 NEW Position Transaction Information For Analysis
                                 * If we enter a new open position, we want to record that
                                 ********************************************************************************/

                                if ($positionDecision['newPosition'] == "long") {

                                    $this->currentPositionStatus = 1;
                                    $this->currentLongPrice = $currencyResponse['rates'][$key]['amount'];
                                    $this->currentLongMax = $currencyResponse['rates'][$key]['amount'];
                                    $currencyResponse['rates'][$key]['newPosition'] = 'long';

                                    $this->periodsSincePosition = 0;
                                    $this->biggestGainPeriodSince = 0;

                                    //Transaction History
                                    $this->transactions[$transactionIndex]['transactionType'] = 'long';
                                    $this->transactions[$transactionIndex]['transactionDate'] = $currency->currency_date;
                                    $this->transactions[$transactionIndex]['price'] = $currencyResponse['rates'][$key]['amount'];
                                    $this->transactions[$transactionIndex]['gainLoss'] = 0;
                                    $this->transactions[$transactionIndex]['maxGainPercent'] = 0;
                                    $this->transactions[$transactionIndex]['maxGainPeriod'] = 0;
                                    $this->transactions[$transactionIndex]['fiveDayChangePercent'] = 0;
                                    $this->transactions[$transactionIndex]['tenDayChangePercent'] = 0;
                                    $this->transactions[$transactionIndex]['gainLossPercent'] = 0;
                                    $this->transactions[$transactionIndex]['oneLotGainLoss'] = 0;
                                    $this->transactions[$transactionIndex]['rateIndex'] = $key;
                                    $transactionIndex++;

                                }
                                else if ($positionDecision['newPosition'] == "short") {

                                    $this->currentPositionStatus = -1;
                                    $this->currentShortPrice = $currencyResponse['rates'][$key]['amount'];
                                    $this->currentShortMin = $currencyResponse['rates'][$key]['amount'];
                                    $currencyResponse['rates'][$key]['newPosition'] = 'short';

                                    $this->biggestGainPeriodSince = 0;
                                    $this->periodsSincePosition = 0;

                                    //Transaction History
                                    $this->transactions[$transactionIndex]['transactionType'] = 'short';
                                    $this->transactions[$transactionIndex]['transactionDate'] = $currency->currency_date;
                                    $this->transactions[$transactionIndex]['price'] = $currencyResponse['rates'][$key]['amount'];
                                    $this->transactions[$transactionIndex]['gainLoss'] = 0;
                                    $this->transactions[$transactionIndex]['maxGainPercent'] = 0;
                                    $this->transactions[$transactionIndex]['maxGainPeriod'] = 0;
                                    $this->transactions[$transactionIndex]['fiveDayChangePercent'] = 0;
                                    $this->transactions[$transactionIndex]['tenDayChangePercent'] = 0;
                                    $this->transactions[$transactionIndex]['gainLossPercent'] = 0;
                                    $this->transactions[$transactionIndex]['oneLotGainLoss'] = 0;
                                    $this->transactions[$transactionIndex]['rateIndex'] = $key;
                                    $transactionIndex++;
                                }

                                if ($currencyResponse['rates'][$key]['gainLoss'] != 0) {
                                    $this->accountValue = $this->accountValue = $this->accountValue + $this->accountValue*10*$currencyResponse['rates'][$key]['gainLoss'];
                                }
                                $currencyResponse['rates'][$key]['accountValue'] = number_format($this->accountValue);
                            }
                        }

                    }
                    else {
                        $currencyResponse['rates'][$key]['position'] = 'none';
                    }

                }
            }
            else {
                $currencyResponse['rates'][$key]['macd'] = 0;
                $currencyResponse['rates'][$key]['macdSignal'] = 0;
            }
            $key++;
        }

        $macdArray = array_map(create_function('$o', 'return $o["macd"];'), $currencyResponse['rates']);

        $currencyResponse['minRate'] = $minAmount;
        $currencyResponse['maxRate'] = $maxAmount;
        $currencyResponse['maxMacd'] = max($macdArray)*1.01;
        $currencyResponse['minMacd'] = min($macdArray)*.99;
        $currencyResponse['rateCount'] = $key;
        $currencyResponse['transactions'] = $this->transactions;
        $currencyResponse['tenMarginAmount'] = $this->tenPercentMarginAmount;
        $currencyResponse['rsiUpperCutoff'] = $this->rsiUpperCutoff;
        $currencyResponse['rsiLowerCutoff'] = $this->rsiLowerCutoff;

        //This Analyzes how well we did on our transactions/positions
        $currencyResponse['transactionStats'] = $this->getTransactionStats($this->transactions);
        return $currencyResponse;
    }

//At this point the longer is only used to confirm a trend
    public function runLonger($longerCurrencies) {
        $indicators = new \App\Services\CurrencyIndicators();
        $key = 0;

        foreach ($longerCurrencies as $currency) {
            $currencyResponse['rates'][$key]['index'] = $key;

            //Formatting a readable date value
            $currencyResponse['rates'][$key]['tradeDate'] = str_replace("00:00:00", "", $currency->currency_date);

            $currencyResponse['rates'][$key]['tradeUnixTime'] = strtotime($currency->currency_date);

            $currencyResponse['rates'][$key]['amount'] = $currency->amount;

            if ($key > 0) {
                $yesterdayKey = $key - 1;
            }
            //Short and Long SMA can be used in a decision.  The idea is if the shorter SMA crosses over the longer SMA a position should be entered
            if ($key > $this->outerPeriodLongEma-1) {
                $currencyResponse['rates'][$key]['shortMacdSma'] = $indicators->calculateSMA($this->outerPeriodShortEma, $currencyResponse['rates'], $key);
            }
            if ($key > $this->outerPeriodLongEma-1) {
                $currencyResponse['rates'][$key]['longMacdSma'] = $indicators->calculateSMA($this->outerPeriodLongEma, $currencyResponse['rates'], $key);
            }

            if ($key > $this->outerPeriodLongEma) {

                //Calculated Exponential Moving Average for MACD
                if (isset($currencyResponse['rates'][$yesterdayKey]['shortEma'])) {
                    $yesterdayShortEma = $currencyResponse['rates'][$yesterdayKey]['shortEma'];
                } else {
                    $yesterdayShortEma = $currencyResponse['rates'][$yesterdayKey]['shortMacdSma'];
                }

                if (isset($currencyResponse['rates'][$yesterdayKey]['longEma'])) {
                    $yesterdayLongEma = $currencyResponse['rates'][$yesterdayKey]['longEma'];
                } else {
                    $yesterdayLongEma = $currencyResponse['rates'][$yesterdayKey]['longMacdSma'];
                }

                $currencyResponse['rates'][$key]['shortEma'] = $indicators->calculateEMA($this->outerPeriodShortEma, $key, $currency->amount, $yesterdayShortEma);
                $currencyResponse['rates'][$key]['longEma'] = $indicators->calculateEMA($this->outerPeriodLongEma, $key, $currency->amount, $yesterdayLongEma);

                if ($currencyResponse['rates'][$key]['shortEma'] > $currencyResponse['rates'][$key]['longEma'] ) {
                    $currencyResponse['rates'][$key]['emaStatus'] = "above";
                }
                else {
                    $currencyResponse['rates'][$key]['emaStatus'] = "below";
                }


//                if ( $currencyResponse['rates'][$key]['longEma'] > 0) {
//                    $currencyResponse['rates'][$key]['macd'] = ($currencyResponse['rates'][$key]['shortEma'] - $currencyResponse['rates'][$key]['longEma']);
//
//                    if ($key > 100)
//                    {
//                        //MACD Signal Start ********** MACD Signal = EMA (Moving Average) of MACD
//                        //Signal is EMA of MACD
//                        $currencyResponse['rates'][$key]['macdSignalSma'] = $indicators->calculateMacdSMA($this->macdLongPeriod, $currencyResponse['rates'], $key);
//
//                        if (isset($currencyResponse['rates'][$yesterdayKey]['macdSignal'])) {
//                            $yesterdayMacd = $currencyResponse['rates'][$yesterdayKey]['macdSignal'];
//                        }
//                        elseif (isset($currencyResponse['rates'][$yesterdayKey]['macdSignalSma']))
//                        {
//                            $yesterdayMacd = $currencyResponse['rates'][$yesterdayKey]['macdSignalSma'];
//                        }
//
//                        if (isset($yesterdayMacd)) {
//                            $currencyResponse['rates'][$key]['macdSignal'] = $indicators->calculateEMA($this->macdIndicatorPeriod, $key,
//                                $currencyResponse['rates'][$key]['macd'], $yesterdayMacd);
//
//                            if ($currencyResponse['rates'][$key]['macdSignal'] > $currencyResponse['rates'][$key]['macd']) {
//                                $currencyResponse['rates'][$key]['macdStatus'] = "signalHigher";
//                            }
//                            else {
//                                $currencyResponse['rates'][$key]['macdStatus'] = "signalLower";
//                            }
//                        }
//
//
//                        //MACD Signal End
//                    }
//                }
            }
            $key++;
        }
        return $currencyResponse;
    }

    public function processPotentialVariables() {

        $macdShortPeriod = 3;
        $macdShortPeriodMax = 30;


        $macdLongPeriodMin = 8;
        $macdLongPeriodMax = 75;

        $macdIndicatorPeriodMin = 5;
        $macdIndicatorPeriodMax = 75;

        $rsiPeriodsMin = 20;
        $rsiPeriodsMax = 20;

        $rsiUpperCutoffMin = 80;
        $rsiUpperCutoffMax = 80;

        $rsiLowerCutoffMin = 20;
        $rsiLowerCutoffMax = 20;

        $bollingerPeriodsMin = 15;
        $bollingerPeriodsMax = 15;

        $smaShortPeriodMin = 12;
        $smaShortPeriodMax = 12;

        $smaLongPeriodMin = 30;
        $smaLongPeriodMax = 30;

        $rateOfChangePeriodMin = 20;
        $rateOfChangePeriodMax = 20;

        $lossCutoffMin = .008;
        $lossCutoffMax = .008;

        $strategyId = 1;

        $currencies = \App\Model\Currency::get();

        //MACD Short
        while ($macdShortPeriod <= $macdShortPeriodMax) {
            $this->macdShortPeriod = $macdShortPeriod;

            $macdLongPeriod = $macdLongPeriodMin;

            //MACD Long
            while ($macdLongPeriod <= $macdLongPeriodMax) {
                $this->macdLongPeriod = $macdLongPeriod;

                $macdIndicatorPeriod = $macdIndicatorPeriodMin;

                //MACD Indicator Period
                while ($macdIndicatorPeriod <= $macdIndicatorPeriodMax) {
                    $this->macdIndicatorPeriod = $macdIndicatorPeriod;

                    //Rest RSI Periods
                    $rsiPeriods = $rsiPeriodsMin;

                    //RSI Periods
                    while ($rsiPeriods <= $rsiPeriodsMax) {
                        $this->rsiPeriods = $rsiPeriods;

                        $rsiUpperCutoff = $rsiUpperCutoffMin;

                        //RSI Upper Cutoff
                        while ($rsiUpperCutoff <= $rsiUpperCutoffMax) {
                            $this->rsiUpperCutoff = $rsiUpperCutoff;

                            //reset RSI Lower
                            $rsiLowerCutoff = $rsiLowerCutoffMin;

                            //RSI Lower Cutoff
                            while ($rsiLowerCutoff <= $rsiLowerCutoffMax) {
                                $this->rsiLowerCutoff = $rsiLowerCutoff;

                                //rest Bollinger Cutoff
                                $bollingerPeriods = $bollingerPeriodsMin;

                                //Bollinger Periods
                                while ($bollingerPeriods <= $bollingerPeriodsMax) {
                                    $this->bollingerPeriods = $bollingerPeriods;

                                    $smaShortPeriod = $smaShortPeriodMin;

                                    //SMA Short Period
                                    while ($smaShortPeriod <= $smaShortPeriodMax) {
                                        $this->smaShortPeriod = $smaShortPeriod;


                                        $smaLongPeriod = $smaLongPeriodMin;

                                        //SMA Long Period
                                        while ($smaLongPeriod <= $smaLongPeriodMax) {
                                            $this->smaLongPeriod = $smaLongPeriod;

                                            //reset Rate of Change
                                            $rateOfChangePeriod = $rateOfChangePeriodMin;

                                            //Rate of Change Periods
                                            while ($rateOfChangePeriod <= $rateOfChangePeriodMax) {
                                                $this->rateOfChangePeriod = $rateOfChangePeriod;

                                                //Reset Lost Cutoff
                                                $lossCutoff = $lossCutoffMin;

                                                //Loss Cutoff
                                                while ($lossCutoff <= $lossCutoffMax) {
                                                    $this->lossCutoff = $lossCutoff;

                                                    $this->getRates($currencies);

                                                    $iteration = new \App\Model\CurrencyStrategyIteration();

                                                    $iteration->strategy_id = $strategyId;

                                                    $iteration->macd_long_period = $this->macdLongPeriod;
                                                    $iteration->macd_short_period = $this->macdShortPeriod;
                                                    $iteration->macd_indicator_period = $this->macdIndicatorPeriod;

                                                    $iteration->rsi_periods = $this->rsiPeriods;
                                                    $iteration->rsi_high_cutoff = $this->rsiUpperCutoff;
                                                    $iteration->rsi_low_cutoff = $this->rsiLowerCutoff;

                                                    $iteration->bollinger_periods = $this->bollingerPeriods;

                                                    $iteration->sma_long_period = $this->smaLongPeriod;
                                                    $iteration->sma_short_period = $this->smaShortPeriod;

                                                    $iteration->rate_of_change_period = $this->rateOfChangePeriod;

                                                    $iteration->loss_cut_off = $this->lossCutoff;

                                                    $iteration->save();

                                                    $gains = [];
                                                    $losses = [];
                                                    $closedPositions = [];

                                                    $maxGainPercents = [];
                                                    $maxGainPeriods = [];
                                                    $fiveDayChanges = [];
                                                    $tenDayChanges = [];

                                                    $fiveDayChangePositive = 0;
                                                    $fiveDayChangeNegative = 0;

                                                    $tenDayChangePositive = 0;
                                                    $tenDayChangeNegative = 0;

                                                    $tenKTrades = 0;
                                                    $longPosition = 0;
                                                    $shortPosition = 0;

                                                    $totalPositions = round(sizeof($this->transactions)/2);

                                                    foreach ($this->transactions as $transaction) {

                                                        if ($transaction['gainLoss'] < 0) {
                                                            $losses[] = $transaction['gainLoss'];
                                                        }

                                                        if ($transaction['gainLoss'] > 0) {
                                                            $gains[] = $transaction['gainLoss'];
                                                        }

                                                        if ($transaction['gainLoss'] > 0 || $transaction['gainLoss'] < 0) {
                                                            $closedPositions[] = $transaction['gainLoss'];
                                                        }

                                                        if ( $transaction['maxGainPercent'] != 0) {
                                                            $maxGainPercents[] = $transaction['maxGainPercent'];
                                                            $maxGainPeriods[] = $transaction['maxGainPeriod'];
                                                        }

                                                        if ( $transaction['fiveDayChangePercent'] != 0) {
                                                            $fiveDayChanges[] = $transaction['fiveDayChangePercent'];

                                                            if ($transaction['fiveDayChangePercent'] > 0) {
                                                                $fiveDayChangePositive++;
                                                            }
                                                            else {
                                                                $fiveDayChangeNegative++;
                                                            }
                                                        }

                                                        if ( $transaction['tenDayChangePercent'] != 0) {
                                                            $tenDayChanges[] = $transaction['tenDayChangePercent'];

                                                            if ($transaction['tenDayChangePercent'] > 0) {
                                                                $tenDayChangePositive++;
                                                            }
                                                            else {
                                                                $tenDayChangeNegative++;
                                                            }
                                                        }

                                                    }
                                                    $totalGains = sizeof($gains);
                                                    $totalLosses = sizeof($losses);

                                                    $iterationAnalysis = new \App\Model\CurrencyStrategyIterationAnalysis();

                                                    $iterationAnalysis->iteration_id = $iteration->id;

                                                    $iterationAnalysis->total_transactions = $totalPositions;

                                                    $iterationAnalysis->total_positive_transactions = $totalPositions;
                                                    $iterationAnalysis->total_transactions = $totalGains;
                                                    $iterationAnalysis->total_negative_transactions = $totalLosses;

                                                    $iterationAnalysis->net_transaction_count = $totalGains - $totalLosses;
                                                    $iterationAnalysis->net_percent = array_sum($gains) - array_sum($losses);

                                                    $iterationAnalysis->median_gain = $this->median($gains);
                                                    $iterationAnalysis->average_gain = $this->average($gains);

                                                    $iterationAnalysis->median_loss = $this->median($losses);
                                                    $iterationAnalysis->average_loss = $this->average($losses);

                                                    $iterationAnalysis->median_position = $this->median($closedPositions);
                                                    $iterationAnalysis->average_position = $this->average($closedPositions);

                                                    if (sizeof($closedPositions) > 0) {
                                                        $iterationAnalysis->max_gain = max($closedPositions);
                                                        $iterationAnalysis->max_loss = min($closedPositions);
                                                    }
                                                    else {
                                                        $iterationAnalysis->max_gain = 0;
                                                        $iterationAnalysis->max_loss = 0;
                                                    }

                                                    if (($totalGains + $totalLosses) > 0) {
                                                        $iterationAnalysis->probability_gain = ($totalGains/($totalGains + $totalLosses))*100;
                                                    }
                                                    else {
                                                        $iterationAnalysis->probability_gain = 0;
                                                    }


                                                    $iterationAnalysis->five_day_change_median = $this->median($fiveDayChanges);
                                                    $iterationAnalysis->five_day_change_average = $this->average($fiveDayChanges);

                                                    if (($fiveDayChangePositive + $fiveDayChangeNegative) > 0) {
                                                        $iterationAnalysis->five_day_change_probability = round(($fiveDayChangePositive/($fiveDayChangePositive + $fiveDayChangeNegative) *100));
                                                    }
                                                    else {
                                                        $iterationAnalysis->five_day_change_probability = 0;
                                                    }

                                                    $iterationAnalysis->ten_day_change_median = $this->median($tenDayChanges);
                                                    $iterationAnalysis->ten_day_change_average = $this->average($tenDayChanges);

                                                    if (($tenDayChangeNegative + $tenDayChangePositive) > 0) {
                                                        $iterationAnalysis->ten_day_change_probability = round(($tenDayChangePositive/($tenDayChangeNegative + $tenDayChangePositive) *100));
                                                    }
                                                    else {
                                                        $iterationAnalysis->ten_day_change_probability = 0;
                                                    }

                                                    $iterationAnalysis->max_gain_average = $this->average($maxGainPercents);
                                                    $iterationAnalysis->max_gain_median = $this->median($maxGainPercents);
                                                    $iterationAnalysis->max_gain_period_average = $this->average($maxGainPeriods);
                                                    $iterationAnalysis->max_gain_period_median = $this->median($maxGainPeriods);

                                                    $iterationAnalysis->save();

                                                    $lossCutoff = $lossCutoff + .001;
                                                }

                                                $rateOfChangePeriod = $rateOfChangePeriod + 1;
                                            }

                                            $smaLongPeriod = $smaLongPeriod + 1;
                                        }

                                        $smaShortPeriod = $smaShortPeriod + 1;
                                    }

                                    $bollingerPeriods = $bollingerPeriods + 1;
                                }

                                $rsiLowerCutoff = $rsiLowerCutoff + 1;
                            }

                            $rsiUpperCutoff = $rsiUpperCutoff + 1;
                        }

                        $rsiPeriods = $rsiPeriods + 1;
                    }

                    $macdIndicatorPeriod = $macdIndicatorPeriod + 1;
                }

                $macdLongPeriod = $macdLongPeriod + 1;
            }

            $macdShortPeriod = $macdShortPeriod + 1;
        }
    }


    public function analyzeMacdVariables() {

        $this->macdShortPeriod = 12;
        $this->macdLongPeriod = 26;

        $this->macdIndicatorPeriod = 5;

        $this->rsiPeriods = 14;
        $this->bollingerPeriods = 12;

        $this->smaLongPeriod = 8;
        $this->smaShortPeriod = 4  ;

        $this->rateOfChangePeriod = 3;

        $this->accountValue = 10000;

        $this->rocFactorPeriods = 100;

        $this->macdShortPeriod = 1;
        $this->macdLongPeriod = 26;

        $this->macdIndicatorPeriod = 5;

        $this->outerPeriodShortEma = 50;
        $this->outerPeriodLongEma = 100;

        $longCurrencies = \App\Model\Currency::where('currency_exchange', '=', $this->outerCurrencyExchange)->get();
        $this->longerIndicatorValues = $this->runLonger($longCurrencies);

        $currencies = \App\Model\Currency::where('currency_exchange', '=', $this->mainCurrencyExchange)->get();

        while ($this->macdShortPeriod < 45) {
            $this->macdLongPeriod = round($this->macdShortPeriod*2);
            $this->macdIndicatorPeriod = round($this->macdShortPeriod*.75);

            $processedData = $this->runStrategy($currencies);

            $this->saveIterationAttempt($processedData['transactionStats']);

            $this->macdLongPeriod = round($this->macdShortPeriod*2);
            $this->macdIndicatorPeriod = round($this->macdShortPeriod*.5);

            $processedData = $this->runStrategy($currencies);

            $this->saveIterationAttempt($processedData['transactionStats']);

            $this->macdLongPeriod = round($this->macdShortPeriod*3);
            $this->macdIndicatorPeriod = round($this->macdShortPeriod*.75);

            $processedData = $this->runStrategy($currencies);

            $this->saveIterationAttempt($processedData['transactionStats']);

            $this->macdShortPeriod++;
        }

    }

    public function saveIterationAttempt($transactionStats) {

        $iteration = new \App\Model\CurrencyMacdStrategyIteration();

        $iteration->strategy_id = $this->strategyId;

        $iteration->macd_long_period = $this->macdLongPeriod;
        $iteration->macd_short_period = $this->macdShortPeriod;
        $iteration->macd_indicator_period = $this->macdIndicatorPeriod;

        $iteration->rsi_periods = $this->rsiPeriods;
        $iteration->rsi_high_cutoff = $this->rsiUpperCutoff;
        $iteration->rsi_low_cutoff = $this->rsiLowerCutoff;

        $iteration->bollinger_periods = $this->bollingerPeriods;

        $iteration->sma_long_period = $this->smaLongPeriod;
        $iteration->sma_short_period = $this->smaShortPeriod;

        $iteration->rate_of_change_period = $this->rateOfChangePeriod;

        $iteration->loss_cut_off = $this->lossCutoff;

        $iteration->save();

        $iterationAnalysis = new \App\Model\CurrencyMacdStrategyIterationAnalysis();

        $iterationAnalysis->iteration_id = $iteration->id;

        $iterationAnalysis->total_transactions = $transactionStats['totalPositions'];

        $iterationAnalysis->total_positive_transactions = $transactionStats['totalGains'];
        $iterationAnalysis->total_transactions = $transactionStats['totalPositions'];
        $iterationAnalysis->total_negative_transactions = $transactionStats['totalLosses'];

        $iterationAnalysis->net_transaction_count = $transactionStats['netPositiveNegative'];
        $iterationAnalysis->net_percent = $transactionStats['netPercent'];

        $iterationAnalysis->median_gain = $transactionStats['medianGain'];
        $iterationAnalysis->average_gain = $transactionStats['averageGain'];

        $iterationAnalysis->median_loss = $transactionStats['medianLoss'];
        $iterationAnalysis->average_loss = $transactionStats['averageLoss'];

        $iterationAnalysis->median_position = $transactionStats['median_position'];
        $iterationAnalysis->average_position = $transactionStats['average_position'];

        $iterationAnalysis->max_gain = $transactionStats['max_gain'];
        $iterationAnalysis->max_loss = $transactionStats['max_loss'];

        $iterationAnalysis->probability_gain = $transactionStats['probabilityGain'];


        $iterationAnalysis->five_day_change_median = $transactionStats['fiveDayChangeMedian'];
        $iterationAnalysis->five_day_change_average = $transactionStats['fiveDayChangeAverage'];

        $iterationAnalysis->five_day_change_probability = $transactionStats['fiveDayChangeProbability'];



        $iterationAnalysis->ten_day_change_median = $transactionStats['tenDayChangeMedian'];
        $iterationAnalysis->ten_day_change_average = $transactionStats['tenDayChangeAverage'];

        $iterationAnalysis->ten_day_change_probability = $transactionStats['tenDayChangeProbability'];

        $iterationAnalysis->max_gain_average = $transactionStats['maxGainAverage'];
        $iterationAnalysis->max_gain_median = $transactionStats['maxGainMedian'];
        $iterationAnalysis->max_gain_period_average = $transactionStats['maxGainPeriodsAverage'];
        $iterationAnalysis->max_gain_period_median = $transactionStats['maxGainPeriodMedian'];

        $iterationAnalysis->save();

    }

    /***************************************
     Get Transaction Stats
     *
     * This is where we get status to evaluate how well we actually did on positions/transactions.
     *
     * As it loops through it performs several calculations on the currency that will be
     * used later to decide whether any position will be entered.  The calculations are often
     * done by passing variables to the CurrencyIndicators service/class.
     *
     * At the bottom of the loop, the indicator values are passed to a decision method located in the
     * CurrencyPositionDecisions service/class, and a decision is made as to whether to enter a new position.
     ***************************************/
    public function getTransactionStats($transactions) {

        $indicators = new \App\Services\CurrencyIndicators();

        $stats = [];

        $gains = [];
        $losses = [];
        $closedPositions = [];

        $maxGainPercents = [];
        $maxGainPeriods = [];
        $fiveDayChanges = [];
        $tenDayChanges = [];

        $lotLossesGains = [];

        $fiveDayChangePositive = 0;
        $fiveDayChangeNegative = 0;

        $tenDayChangePositive = 0;
        $tenDayChangeNegative = 0;

        $totalPositions = round(sizeof($transactions)/2);

        foreach ($transactions as $transaction) {

            if ($transaction['gainLoss'] < 0) {
                $losses[] = $transaction['gainLoss'];
            }

            if ($transaction['gainLoss'] > 0) {
                $gains[] = $transaction['gainLoss'];
            }

            if ($transaction['gainLoss'] > 0 || $transaction['gainLoss'] < 0) {
                $closedPositions[] = $transaction['gainLoss'];
            }

            if ( $transaction['maxGainPercent'] != 0) {
                $maxGainPercents[] = $transaction['maxGainPercent'];
                $maxGainPeriods[] = $transaction['maxGainPeriod'];
            }

            if ( $transaction['fiveDayChangePercent'] != 0) {
                $fiveDayChanges[] = $transaction['fiveDayChangePercent'];

                if ($transaction['fiveDayChangePercent'] > 0) {
                    $fiveDayChangePositive++;
                }
                else {
                    $fiveDayChangeNegative++;
                }
            }

            if ( $transaction['tenDayChangePercent'] != 0) {
                $tenDayChanges[] = $transaction['tenDayChangePercent'];

                if ($transaction['tenDayChangePercent'] > 0) {
                    $tenDayChangePositive++;
                }
                else {
                    $tenDayChangeNegative++;
                }
            }

            $lotLossesGains[] = $transaction['oneLotGainLoss'];
        }

        $stats['totalGains'] = sizeof($gains);
        $stats['totalLosses'] = sizeof($losses);

        $stats['totalPositions'] = $totalPositions;

        $stats['netPositiveNegative'] = $stats['totalGains'] - $stats['totalLosses'];
        $stats['netPercent'] = array_sum($gains) - array_sum($losses);

        $stats['medianGain'] = $indicators->median($gains);
        $stats['averageGain'] = $indicators->average($gains);

        $stats['medianLoss'] = $indicators->median($losses);
        $stats['averageLoss'] = $indicators->average($losses);

        $stats['median_position'] = $indicators->median($closedPositions);
        $stats['average_position'] = $indicators->average($closedPositions);

        $stats['netPercent'] = array_sum($gains) - array_sum($losses);

        $stats['netLotsGainLoss'] = array_sum($lotLossesGains);

        if (sizeof($closedPositions) > 0) {
            $stats['max_gain'] = max($closedPositions);
            $stats['max_loss'] = min($closedPositions);
        }
        else {
            $stats['max_gain'] = 0;
            $stats['max_loss'] = 0;
        }

        if (($stats['totalGains'] + $stats['totalLosses']) > 0) {
            $stats['probabilityGain'] = ($stats['totalGains']/($stats['totalGains'] + $stats['totalLosses']))*100;
        }
        else {
            $stats['probabilityGain'] = 0;
        }


        $stats['fiveDayChangeMedian'] = $indicators->median($fiveDayChanges);
        $stats['fiveDayChangeAverage'] = $indicators->average($fiveDayChanges);

        if (($fiveDayChangePositive + $fiveDayChangeNegative) > 0) {
            $stats['fiveDayChangeProbability'] = round(($fiveDayChangePositive/($fiveDayChangePositive + $fiveDayChangeNegative) *100));
        }
        else {
            $stats['fiveDayChangeProbability'] = 0;
        }

        $stats['tenDayChangeMedian'] = $indicators->median($tenDayChanges);
        $stats['tenDayChangeAverage'] = $indicators->average($tenDayChanges);

        if (($tenDayChangeNegative + $tenDayChangePositive) > 0) {
            $stats['tenDayChangeProbability'] = round(($tenDayChangePositive/($tenDayChangeNegative + $tenDayChangePositive) *100));
        }
        else {
            $stats['tenDayChangeProbability'] = 0;
        }

        $stats['maxGainAverage'] = $indicators->average($maxGainPercents);
        $stats['maxGainMedian'] = $indicators->median($maxGainPercents);
        $stats['maxGainPeriodsAverage'] = $indicators->average($maxGainPeriods);
        $stats['maxGainPeriodMedian']= $indicators->median($maxGainPeriods);

        return $stats;
    }
}
