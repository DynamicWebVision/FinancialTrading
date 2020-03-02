<?php namespace App\Http\Controllers;

use \DB;
use \Log;
use Request;

use \App\Model\ProcessLog\Process;
use \App\Model\ProcessLog\ProcessLog;
use \App\Model\ProcessLog\ProcessQueue;
use \App\Model\ProcessLog\ProcessLogMessageType;

use \App\Model\ProcessLog\ProcessLogMessage;
use \App\Model\Servers;
use App\Services\ProcessLogger;
use App\Services\ProcessLog\ProcessLogFilter;
use App\Services\ProcessLog\ProcessLogMessagesFilter;

class ProcessLogController extends Controller {

    public $logger;

    public function index() {
        $processLogFilter = new ProcessLogFilter();
        $processLogFilter->criteria = ['errors'=>true];
        $processLogFilter->criteria['orderDirection'] = 1;
        $processLogFilter->criteria['orderBy'] = 'start_date_time';
        $processLogFilter->criteria['currentPage'] = 1;
        $processLogFilter->criteria['errors'] = 1;
        $processLogFilter->setQuery();
        $processLogFilter->setOrderDirection();
        $logs = $processLogFilter->getCurrentResult();

        return ['message_types'=> ProcessLogMessageType::get()->toArray(),
            'processes'=>Process::get()->toArray(),
            'servers'=>Servers::get()->toArray(),
            'logResults'=>$logs,
            'recordCount'=>$processLogFilter->getResultTotalCount(),
        ];
    }

    public function deleteOldProcessLogs() {
        $this->logger = new ProcessLogger('delete_process_logs');

        ini_set('memory_limit', '2048M');
        $processes = Process::get()->toArray();
        $today = date('Y-m-d H:i:s', time());

        //Loop Through All Processes to Get Log to Keep Cutoff
        foreach ($processes as $process) {
            $cutoffDate = date('Y-m-d H:i:s', strtotime($today. ' - '.$process['days_to_keep'].' days'));

            $this->logger->logMessage('Checking for Process '.$process['name'].' with cutoff date '.$cutoffDate);

            $processToBeDeletedCount = ProcessLog::where('start_date_time', '<', $cutoffDate)
                ->where('process_id', '=', $process['id'])->count();

            //Loop Through Specific Process for Logs to Delete in Batches of 1000
            while ($processToBeDeletedCount > 0) {
                $processLogsToBeDeleted = ProcessLog::where('start_date_time', '<', $cutoffDate)
                    ->where('process_id', '=', $process['id'])->take(1000)->get(['id'])->toArray();

                $this->logger->logMessage('Deleting another 1000 Logs for Process Id '.$process['name']);

                //Loop Through Specific Process for Logs to Delete
                foreach($processLogsToBeDeleted as $processLog) {
                    ProcessLogMessage::where('process_log_id', '=', $processLog['id'])->delete();
                    ProcessLog::destroy($processLog['id']);
                    ProcessQueue::destroy($processLog['process_queue_id']);
                }
                //Getting Count of Process Logs to be Deleted after last batch
                $processToBeDeletedCount = ProcessLog::where('start_date_time', '<', $cutoffDate)
                    ->where('process_id', '=', $process['id'])->count();
            }
        }
        $this->logger->processEnd();
    }

    public function getLogs() {
        $data = Request::all();

        $processLogFilter = new ProcessLogFilter();
        $processLogFilter->criteria = $data;
        $processLogFilter->criteria['orderDirection'] = $data['orderDirection'];
        $processLogFilter->criteria['orderBy'] = $data['orderBy'];

        $processLogFilter->setQuery();
        $processLogFilter->setOrderDirection();
        $logs = $processLogFilter->getCurrentResult();
        $count = $processLogFilter->getResultTotalCount();

        return ['logs'=>$logs, 'count'=>$count];
    }

    public function getLogMessages() {
        $data = Request::all();
        $response = [];

        $messagesFilter = new ProcessLogMessagesFilter();
        $messagesFilter->criteria = $data;
        $messagesFilter->setQuery();

        if (!$data['total_count']) {
            $response['total_count'] = $messagesFilter->getResultTotalCount();
        }
        else {
            $response['total_count'] = $data['total_count'];
        }

        \DB::enableQueryLog();

        $response['messages'] = $messagesFilter->getCurrentResult();

        $logs = \DB::getQueryLog();

        return $response;
    }
}