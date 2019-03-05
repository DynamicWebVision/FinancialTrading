<?php namespace App\Http\Controllers;

use \DB;
use \Log;
use Request;

use \App\Model\Process;
use \App\Model\ProcessLog\ProcessLog;
use \App\Model\ProcessLog\ProcessLogMessage;


class ProcessLogController extends Controller {

    public function deleteOldProcessLogs() {
        $processes = Process::get()->toArray();
        $today = date('Y-m-d H:i:s', time());

        foreach ($processes as $process) {
            $cutoffDate = date('Y-m-d H:i:s', strtotime($today. ' - '.$process['days_to_keep'].' days'));
            $processLogs = ProcessLog::where('end_date_time', '<', $cutoffDate)->get()->toArray();

            foreach($processLogs as $processLog) {
                ProcessLogMessage::where('process_log_id', '=', $processLog['id'])->delete();
                ProcessLog::destroy($processLog['id']);
            }
        }
    }
}