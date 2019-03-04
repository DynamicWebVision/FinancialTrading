<?php namespace App\Http\Controllers\Equity;


use \DB;
use \Log;
use Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ServersController;
use App\Broker\IexTrading;
use Illuminate\Support\Facades\Config;

use App\Model\Stocks\StocksDump;
use App\Model\Stocks\Stocks;
use App\Model\Stocks\StocksCompanyFinancialsAnnual;
use App\Model\Stocks\StocksCompanyFinancialsQuarter;
use App\Model\Servers;
use App\Services\Csv;

class StocksFinancialsController extends Controller {

    public $symbol = false;
    public $stockId = false;
    public $year = false;
    public $keepRunningCheck = true;
    public $lastPullTime = 0;
    public $keepRunningStartTime = 0;

    public function __construct() {
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $serverController = new ServersController();

        $serverController->setServerId();
    }

    public function keepRunning() {
        Log::emergency('Keep Running Stock Financials Start');
        $serverController = new ServersController();
        $lastGitPullTime = $serverController->getLastGitPullTime();
        Config::set('last_git_pull_time', $lastGitPullTime);
        $this->keepRunningStartTime = time();
        $currentDay = date('z') + 1;
        Log::emergency('Keep Running Start Time '.$this->keepRunningStartTime);
        $stocksToPullCount = StocksApiJobs::where('last_fin_pull', '!=', $currentDay)->count();

        while ($this->keepRunningCheck && $stocksToPullCount > 0) {
            $this->lastPullTime = time();
            $this->pullOneStock($currentDay);

            $lastGitPullTime = $serverController->getLastGitPullTime();
            $configLastGitPullTime = Config::get('last_git_pull_time');

            if ($lastGitPullTime != $configLastGitPullTime) {
                $this->keepRunningCheck = false;
            }

            $stocksToPullCount = StocksApiJobs::where('last_fin_pull', '!=', $currentDay)->count();
        }
        Log::emergency('Keep Running Stock Financials End');
    }

    public function pullOneStock($currentDay) {
        $stock = Stocks::orderBy('last_fin_pull')->first();
        $this->updateFinancialDataAnnual($stock);
        $this->updateFinancialDataQuarter($stock);
        $stock->last_fin_pull = $currentDay;
        $stock->save();
    }

    public function updateFinancialDataAnnual($stock) {
        $ieTrading = new IexTrading();


        $response = $ieTrading->getCompanyFinancials($stock->symbol, 'annual');

        if (!$response) {
            return false;
        }

        foreach ($response as $financial) {
            $newStockFinancialsAnnual = StocksCompanyFinancialsAnnual::firstOrNew(['stock_id'=> $stock->id, 'report_date'=> $financial->reportDate]);

            if (isset($financial->reportDate)) {
                $newStockFinancialsAnnual->report_date = $financial->reportDate;
            }

            if (isset($financial->grossProfit)) {
                $newStockFinancialsAnnual->gross_profit = $financial->grossProfit;
            }

            if (isset($financial->costOfRevenue)) {
                $newStockFinancialsAnnual->cost_of_revenue = $financial->costOfRevenue;
            }

            if (isset($financial->operatingRevenue)) {
                $newStockFinancialsAnnual->operating_revenue = $financial->operatingRevenue;
            }

            if (isset($financial->totalRevenue)) {
                $newStockFinancialsAnnual->total_revenue = $financial->totalRevenue;
            }

            if (isset($financial->operatingIncome)) {
                $newStockFinancialsAnnual->operating_income = $financial->operatingIncome;
            }

            if (isset($financial->netIncome)) {
                $newStockFinancialsAnnual->net_income = $financial->netIncome;
            }

            if (isset($financial->researchAndDevelopment)) {
                $newStockFinancialsAnnual->research_and_development = $financial->researchAndDevelopment;
            }

            if (isset($financial->operatingExpense)) {
                $newStockFinancialsAnnual->operating_expense = $financial->operatingExpense;
            }

            if (isset($financial->currentAssets)) {
                $newStockFinancialsAnnual->current_assets = $financial->currentAssets;
            }

            if (isset($financial->totalAssets)) {
                $newStockFinancialsAnnual->total_assets = $financial->totalAssets;
            }

            if (isset($financial->totalLiabilities)) {
                $newStockFinancialsAnnual->total_liabilities = $financial->totalLiabilities;
            }

            if (isset($financial->currentCash)) {
                $newStockFinancialsAnnual->current_cash = $financial->currentCash;
            }

            if (isset($financial->currentDebt)) {
                $newStockFinancialsAnnual->current_debt = $financial->currentDebt;
            }

            if (isset($financial->totalCash)) {
                $newStockFinancialsAnnual->total_cash = $financial->totalCash;
            }

            if (isset($financial->totalDebt)) {
                $newStockFinancialsAnnual->total_debt = $financial->totalDebt;
            }

            if (isset($financial->shareholderEquity)) {
                $newStockFinancialsAnnual->shareholder_equity = $financial->shareholderEquity;
            }

            if (isset($financial->cashChange)) {
                $newStockFinancialsAnnual->cash_change = $financial->cashChange;
            }

            if (isset($financial->cashFlow)) {
                $newStockFinancialsAnnual->cash_flow = $financial->cashFlow;
            }

            if (isset($financial->operatingGainsLosses)) {
                $newStockFinancialsAnnual->operating_gains_losses = $financial->operatingGainsLosses;
            }
            $newStockFinancialsAnnual->save();
        }
    }

    public function getFinancial($stockId) {
        $financials = StocksCompanyFinancialsAnnual::where('stock_id', '=', $stockId)->get()->toArray();

        return $financials;
    }
}