<?php

namespace Tests\Unit\BacktestDebug;

use Tests\TestCase;
use App\Http\Controllers\AutomatedBackTestController;
use App\Http\Controllers\BackTestStatsController;
use App\Http\Controllers\BackTestingController;
use App\Broker\OandaV20;

class BackTestDebugTest extends TestCase
{

    public $transactionController;
    public $oanda;

    /*******************************************************
     FULL BACKTEST GROUP
     *******************************************************/

    //Re-Run Full Process
    public function testFullProcess() {
        $backTestingController = new BackTestingController();
        $backTestingController->rollBackServerGroup();

        $automatedBackTestController = new AutomatedBackTestController();
        $automatedBackTestController->environmentVariableDriveProcess();

        $backTestStatsController = new BackTestStatsController();
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
        $groupsToRollback = [252,
            253,
            254,
            255,
            256,
            281,
            284,
            287,
            288,
            258,
            259,
            260,
            261,
            262,
            275,
            276,
            277,
            278,
            279,
            280,
            289,
            290,
            291,
            292];

        $backTestingController = new BackTestingController();

        foreach ($groupsToRollback as $id) {
            $backTestingController->rollbackBackTestGroup($id);
        }
    }

    public function testDeleteTestGroup() {
        $backTestingController = new BackTestingController();
        $backTestingController->deleteServerGroup();
    }

    /*******************************************************
     SINGLE SPECIFIC PROCESS
     *******************************************************/

    //Re-Run Full Process
    public function testFullSpecificProcess() {
        $processId = 218486;

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
