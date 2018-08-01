<?php namespace App\Model\CronJobs;

use Illuminate\Database\Eloquent\Model;

class CronJob extends Model {

    protected $table = 'cron_job';
    protected $connection = 'utility_db';

    public $incrementing = false;
    public $timestamps = false;
}
