<?php namespace App\Services;

use \App\Model\ProcessLog\ProcessLog;
use \App\Model\ProcessLog\ProcessLogMessage;

use Illuminate\Support\Facades\Config;
use \App\Services\Utility;
use \App\Services\AwsService;

class ProcessLogger  {

    public $runId;
    public $method;
    public $oanda_account_id;
    public $exchange_id;

    public $logId;
    public $apiId;

    public $loggingOn;

    public function __construct($processId) {
        $this->utility = new Utility();
        $this->newStrategyLog($processId);
    }

    public function newStrategyLog($processId) {
        $awsService = new AwsService();
        $ipAddress = $awsService->getCurrentInstanceIp();
        $serverId = Config::get('server_id');

        $newProcessLog = new ProcessLog();

        $newProcessLog->start_date_time = $this->utility->mysqlDateTime();

        $newProcessLog->process_id = $processId;
        $newProcessLog->server_address = $ipAddress;
        $newProcessLog->server_id = $serverId;

        $newProcessLog->save();

        $this->logId = $newProcessLog->id;
        Config::set('process_log_id', $newProcessLog->id);
    }

    public function logMessage($message, $type = 4) {
            $logMessage = new ProcessLogMessage();
            $logMessage->process_log_id = $this->logId;
            $logMessage->message = $message;
            $logMessage->message = $message;
            $logMessage->message_type_id = $type;
            $logMessage->save();
    }

    public function processEnd() {
        $logStrategy = ProcessLog::find($this->logId);
        $logStrategy->end_date_time = $this->utility->mysqlDateTime();
        $logStrategy->save();
    }

    public function setRelevantId($id) {
        Config::set('process_log_relevant_id', $id);
    }
}
