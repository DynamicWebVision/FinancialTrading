<?php namespace App\Services;

class CronJobs {

    public $cronId;

    public function __construct($cronId) {
        $this->cronId = $cronId;
        $this->start();
    }

    public function start() {
        $cron = \App\Model\CronJobs\CronJob::find($this->cronId);

        $currentTime = \Carbon\Carbon::now();
        $cron->last_start = $currentTime;
        $cron->save();
    }

    public function end($records) {
        $cron = \App\Model\CronJobs\CronJob::find($this->cronId);

        $currentTime = \Carbon\Carbon::now();
        $cron->last_end = $currentTime;
        $cron->save();

        $cronComplete = new \App\Model\CronJobs\CronJobComplete();

        $cronComplete->cron_job_id = $this->cronId;
        $cronComplete->records_processed = $records;

        $cronComplete->save();
    }
}
