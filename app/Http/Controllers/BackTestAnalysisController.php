<?php namespace App\Http\Controllers;

use App\ForexBackTest\BackTest;
use \App\ForexBackTest\CowabungaBackTest;
use App\Services\Utility;
use View;
use \App\Model\BackTestPosition;
use Illuminate\Support\Facades\DB;
use \App\ForexBackTest\StopLossWithTrailingStopTest;
use \App\ForexBackTest\EmaMomentumTPSLTest;
use App\Model\BackTestToBeProcessed;
use App\Model\Exchange;
use App\Model\DecodeFrequency;
use App\Model\BackTestStats;
use Request;
use App\Model\BackTestGroup;
use App\Services\CurrencyIndicators;


class BackTestAnalysisController extends Controller {

    public function __construct() {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
    }

    public function index() {
        return view('back_test');
    }

    public function backTestGroups() {
        return BackTestGroup::get()->toArray();
    }

    public function getBackTests() {
//        $back_tests = DB::select('select strategy.name as strategy_name, strategy.description  as strategy_desc, decode_frequency.frequency,
//                exchange.exchange, back_test.created_at , back_test.id , back_test_to_be_processed.take_profit_pips, exchange.pip, back_test_to_be_processed.id as processed_id ,
//                    back_test_to_be_processed.stop_loss_pips,
//                    back_test_to_be_processed.trailing_stop_pips,
//                    back_test_to_be_processed.variable_1,
//                    back_test_to_be_processed.variable_2,
//                    back_test_to_be_processed.variable_3,
//                    back_test_to_be_processed.variable_4,
//                    back_test_to_be_processed.variable_5,
//                    back_test_group.variable_1_desc,
//                    back_test_group.variable_2_desc,
//                    back_test_group.variable_3_desc,
//                    back_test_group.variable_4_desc,
//                    back_test_group.variable_5_desc,
//                    back_test_group.name,
//                    back_test_group.description,
//                    back_test_stats.total_gain_loss_pips,
//                    back_test_stats.total_gain_transactions,
//                    back_test_stats.total_loss_transactions,
//                    back_test_stats.positive_months,
//                    back_test_stats.negative_months,
//                    back_test_stats.median_gain,
//                    back_test_stats.median_loss,
//                    back_test_stats.mean_gain,
//                    back_test_stats.mean_loss,
//                    back_test_stats.median_month_pip_gl,
//                    back_test_stats.mean_month_pip_gl,
//                    back_test_stats.median_month_gains,
//                    back_test_stats.mean_month_gains,
//                    back_test_stats.median_month_losses,
//                    back_test_stats.mean_month_losses
//                from back_test join strategy on back_test.strategy_id = strategy.id
//                join decode_frequency on back_test.frequency_id = decode_frequency.id
//                join back_test_stats on back_test.id = back_test_stats.back_test_id
//                join exchange on back_test.exchange_id = exchange.id
//                left join back_test_to_be_processed on back_test.process_id = back_test_to_be_processed.id
//                left join back_test_group on back_test_to_be_processed.back_test_group_id = back_test_group.id
//                where back_test.finish = 1
//                order by processed_id, strategy.id, decode_frequency.id, exchange.id
//                ;', []);

        $backTestGroups = $this->backTestGroups();

        return ['back_test_groups'=>$backTestGroups];
    }

    public function getBackTestsByGroupId($groupId) {
        $back_tests = DB::select('select strategy.name as strategy_name, strategy.description  as strategy_desc, decode_frequency.frequency,
                exchange.exchange, back_test.created_at , back_test.id , back_test_to_be_processed.take_profit_pips, exchange.pip, back_test_to_be_processed.id as processed_id , 
                    back_test_to_be_processed.stop_loss_pips, 
                    back_test_to_be_processed.trailing_stop_pips, 
                    back_test_to_be_processed.variable_1, 
                    back_test_to_be_processed.variable_2, 
                    back_test_to_be_processed.variable_3, 
                    back_test_to_be_processed.variable_4, 
                    back_test_to_be_processed.variable_5, 
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
                    back_test_stats.mean_month_losses 
                from back_test join strategy on back_test.strategy_id = strategy.id
                join decode_frequency on back_test.frequency_id = decode_frequency.id
                join back_test_stats on back_test.id = back_test_stats.back_test_id 
                join exchange on back_test.exchange_id = exchange.id
                left join back_test_to_be_processed on back_test.process_id = back_test_to_be_processed.id
                left join back_test_group on back_test_to_be_processed.back_test_group_id = back_test_group.id 
                where back_test.finish = 1 and back_test_group.id = ?
                order by processed_id, strategy.id, decode_frequency.id, exchange.id
                ;', [$groupId]);

        return ['back_tests'=>$back_tests];
    }



    public function setBackTest($backTestId) {
        session(['current_back_test_id'=> $backTestId]);
        return 1;
    }

    public function backtestProcessStats() {

        $processedBackTest = BackTestToBeProcessed::where('stats_finish', '=', 0)->where('stats_start', '=', 0)->where('finish', '=', 1)->where('start', '=', 1)->first();

        $processedBackTest->stats_start = 1;

        $processedBackTest->save();

        $processedBackTest = $processedBackTest->toArray();

        $backTest = \App\Model\BackTest::where('process_id', '=', $processedBackTest['id'])->first()->toArray();

        $exchange = Exchange::find($processedBackTest['exchange_id'])->toArray();

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
        }
        else {
            $exchanges = [$post['exchange']];
        }

        //Frequencies
        if ($post['all_frequencies']) {
            $frequencies = DecodeFrequency::get()->toArray();
            $frequencies = array_column($frequencies, 'id');
        }
        else {
            $frequencies = [$post['frequency']];
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

        $newBackTestGroup->name = $post['back_test_name'];
        $newBackTestGroup->description = $post['group_desc'];

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

        return 1;
    }
}