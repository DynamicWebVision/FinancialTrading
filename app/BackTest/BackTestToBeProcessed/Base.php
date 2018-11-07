<?php namespace App\BackTest\BackTestToBeProcessed;

use \DB;

use \Log;
use App\Services\Utility;
use App\Model\BackTestToBeProcessed;


use Illuminate\Support\Facades\Config;

abstract class Base  {
    public $setProcessId = false;
    public $groupId;
    public $backTestToBeProcessed;
    public $utility;

    public function __construct($processId, $server) {
        //Set it so there is no process timeout
        set_time_limit(0);

        $this->utility = new Utility();
       // $utility->recursiveClearDirectoryFiles('/var/www/html/CurrencyTrading/storage/logs');
        $this->server = $server;
        /******************************
         * RECORD START
         ******************************/
        $this->groupId = $this->server->current_back_test_group_id;
        $this->start($processId);
    }

    public function __destruct() {
        $utility = new Utility();
       // $utility->recursiveClearDirectoryFiles('/var/www/html/CurrencyTrading/storage/logs');

        $this->backTestToBeProcessed->finish = 1;

        $this->backTestToBeProcessed->save();

        Log::debug('FINISH Back Test Group ID '.$this->backTestToBeProcessed->back_test_group_id.'Process ID '.$this->backTestToBeProcessed->id);
    }

    public function start($processId = false) {
        if ($processId) {
            $this->backTestToBeProcessed = BackTestToBeProcessed::find($processId);
        }
        else {
            $this->backTestToBeProcessed = BackTestToBeProcessed::where('back_test_group_id', '=', $this->groupId)
                ->where('finish', '=', 0)
                ->where('start', '=', 0)
                ->first();
        }

        $this->backTestToBeProcessed->start = 1;

        $this->backTestToBeProcessed->save();

        Config::set('back_test_process_id', $this->backTestToBeProcessed->id);
        Config::set('back_test_job', 'full_test_run');

        Log::emergency('START Back Test Group ID '.$this->backTestToBeProcessed->back_test_group_id.'Process ID '.$this->backTestToBeProcessed->id);
    }

    public function returnMax($values) {
        return intval(round(max($values)));
    }
}