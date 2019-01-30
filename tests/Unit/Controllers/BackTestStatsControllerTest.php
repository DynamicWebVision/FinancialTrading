<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\CurrencyIndicators;
use App\Model\HistoricalRates;
use App\Model\TmpTestRates;
use App\Http\Controllers\BackTestStatsController;
use App\Broker\OandaV20;

class BackTestStatsControllerTest extends TestCase
{

    public $transactionController;
    public $oanda;

    public function testBacktestProcessStatsSpecificProcess() {
        $backTestStatsController = new BackTestStatsController();

        $backTestGroupId = 254;
        $processId = 203742;
        $backTestStatsController->rollbackBackTestGroupStats($backTestGroupId);

        $backTestStatsController->backtestProcessStatsSpecificProcess($processId);
    }

    public function testRollBackReviewedNonProfitableProcesses() {
        $backTestStatsController = new BackTestStatsController();

        $backTestStatsController->rollBackReviewedNonProfitableProcesses();
    }
}
