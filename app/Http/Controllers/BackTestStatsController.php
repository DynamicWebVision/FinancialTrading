<?php namespace App\Http\Controllers;

use App\ForexBackTest\BackTest;
use \App\ForexBackTest\CowabungaBackTest;
use \App\Http\Controllers\BackTestingController;
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
use App\Model\BackTest as BackTestModel;
use Request;
use App\Model\BackTestGroup;
use App\Services\CurrencyIndicators;
use App\Services\TransactionAmountHelpers;
use App\Model\Servers;

use \App\ForexBackTest\IndicatorRunThroughTest;
use \App\ForexStrategy\HullMovingAverage\HmaIndicatorRunThrough;
use Illuminate\Support\Facades\Config;


class BackTestStatsController extends Controller {

    public function __construct() {
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $serverController = new ServersController();

        $serverController->setServerId();
    }

    public function index() {
        return view('back_test');
    }

    public function backtestProcessStatsSpecificProcess($id) {
        $this->backtestProcessStats($id);
    }

    public function backtestProcessStats($processId = false) {
        $utility = new Utility();

        if ($processId) {
            $processedBackTest = BackTestToBeProcessed::where('id', '=', $processId)->first();
        }
        else {
            $server = Servers::find(Config::get('server_id'));

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
        $lossCount = DB::select("select count(*) as loss_count, AVG(gain_loss) as mean_loss from back_test_positions where back_test_id = ? and gain_loss <= 0", [$backTestId]);

        //Total Gain Loss Count
        $gainLossOffset = DB::select("select sum(gain_loss) as gain_count from back_test_positions where back_test_id = ?", [$backTestId]);

        $backTestPositionGains = BackTestPosition::where('gain_loss', '>', 0)->where('back_test_id', '=', $backTestId)->get()->toArray();
        $backTestPositionGains = array_column($backTestPositionGains, 'gain_loss');

        $backTestPositionLosses = BackTestPosition::where('gain_loss', '<=', 0)->where('back_test_id', '=', $backTestId)->get()->toArray();
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
            $gainProbabilities[] = $utility->getRatio($month->positive_count, $month->negative_count);
        }

        $gainProbabilityStandardDeviation = $indicators->standardDeviation($gainProbabilities);

        if (($gainCount[0]->gain_count + $lossCount[0]->loss_count) == 0) {
            $gainProbability = 1;
        }
        else {
            $gainProbability = $utility->getRatioDecimal($gainCount[0]->gain_count, $lossCount[0]->loss_count);
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

        $expectedGainInPips = round($indicators->median($backTestPositionGains)/$exchange['pip']);
        $expectedLossInPips = round($indicators->median($backTestPositionLosses)/$exchange['pip']);

        $percentageToRisk = $transactionHelpers->kellyCriterion($expectedGainInPips,
            $expectedLossInPips, $gainProbability);

        $expectedTenKGain = round($transactionHelpers->expectedGainFromOneTransactionTenK($percentageToRisk, $expectedGainInPips, $expectedLossInPips,$gainProbability), 2);
        $monthCount = sizeof($monthData);
        $totalBackTestPositions = BackTestPosition::where('back_test_id', '=', $backTestId)->count();
        $expectedMonthlyIncomeTenK = round($expectedTenKGain*(round($totalBackTestPositions/$monthCount)), 2);

        $newBackTestStats = new BackTestStats();

        $newBackTestStats->back_test_id =  $backTestId;

        $newBackTestStats->total_gain_loss_pips =  round($gainLossOffset[0]->gain_count/$exchange['pip']);

        $newBackTestStats->total_gain_transactions =  $gainCount[0]->gain_count;

        $newBackTestStats->total_loss_transactions =  $lossCount[0]->loss_count;

        $newBackTestStats->gl_ratio =  $utility->getRatio($gainCount[0]->gain_count, $lossCount[0]->loss_count);

        $newBackTestStats->positive_months =  $positiveMonthCount;

        $newBackTestStats->negative_months =  $negativeMonthCount;

        $newBackTestStats->median_gain =  round($indicators->median($backTestPositionGains)/$exchange['pip']);
        $newBackTestStats->median_loss =  round($indicators->median($backTestPositionLosses)/$exchange['pip']);

        $newBackTestStats->mean_gain =  round($indicators->average($backTestPositionGains)/$exchange['pip']);
        $newBackTestStats->mean_loss =  round($indicators->average($backTestPositionLosses)/$exchange['pip']);

        $newBackTestStats->gain_sd =  round($indicators->standardDeviation($backTestPositionGains)/$exchange['pip']);
        $newBackTestStats->loss_sd =  round($indicators->standardDeviation($backTestPositionLosses)/$exchange['pip']);

        $newBackTestStats->median_month_pip_gl =  round($indicators->median($allMonths));
        $newBackTestStats->mean_month_pip_gl =  round($indicators->average($allMonths));

        $newBackTestStats->median_month_gains =  round($indicators->median($positiveMonths));
        $newBackTestStats->mean_month_gains =  round($indicators->average($positiveMonths));

        $newBackTestStats->median_month_losses =  round($indicators->median($negativeMonths));
        $newBackTestStats->mean_month_losses =  round($indicators->average($negativeMonths));

//        $newBackTestStats->median_max_gain =  round($indicators->median($maxGains));
//        $newBackTestStats->mean_max_gain =  round($indicators->average($maxGains));
//
//        $newBackTestStats->median_max_loss =  round($indicators->median($maxLosses));
//        $newBackTestStats->mean_max_loss =  round($indicators->average($maxLosses));

        $newBackTestStats->month_gain_loss_probability_sd =  $gainProbabilityStandardDeviation;
        $newBackTestStats->expected_gl_kelly_10k =  $expectedTenKGain;
        $newBackTestStats->expected_month_gl_kelly_10k =  $expectedMonthlyIncomeTenK;
        $newBackTestStats->percent_to_risk =  $percentageToRisk;

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

    public function removeNegativePipData($processId) {
        $bts = BackTestModel::where('process_id', '=', $processId)->get();

        foreach ($bts as $bt) {
            BackTestStats::where('back_test_id', '=', $bt->id)->delete();

            BackTestPosition::where('back_test_id', '=', $bt->id)->delete();
        }

        $backTest = BackTestModel::where('process_id', '=', $processId)->first();
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

        }

        $rolledBackGroup = BackTestGroup::find($backTestGroup);

        $rolledBackGroup->process_run = 0;
        $rolledBackGroup->stats_run = 0;

        $rolledBackGroup->save();
    }

    public function rollBackStatsProcess($processId) {
        $this->rollbackSingleProcess($processId);
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
            $backTestToBeProcessed->stats_exception = 0;

            $backTestToBeProcessed->save();

        }
    }

    public function rollBackTestStatsServerId() {
        $server = Servers::find(Config::get('server_id'));

        $groupId = $server->current_back_test_group_id;

        $this->rollbackBackTestGroupStats($groupId);
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

    public function gainLossAnalysisLow($backtestId) {

        $currentMax = 0;

        $pip = .0001;
        $labels = [];
        $data = ['losses'=>[], 'gains'=>[]];

        while ($currentMax < 26) {
            $startBlock = $currentMax*$pip;

            $nextMax = $currentMax + 5;
            $endBlock = $nextMax*$pip;

            $labels[] = $currentMax.'-'.$nextMax;

            $countGain = BackTestPosition::where('back_test_id', '=', $backtestId)->where('gain_loss', '>=', $startBlock)->where('gain_loss', '<', $endBlock)->count();
            $countLoss = BackTestPosition::where('back_test_id', '=', $backtestId)->where('gain_loss', '<=', $startBlock*-1)->where('gain_loss', '>', $endBlock*-1)->count();

            $data['gains'][] = $countGain;
            $data['losses'][] = $countLoss;

            $currentMax = $nextMax;
        }

        return ['labels'=>$labels, 'data'=>[$data['losses'],$data['gains']]];
    }

    public function gainLossAnalysisHigh($backtestId) {
        $gainMax = BackTestPosition::where('back_test_id', '=', $backtestId)->where('gain_loss', '>', 0)->max('gain_loss');
        $lossMax = BackTestPosition::where('back_test_id', '=', $backtestId)->where('gain_loss', '<', 0)->min('gain_loss');

        $pip = .0001;

        $graphEnd = round(max([abs($lossMax), $gainMax])/$pip);

        $currentMax = 25;

        $labels = [];
        $data = ['losses'=>[], 'gains'=>[]];

        while ($currentMax < $graphEnd) {
            $startBlock = $currentMax*$pip;

            $nextMax = $currentMax + 25;
            $endBlock = $nextMax*$pip;

            $labels[] = $currentMax.'-'.$nextMax;

            $countGain = BackTestPosition::where('back_test_id', '=', $backtestId)->where('gain_loss', '>=', $startBlock)->where('gain_loss', '<', $endBlock)->count();
            $countLoss = BackTestPosition::where('back_test_id', '=', $backtestId)->where('gain_loss', '<=', $startBlock*-1)->where('gain_loss', '>', $endBlock*-1)->count();

            $data['gains'][] = $countGain;
            $data['losses'][] = $countLoss;

            $currentMax = $nextMax;
        }

        return ['labels'=>$labels, 'data'=>[$data['losses'],$data['gains']]];
    }

    public function rollBackReviewedNonProfitableProcesses() {
        $backTestingController = new BackTestingController();
        $backTestsGroup = BackTestGroup::where('reviewed', '=', 1)->where('delete_negative_process', '=', 0)->first();

        $backTestsToBeProcessed = BackTestToBeProcessed::where('back_test_group_id', '=', $backTestsGroup->id)->get()->toArray();

        foreach ($backTestsToBeProcessed as $process) {
            $backTest = BackTestModel::where('process_id', '=', $process['id'])->first();

            if (!isset($backTest)) {
                $backTestingController->rollbackSingleProcess($process['id']);
            }
            else {
                $backTestStats = BackTestStats::where('back_test_id', '=', $backTest->id)->first();

                if (!isset($backTestStats)) {
                    $backTestingController->rollbackSingleProcess($process['id']);
                }
                else {
                    if ($backTestStats->total_gain_loss_pips <= 0) {
                        $backTestingController->rollbackSingleProcess($process['id']);
                    }
                }
            }
        }
        $backTestsGroup->delete_negative_process = 1;
        $backTestsGroup->save();
    }
}