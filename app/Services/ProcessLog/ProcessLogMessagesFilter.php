<?php namespace App\Services\ProcessLog;

use \App\Model\ProcessLog\ProcessLog;
use \App\Model\ProcessLog\ProcessLogMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use \App\Services\Utility;
use \App\Services\AwsService;
use App\Model\ProcessLog\Process;
use App\Http\Controllers\ServersController;

class ProcessLogMessagesFilter  {

    public $data;

    protected $searchObject;
    public $criteria;
    const RESULT_COUNT = 10;
    public $currentPage;

    protected $selectArray = [
        'process_log_message.*',
    ];

    public function setQuery() {

        $this->searchObject = DB::table('process_log_message')
                                ->where('process_log_id', '=', $this->criteria['log_id']);

        $this->errorCriteria();
        $this->searchCriteria();
    }

    protected function errorCriteria() {
        if ($this->criteria['error_check']) {
            $this->searchObject->where('process_log_message.message_type_id', '=', 1);
        }
    }

    protected function searchCriteria() {
        if (strlen($this->criteria['search_text']) > 0) {
            $this->searchObject->whereRaw("lower(process_log_message.message) like ?", ["%".strtolower($this->criteria['search_text'])."%"]);
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
        return ($this->criteria['current_page']-1)*self::RESULT_COUNT;
    }

    public function getCurrentResult() {
        $skip = $this->getSkip();
        return $this->searchObject->select($this->selectArray)
            ->skip($skip)
            ->take(self::RESULT_COUNT)
            ->get()->toArray();
    }
}
