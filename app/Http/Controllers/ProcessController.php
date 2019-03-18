<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\ProcessLog\Process;
use App\Model\ProcessLog\ProcessQueue;
use App\Http\Controllers\ServersController;
use App\Http\Controllers\AutomatedBackTestController;
use App\Services\Utility;
use Illuminate\Support\Facades\Config;
use App\Services\ProcessLogger;
use App\Model\ProcessLog\ProcessLog;

class ProcessController extends Controller
{
    protected $processId;
    public $logger;
    public $serverController;

    public function __construct()
    {
        $this->utility = new Utility();
    }

    public function serverRunCheck() {
        $this->logger = new ProcessLogger('server_run_check');
        $this->serverController = new ServersController();
        $this->serverController->setServerId();
        $this->serverController->logger = $this->logger;

        $this->currentRunningProcessThresholdCheck();

        $this->serverController->logger = $this->logger;

        $this->serverController->updateProcessRun();
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

        $processToBeRun = ProcessQueue::where('server_id', '=', 0)->orderBy('priority')->first();

        if (is_null($processToBeRun)) {
            $this->logger->logMessage('$processToBeRun is null, kicking off backtest');
            $this->logger->processEnd();

            $automatedBacktestController = new AutomatedBackTestController();
            $automatedBacktestController->runOneProcessOrAllBacktestStats();
        }
        else {
            $processToBeRun->server_id = Config::get('server_id');
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
        $this->logger = new ProcessLogger('process_complete');

        $this->serverController->logger = $this->logger;

        $this->serverController->killIfProcessOverMinuteThreshold();

        $this->processNextJob();
    }

    public function currentRunningProcessThresholdCheck() {

        $stillRunningProcesses = ProcessLog::whereNull('end_date_time')->where('server_id', '=', $this->serverController->serverId)
            ->distinct(['linux_pid'])->get()->toArray();

        $this->logger->logMessage('stillRunningProcessPids :'.json_encode($stillRunningProcesses));

        $processesRunningCount = 0;

        foreach($stillRunningProcesses as $stillRunningProcess) {
            $pidStillRunning = $this->serverController->seeIfPidIsRunning($stillRunningProcess['linux_pid']);
            if ($pidStillRunning) {
                $this->logger->logMessage($stillRunningProcess['linux_pid'].' pid still comming');
                $processesRunningCount = $processesRunningCount + 1;
            }
            else {
                $this->logger->logMessage($stillRunningProcess['linux_pid'].' not still comming');
                $this->logger->forceEndProcess($stillRunningProcess['id']);
            }
        }

        $this->logger->logMessage($processesRunningCount.' processes running count.');

        if ($processesRunningCount > 1) {
            $this->logger->logMessage('Killing this run due to already too many processes running.');
            $this->logger->processEnd();
            die();
        }
    }
}
