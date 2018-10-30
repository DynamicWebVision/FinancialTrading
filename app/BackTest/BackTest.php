<?php namespace App\BackTest;

use App\Model\BackTestToBeProcessed;
use \DB;
use \App\Model\HistoricalRates;
use \App\Model\DebugLog;
use \App\Model\DecodeFrequency;
use \App\Services\Utility;
use \Log;
use Illuminate\Support\Facades\Config;

abstract class BackTest  {

    public $oanda;
    public $utility;
    public $passedOuter;

    public $strategyRunName;

    public $rateCount;
    public $slowRateCount;
    public $rateIndicatorMin;
    public $slowRateIndicatorMin = 0;

    public $rates;
    protected $shortMinimum;
    public $slowRates;
    public $twoTierRates = false;
    public $currentSlowRates;

    public $currentRates;

    protected $longCutOffDate;

    public $lastId;
    public $lastSlowId;

    public $currencyId;
    public $frequencyId;

    public $slowFrequencyId;

    public $exchange;

    public $backTestId;
    public $rateIndex;
    public $slowRateIndex;

    public $trailingStopRate;

    public $currentRatesProcessed;
    public $currentSlowRatesProcessed;

    public $strategy;

    public $currentPriceData;
    
    public $rateUnixStart;
    public $slowRateUnixStart;

    public $strategyId;

    public $keepLooping = true;

    public $stopLoss;
    public $takeProfit;
    public $trailingStop;

    public $currentPositionMaxRate;
    public $currentPositionMinRate;

    public $rateLevel = 'simple';
    public $slowRateLevel = 'simple';

    public $processId;
    public $forceEnd = false;

    public function __construct($testDesc) {
        Log::info('BACK TEST '.$testDesc.' START');
        $this->utility = new Utility();

        //Set it so there is no process timeout
        set_time_limit(0);
    }

    public function recordBackTestStart($processId = 0) {
        $backTestDB = new \App\Model\BackTest();

        $backTestDB->strategy_id = $this->strategyId;

        $backTestDB->frequency_id = $this->frequencyId;

        $backTestDB->exchange_id = $this->currencyId;

        $backTestDB->process_id = $processId;

        $backTestDB->start = 1;

        $backTestDB->save();

        $this->backTestId = $backTestDB->id;

        $this->processId = $processId;
    }

    public function startNewPeriod() {
        //Increase Indexes
        $this->rateIndex++;

        //Rotate Rates Check
        if (!isset($this->rates[$this->rateIndex])) {
            $this->getMoreOneTierRates();
        }

        if ($this->rates[$this->rateIndex]['rate_unix_time'] == 1540873800) {
            $debug = 1;
        }

        if ($this->twoTierRates) {
            if (!isset($this->slowRates[$this->slowRateIndex + 1])) {
                $this->getMoreSlowRates();
                $this->currentSlowRates = $this->getCurrentRates($this->slowRates, $this->slowRateIndicatorMin, $this->slowRateIndex, $this->slowRateLevel);
            }

            if ((int) $this->slowRates[$this->slowRateIndex + 1]['rate_unix_time'] <= (int) $this->rates[$this->rateIndex]['rate_unix_time'] || $this->currentSlowRates == null) {
                $this->slowRateIndex = $this->slowRateIndex + 1;
                $this->currentSlowRates = $this->getCurrentRates($this->slowRates, $this->slowRateIndicatorMin, $this->slowRateIndex, $this->slowRateLevel);
            }
        }

        //Simulate calling Oanda to check status of existing positions
        //$this->strategy->checkFullPositionInfo();

        //Get Current Short Rates
        $this->currentRates = $this->getCurrentRates($this->rates, $this->rateIndicatorMin, $this->rateIndex, $this->rateLevel);

        //Get Current Price Data in Format for Strategies
        $this->getCurrentPriceData();
        $this->strategy->currentPriceData = $this->currentPriceData;

        $this->strategy->rates = $this->currentRates;
        $this->strategy->slowRates = $this->currentSlowRates;

        $this->runInPositionTasks();
    }

    public function endPeriod() {
        //Check to see if a stop loss was hit
        $this->checkStopLoss();
        //Handle Trailing Stop
        $this->checkTrailingStop();
        $this->checkMarketIfTouched();
        //Check to see if take profit was hit
        $this->checkTakeProfit();
    }

    public function getLongPeriodStart() {
        return $this->longRates[$this->longRateMinimum-1]['rate_unix_time'];
    }

    public function getFirstrateIndex($longDateMinimum) {
        $longDateMinimum = date($longDateMinimum);
        foreach ($this->rates as $indx=>$rate) {
            $rateDate = date($rate['rate_date_time']);

            if ($longDateMinimum < $rateDate) {
                return $indx;
            }
        }
    }

    //The Last Historical Rates ID
    public function setLastId() {
        $this->lastId = DB::table('historical_rates')
            ->where('currency_id', '=', $this->currencyId)
            ->where('frequency_id', '=', $this->frequencyId)
            ->max('id');

        if ($this->twoTierRates) {
            $this->lastSlowId = DB::table('historical_rates')
                ->where('currency_id', '=', $this->currencyId)
                ->where('frequency_id', '=', $this->slowFrequencyId)
                ->max('id');
        }

        $frequencyClass = new DecodeFrequency();
        $frequency = DecodeFrequency::find($this->frequencyId);
        $this->strategy->backTestOpenPositionSecondCutoff = $frequencyClass->secondFrequencyCutoffs[$frequency->oanda_code];
    }

    //Has Test Written
    public function checkTakeProfit() {
        if (sizeof($this->strategy->backTestPositions) > 0 && ($this->strategy->backTestCurrentPosition == "long" || $this->strategy->backTestCurrentPosition == "short") && $this->strategy->takeProfitPipAmount > 0) {
            $openPositionIndex = sizeof($this->strategy->backTestPositions)-1;
            $openPosition = $this->strategy->backTestPositions[$openPositionIndex];

            if ($openPosition['positionType'] == 'short') {
                if ($openPosition['takeProfit'] >= $this->currentPriceData->low) {
                    $gainLoss = $openPosition['amount'] - $openPosition['takeProfit'];
                    $this->closeBackTestPosition($gainLoss, $openPositionIndex, 'Take Profit', $openPosition['takeProfit']);
                    $this->markCurrentPositionOpen();
                }
                else {
                    return false;
                }
            }
            else {
                //Handle Long
                if ($openPosition['takeProfit'] <= $this->currentPriceData->high) {
                    $gainLoss = $openPosition['takeProfit'] - $openPosition['amount'];
                    $this->closeBackTestPosition($gainLoss, $openPositionIndex, 'Take Profit', $openPosition['takeProfit']);
                    $this->markCurrentPositionOpen();
                }
                else {
                    return false;
                }
            }
        }
    }

    //Saves the Positions For a Record
    public function savePositions() {
            $debug = 1;
            if (isset($this->strategy->backTestPositions[0])) {
                $lastPosition = end($this->strategy->backTestPositions);

                \Log::emergency('Saving Positions for '.date("Y-m-d H:i:s", strtotime($lastPosition['dateTime'])));
            }

        foreach ($this->strategy->backTestPositions as $position) {
                try {
                    if (isset($position['closeDateTime'])) {
                        $newBackPosition = new \App\Model\BackTestPosition();

                        $newBackPosition->back_test_id = $this->backTestId;

                        $newBackPosition->open_date_date = date("Y-m-d H:i:s", strtotime($position['dateTime']));

                        $newBackPosition->open_date = $position['dateTime'];

                        $newBackPosition->close_date = $position['closeDateTime'];

                        $newBackPosition->close_date_date = date("Y-m-d H:i:s", strtotime($position['closeDateTime']));

                        if (isset($position['trailingStopAddDate'])) {
                            $newBackPosition->add_ts_date = $position['trailingStopAddDate'];
                            $newBackPosition->ts_rate = $position['trailingStopRate'];
                        }

                        //2 Pips per Spread (which is high)
                        $newBackPosition->gain_loss = $position['profitLoss'] - ($this->exchange->pip*2);

                        $newBackPosition->open_price = $position['amount'];
                        $newBackPosition->close_price = $position['closePrice'];

                        $newBackPosition->close_reason = $position['closeReason'];

                        $newBackPosition->highest_price = $position['highestRate'];
                        $newBackPosition->highest_price_date = date("Y-m-d H:i:s", strtotime($position['highestRateDate']));

                        $newBackPosition->lowest_price = $position['lowestRate'];
                        $newBackPosition->lowest_price_date = date("Y-m-d H:i:s", strtotime($position['lowestRateDate']));

                        if ($position['positionType'] == "long" ) {
                            $newBackPosition->position_type = 1;
                        }
                        else {
                            $newBackPosition->position_type = -1;
                        }

                        $newBackPosition->save();
                    }
                }
                catch (\Exception $e) {
                    $debugLog = new DebugLog();

                    $debugLog->debug_purpose = 'BT Save Position Exception';

                    $debugLog->debug_data = 'BT ID'.$this->backTestId."    ".json_encode($e).json_encode($position);

                    Log::emergency($this->processId.' - Save BT Transaction Error');
                    Log::emergency($this->processId.' - '.$e->getMessage());
                    Log::emergency($this->processId.' - '.PHP_EOL.json_encode($position));
                }
        }

        if (sizeof($this->strategy->backTestPositions) > 0) {
            $lastBackTestPosition = end($this->strategy->backTestPositions);

            $this->strategy->backTestPositions = [];

            if (!isset($lastBackTestPosition['closeDateTime'])) {
                $this->strategy->backTestPositions[] = $lastBackTestPosition;
            }
        }
    }

    //Gets the Current Rates to Run Through Strategy Iteration
    public function getCurrentRates($rates, $count, $index, $rateLevel) {
        $currentRates = array_slice($rates,$index - $count,$count);

        if ($rateLevel == 'simple') {
            return array_map(function($rate) {
                return (float) $rate['close_mid'];
            }, $currentRates);
        }
        elseif ($rateLevel == 'both') {
            $fullRates = array_map(function($rate) {

                $stdRate = new \StdClass();

                $stdRate->highMid = (float) $rate['high_mid'];
                $stdRate->closeMid = (float) $rate['close_mid'];
                $stdRate->lowMid = (float) $rate['low_mid'];
                $stdRate->openMid = (float) $rate['open_mid'];
                $stdRate->volume = (float) $rate['volume'];

                return $stdRate;
            }, $currentRates);

            $simpleRates = array_map(function($rate) {
                return (float) $rate['close_mid'];
            }, $currentRates);

            return [
                'full'=> $fullRates,
                'simple'=>$simpleRates
            ];
        }
        else {
            $fullRates = array_map(function($rate) {
                $stdRate = new \StdClass();

                $stdRate->highMid = (float) $rate['high_mid'];
                $stdRate->closeMid = (float) $rate['close_mid'];
                $stdRate->lowMid = (float) $rate['low_mid'];
                $stdRate->openMid = (float) $rate['open_mid'];
                $stdRate->volume = (float) $rate['volume'];

                return $stdRate;
            }, $currentRates);
            return $fullRates;
        }
    }

    //Get More Rates
    public function getMoreOneTierRates() {
        $skipAmount = $this->currentRatesProcessed - $this->rateIndicatorMin;

        Log::debug('Get More Rates'.$skipAmount);

        //Get the Next Section of Short ID's
        $this->rates = HistoricalRates::where('currency_id', '=', $this->currencyId)
            ->where('frequency_id', '=', $this->frequencyId)
            ->where('rate_unix_time', '>=', $this->rateUnixStart)
            ->skip($skipAmount)
            ->take($this->rateCount)
            ->orderBy('rate_dt')
            ->get()
            ->toArray();

        $minNecessaryRateCount = $this->rateIndicatorMin*2;

        if (sizeof($this->rates) <= $minNecessaryRateCount) {
            $skipAmount = $skipAmount - (($this->rateIndicatorMin*2) - sizeof($this->rates));

            $this->rates = HistoricalRates::where('currency_id', '=', $this->currencyId)
                ->where('frequency_id', '=', $this->frequencyId)
                ->where('rate_unix_time', '>=', $this->rateUnixStart)
                ->skip($skipAmount)
                ->take($this->rateCount)
                ->orderBy('rate_dt')
                ->get()
                ->toArray();
        }

        $this->currentRatesProcessed = $this->currentRatesProcessed + ($this->rateCount - $this->rateIndicatorMin);

        $this->rateIndex = $this->rateIndicatorMin + 1;

        //Save Backtest To Be Processed
        if ($this->processId > 0) {
            $backTestToBeProcessed = BackTestToBeProcessed::find($this->processId);

            $backTestToBeProcessed->in_process_unix_time = time();

            $backTestToBeProcessed->save();
        }

        if (env('APP_ENV') == 'local') {
            DB::table('tbd_debug_bt_rate_time')
                ->where('id', 1)
                ->update(['rate_time' => $this->currentPriceData->dateTime]);
        }

        $this->savePositions();
    }

    //Get More Rates
    public function getMoreSlowRates() {

        $skipAmount = $this->currentSlowRatesProcessed - $this->slowRateIndicatorMin - 1;

        $ratesStart = HistoricalRates::where('currency_id', '=', $this->currencyId)
            ->where('frequency_id', '=', $this->slowFrequencyId)
            ->where('rate_unix_time', '<', (int) $this->slowRates[$this->slowRateIndex]['rate_unix_time'])
            ->take($this->slowRateIndicatorMin)
            ->orderBy('rate_dt', 'desc')
            ->get(['rate_unix_time', 'rate_dt'])
            ->toArray();

        Log::debug('Get More Rates'.$skipAmount);

        //Get the Next Section of Short ID's
        $this->slowRates = HistoricalRates::where('currency_id', '=', $this->currencyId)
            ->where('frequency_id', '=', $this->slowFrequencyId)
            ->where('rate_unix_time', '>=', end($ratesStart)['rate_unix_time'])
            ->take($this->slowRateCount)
            ->orderBy('rate_dt')
            ->get()
            ->toArray();

        $this->slowRateIndex = $this->slowRateIndicatorMin;
    }

    //Creates Price Object in the Format that Oanda API will return for Strategy
    public function getCurrentPriceData() {
        $priceData = new \stdClass();
        $priceData->ask = $this->rates[$this->rateIndex]['open_mid'];
        $priceData->bid = $this->rates[$this->rateIndex]['open_mid'];
        $priceData->high = $this->rates[$this->rateIndex]['high_mid'];
        $priceData->open = $this->rates[$this->rateIndex]['open_mid'];
        $priceData->low = $this->rates[$this->rateIndex]['low_mid'];
        $priceData->id = $this->rates[$this->rateIndex]['id'];
        $priceData->dateTime = $this->rates[$this->rateIndex]['rate_date_time'];
        $priceData->rateUnixTime = $this->rates[$this->rateIndex]['rate_unix_time'];
        $priceData->instrument = $this->exchange->exchange;

        Config::set('bt_rate_time', $this->rates[$this->rateIndex]['rate_unix_time']);

        $this->currentPriceData = $priceData;
    }

    public function getClosedPositionProfitLoss($currentOpenPosition) {
        $currentPrice = $this->currentPriceData->open;
        if ($currentOpenPosition['positionType'] == 'short') {
            return $currentOpenPosition['amount'] - $currentPrice;
        }
        else {
            return $currentPrice - $currentOpenPosition['amount'];
        }
    }

    public function recordClosedPositionPossibleNewPositionOrNoOpen() {
        $openPositionIndex = sizeof($this->strategy->backTestPositions)-2;

        //This check determines if a new position was immediately opened within the strategy
        if (!isset($this->strategy->backTestPositions[$openPositionIndex]['closeDateTime']) && $openPositionIndex > -1) {
            $backTestPosition = $this->strategy->backTestPositions[$openPositionIndex];

            $profitLoss = $this->getClosedPositionProfitLoss($backTestPosition);
            $this->closeBackTestPosition($profitLoss, $openPositionIndex, 'New Position', $this->currentPriceData->open);

        }
        else {
            $openPositionIndex = sizeof($this->strategy->backTestPositions)-1;

            if (!isset($this->strategy->backTestPositions[$openPositionIndex]['closeDateTime']) && $openPositionIndex > -1) {
                $backTestPosition = $this->strategy->backTestPositions[$openPositionIndex];

                $profitLoss = $this->getClosedPositionProfitLoss($backTestPosition);
                $this->closeBackTestPosition($profitLoss, $openPositionIndex, 'Strategy Said to Close', $this->currentPriceData->open);
            }
        }
        $this->strategy->backTestClosedAllPositions = false;
    }

    public function closeBackTestPosition($profitLoss, $index, $closeReason, $closePrice) {
        $this->strategy->backTestPositions[$index]['profitLoss'] = $profitLoss;
        $this->strategy->backTestPositions[$index]['closeDateTime'] = $this->currentPriceData->dateTime;
        $this->strategy->backTestPositions[$index]['closePrice'] = $closePrice;
        $this->strategy->backTestPositions[$index]['closeReason'] = $closeReason;
    }

    public function closeDueToStrategyRules() {
        $openPositionIndex = $this->currentPositionIndex();
        $backTestPosition = $this->strategy->backTestPositions[$openPositionIndex];
        $profitLoss = $this->getClosedPositionProfitLoss($backTestPosition);

        $this->closeBackTestPosition($profitLoss, $openPositionIndex, 'Strategy Rules', $this->currentPriceData->open);
        $this->strategy->backTestCurrentPosition = null;
        $this->strategy->backTestClosedAllPositions = false;
    }

    public function markCurrentPositionOpen() {
        $this->strategy->backTestCurrentPosition = null;
        $this->strategy->backTestTrailingStop = false;
        $this->strategy->currentPosition['current_position'] = 0;

    }

    public function currentPositionIndex() {
        return sizeof($this->strategy->backTestPositions)-1;
    }

    public function processLongTrailingStop() {
        if ($this->currentPriceData->low <= $this->strategy->backTestTrailingStopRate) {
            //Hit Trailing Stop, CLose Position
            $openPositionIndex = sizeof($this->strategy->backTestPositions)-1;
            $backTestPosition = $this->strategy->backTestPositions[$openPositionIndex];

            $profitLoss = $this->strategy->backTestTrailingStopRate - $backTestPosition['amount'];
            $this->closeBackTestPosition($profitLoss, $openPositionIndex, 'Trailing Stop', $this->strategy->backTestTrailingStopRate);
            $this->markCurrentPositionOpen();
        }
        elseif ($this->currentPriceData->ask > ($this->strategy->backTestTrailingStopRate + ($this->strategy->optionalTrailingStopAmount*$this->exchange->pip))) {
            $this->strategy->backTestTrailingStopRate = $this->currentPriceData->high - ($this->strategy->optionalTrailingStopAmount*$this->exchange->pip);
        }
    }

    public function processShortTrailingStop() {
        if ($this->currentPriceData->high >= $this->strategy->backTestTrailingStopRate) {
            //Hit Trailing Stop, CLose Position
            $openPositionIndex = sizeof($this->strategy->backTestPositions)-1;
            $backTestPosition = $this->strategy->backTestPositions[$openPositionIndex];

            $profitLoss = $backTestPosition['amount'] - $this->strategy->backTestTrailingStopRate;
            $this->closeBackTestPosition($profitLoss, $openPositionIndex, 'Trailing Stop', $this->strategy->backTestTrailingStopRate);
            $this->markCurrentPositionOpen();
        }
        elseif ($this->currentPriceData->ask < ($this->strategy->backTestTrailingStopRate - ($this->strategy->optionalTrailingStopAmount*$this->exchange->pip))) {
            $this->strategy->backTestTrailingStopRate = $this->currentPriceData->low + ($this->strategy->optionalTrailingStopAmount*$this->exchange->pip);
        }
    }

    public function getInitialRates() {
        if ($this->twoTierRates) {

            $this->getInitialSlowRates();
            $this->slowRateIndex = $this->slowRateIndicatorMin-1;

            $slowUnixStart = $this->slowRates[$this->slowRateIndex]['rate_unix_time'];

            //Get Fast Rates
            $this->rates = HistoricalRates::where('currency_id', '=', $this->currencyId)
                ->where('frequency_id', '=', $this->frequencyId)
                ->where('rate_unix_time', '<=', $slowUnixStart)
                ->take($this->rateIndicatorMin)
                ->orderBy('rate_dt', 'desc')
                ->get()
                ->toArray();

            $this->rateUnixStart = end($this->rates)['rate_unix_time'];

            //Get Fast Rates
            $this->rates = HistoricalRates::where('currency_id', '=', $this->currencyId)
                ->where('frequency_id', '=', $this->frequencyId)
                ->where('rate_unix_time', '>=', $this->rateUnixStart)
                ->take($this->rateCount)
                ->orderBy('rate_dt')
                ->get()
                ->toArray();

            $this->rateIndex = $this->utility->findArrayIndexWithElementAttribute($this->rates, 'rate_unix_time', $this->slowRates[$this->slowRateIndex]['rate_unix_time']);

            $this->currentRatesProcessed = $this->rateCount;
        }
        else {
            $this->rateIndex = $this->rateIndicatorMin-1;

            //Get Fast Rates
            $this->rates = HistoricalRates::where('currency_id', '=', $this->currencyId)
                ->where('frequency_id', '=', $this->frequencyId)
                ->where('rate_unix_time', '>=', $this->rateUnixStart)
                ->take($this->rateCount)
                ->orderBy('rate_dt')
                ->get()
                ->toArray();

            if (!isset($this->rates[$this->rateIndex])) {
                $this->forceEnd = true;
                $this->rateIndex = 10;
            }

            $this->currentRatesProcessed = $this->rateCount;
        }
    }

    public function getInitialSlowRates() {
        //Get Short Rates
        $this->slowRates = HistoricalRates::where('currency_id', '=', $this->currencyId)
            ->where('frequency_id', '=', $this->slowFrequencyId)
            ->where('rate_unix_time', '>=', $this->rateUnixStart)
            ->take($this->slowRateCount)
            ->orderBy('rate_dt')
            ->get()
            ->toArray();
    }

    public function checkStopLoss() {
        if (($this->strategy->backTestCurrentPosition == "long" || $this->strategy->backTestCurrentPosition == "short") && !$this->strategy->backTestTrailingStop) {

            $openPositionIndex = sizeof($this->strategy->backTestPositions)-1;
            $backTestPosition = $this->strategy->backTestPositions[$openPositionIndex];

            if ($this->strategy->backTestCurrentPosition == 'long') {
                if ($this->currentPriceData->low <= $this->strategy->backTestPositions[$openPositionIndex]['stopLoss']) {
                    //Close Position
                    $gainLoss = $this->strategy->backTestPositions[$openPositionIndex]['stopLoss'] - $backTestPosition['amount'];
                    $this->closeBackTestPosition($gainLoss, $openPositionIndex, 'Stop Loss', $this->strategy->backTestPositions[$openPositionIndex]['stopLoss']);
                    $this->markCurrentPositionOpen();
                }
            }
            else {
                if ($this->currentPriceData->high >= $this->strategy->backTestPositions[$openPositionIndex]['stopLoss']) {
                    //Close Position
                    $gainLoss = $backTestPosition['amount'] - $this->strategy->backTestPositions[$openPositionIndex]['stopLoss'];
                    $this->closeBackTestPosition($gainLoss, $openPositionIndex, 'Stop Loss', $this->strategy->backTestPositions[$openPositionIndex]['stopLoss']);
                    $this->markCurrentPositionOpen();
                }
            }
        }
    }

    public function runInPositionTasks() {
        if ($this->strategy->backTestCurrentPosition == "long" || $this->strategy->backTestCurrentPosition == "short") {
            $this->recordMaxRateMinRate();
        }
    }

    public function recordMaxRateMinRate() {
        $currentIndex = $this->currentPositionIndex();

        if ($this->currentPriceData->ask > $this->strategy->backTestPositions[$currentIndex]['highestRate']) {
            $this->strategy->backTestPositions[$currentIndex]['highestRate'] = $this->currentPriceData->ask;
            $this->strategy->backTestPositions[$currentIndex]['highestRateDate'] = $this->currentPriceData->dateTime;
        }
        elseif ($this->currentPriceData->ask < $this->strategy->backTestPositions[$currentIndex]['lowestRate']) {
            $this->strategy->backTestPositions[$currentIndex]['lowestRate'] = $this->currentPriceData->ask;
            $this->strategy->backTestPositions[$currentIndex]['lowestRateDate'] = $this->currentPriceData->dateTime;
        }
    }

    public function lastIdCheck() {
        if ($this->rates[$this->rateIndex]['id'] == $this->lastId || $this->forceEnd) {
            return true;
        }
        elseif ($this->twoTierRates) {
            if ($this->slowRates[$this->slowRateIndex]['id'] == $this->lastSlowId) {
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

    public function checkTrailingStop() {
        if (($this->strategy->backTestCurrentPosition == "long" || $this->strategy->backTestCurrentPosition == "short") && $this->strategy->backTestTrailingStop) {
                if ($this->strategy->backTestCurrentPosition == "long") {
                    $this->processLongTrailingStop();
                }
                else {
                    $this->processShortTrailingStop();
                }
            }
    }

    public function endBackTest() {
        \Log::emergency('We Got TO End Back Test endBackTest');
        $this->savePositions();

        $backTest = \App\Model\BackTest::find($this->backTestId);

        $backTest->finish = 1;

        $backTest->save();
    }

    public function checkMarketIfTouched() {
        if ($this->strategy->backTestUnfilledMarketIfTouchedOrder && $this->currentPriceData->rateUnixTime < $this->strategy->backTestUnfilledMarketIfTouchedExpiration) {
            $possiblePosition = $this->strategy->backTestUnfilledMarketIfTouchedOrderPosition;
            if ($possiblePosition['positionType'] == 'long') {
                if ($this->strategy->backTestUnfilledMarketIfTouchedOrderPriceTarget <= $this->currentPriceData->high) {
                    $this->strategy->backTestUnfilledMarketIfTouchedOrder = false;
                    $this->strategy->backTestPositions[] = $possiblePosition;

                    $this->strategy->backTestCurrentPosition = "long";
                    $this->strategy->currentPosition['current_position'] = 1;

                    $this->strategy->fullPositionInfo['open'] = true;
                    $this->strategy->fullPositionInfo['side'] = 'buy';

                    if ($this->strategy->trailingStopNewPosition) {
                        $this->strategy->backTestTrailingStop = true;

                        $newPosition['trailingStopAddDate'] = $this->currentPriceData->dateTime;
                        $newPosition['trailingStopRate'] = $this->currentPriceData->ask;

                        $this->strategy->backTestTrailingStopRate = $this->currentPriceData->open - ($this->strategy->trailingStopPipAmount*$this->exchange->pip);
                    }
                }
            }
            elseif ($possiblePosition['positionType'] == 'short') {
                if ($this->strategy->backTestUnfilledMarketIfTouchedOrderPriceTarget >= $this->currentPriceData->low) {
                    $this->strategy->backTestUnfilledMarketIfTouchedOrder = false;
                    $this->strategy->backTestPositions[] = $possiblePosition;

                    $this->strategy->backTestCurrentPosition = "short";
                    $this->strategy->currentPosition['current_position'] = -1;

                    $this->strategy->fullPositionInfo['open'] = true;
                    $this->strategy->fullPositionInfo['side'] = 'sell';

                    if ($this->strategy->trailingStopNewPosition) {
                        $this->strategy->backTestTrailingStop = true;

                        $newPosition['trailingStopAddDate'] = $this->currentPriceData->dateTime;
                        $newPosition['trailingStopRate'] = $this->currentPriceData->ask;

                        $this->strategy->backTestTrailingStopRate = $this->currentPriceData->open + ($this->strategy->trailingStopPipAmount*$this->exchange->pip);

                    }
                }
            }
        }
    }

    public function getLastPosition() {
        return end($this->strategy->backTestPositions);
    }
}