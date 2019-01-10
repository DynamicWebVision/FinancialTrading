<?php namespace App\Http\Controllers\Equity;


use \DB;
use \Log;
use Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ServersController;
use App\Broker\TDAmeritrade;
use Illuminate\Support\Facades\Config;

use App\Model\Stocks\StocksDump;
use App\Model\Stocks\Stocks;
use App\Model\Stocks\StocksFundamentalData;
use App\Model\Servers;
use App\Services\Csv;

class StockFundamentalDataController extends Controller {

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
        Log::emergency('Keep Running Stock Fundamental Data Start');
        $serverController = new ServersController();
        $lastGitPullTime = $serverController->getLastGitPullTime();
        Config::set('last_git_pull_time', $lastGitPullTime);
        $this->keepRunningStartTime = time();
        Log::emergency('Keep Running Start Time '.$this->keepRunningStartTime);

        while ($this->keepRunningCheck) {
            if ($this->lastPullTime == time()) {
                sleep(2);
            }
            $this->lastPullTime = time();
            $this->pullOneStock();

            $lastGitPullTime = $serverController->getLastGitPullTime();
            $configLastGitPullTime = Config::get('last_git_pull_time');

            if ($lastGitPullTime != $configLastGitPullTime) {
                $this->keepRunningCheck = false;
            }

            if (($this->keepRunningStartTime + (15*60)) < time()) {
                $this->keepRunningCheck = false;
            }
        }
    }

    public function pullOneStock() {
        $stock = Stocks::orderBy('last_fundamental_pull')->first();
        $this->updateFundamentalData($stock);
        $stock->last_fundamental_pull = time();
        $stock->save();
    }

    public function updateFundamentalData($stock) {
        $tdAmeritrade = new TDAmeritrade();

        $response = $tdAmeritrade->getStockFundamentalData($stock->symbol);

        $response = $response->{$stock->symbol}->fundamental;

        $newStockFundamentalData = StocksFundamentalData::firstOrNew(['stock_id'=> $stock->id]);

        if (isset($response->high52)) {
            $newStockFundamentalData->high52 = $response->high52;
        }

        if (isset($response->low52)) {
            $newStockFundamentalData->low52 = $response->low52;
        }

        if (isset($response->dividendAmount)) {
            $newStockFundamentalData->dividend_amount = $response->dividendAmount;
        }

        if (isset($response->dividendYield)) {
            $newStockFundamentalData->dividend_yield = $response->dividendYield;
        }

        if (isset($response->dividendDate)) {
            $newStockFundamentalData->dividend_date = $response->dividendDate;
        }

        if (isset($response->peRatio)) {
            $newStockFundamentalData->pe_ratio = $response->peRatio;
        }

        if (isset($response->pegRatio)) {
            $newStockFundamentalData->peg_ratio = $response->pegRatio;
        }

        if (isset($response->pbRatio)) {
            $newStockFundamentalData->pb_ratio = $response->pbRatio;
        }

        if (isset($response->prRatio)) {
            $newStockFundamentalData->pr_ratio = $response->prRatio;
        }

        if (isset($response->pcfRatio)) {
            $newStockFundamentalData->pcf_ratio = $response->pcfRatio;
        }

        if (isset($response->grossMarginTTM)) {
            $newStockFundamentalData->gross_margin_ttm = $response->grossMarginTTM;
        }

        if (isset($response->grossMarginMRQ)) {
            $newStockFundamentalData->gross_margin_mrq = $response->grossMarginMRQ;
        }

        if (isset($response->netProfitMarginTTM)) {
            $newStockFundamentalData->net_profit_margin_ttm = $response->netProfitMarginTTM;
        }

        if (isset($response->netProfitMarginMRQ)) {
            $newStockFundamentalData->net_profit_margin_mrq = $response->netProfitMarginMRQ;
        }

        if (isset($response->operatingMarginTTM)) {
            $newStockFundamentalData->operating_margin_ttm = $response->operatingMarginTTM;
        }

        if (isset($response->operatingMarginMRQ)) {
            $newStockFundamentalData->operating_margin_mrq = $response->operatingMarginMRQ;
        }

        if (isset($response->returnOnEquity)) {
            $newStockFundamentalData->return_on_equity = $response->returnOnEquity;
        }

        if (isset($response->returnOnAssets)) {
            $newStockFundamentalData->return_on_assets = $response->returnOnAssets;
        }

        if (isset($response->returnOnInvestment)) {
            $newStockFundamentalData->return_on_investment = $response->returnOnInvestment;
        }

        if (isset($response->quickRatio)) {
            $newStockFundamentalData->quick_ratio = $response->quickRatio;
        }

        if (isset($response->currentRatio)) {
            $newStockFundamentalData->current_ratio = $response->currentRatio;
        }

        if (isset($response->interestCoverage)) {
            $newStockFundamentalData->interest_coverage = $response->interestCoverage;
        }

        if (isset($response->totalDebtToCapital)) {
            $newStockFundamentalData->total_debt_to_capital = $response->totalDebtToCapital;
        }

        if (isset($response->ltDebtToEquity)) {
            $newStockFundamentalData->lt_debt_to_equity = $response->ltDebtToEquity;
        }

        if (isset($response->totalDebtToEquity)) {
            $newStockFundamentalData->total_debt_to_equity = $response->totalDebtToEquity;
        }

        if (isset($response->epsTTM)) {
            $newStockFundamentalData->eps_ttm = $response->epsTTM;
        }

        if (isset($response->epsChangePercentTTM)) {
            $newStockFundamentalData->eps_change_percent_ttm = $response->epsChangePercentTTM;
        }

        if (isset($response->epsChangeYear)) {
            $newStockFundamentalData->eps_change_year = $response->epsChangeYear;
        }

        if (isset($response->epsChange)) {
            $newStockFundamentalData->eps_change = $response->epsChange;
        }

        if (isset($response->revChangeYear)) {
            $newStockFundamentalData->rev_change_year = $response->revChangeYear;
        }

        if (isset($response->revChangeTTM)) {
            $newStockFundamentalData->rev_change_ttm = $response->revChangeTTM;
        }

        if (isset($response->revChangeIn)) {
            $newStockFundamentalData->rev_change_in = $response->revChangeIn;
        }

        if (isset($response->sharesOutstanding)) {
            $newStockFundamentalData->shares_outstanding = $response->sharesOutstanding;
        }

        if (isset($response->marketCapFloat)) {
            $newStockFundamentalData->market_cap_float = $response->marketCapFloat;
        }

        if (isset($response->marketCap)) {
            $newStockFundamentalData->market_cap = $response->marketCap;
        }

        if (isset($response->bookValuePerShare)) {
            $newStockFundamentalData->book_value_per_share = $response->bookValuePerShare;
        }

        if (isset($response->shortIntToFloat)) {
            $newStockFundamentalData->short_int_to_float = $response->shortIntToFloat;
        }

        if (isset($response->shortIntDayToCover)) {
            $newStockFundamentalData->short_int_day_to_cover = $response->shortIntDayToCover;
        }

        if (isset($response->divGrowthRate3Year)) {
            $newStockFundamentalData->div_growth_rate3_year = $response->divGrowthRate3Year;
        }

        if (isset($response->dividendPayAmount)) {
            $newStockFundamentalData->dividend_pay_amount = $response->dividendPayAmount;
        }

        if (isset($response->dividendPayDate)) {
            $newStockFundamentalData->dividend_pay_date = $response->dividendPayDate;
        }

        if (isset($response->beta)) {
            $newStockFundamentalData->beta = $response->beta;
        }

        if (isset($response->vol1DayAvg)) {
            $newStockFundamentalData->vol1_day_avg = $response->vol1DayAvg;
        }

        if (isset($response->vol10DayAvg)) {
            $newStockFundamentalData->vol10_day_avg = $response->vol10DayAvg;
        }

        if (isset($response->vol3MonthAvg)) {
            $newStockFundamentalData->vol3_month_avg = $response->vol3MonthAvg;
        }



        $newStockFundamentalData->save();


    }
}