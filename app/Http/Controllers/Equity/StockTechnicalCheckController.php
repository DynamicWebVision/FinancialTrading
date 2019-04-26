<?php namespace App\Http\Controllers\Equity;

use App\Http\Controllers\Controller;

use App\EquityTechnicalCheck\HmaReversal\HmaReversal;
use App\Model\Stocks\StocksTechnicalCheckResult;
use App\Model\Stocks\StocksTechnicalCheck;
use App\Model\Stocks\Stocks;
use App\Model\ProcessLog\ProcessQueue;


class StockTechnicalCheckController extends Controller {

    public $logger;

    public function hmaReversalCheck($stockId) {
        $hmaReversal = new HmaReversal($stockId, $this->logger);
        $hmaReversal->hmaChangeDirPeriods = 3;
        $hmaReversal->hmaLength = 9;
        $hmaReversal->check();
    }

    public function flushOldTechnicalCheckResults() {
        StocksTechnicalCheckResult::truncate();
    }

    public function createStockProcessQueueRecords() {
        $stocksTechnicalChecks = StocksTechnicalCheck::where('active', '=', 1)->get();

        $stocks = Stocks::get();

        foreach ($stocksTechnicalChecks as $stockTechnicalCheck) {

            foreach ($stocks as $stock) {
                $processQueue = new ProcessQueue();
                $processQueue->process_id = $stockTechnicalCheck->process_id;
                $processQueue->variable_id = $stock->id;
                $processQueue->priority = 8;

                $processQueue->save();
            }
        }
    }
}