<?php namespace App\Services\ProcessLog;

use \App\Model\ProcessLog\ProcessLog;
use \App\Model\ProcessLog\ProcessLogMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use \App\Services\Utility;
use \App\Services\AwsService;
use App\Model\ProcessLog\Process;
use App\Http\Controllers\ServersController;

class ProcessLogFilter  {

    public $data;

    protected $searchObject;
    public $criteria;
    const RESULT_COUNT = 25;
    public $currentPage;

    protected $selectArray = [
        'process_log.id',
        'process_log.process_id',
        'process_log.start_date_time',
        'process_log.end_date_time',
        'process_log.updated_at',
        'process_log.created_at',
        'process_log.server_address',
        'process_log.server_id',
        //'process_log.process_queue_id',
        'process_log.linux_pid',
        'process_log.forced_end',
        'process_log.has_error',
        'servers.id as server_id',
        'servers.name as server_name',
        'process.name as process_name',
        'process.id as process_id',
    ];

    public function setQuery() {

        $this->searchObject = DB::table('process_log')
            ->join('servers', 'process_log.server_id', '=', 'servers.id')
            ->join('process', 'process_log.process_id', '=', 'process.id');

        $this->serverCriteria();
        $this->errorCriteria();
        $this->processCriteria();

        $this->setOrderDirection();
    }

    protected function serverCriteria() {
        if (isset($this->criteria['selectedServer'])) {
            if (isset($this->criteria['selectedServer']['id'])) {
                $this->searchObject->where('process_log.server_id', '=', $this->criteria['selectedServer']['id']);
            }
        }
    }

    protected function errorCriteria() {
        if (isset($this->criteria['errors'])) {
            $this->searchObject->where('process_log.has_error', '=', 1);
        }
    }

    protected function processCriteria() {
        if (isset($this->criteria['selectedProcess'])) {
            if (isset($this->criteria['selectedProcess']['id'])) {
                $this->searchObject->where('process_log.process_id', '=', $this->criteria['selectedProcess']['id']);
            }
        }
    }

    public function getResultTotalCount() {
        return $this->searchObject->count();
    }

    public function setOrderDirection() {
        if ($this->criteria['orderDirection'] == 1) {
            $this->criteria['orderDirection'] = 'asc';
        }
        else {
            $this->criteria['orderDirection'] = 'desc';
        }
    }

    public function getSkip() {
        return ($this->criteria['currentPage']-1)*self::RESULT_COUNT;
    }

    public function getCurrentResult() {
        $skip = $this->getSkip();
        return $this->searchObject->select($this->selectArray)
            ->skip($skip)
            ->take(self::RESULT_COUNT)
            ->orderBy($this->criteria['orderBy'], $this->criteria['orderDirection'])
            ->get()->toArray();
    }
}
