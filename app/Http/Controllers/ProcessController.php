<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\ProcessLog\Process;
use App\Model\ProcessLog\ProcessQueue;
use App\Http\Controllers\ServersController;
use App\Http\Controllers\AutomatedBackTestController;
use App\Services\Utility;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
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
        $this->serverController = new ServersController();
        $this->serverController->setServerId();

        $this->logger = new ProcessLogger('server_run_check');

        $this->serverController->logger = $this->logger;

        $this->currentRunningProcessThresholdCheck();

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
        DB::statement("UPDATE process_queue SET server_id = ? where server_id = 0 limit 1", [Config::get('server_id')]);

        $processToBeRun = ProcessQueue::where('server_id', '=', Config::get('server_id'))->whereNull('start_time')->first();

        if (is_null($processToBeRun)) {
            $this->logger->logMessage('$processToBeRun is null, kicking off backtest');
            $this->logger->processEnd();

            $automatedBacktestController = new AutomatedBackTestController();
            $automatedBacktestController->runOneProcessOrAllBacktestStats();
        }
        else {
            DB::table('tbd_process_log_debug')->insert(
                ['server_id' => Config::get('server_id'), 'process_queue_id' => $processToBeRun->id,
                'created_at'=>$this->utility->mysqlDateTime()]
            );

            $processToBeRun->server_id = Config::get('server_id');
            $processToBeRun->start_time = $this->utility->mysqlDateTime();

            $processToBeRun->save();

            $this->processId = $processToBeRun->process_id;

            $relevantVariable = false;

            if ($processToBeRun->variable_id != 0) {
                $relevantVariable = $processToBeRun->variable_id;
            }
            $this->logger->processEnd();
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

        $this->logger->logMessage('stillRunningProcessPids :'. substr(json_encode($stillRunningProcesses), 0, 20000));

        $processesRunningCount = 0;

        foreach($stillRunningProcesses as $stillRunningProcess) {
            $pidStillRunning = $this->serverController->seeIfPidIsRunning($stillRunningProcess['linux_pid']);
            if ($pidStillRunning) {
                $this->logger->logMessage($stillRunningProcess['linux_pid'].' pid IS still running');
                $processesRunningCount = $processesRunningCount + 1;
            }
            else {
                $this->logger->logMessage($stillRunningProcess['linux_pid'].' pid NOT running. Forcing process end.');
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
