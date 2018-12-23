<?php namespace App\Http\Controllers;

use \DB;
use \Log;
use Request;

use App\Http\Controllers\AutomatedBackTestController;
use App\Http\Controllers\BackTestStatsController;
use App\Http\Controllers\BackTestingController;
use App\Http\Controllers\Controller;
use \App\Model\Indicators;


class BackTestRunning extends Controller {

    /*******************************************************
        SINGLE SPECIFIC PROCESS
     *******************************************************/

    //ROLL BACK
    public function rollbackLastRunProcess() {
        $backTestingController = new BackTestingController();

        $backTestingController->rollBackServerGroup();
    }

    //RUN PROCESS
    public function savePracticeTransactions() {
        $backTestStatsController = new BackTestStatsController();
        $backTestStatsController->backtestProcessStats();
    }
}