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
use App\Model\BackTest as BackTestModel;
use Request;
use App\Model\BackTestGroup;
use App\Services\CurrencyIndicators;
use App\Services\TransactionAmountHelpers;

use \App\ForexBackTest\IndicatorRunThroughTest;
use \App\ForexStrategy\HullMovingAverage\HmaIndicatorRunThrough;


class BackTestManagementController extends Controller {

    public function createBackTestGroupFromProcessIdToOtherExchanges($processId) {
        $backTestToBeProcessed = BackTestToBeProcessed::find($processId);

        $backTestGroup = BackTestGroup::find($backTestToBeProcessed->back_test_group_id);

        $newBackTestGroup = new BackTestGroup();

        $newBackTestGroup->strategy_id = $backTestGroup->strategy_id;
        $newBackTestGroup->strategy_system_id = $backTestGroup->strategy_system_id;

        $newBackTestGroup->name = 'Ex Copy '.$backTestGroup->name.' '.$backTestGroup->id.'-'.$backTestToBeProcessed->id;
        $newBackTestGroup->description = 'Exchange Copy '.$backTestGroup->description;


        $newBackTestGroup->exchange_id = $backTestGroup->exchange_id;
        $newBackTestGroup->frequency_id = $backTestGroup->frequency_id;
        $newBackTestGroup->slow_frequency_id = $backTestGroup->slow_frequency_id;
        $newBackTestGroup->rate_unix_time_start = 1325376000;
        $newBackTestGroup->priority = 4;

        $newBackTestGroup->variable_1_desc = $newBackTestGroup->variable_1_desc;
        $newBackTestGroup->variable_2_desc = $newBackTestGroup->variable_2_desc;
        $newBackTestGroup->variable_3_desc = $newBackTestGroup->variable_3_desc;
        $newBackTestGroup->variable_4_desc = $newBackTestGroup->variable_4_desc;
        $newBackTestGroup->variable_5_desc = $newBackTestGroup->variable_5_desc;

        $newBackTestGroup->save();

        $exchanges = Exchange::get();

        foreach ($exchanges as $exchange) {
            $newBackTest = new BackTestToBeProcessed();

            $newBackTest->back_test_group_id = $newBackTestGroup->id;

            $newBackTest->exchange_id = $exchange->id;

            $newBackTest->frequency_id = $backTestToBeProcessed->frequency_id;

            $newBackTest->slow_frequency_id = $backTestToBeProcessed->slow_frequency_id;

            $newBackTest->take_profit_pips = $backTestToBeProcessed->take_profit_pips;

            $newBackTest->stop_loss_pips = $backTestToBeProcessed->stop_loss_pips;

            $newBackTest->trailing_stop_pips = $backTestToBeProcessed->trailing_stop_pips;

            $newBackTest->variable_1 = $backTestToBeProcessed->variable_1;

            $newBackTest->variable_2 = $backTestToBeProcessed->variable_2;

            $newBackTest->variable_3 = $backTestToBeProcessed->variable_3;

            $newBackTest->variable_4 = $backTestToBeProcessed->variable_4;

            $newBackTest->variable_5 = $backTestToBeProcessed->variable_5;

            $newBackTest->save();
        }

        return $newBackTestGroup;
    }

    public function backTestGroupFromIteration($processId)
    {
        $backTestToBeProcessed = BackTestToBeProcessed::find($processId);

        $backTestGroup = BackTestGroup::find($backTestToBeProcessed->back_test_group_id);

        $newBackTestGroup['all_currency_pairs'] = false;
        $newBackTestGroup['all_frequencies'] = false;
        $newBackTestGroup['pair_variables_1_2'] = false;

        $newBackTestGroup['take_profit'] = $backTestToBeProcessed->take_profit_pips;
        $newBackTestGroup['stop_loss'] = $backTestToBeProcessed->stop_loss_pips;
        $newBackTestGroup['trailing_stop'] = $backTestToBeProcessed->trailing_stop_pips;

        $newBackTestGroup['frequency'] = $backTestToBeProcessed->frequency_id;
        $newBackTestGroup['exchange'] = $backTestToBeProcessed->exchange_id;

        $newBackTestGroup['exchange'] = $backTestGroup->rate_unix_time_start;

        $newBackTestGroup['variable_1_values'] = round($backTestToBeProcessed->variable_1);
        $newBackTestGroup['variable_2_values'] = round($backTestToBeProcessed->variable_2);
        $newBackTestGroup['variable_3_values'] = round($backTestToBeProcessed->variable_3);
        $newBackTestGroup['variable_4_values'] = round($backTestToBeProcessed->variable_4);
        $newBackTestGroup['variable_5_values'] = round($backTestToBeProcessed->variable_5);

        $newBackTestGroup['variable_1_name'] = $backTestGroup->variable_1_desc;
        $newBackTestGroup['variable_2_name'] = $backTestGroup->variable_2_desc;
        $newBackTestGroup['variable_3_name'] = $backTestGroup->variable_3_desc;
        $newBackTestGroup['variable_4_name'] = $backTestGroup->variable_4_desc;
        $newBackTestGroup['variable_5_name'] = $backTestGroup->variable_5_desc;

        $newBackTestGroup['back_test_name'] = 'NEW BACK TEST GROUP';
        $newBackTestGroup['group_desc'] = $backTestGroup->description;
        $newBackTestGroup['strategy_class_name'] = $backTestGroup->strategy_class;
        $newBackTestGroup['strategy'] = $backTestGroup->strategy_id;

        return $newBackTestGroup;
    }

    public function backTestGroupReviewed($backTestGroupId)
    {
        $backTestGroup = BackTestGroup::find($backTestGroupId);

        $backTestGroup->reviewed = 1;

        $backTestGroup->save();

        return $backTestGroup;
    }
}