<?php namespace App\Http\Controllers\Equity;


use \DB;
use \Log;
use Request;
use App\Http\Controllers\Controller;
use App\Model\Stocks\StocksTechnicalCheck;
use App\Model\Stocks\StocksBackTestGroup;
use App\Model\Stocks\Stocks;
use App\Model\Stocks\StocksBackTestIteration;
use App\Model\Stocks\StocksBackTestGroupVariableDefinitions;
use App\Model\Stocks\StocksBackTestIterationVariables;
use App\EquityBacktest\EquityBacktestSimulator;
use App\Model\ProcessLog\ProcessQueue;
use App\Services\Utility;

class StockBacktestController extends Controller {

    public function processStockBacktest($iteration_id) {
        $backTestIteration = StocksBackTestIteration::find($iteration_id);
        $backTestGroup = StocksBackTestGroup::find($backTestIteration->stocks_back_test_group_id);

        $technicalCheck = StocksTechnicalCheck::find($backTestGroup->technical_check_id);

        $technicalCheck = new $technicalCheck->class($backTestIteration->stock_id);

        $backTestVariables = StocksBackTestGroupVariableDefinitions::where('stocks_back_test_group_id', '=', $backTestGroup->id)
                                ->get()
                                ->toArray();

        $indicatorValues = [];
        foreach ($backTestVariables as $backTestVariable) {
            $variableValue = StocksBackTestIterationVariables::where('stocks_back_test_iteration_id', '=', $iteration_id)
                                ->where('variable_number', '=', $backTestVariable['variable_number'])
                                ->first();

            $indicatorValues[] = $variableValue->variable_value;
            $technicalCheck->{$backTestVariable['variable_name']} = $variableValue->variable_value;
        }

        //!!!!Need more Logic down the road!!!!
        $indicatorMin = max($indicatorValues)*3;

        $backTestIteration->start = 1;
        $backTestIteration->save();

        $equityBacktestSimulator = new EquityBacktestSimulator($backTestIteration->stock_id, $indicatorMin, $technicalCheck, $iteration_id);
        $equityBacktestSimulator->run();

        $backTestIteration->finish = 1;
        $backTestIteration->save();
    }

    public function createBacktest() {
        $post = Request::all();

        $utility = new Utility();

        $backTestGroup = new StocksBackTestGroup();
        $backTestGroup->name = $post['name'];
        $backTestGroup->technical_check_id = $post['technical_check'];
        $backTestGroup->save();

        if (strlen($post['stop_loss']) > 0) {
            $post['technicalCheckVariables'][] = [
                'variable_name' => 'stop_loss',
                'variable_declaration' => 'stop_loss',
                'variable_default_values' => $post['stop_loss']
            ];
        }

        $variable_number = 1;

        $variableNumberMapping = [];

        foreach ($post['technicalCheckVariables'] as $technicalCheckVariable) {
            $backtestGroupVariableDefinition = new StocksBackTestGroupVariableDefinitions();

            $backtestGroupVariableDefinition->stocks_back_test_group_id = $backTestGroup->id;

            $backtestGroupVariableDefinition->variable_number = $variable_number;

            $backtestGroupVariableDefinition->variable_name = $technicalCheckVariable['variable_declaration'];

            $backtestGroupVariableDefinition->save();

            $variableValues =  explode(",",$technicalCheckVariable['variable_default_values']);

            $varVariablesWithVarNumbers = [];
            foreach ($variableValues as $variableValue) {
                $varVariablesWithVarNumbers[] = [
                    'number' => $variable_number,
                    'value' =>$variableValue
                ];
            }

            $variableNumberMapping[] = $varVariablesWithVarNumbers;
            $variable_number++;
        }

        $stocks = Stocks::where('default_backtest', '=', 1)->get()->toArray();

        $stocks = array_column($stocks, 'id');

        $allCombinations = $utility->getCombinations($variableNumberMapping);

        $process_id = 24;

        foreach ($stocks as $stock) {
            foreach ($allCombinations as $combination) {
                $iteration = new StocksBackTestIteration();

                $iteration->stocks_back_test_group_id = $backTestGroup->id;

                $iteration->stock_id = $stock;

                $iteration->save();

                foreach ($combination as $singleVariable) {
                    $iterationVariable = new StocksBackTestIterationVariables();

                    $iterationVariable->stocks_back_test_iteration_id = $iteration->id;
                    $iterationVariable->variable_number = $singleVariable['number'];
                    $iterationVariable->variable_value = $singleVariable['value'];

                    $iterationVariable->save();
                }

                $processQueue = new ProcessQueue();

                $processQueue->process_id = $process_id;
                $processQueue->variable_id = $iteration->id;
                $processQueue->priority = 1;

                $processQueue->save();
            }
        }
    }
}