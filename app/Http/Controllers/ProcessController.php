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
use App\Model\ProcessLog\VwServerLastLogMessage;
use App\Model\Yelp\YelpApi;

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
        DB::statement("UPDATE process_queue SET server_id = ? where server_id = 0 order by priority desc limit 1", [Config::get('server_id')]);

        $processToBeRun = ProcessQueue::where('server_id', '=', Config::get('server_id'))->whereNull('start_time')->first();

        if (is_null($processToBeRun)) {
            $this->logger->logMessage('$processToBeRun is null, Sleeping for 5 Minutes');
            $this->logger->processEnd();

            sleep(300);
//            $automatedBacktestController = new AutomatedBackTestController();
//            $automatedBacktestController->runOneProcessOrAllBacktestStats();
        }
        else {

            Config::set('process_queue_id', $processToBeRun->id);

            $processToBeRun->server_id = Config::get('server_id');
            $processToBeRun->start_time = $this->utility->mysqlDateTime();

            $processToBeRun->save();

            $this->processId = $processToBeRun->process_id;

            $relevantVariable = false;

            if ($processToBeRun->variable_id != 0) {
                $relevantVariable = $processToBeRun->variable_id;
            }
            $this->logger->processEnd();

            try {
                $this->runJob($relevantVariable);
            }
            catch (\Exception $e) {

            }


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
        $this->logger->logMessage('Server ID: '. $this->serverController->serverId.'XX');

        $stillRunningProcesses = ProcessLog::whereNull('end_date_time')->where('server_id', '=', $this->serverController->serverId)
            ->distinct(['linux_pid'])->get()->toArray();

        $lastProcessLogMessage = VwServerLastLogMessage::where('server_id','=', $this->serverController->serverId)->first();

        if (is_null($lastProcessLogMessage)) {
            $hoursSinceLastLogMessage = 2.1;
        }
        else {
            $hoursSinceLastLogMessage = $lastProcessLogMessage->last_message_unix/(3600);
        }


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

        if ($processesRunningCount > 1 && $hoursSinceLastLogMessage < 2) {
            $this->logger->logMessage('Killing this run due to already too many processes running.');
            $this->logger->processEnd();
            die();
        }
    }

    public function createContinuousToRunRecords() {
        $processes = Process::where('continuous_run', '=', 1)->get();
        $scheduleController = new ProcessScheduleController();

        foreach ($processes as $process) {
            if ($process->id == 37) {
                $yelpAPiKeys = YelpApi::get();

                foreach ($yelpAPiKeys as $yelpApiKey) {
                    ProcessQueue::where('process_id', '=', $process->id)->where('server_id','=',0)->delete();
                    $scheduleController->createQueueRecordsWithVariableIds($process->code, $yelpApiKey->id);
                }
            }
            else {
                ProcessQueue::where('process_id', '=', $process->id)->where('server_id','=',0)->delete();
                $scheduleController->createQueueRecord($process->code);
            }
        }
    }
}
