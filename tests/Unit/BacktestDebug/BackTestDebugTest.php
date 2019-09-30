<?php

namespace Tests\Unit\BacktestDebug;

use Tests\TestCase;
use App\Http\Controllers\AutomatedBackTestController;
use App\Http\Controllers\BackTestStatsController;
use App\Http\Controllers\BackTestingController;
use App\Broker\OandaV20;
use App\Services\ProcessLogger;

class BackTestDebugTest extends TestCase
{

    public $transactionController;
    public $oanda;

    /*******************************************************
     FULL BACKTEST GROUP
     *******************************************************/

    //Re-Run Full Process
    public function testFullProcess() {
        $logger = new ProcessLogger('fx_backtest');
        $backTestingController = new BackTestingController();
        $backTestingController->rollBackServerGroup();

        $automatedBackTestController = new AutomatedBackTestController();
        $automatedBackTestController->logger = $logger;
        $automatedBackTestController->environmentVariableDriveProcess();

        $backTestStatsController = new BackTestStatsController();
        $automatedBackTestController->logger = $logger;
        $backTestStatsController->backtestProcessStats();
    }

//    //Simple EBT Test
    public function testEbt() {
        $automatedBackTestController = new AutomatedBackTestController();
        $automatedBackTestController->environmentVariableDriveProcess();
    }

    public function testBackTestStats() {

        $backTestStatsController = new BackTestStatsController();
        $backTestStatsController->rollBackTestStatsServerId();

        $automatedBackTestController = new AutomatedBackTestController();
        $automatedBackTestController->processBackTestStats();
    }

    public function testRollBackBackTestGroup() {
        $backTestingController = new BackTestingController();
        $backTestingController->rollBackServerGroup();
    }

    public function testRollBackMultipleBackTestGroups() {
        $groupsToRollback = [
            353, 354, 355, 356, 357
        ];

        $backTestingController = new BackTestingController();

        foreach ($groupsToRollback as $id) {
            $backTestingController->rollbackBackTestGroup($id);
        }
    }

    public function testDeleteTestGroup() {
        $backTestingController = new BackTestingController();
        $backTestingController->deleteServerGroup();
    }

    public function testDeletMultipleGroups() {
        $backTestingController = new BackTestingController();

        $groupsToBeDeleted = [];

        $backTestingController->deleteBackTestGroup($groupId);
    }

    /*******************************************************
     SINGLE SPECIFIC PROCESS
     *******************************************************/

    //Re-Run Full Process
    public function testFullSpecsetUpLoggerificProcess() {
        $processId = 225332;

        $backTestingController = new BackTestingController();
        $backTestingController->rollbackSingleProcess($processId);

        $automatedBackTestController = new AutomatedBackTestController();
        $automatedBackTestController->environmentVariableDriveProcessId($processId);

        $backTestStatsController = new BackTestStatsController();
        $backTestStatsController->backtestProcessStatsSpecificProcess($processId);
    }

    //ROLL BACK & Rer-Run
    public function testRollbackLastRunProcess() {
        $backTestingController = new BackTestingController();

        $backTestingController->rollBackServerGroup();
    }

    //RUN PROCESS
    public function testSavePracticeTransactions() {
        $backTestStatsController = new BackTestStatsController();
        $backTestStatsController->backtestProcessStats();
    }
}
