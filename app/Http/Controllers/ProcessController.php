<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\ProcessLog\Process;
use App\Model\ProcessLog\ProcessQueue;
use App\Http\Controllers\ServersController;
use App\Http\Controllers\AutomatedBackTestController;
use App\Services\Utility;
use App\Services\ProcessLogger;

class ProcessController extends Controller
{
    protected $processId;
    public $logger;

    public function __construct()
    {
        $this->utility = new Utility();
    }

    public function serverRunCheck() {
        $serverController = new ServersController();
        $serverController->setServerId();
        $this->logger = new ProcessLogger('server_run_check');

        $serverController->logger = $this->logger;
        $serverController->serverAlreadyRunningCheck();

        $this->processNextJob();
    }

    public function runJob($relevantVariable = false) {
        $processToBeRun = Process::find($this->processId);

        $jobClass = new $processToBeRun->class();

        if ($relevantVariable) {
            $this->logger->logMessage('Running Process Method '.$processToBeRun->method.' with relevant variable '.$relevantVariable);
            $this->logger->processEnd();
            $jobClass->{$processToBeRun->method}($relevantVariable);
        }
        else {
            $this->logger->logMessage('Running Process Method '.$processToBeRun->method);
            $this->logger->processEnd();
            $jobClass->{$processToBeRun->method}();
        }
    }

    public function processNextJob() {
        $serversController = new ServersController();
        $serversController->setServerId();

        $processToBeRun = ProcessQueue::where('server_id', '=', 0)->orderBy('priority')->first();

        if (is_null($processToBeRun)) {
            $this->logger->logMessage('$processToBeRun is null, kicking off backtest');
            $this->logger->processEnd();

            $automatedBacktestController = new AutomatedBackTestController();
            $automatedBacktestController->runOneProcessOrAllBacktestStats();
        }
        else {
            $processToBeRun->server_id = $serversController->serverId;
            $processToBeRun->start_time = $this->utility->mysqlDateTime();

            $processToBeRun->save();

            $this->processId = $processToBeRun->process_id;

            $relevantVariable = false;

            if ($processToBeRun->variable_id != 0) {
                $relevantVariable = $processToBeRun->variable_id;
            }

            $this->runJob($relevantVariable);

            $processToBeRun->end_time = $this->utility->mysqlDateTime();
            $processToBeRun->save();
        }
        $this->proccessRunCompletion();

    }

    public function proccessRunCompletion() {
        $serversController = new ServersController();
        $serversController->updateProcessRun();
        $serversController->gitPullCheck();

        $this->processNextJob();
    }

//    public function
}
