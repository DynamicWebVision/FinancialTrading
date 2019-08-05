<?php namespace App\Http\Controllers\Equity;


use \DB;
use \Log;
use Request;
use App\Http\Controllers\Controller;
use App\Model\Stocks\StocksTechnicalCheck;
use App\Model\Stocks\StocksBackTestGroup;
use App\Model\Stocks\StocksBackTestIteration;
use App\Model\Stocks\StocksBackTestGroupVariableDefinitions;
use App\Model\Stocks\StocksBackTestIterationVariables;
use App\EquityBacktest\EquityBacktestSimulator;

class StockBacktestController extends Controller {

    public function processStockBacktest($iteration_id) {
        $backTestIteration = StocksBackTestIteration::find($iteration_id);
        $backTestGroup = StocksBackTestGroup::find($backTestIteration->stocks_back_test_group_id);

        $technicalCheck = StocksTechnicalCheck::find($backTestGroup->technical_check_id);

        $technicalCheck = new $technicalCheck->class();

        $backTestVariables = StocksBackTestGroupVariableDefinitions::where('stocks_back_test_group_id', '=', $backTestGroup->id)
                                ->get()
                                ->toArray();

        foreach ($backTestVariables as $backTestVariable) {
            $variableValue = StocksBackTestIterationVariables::where('stocks_back_test_iteration_id', '=', $iteration_id)
                                ->where('variable_number', '=', $backTestVariable['variable_number'])
                                ->first();

            $technicalCheck->{$backTestVariable->variable_name} = $variableValue->variable_value;
        }

        //!!!!Need more Logic down the road!!!!
        $indicatorMin = 50;

        $equityBacktestSimulator = new EquityBacktestSimulator($backTestIteration->stock_id, $indicatorMin, $technicalCheck);
        $equityBacktestSimulator->run();
    }

    public function createBacktest() {

    }
}