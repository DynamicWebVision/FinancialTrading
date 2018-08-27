<?php namespace App\Services;

use \App\Model\StrategyLog;
use \App\Model\StrategyLogIndicators;
use \App\Model\StrategyLogMessage;
use \App\Model\StrategyLogRates;
use \App\Services\Utility;
use \App\Model\StrategyLogApi;

class StrategyLogger  {

    public $runId;
    public $method;
    public $oanda_account_id;
    public $exchange_id;

    public $logId;
    public $apiId;

    public $loggingOn;

    public function __construct() {
        $this->utility = new Utility();

        if (env('LOG_STRATEGY') == 1) {
            $this->loggingOn = true;
        }
        else {
            $this->loggingOn = false;
        }
    }

    public function newStrategyLog() {
        if ($this->loggingOn) {
            $newStrategyLog = new StrategyLog();

            $newStrategyLog->start_date_time = $this->utility->mysqlDateTime();
            $newStrategyLog->method = $this->method;
            $newStrategyLog->oanda_account_id = $this->oanda_account_id;
            $newStrategyLog->exchange_id = $this->exchange_id;

            $newStrategyLog->save();

            $this->logId = $newStrategyLog->id;
        }
    }

    public function logMessage($message, $type = 1) {
        if ($this->loggingOn) {
            $logMessage = new StrategyLogMessage();
            $logMessage->log_id = $this->logId;
            $logMessage->message = $message;
            $logMessage->message_type_id = $type;
            $logMessage->save();
        }
    }

    public function logIndicators($indicators) {
        if ($this->loggingOn) {
            $logIndicators = new StrategyLogIndicators();
            $logIndicators->log_id = $this->logId;
            $logIndicators->indicators = json_encode($indicators);
            $logIndicators->save();
        }
    }

    public function logRates($rates) {
        if ($this->loggingOn) {

            if (isset($rates['full'])) {
                $rates = $this->utility->getLastXElementsInArray($rates['full'], 10);
            }
            else {
                $rates = $this->utility->getLastXElementsInArray($rates, 10);
            }

            $logIndicators = new StrategyLogRates();
            $logIndicators->log_id = $this->logId;
            $logIndicators->rates = json_encode($rates);
            $logIndicators->save();
        }
    }

    public function logDecisionType($decisionType) {
        if ($this->loggingOn) {
            $logStrategy = StrategyLog::find($this->logId);
            $logStrategy->decision_type = $decisionType;
            $logStrategy->save();
        }
    }

    public function logDecisionMade($decisionMade) {
        if ($this->loggingOn) {
            $logStrategy = StrategyLog::find($this->logId);
            $logStrategy->decision_made = $decisionMade;
            $logStrategy->save();

            $this->logMessage('Made Decision '.$decisionMade, 1);
        }
    }

    public function logApiRequestStart($url, $fields, $action) {
        if ($this->loggingOn) {
            $logApi = new StrategyLogApi();
            $logApi->log_id = $this->logId;
            $logApi->url = $url;
            $logApi->action = $action;
            $logApi->fields = json_encode($fields);
            $logApi->save();

            $this->apiId = $logApi->id;
        }
    }

    public function logApiRequestResponse($response) {
        if ($this->loggingOn) {
            $logApi = StrategyLogApi::find($this->apiId);
            $logApi->response = json_encode($response);
            $logApi->save();
        }
    }

    public function logStrategyEnd() {
        if ($this->loggingOn) {
            $logStrategy = StrategyLog::find($this->logId);
            $logStrategy->end_date_time = $this->utility->mysqlDateTime();
            $logStrategy->save();
        }
    }
}
