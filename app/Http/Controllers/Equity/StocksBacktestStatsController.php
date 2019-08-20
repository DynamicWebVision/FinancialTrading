<?php namespace App\Http\Controllers\Equity;


use \DB;
use \Log;
use Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ServersController;
use App\Broker\TDAmeritrade;
use Illuminate\Support\Facades\Config;
use App\Model\Stocks\StocksBackTestIteration;
use App\Broker\IexTrading;
use App\Model\Stocks\Stocks;
use App\Model\Stocks\StockIexDailyRates;
use App\Model\Stocks\StocksBackTestPositions;
use App\Model\Stocks\VwStocksWithRateCount;

class StocksBacktestStatsController extends Controller {

    protected $accountValue = 10000;

    public function analyzeBacktest($iteration_id) {
        $this->accountValue = 10000;
        $backTestIteration = StocksBackTestIteration::find($iteration_id);
        $backTestIteration->stats_start = 1;
        $backTestIteration->save();

        $positions = StocksBackTestPositions::where('stocks_back_test_iteration_id','=', $iteration_id)
                        ->orderBy('id')
                        ->get()->toArray();

        foreach ($positions as $position) {
            $positionMultiplier = 1 + $position['gain_loss'];

            $newAccountValue = $this->accountValue*$positionMultiplier;
            $difference = $newAccountValue - $this->accountValue;

            $tenThousandNewValue = 10000*$positionMultiplier;
            $tenDifference = $tenThousandNewValue - 10000;

            $stockBackTestPosition = StocksBackTestPositions::find($position['id']);

            $stockBackTestPosition->ten_k_account_value = $newAccountValue;
            $stockBackTestPosition->transaction_gain_loss = $difference;
            $stockBackTestPosition->ten_k_single_transaction = $tenDifference;

            $stockBackTestPosition->save();
            $this->accountValue = $newAccountValue;
        }

        $position_average = StocksBackTestPositions::where('stocks_back_test_iteration_id','=', $iteration_id)->avg('gain_loss');

        $backTestIteration = StocksBackTestIteration::find($iteration_id);

        $backTestIteration->final_account_value = $this->accountValue;
        $backTestIteration->position_average = $position_average;
        $backTestIteration->stats_finish = 1;

        $backTestIteration->save();
    }

    public function analyzeUnprocessBacktests() {
        $iterations = StocksBackTestIteration::where('finish', '=', 1)
                        ->where('stats_start', '=', 0)
                        ->get()->toArray();

        foreach ($iterations as $iteration) {
            $this->analyzeBacktest($iteration['id']);
        }
    }
}