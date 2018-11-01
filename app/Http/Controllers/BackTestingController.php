<?php namespace App\Http\Controllers;

use App\BackTest\BackTest;
use \App\BackTest\CowabungaBackTest;
use App\Services\Utility;
use View;
use \App\Model\BackTestPosition;
use Illuminate\Support\Facades\DB;
use \App\BackTest\StopLossWithTrailingStopTest;
use \App\BackTest\EmaMomentumTPSLTest;
use App\Model\BackTestToBeProcessed;
use App\Model\Exchange;
use App\Model\DecodeFrequency;
use App\Model\BackTestStats;
use App\Model\BackTest as BackTestModel;
use Request;
use App\Model\BackTestGroup;
use App\Services\CurrencyIndicators;
use App\Services\TransactionAmountHelpers;
use App\Model\Servers;

use \App\BackTest\IndicatorRunThroughTest;
use \App\Strategy\HullMovingAverage\HmaIndicatorRunThrough;
use Illuminate\Support\Facades\Config;


class BackTestingController extends Controller {

    public function __construct() {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
    }

    public function index() {
        return view('back_test');
    }

    public function backTestGroups() {
        $backTestGroups = DB::select("select btg.*, s.name as 'strategy_name', ss.name as 'strategy_system_name', ss.method as 'strategy_method', 
                            exchange.exchange as 'exchange_name', f1.frequency as 'frequency_name', f1.oanda_code as 'frequency_code', f2.frequency as 'slow_frequency_name'
                            from back_test_group btg
                            join exchange on btg.exchange_id = exchange.id
                            join decode_frequency f1 on btg.frequency_id = f1.id
                            left join decode_frequency f2 on btg.slow_frequency_id = f1.id
                            join strategy s on btg.strategy_id = s.id
                            join strategy_system ss on btg.strategy_system_id = ss.id
                            ;");

        return $backTestGroups;
    }

    public function indicatorTest() {
        $indicatorRunThroughTest = new IndicatorRunThroughTest('test');

        $indicatorRunThroughTest->strategyRunName = 'Indicator Run Through';
        $indicatorRunThroughTest->strategyId = 6;

        //All Back Tests
        $indicatorRunThroughTest->currencyId = 1;
        $fullExchange = Exchange::find(1);

        $indicatorRunThroughTest->exchange = $fullExchange;
        $indicatorRunThroughTest->frequencyId = 2;

     //   $indicatorRunThroughTest->recordBackTestStart(12);

        //Starting Unix Time to Run Strategy
        $indicatorRunThroughTest->rateUnixStart = 1492044382;

        /******************************
         * SET STRATEGY
         ******************************/
        //Set Strategy
        $strategy = new HmaIndicatorRunThrough(7827172, 'BackTestABC', true);

        $strategy->strategyId = 5;
        $strategy->strategyDesc = 'fiftyOneHundred';
        $strategy->positionMultiplier = 5;

        //Unique Strategy Variables
        $strategy->slowEmaLength = 10;
        $strategy->fastEmaLength = 9;

        //ALL
        $strategy->exchange = $fullExchange;
        $strategy->maxPositions = 5;

        $indicatorRunThroughTest->strategy = $strategy;

        //Values for Getting Rates
        $indicatorRunThroughTest->rateCount = 250*10;
        $indicatorRunThroughTest->rateIndicatorMin = 250*3;
        $indicatorRunThroughTest->currentRatesProcessed = $indicatorRunThroughTest->rateCount;

        $indicatorRunThroughTest->run();
    }

    public function highLowAnalysis($backTestId) {
        $backTestPositions = BackTestPosition::where('back_test_id', '=', $backTestId)->get()->toArray();
        $exchangePip = .0001;

        foreach ($backTestPositions as $index=>$position) {
            if ($position['position_type'] == 1) {
                $backTestPositions[$index]['highPips'] = ($position['highest_price']/$exchangePip) - ($position['open_price']/$exchangePip);
                $backTestPositions[$index]['lowPips'] = ($position['open_price']/$exchangePip) - ($position['lowest_price']/$exchangePip);
            }
            else {
                $backTestPositions[$index]['lowPips'] = ($position['highest_price']/$exchangePip) - ($position['open_price']/$exchangePip);
                $backTestPositions[$index]['highPips'] = ($position['open_price']/$exchangePip) - ($position['lowest_price']/$exchangePip);
            }

            if ($position['highest_price_date'] < $position['lowest_price_date']) {
                $backTestPositions[$index]['highLowFirst'] = 'highFirst';
            }
            elseif ((strtotime($position['lowest_price_date']) - strtotime($position['open_date_date'])) < 360) {
                $backTestPositions[$index]['highLowFirst'] = 'lowEarly';
            }
            else {
                $backTestPositions[$index]['highLowFirst'] = 'lowFirst';
            }
        }
        return $backTestPositions;
    }

    public function getBackTests() {

        $backTestGroups = $this->backTestGroups();

        return ['back_test_groups'=>$backTestGroups];
    }

    public function getBackTestsByGroupId($groupId) {
        $utility = new Utility();
        $back_tests = DB::select('select strategy.name as strategy_name, strategy.description  as strategy_desc, decode_frequency.frequency,
                exchange.exchange, exchange.pip, back_test.created_at , back_test.id , back_test_to_be_processed.take_profit_pips, exchange.pip, back_test_to_be_processed.id as processed_id , 
                    back_test_to_be_processed.stop_loss_pips, 
                    back_test_to_be_processed.trailing_stop_pips, 
                    back_test_to_be_processed.variable_1, 
                    back_test_to_be_processed.variable_2, 
                    back_test_to_be_processed.variable_3, 
                    back_test_to_be_processed.variable_4, 
                    back_test_to_be_processed.variable_5, 
                    back_test_to_be_processed.exchange_id, 
                    back_test_to_be_processed.frequency_id, 
                    back_test_group.variable_1_desc, 
                    back_test_group.variable_2_desc, 
                    back_test_group.variable_3_desc, 
                    back_test_group.variable_4_desc, 
                    back_test_group.variable_5_desc, 
                    back_test_group.name, 
                    back_test_group.description,
                    back_test_stats.total_gain_loss_pips, 
                    back_test_stats.total_gain_transactions, 
                    back_test_stats.total_loss_transactions, 
                    back_test_stats.positive_months, 
                    back_test_stats.negative_months, 
                    back_test_stats.median_gain, 
                    back_test_stats.median_loss, 
                    back_test_stats.mean_gain, 
                    back_test_stats.mean_loss, 
                    back_test_stats.median_month_pip_gl, 
                    back_test_stats.mean_month_pip_gl, 
                    back_test_stats.median_month_gains, 
                    back_test_stats.mean_month_gains, 
                    back_test_stats.median_month_losses, 
                    back_test_stats.mean_month_losses,
                    back_test_stats.median_max_gain,
                    back_test_stats.median_max_loss,
                    back_test_stats.total_kelly_criterion_gain_loss,
                    back_test_stats.month_gain_loss_probability_sd,
                    back_test_stats.total_kelly_by_month,
                    back_test_stats.percent_to_risk,
                    back_test_stats.position_amount_ten_k,
                    back_test_stats.expected_gain_loss,
                    back_test_stats.mean_max_gain,
                    back_test_stats.mean_max_loss
                from back_test join strategy on back_test.strategy_id = strategy.id
                join decode_frequency on back_test.frequency_id = decode_frequency.id
                join back_test_stats on back_test.id = back_test_stats.back_test_id 
                join exchange on back_test.exchange_id = exchange.id
                left join back_test_to_be_processed on back_test.process_id = back_test_to_be_processed.id
                left join back_test_group on back_test_to_be_processed.back_test_group_id = back_test_group.id 
                where back_test.finish = 1 and back_test_group.id = ?
                order by processed_id, strategy.id, decode_frequency.id, exchange.id
                ;', [$groupId]);

        $variable_1_values = BackTestToBeProcessed::where('back_test_group_id', '=', $groupId)->distinct()->get(['variable_1'])->toArray();
        $variable_1_values = $utility->arrayAttributeToSimpleArray($variable_1_values, 'variable_1');

        $variable_2_values = BackTestToBeProcessed::where('back_test_group_id', '=', $groupId)->distinct()->get(['variable_2'])->toArray();
        $variable_2_values = $utility->arrayAttributeToSimpleArray($variable_2_values, 'variable_2');

        $variable_3_values = BackTestToBeProcessed::where('back_test_group_id', '=', $groupId)->distinct()->get(['variable_3'])->toArray();
        $variable_3_values = $utility->arrayAttributeToSimpleArray($variable_3_values, 'variable_3');

        $variable_4_values = BackTestToBeProcessed::where('back_test_group_id', '=', $groupId)->distinct()->get(['variable_4'])->toArray();
        $variable_4_values = $utility->arrayAttributeToSimpleArray($variable_4_values, 'variable_4');

        $variable_5_values = BackTestToBeProcessed::where('back_test_group_id', '=', $groupId)->distinct()->get(['variable_5'])->toArray();
        $variable_5_values = $utility->arrayAttributeToSimpleArray($variable_5_values, 'variable_5');

        $take_profits = BackTestToBeProcessed::where('back_test_group_id', '=', $groupId)->distinct()->get(['take_profit_pips'])->toArray();
        $take_profits = $utility->arrayAttributeToSimpleArray($take_profits, 'take_profit_pips');

        $stop_losses = BackTestToBeProcessed::where('back_test_group_id', '=', $groupId)->distinct()->get(['stop_loss_pips'])->toArray();
        $stop_losses = $utility->arrayAttributeToSimpleArray($stop_losses, 'stop_loss_pips');

        $trailing_stops = BackTestToBeProcessed::where('back_test_group_id', '=', $groupId)->distinct()->get(['trailing_stop_pips'])->toArray();
        $trailing_stops = $utility->arrayAttributeToSimpleArray($trailing_stops, 'trailing_stop_pips');

        return ['back_tests'=>$back_tests, 'variable_1_values'=>$variable_1_values, 'variable_2_values'=>$variable_2_values,
            'variable_3_values'=>$variable_3_values, 'variable_4_values'=>$variable_4_values, 'variable_5_values'=>$variable_5_values,
                'take_profits'=>$take_profits,'stop_losses'=>$stop_losses,'trailing_stops'=>$trailing_stops];
    }



    public function setBackTest($backTestId) {
        session(['current_back_test_id'=> $backTestId]);
        return 1;
    }

    public function backtestProcessStatsSpecificProcess($id) {
        $this->backtestProcessStats($id);
    }

    public function backtestProcessStats($processId = false) {

        if ($processId) {
            $processedBackTest = BackTestToBeProcessed::where('id', '=', $processId)->first();
        }
        else {
            $server = Servers::find(env('SERVER_ID'));

            $statCount = BackTestToBeProcessed::where('stats_finish', '=', 0)->where('stats_start', '=', 0)
                ->where('hung_up', '=', 0)
                ->where('finish', '=', 1)->where('start', '=', 1)->where('back_test_group_id', '=', $server->current_back_test_group_id)->count();

            if ($statCount > 0) {
                $processedBackTest = BackTestToBeProcessed::where('stats_finish', '=', 0)->where('stats_start', '=', 0)->where('hung_up', '=', 0)
                    ->where('finish', '=', 1)->where('start', '=', 1)->where('back_test_group_id', '=', $server->current_back_test_group_id)
                                        ->orderBy('back_test_group_id', 'desc')->first();
            }
            else {
                $processedBackTest = BackTestToBeProcessed::where('stats_finish', '=', 0)->where('stats_start', '=', 0)->where('hung_up', '=', 0)
                    ->where('finish', '=', 1)->where('start', '=', 1)->orderBy('back_test_group_id', 'desc')->first();
            }
        }

        $processedBackTest->stats_start = 1;

        $processedBackTest->save();

        $processedBackTest = $processedBackTest->toArray();

        Config::set('back_test_process_id', $processedBackTest['id']);
        Config::set('back_test_job', 'stats');

        try {
            $backTest = \App\Model\BackTest::where('process_id', '=', $processedBackTest['id'])->first()->toArray();
        }
        catch (\Exception $e) {
            Log::emergency($e);
            Log::emergency(json_encode($processedBackTest));
        }

        $exchange = Exchange::find($processedBackTest['exchange_id'])->toArray();
        $exchangePips = $exchange['pip'];

        $indicators = new CurrencyIndicators();

        $backTestId = $backTest['id'];

        //Gain Count
        $gainCount = DB::select("select count(*) as gain_count, AVG(gain_loss) as mean_gain from back_test_positions where back_test_id = ? and gain_loss > 0", [$backTestId]);

        //Loss Count
        $lossCount = DB::select("select count(*) as loss_count, AVG(gain_loss) as mean_loss from back_test_positions where back_test_id = ? and gain_loss < 0", [$backTestId]);

        //Total Gain Loss Count
        $gainLossOffset = DB::select("select sum(gain_loss) as gain_count from back_test_positions where back_test_id = ?", [$backTestId]);

        $backTestPositionGains = BackTestPosition::where('gain_loss', '>', 0)->where('back_test_id', '=', $backTestId)->get()->toArray();
        $backTestPositionGains = array_column($backTestPositionGains, 'gain_loss');

        $backTestPositionLosses = BackTestPosition::where('gain_loss', '<', 0)->where('back_test_id', '=', $backTestId)->get()->toArray();
        $backTestPositionLosses = array_column($backTestPositionLosses, 'gain_loss');

        $positiveMonthCount = 0;
        $negativeMonthCount = 0;

        $positiveMonths = [];
        $negativeMonths = [];

        $allMonths = [];

        $transactionHelpers = new TransactionAmountHelpers();

        if ($processedBackTest['take_profit_pips'] < 1) {
            $processedBackTest['take_profit_pips'] = $indicators->median($backTestPositionGains)/$exchangePips;
        }


        $monthPositiveNegative = DB::select("SELECT  DATE_FORMAT(open_date_date, '%m/%Y') as transaction_month,
                                                SUM(if(gain_loss > 0, 1, 0)) AS positive_count,
                                                SUM(if(gain_loss < 0, 1, 0)) AS negative_count
                                        FROM    back_test_positions
                                        where back_test_id = ? 
                                        GROUP   BY transaction_month;", [$backTestId]);

        $gainProbabilities = [];


        foreach ($monthPositiveNegative as $index=>$month) {
            $gainProbabilities[] = $month->positive_count/($month->positive_count + $month->negative_count);
        }

        $gainProbabilityStandardDeviation = $indicators->standardDeviation($gainProbabilities);

        $gainProbability = $gainCount[0]->gain_count/($gainCount[0]->gain_count + $lossCount[0]->loss_count);

        $percentageToRisk = $transactionHelpers->kellyCriterion($processedBackTest['take_profit_pips'], $processedBackTest['stop_loss_pips'] + 2, $gainProbability);

        if ($percentageToRisk < 0) {

            $positionAmount = 0;

            $expectedGanLoss = 0;

            $totalGainLoss = 0;

            $totalGainLossByMonth = 0;
        }
        else {
            $pip = .0001;
            $currentPrice = 1.25;
            $riskAmount = round($percentageToRisk*10000);

            $positionAmount = $transactionHelpers->calculatePositionAmount($currentPrice, $pip, $processedBackTest['stop_loss_pips'], $riskAmount);

            $potentialGain = ($positionAmount*($currentPrice + ($pip * $processedBackTest['take_profit_pips']))) - ($positionAmount*$currentPrice);
            $potentialLoss = ($positionAmount*($currentPrice - ($pip * $processedBackTest['stop_loss_pips']))) - ($positionAmount*$currentPrice);

            $expectedGanLoss = ($potentialGain*$gainProbability) + ($potentialLoss*(1-$gainProbability));

            $totalGainLoss = round(($gainCount[0]->gain_count + $lossCount[0]->loss_count)*$expectedGanLoss);

            $totalGainLossByMonth = round($totalGainLoss/sizeof($monthPositiveNegative));
        }

        $backTestPositions = BackTestPosition::where('position_type', '=', 1)->where('back_test_id', '=', $backTestId)->get()->toArray();

        $maxGains = [];
        $maxLosses = [];

        foreach ($backTestPositions as $position) {
            if ($position['position_type'] == 1) {
                $maxGains[] = ($position['highest_price']/$exchangePips) - ($position['open_price']/$exchangePips);
                $maxLosses[] = ($position['open_price']/$exchangePips) - ($position['lowest_price']/$exchangePips);
            }
            else {
                $maxLosses[] = ($position['highest_price']/$exchangePips) - ($position['open_price']/$exchangePips);
                $maxGains[] = ($position['open_price']/$exchangePips) - ($position['lowest_price']/$exchangePips);
            }
        }

        $monthData = DB::select("select distinct DATE_FORMAT(open_date_date, '%m/%Y') as transaction_month, sum(gain_loss) as sum_gain_loss  
                              from back_test_positions where back_test_id = ? group by transaction_month order by transaction_month;", [$backTestId]);

        foreach ($monthData as $index=>$month) {
            if ($month->sum_gain_loss > 0) {
                $positiveMonthCount++;
                $positiveMonths[] = ($month->sum_gain_loss/$exchange['pip']);
            }
            else {
                $negativeMonthCount++;
                $negativeMonths[] = ($month->sum_gain_loss/$exchange['pip']);
            }
            $allMonths[] = ($month->sum_gain_loss/$exchange['pip']);
        }

        $newBackTestStats = new BackTestStats();

        $newBackTestStats->back_test_id =  $backTestId;

        $newBackTestStats->total_gain_loss_pips =  round($gainLossOffset[0]->gain_count/$exchange['pip']);

        $newBackTestStats->total_gain_transactions =  $gainCount[0]->gain_count;

        $newBackTestStats->total_loss_transactions =  $lossCount[0]->loss_count;

        $newBackTestStats->positive_months =  $positiveMonthCount;

        $newBackTestStats->negative_months =  $negativeMonthCount;

        $newBackTestStats->median_gain =  round($indicators->median($backTestPositionGains));
        $newBackTestStats->median_loss =  round($indicators->median($backTestPositionLosses));

        $newBackTestStats->mean_gain =  round($indicators->average($backTestPositionGains));
        $newBackTestStats->mean_loss =  round($indicators->average($backTestPositionLosses));

        $newBackTestStats->median_month_pip_gl =  round($indicators->median($allMonths));
        $newBackTestStats->mean_month_pip_gl =  round($indicators->average($allMonths));

        $newBackTestStats->median_month_gains =  round($indicators->median($positiveMonths));
        $newBackTestStats->mean_month_gains =  round($indicators->average($positiveMonths));

        $newBackTestStats->median_month_losses =  round($indicators->median($negativeMonths));
        $newBackTestStats->mean_month_losses =  round($indicators->median($negativeMonths));

        $newBackTestStats->median_max_gain =  round($indicators->median($maxGains));
        $newBackTestStats->mean_max_gain =  round($indicators->average($maxGains));

        $newBackTestStats->median_max_loss =  round($indicators->median($maxLosses));
        $newBackTestStats->mean_max_loss =  round($indicators->median($maxLosses));

        $newBackTestStats->total_kelly_criterion_gain_loss =  $totalGainLoss;
        $newBackTestStats->month_gain_loss_probability_sd =  $gainProbabilityStandardDeviation;
        $newBackTestStats->total_kelly_by_month =  $totalGainLossByMonth;
        $newBackTestStats->percent_to_risk =  $percentageToRisk;
        $newBackTestStats->position_amount_ten_k =  round($positionAmount);
        $newBackTestStats->expected_gain_loss =  round($expectedGanLoss);

        $newBackTestStats->save();

        $backTestToBeProcessed = BackTestToBeProcessed::find($processedBackTest['id']);

        $backTestToBeProcessed->stats_finish = 1;

        $backTestToBeProcessed->save();
    }

    public function fullTestStats($backTestId) {

        $positiveCount = 0;
        $negativeCount = 0;

        $fullAccountValue = 10000;

        $positions = BackTestPosition::where('back_test_id', '=', $backTestId)->get()->toArray();

        $monthData = DB::select("select distinct DATE_FORMAT(open_date_date, '%m/%Y') as transaction_month, sum(gain_loss) as sum_gain_loss, max(gain_loss) as max_gain, min(gain_loss) as max_loss, max(id) as max_id 
                              from back_test_positions where back_test_id = ? group by transaction_month order by max_id;", [$backTestId]);

        foreach ($monthData as $index=>$month) {
            $monthData[$index] = json_decode(json_encode($month), true);

            $monthTransactions = DB::select("select * 
                              from back_test_positions where back_test_id = ? and DATE_FORMAT(open_date_date, '%m/%Y') = ? order by id;", [$backTestId, $month->transaction_month]);

            $monthAccountAmount = 10000;
            $monthPositiveCount = 0;
            $monthNegativeCount = 0;

            $monthGainLossValues = [];
            $monthAccountGainLossValues = [];

            foreach ($monthTransactions as $monthIndex=>$transaction) {
                $transaction = json_decode(json_encode($transaction), true);

                $transaction['ten_k_gain_loss'] = (50000*($transaction['open_price'] + $transaction['gain_loss'])) - 50000*$transaction['open_price'];

                //Monthly Account Starting with 10K
                $monthAccountTradeAmount = $monthAccountAmount*5;
                $monthAccountTransactionGainLoss = ($monthAccountTradeAmount*($transaction['open_price'] + $transaction['gain_loss'])) - $monthAccountTradeAmount*$transaction['open_price'];
                $transaction['mounth_account_gain_loss'] = round($monthAccountTransactionGainLoss, 2);
                $monthGainLossValues[] = $transaction['mounth_account_gain_loss'];
                $monthAccountAmount = $monthAccountAmount + $monthAccountTransactionGainLoss;
                $transaction['mounth_account_amount'] = round($monthAccountAmount);

                //Running Account
                $accountTradeAmount = $fullAccountValue*5;
                $accountTradeGainLoss = ($accountTradeAmount*($transaction['open_price'] + $transaction['gain_loss'])) - $accountTradeAmount*$transaction['open_price'];
                $transaction['running_account_gain_loss'] = round($accountTradeGainLoss, 2);
                $monthAccountGainLossValues[] = $transaction['running_account_gain_loss'];
                $fullAccountValue = $fullAccountValue + $accountTradeGainLoss;
                $transaction['running_account_amount'] = round($fullAccountValue);

                if ($transaction['gain_loss'] > 0) {
                    $positiveCount++;
                    $monthPositiveCount++;
                }
                else {
                    $negativeCount++;
                    $monthNegativeCount++;
                }
                $monthTransactions[$monthIndex] = $transaction;
            }
            $monthData[$index]['transactions'] = $monthTransactions;
            $monthData[$index]['positiveCount'] = $monthPositiveCount;
            $monthData[$index]['negativeCount'] = $monthNegativeCount;
            $monthData[$index]['sum10KGainLoss'] = array_sum($monthGainLossValues);
            $monthData[$index]['sumRunningAccountGainLoss'] = array_sum($monthAccountGainLossValues);
        }
        return ['positions'=>$positions, 'monthData'=>$monthData, 'positiveNegative'=>round(($positiveCount/$negativeCount)*100)];
    }


    public function deleteDuplicates() {
        echo time();
        $dupes = DB::table('tbd_historical_rates_duplicates')
            ->where('processed', '=', 0)
            ->take(20000)
            ->get();

        foreach ($dupes as $dupe) {
            DB::table('historical_rates')->where('currency_id', '=', $dupe->currency_id)
                ->where('frequency_id', '=', $dupe->frequency_id)
                ->where('rate_unix_time', '=', $dupe->rate_unix_time)
                ->where('id', '>', $dupe->min_id)
                ->delete();

            DB::update('update tbd_historical_rates_duplicates set processed = 1 where frequency_id = ? 
                and currency_id = ? and rate_unix_time = ?', [$dupe->frequency_id, $dupe->currency_id, $dupe->rate_unix_time]);
        }
        echo "<BR><BR>".time();
    }

    public function createGroup() {
        $post = Request::all();

        $strategy_id = $post['strategy'];

        //Currency Pairs
        if ($post['all_currency_pairs']) {
            $exchanges = Exchange::get()->toArray();
            $exchanges = array_column($exchanges, 'id');
            $groupExchange = 1;
        }
        else {
            $exchanges = [$post['exchange']];
            $groupExchange = $post['exchange'];
        }

        //Frequencies
        if ($post['all_frequencies']) {
            $frequencies = DecodeFrequency::get()->toArray();
            $frequencies = array_column($frequencies, 'id');
            $groupFrequency = 1;
        }
        else {
            $frequencies = [$post['frequency']];
            $groupFrequency = $post['frequency'];
        }

        //Frequencies
        if (isset($post['twoTier'])) {
            if ($post['twoTier']) {
                $slowFrequency = $post['slowFrequency'];
                $groupSlowFrequency = $post['slowFrequency'];
            }
        }
        else {
            $slowFrequency = 0;
            $groupSlowFrequency = 0;
        }

        //Take Profit
        if (strlen($post['take_profit']) > 0) {
            $takeProfits = explode(",",$post['take_profit']);
            $takeProfits = array_map('trim', $takeProfits);
        }
        else {
            $takeProfits = [0];
        }

        //Stop Loss
        if (strlen($post['stop_loss']) > 0) {
            $stopLosses = explode(",",$post['stop_loss']);
            $stopLosses = array_map('trim', $stopLosses);
        }
        else {
            $stopLosses = [0];
        }

        //Trailing Stop
        if (strlen($post['trailing_stop']) > 0) {
            $trailingStops = explode(",",$post['trailing_stop']);
            $trailingStops = array_map('trim', $trailingStops);
        }
        else {
            $trailingStops = [0];
        }

        //Variable 1 Values
        if (strlen($post['variable_1_values']) > 0) {
            $variable1Values = explode(",",$post['variable_1_values']);
            $variable1Values = array_map('trim', $variable1Values);
        }
        else {
            $variable1Values = [0];
        }

        //Variable 2 Values
        if (strlen($post['variable_2_values']) > 0) {
            $variable2Values = explode(",",$post['variable_2_values']);
            $variable2Values = array_map('trim', $variable2Values);
        }
        else {
            $variable2Values = [0];
        }

        //Variable 1 and 2 Pairs
        if ($post['pair_variables_1_2']) {

            $variableOneTwoPairs = [];

            $arrayLevelOne = explode("|",str_replace(' ', '', $post['variable_1_2_pairs']));

            foreach ($arrayLevelOne as $levelOne)
            {
                $variableOneTwoPairs[] = explode(",",$levelOne);
            }
        }

        //Variable 3 Values
        if (strlen($post['variable_3_values']) > 0) {
            $variable3Values = explode(",",$post['variable_3_values']);
            $variable3Values = array_map('trim', $variable3Values);
        }
        else {
            $variable3Values = [0];
        }

        //Variable 4 Values
        if (strlen($post['variable_4_values']) > 0) {
            $variable4Values = explode(",",$post['variable_4_values']);
            $variable4Values = array_map('trim', $variable4Values);
        }
        else {
            $variable4Values = [0];
        }

        //Variable 5 Values
        if (strlen($post['variable_5_values']) > 0) {
            $variable5Values = explode(",",$post['variable_5_values']);
            $variable5Values = array_map('trim', $variable5Values);
        }
        else {
            $variable5Values = [0];
        }

        $newBackTestGroup = new BackTestGroup();

        $newBackTestGroup->strategy_id = $strategy_id;

        $newBackTestGroup->exchange_id = $groupExchange;
        $newBackTestGroup->frequency_id = $groupFrequency;
        $newBackTestGroup->slow_frequency_id = $groupSlowFrequency;

        $newBackTestGroup->strategy_system_id = $post['strategySystem'];

        $newBackTestGroup->name = $post['back_test_name'];
        $newBackTestGroup->description = $post['group_desc'];
        $newBackTestGroup->rate_unix_time_start = $post['rate_unix_time_start'];
        $newBackTestGroup->priority = $post['priority'];

        $newBackTestGroup->variable_1_desc = $post['variable_1_name'];
        $newBackTestGroup->variable_2_desc = $post['variable_2_name'];
        $newBackTestGroup->variable_3_desc = $post['variable_3_name'];
        $newBackTestGroup->variable_4_desc = $post['variable_4_name'];
        $newBackTestGroup->variable_5_desc = $post['variable_5_name'];

        $newBackTestGroup->save();

        foreach ($exchanges as $exchange) {
            foreach ($frequencies as $frequency) {
                foreach ($takeProfits as $takeProfit) {
                    foreach ($stopLosses as $stopLoss) {
                        foreach ($trailingStops as $trailingStop) {

                            if ($post['pair_variables_1_2']) {
                                foreach($variableOneTwoPairs as $oneTwoVariablePairs) {
                                    $variable1 = $oneTwoVariablePairs[0];
                                    $variable2 = $oneTwoVariablePairs[1];

                                    foreach ($variable3Values as $variable3)  {
                                        foreach ($variable4Values as $variable4)  {
                                            foreach ($variable5Values as $variable5)  {

                                                $newBackTest = new BackTestToBeProcessed();

                                                $newBackTest->back_test_group_id = $newBackTestGroup->id;

                                                $newBackTest->exchange_id = $exchange;

                                                $newBackTest->frequency_id = $frequency;

                                                $newBackTest->slow_frequency_id = $slowFrequency;

                                                $newBackTest->take_profit_pips = $takeProfit;

                                                $newBackTest->stop_loss_pips = $stopLoss;

                                                $newBackTest->trailing_stop_pips = $trailingStop;

                                                $newBackTest->variable_1 = $variable1;

                                                $newBackTest->variable_2 = $variable2;

                                                $newBackTest->variable_3 = $variable3;

                                                $newBackTest->variable_4 = $variable4;

                                                $newBackTest->variable_5 = $variable5;

                                                $newBackTest->save();
                                            }
                                        }
                                    }
                                }
                            }
                            else {
                                foreach ($variable1Values as $variable1)  {
                                    foreach ($variable2Values as $variable2)  {
                                        foreach ($variable3Values as $variable3)  {
                                            foreach ($variable4Values as $variable4)  {
                                                foreach ($variable5Values as $variable5)  {

                                                    $newBackTest = new BackTestToBeProcessed();

                                                    $newBackTest->back_test_group_id = $newBackTestGroup->id;

                                                    $newBackTest->exchange_id = $exchange;

                                                    $newBackTest->frequency_id = $frequency;

                                                    $newBackTest->take_profit_pips = $takeProfit;

                                                    $newBackTest->stop_loss_pips = $stopLoss;

                                                    $newBackTest->trailing_stop_pips = $trailingStop;

                                                    $newBackTest->slow_frequency_id = $slowFrequency;

                                                    $newBackTest->variable_1 = $variable1;

                                                    $newBackTest->variable_2 = $variable2;

                                                    $newBackTest->variable_3 = $variable3;

                                                    $newBackTest->variable_4 = $variable4;

                                                    $newBackTest->variable_5 = $variable5;

                                                    $newBackTest->save();
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return ['back_test_group_id'=>$newBackTestGroup->id];
    }

    public function removeNegativePipData($processId) {
        $bts = BackTestModel::where('process_id', '=', $processId)->get();

        foreach ($bts as $bt) {
            BackTestStats::where('back_test_id', '=', $bt->id)->delete();

            BackTestPosition::where('back_test_id', '=', $bt->id)->delete();
        }

        $backTest = BackTestModel::where('process_id', '=', $processId)->first();
    }

    public function rollbackSingleProcess($processId) {
        $bts = BackTestModel::where('process_id', '=', $processId)->get();

        foreach ($bts as $bt) {
            BackTestStats::where('back_test_id', '=', $bt->id)->delete();

            BackTestPosition::where('back_test_id', '=', $bt->id)->delete();
        }

        BackTestModel::where('process_id', '=', $processId)->delete();

        $backTestToBeProcessed = BackTestToBeProcessed::find($processId);

        $backTestToBeProcessed->start = 0;
        $backTestToBeProcessed->finish = 0;
        $backTestToBeProcessed->stats_start = 0;
        $backTestToBeProcessed->stats_finish = 0;
        $backTestToBeProcessed->hung_up = 0;
        $backTestToBeProcessed->run_exception = 0;
        $backTestToBeProcessed->stats_exception = 0;
        $backTestToBeProcessed->in_process_unix_time = 0;

        $backTestToBeProcessed->save();
    }

    public function rollbackBackTestGroup($backTestGroup) {
        $backTestToBeProcessed = BackTestToBeProcessed::where('back_test_group_id', '=', $backTestGroup)->get();

        foreach ($backTestToBeProcessed as $backTestProcess) {

            try {
                $backTest = BackTestModel::where('process_id', '=', $backTestProcess->id)->first();
                BackTestPosition::where('back_test_id', '=', $backTest->id)->delete();
                BackTestStats::where('back_test_id', '=', $backTest->id)->delete();
            }
            catch (\Exception $e) {

            }

            BackTestModel::where('process_id', '=', $backTestProcess->id)->delete();

            DB::enableQueryLog();

            $backTestToBeProcessed = BackTestToBeProcessed::find($backTestProcess->id);

            $backTestToBeProcessed->start = 0;
            $backTestToBeProcessed->finish = 0;
            $backTestToBeProcessed->stats_start = 0;
            $backTestToBeProcessed->stats_finish = 0;
            $backTestToBeProcessed->hung_up = 0;
            $backTestToBeProcessed->run_exception = 0;
            $backTestToBeProcessed->stats_exception = 0;
            $backTestToBeProcessed->in_process_unix_time = 0;

            $backTestToBeProcessed->save();

            $queries = DB::getQueryLog();

        }

        $rolledBackGroup = BackTestGroup::find($backTestGroup);

        $rolledBackGroup->process_run = 0;
        $rolledBackGroup->stats_run = 0;

        $rolledBackGroup->save();
    }

    public function manualRollbackProcess() {
        $this->rollbackSingleProcess(195904);
    }

    public function manualRollbackGroup() {
        $this->rollbackSingleProcess(41);
    }

    public function manualRollbackGroupStats() {
        $this->rollbackBackTestGroupStats(42);
    }

    public function rollbackBackTestGroupStats($backTestGroup) {
        $backTestToBeProcessed = BackTestToBeProcessed::where('back_test_group_id', '=', $backTestGroup)->get();

        foreach ($backTestToBeProcessed as $backTestProcess) {

            try {
                $backTest = BackTestModel::where('process_id', '=', $backTestProcess->id)->first();
                BackTestStats::where('back_test_id', '=', $backTest->id)->delete();
            }
            catch (\Exception $e) {

            }

            $backTestToBeProcessed = BackTestToBeProcessed::find($backTestProcess->id);

            $backTestToBeProcessed->stats_start = 0;
            $backTestToBeProcessed->stats_finish = 0;

            $backTestToBeProcessed->save();

        }
    }

    public function populateBTGExchangeFrequency() {
        $groups = BackTestGroup::get();

        foreach ($groups as $group) {
            $backTestToBeProcessed = BackTestToBeProcessed::where('back_test_group_id', '=', $group->id)->first();

            $backTestGroup = BackTestGroup::find($group->id);

            if (isset($backTestToBeProcessed->exchange_id)) {
                $backTestGroup->exchange_id = $backTestToBeProcessed->exchange_id;
                $backTestGroup->frequency_id = $backTestToBeProcessed->frequency_id;
                $backTestGroup->slow_frequency_id = $backTestToBeProcessed->slow_frequency_id;

                $backTestGroup->save();
            }
        }

    }
}