<?php namespace App\Http\Controllers;

use \DB;
use \Log;
use Request;

use \App\Model\StrategyLog;
use \App\Model\StrategyLogIndicators;
use \App\Model\StrategyLogMessage;
use \App\Model\StrategyLogRates;
use \App\Services\Utility;
use \App\Model\StrategyLogApi;


use \App\Model\Exchange;
use \App\Model\OandaAccounts;


class StrategyLogController extends Controller {

    public $logQuery;
    public $orderDesc = true;

    public $pageCount = 10;

    public function index() {
        $response = [];
        $response['exchanges'] = Exchange::get()->toArray();
        $response['exchanges'][-1] = ['id'=> -1, 'exchange' => 'ALL'];
        $response['accounts'] = OandaAccounts::get()->toArray();
        return $response;
    }

    public function buildQuery($account, $exchange, $dateTime, $onlyEvents) {
        $this->logQuery = DB::table('strategy_log')
            ->join('exchange', 'strategy_log.exchange_id', '=', 'exchange.id')
            ->where('oanda_account_id', '=', $account);

        if ($exchange != -1) {
            $this->logQuery = $this->logQuery->where('exchange_id', '=', $exchange);
        }

        if (strlen($dateTime) > 5) {
            $this->logQuery = $this->logQuery->where('start_date_time', '>=', $dateTime);
            $this->orderDesc = false;
        }

        if ($onlyEvents == 1) {
            $this->logQuery = $this->logQuery->where('decision_made', '!=', 'none');
        }
    }

    public function getLogs($account, $exchange, $dateTime, $onlyEvents) {
        $this->buildQuery($account, $exchange, $dateTime, $onlyEvents);

        $recordCount = $this->getLogCount();
        $logs = $this->getCurrentLogs(0);

        return ['count'=>$recordCount, 'logs'=>$logs];
    }

    public function getLogsSubset($pageNumber, $account, $exchange, $dateTime, $onlyEvents) {
        $this->buildQuery($account, $exchange, $dateTime, $onlyEvents);
        $logs = $this->getCurrentLogs($this->pageCount*$pageNumber);
        return ['logs'=>$logs];
    }


    public function getLogCount() {
        $countQuery = $this->logQuery;
        return $countQuery->count();
    }

    public function getCurrentLogs($skipAmount) {
        $this->logQuery = $this->logQuery
            ->skip($skipAmount);

        if ($this->orderDesc) {
            $this->logQuery = $this->logQuery->orderBy('start_date_time', 'desc');
        }
        else {
            $this->logQuery = $this->logQuery->orderBy('start_date_time');
        }

        return $this->logQuery
            ->take($this->pageCount)
            ->get(['strategy_log.id',
                'strategy_log.run_id',
                'strategy_log.start_date_time',
                'strategy_log.end_date_time',
                'strategy_log.method',
                'strategy_log.oanda_account_id',
                'strategy_log.exchange_id',
                'strategy_log.decision_type',
                'strategy_log.decision_made',
                'exchange.exchange']);
    }

    public function getLogMessages($logId) {
        return StrategyLogMessage::where('log_id', '=', $logId)->orderBy('updated_at')->get()->toArray();
    }

    public function getLogIndicators($logId) {
        $indicators = StrategyLogIndicators::where('log_id', '=', $logId)->first(['indicators']);
        return json_decode($indicators->indicators);
    }

    public function getLogApi($logId) {
        return StrategyLogApi::where('log_id', '=', $logId)->orderBy('updated_at')->get()->toArray();
    }
}