<?php

namespace Tests\Unit\Controllers;

use App\Model\BackTestGroup;
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

//    public function testBacktestProcessStatsSpecificProcess() {
//        $backTestStatsController = new BackTestStatsController();
//
//        $backTestGroupId = 280;
//        $processId = 217624;
//        $backTestStatsController->rollbackBackTestGroupStats($backTestGroupId);
//
//        $backTestStatsController->backtestProcessStatsSpecificProcess($processId);
//    }
//
//    public function testRollBackReviewedNonProfitableProcesses() {
//        $backTestStatsController = new BackTestStatsController();
//
//        $backTestStatsController->rollBackReviewedNonProfitableProcesses();
//    }

    public function testRollBackSeveralStats() {
        $backTestStatsController = new BackTestStatsController();

        $backTestGroups = BackTestGroup::where('id', '>', 225)->get();

        foreach($backTestGroups as $group) {
            $backTestStatsController->rollbackBackTestGroupStats($group->id);
        }
    }
}