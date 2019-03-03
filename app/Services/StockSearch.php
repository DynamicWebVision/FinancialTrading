<?php namespace App\Services;

use Illuminate\Support\Facades\DB;

class StockSearch  {

    protected $searchObject;
    public $criteria;
    const RESULT_COUNT = 25;
    public $currentPage;

    protected $selectArray = ['stocks.id',
        'stocks.symbol',
        'stocks.name',
        'stocks.last_sale',
        'stocks.market_cap',
        'stocks.ipo_year',
        'stocks_company_profile.exchange',
        'stocks_company_profile.website',
        'stocks_company_profile.description',
        'stocks_company_profile.ceo',
        'stocks_company_profile.issue_type',
        'stocks_fundamental.high52',
        'stocks_fundamental.low52',
        'stocks_fundamental.dividend_amount',
        'stocks_fundamental.dividend_yield',
        'stocks_fundamental.dividend_date',
        'stocks_fundamental.pe_ratio',
        'stocks_fundamental.peg_ratio',
        'stocks_fundamental.pb_ratio',
        'stocks_fundamental.pr_ratio',
        'stocks_fundamental.pcf_ratio',
        'stocks_fundamental.gross_margin_ttm',
        'stocks_fundamental.gross_margin_mrq',
        'stocks_fundamental.net_profit_margin_ttm',
        'stocks_fundamental.net_profit_margin_mrq',
        'stocks_fundamental.operating_margin_ttm',
        'stocks_fundamental.operating_margin_mrq',
        'stocks_fundamental.return_on_equity',
        'stocks_fundamental.return_on_assets',
        'stocks_fundamental.return_on_investment',
        'stocks_fundamental.quick_ratio',
        'stocks_fundamental.current_ratio',
        'stocks_fundamental.interest_coverage',
        'stocks_fundamental.total_debt_to_capital',
        'stocks_fundamental.lt_debt_to_equity',
        'stocks_fundamental.total_debt_to_equity',
        'stocks_fundamental.eps_ttm',
        'stocks_fundamental.eps_change_percent_ttm',
        'stocks_fundamental.eps_change_year',
        'stocks_fundamental.eps_change',
        'stocks_fundamental.rev_change_year',
        'stocks_fundamental.rev_change_ttm',
        'stocks_fundamental.rev_change_in',
        'stocks_fundamental.shares_outstanding',
        'stocks_fundamental.market_cap_float',
        'stocks_fundamental.market_cap',
        'stocks_fundamental.book_value_per_share',
        'stocks_fundamental.short_int_to_float',
        'stocks_fundamental.short_int_day_to_cover',
        'stocks_fundamental.div_growth_rate3_year',
        'stocks_fundamental.dividend_pay_amount',
        'stocks_fundamental.dividend_pay_date',
        'stocks_fundamental.beta',
        'stocks_fundamental.vol1_day_avg',
        'stocks_fundamental.vol10_day_avg',
        'stocks_sector.id as sector_id',
        'stocks_sector.name as sector_name',
        'stocks_industry.id as industry_id',
        'stocks_industry.name as industry_name',
        'stocks_book.open',
        'stocks_book.close',
        'stocks_book.high',
        'stocks_book.low',
        'stocks_book.latest_price',
        'stocks_book.latest_volume',
        'stocks_book.change_price',
        'stocks_book.change_percent',
        'stocks_book.avg_total_volume',
        'stocks_book.market_cap',
        'stocks_book.week52_high',
        'stocks_book.week52_low',
        'stocks_book.ytd_change'
        ];

    public function setQuery() {
        $this->searchObject = DB::table('stocks')
            ->join('stocks_company_profile', 'stocks.id', '=', 'stocks_company_profile.stock_id')
            ->join('stocks_fundamental', 'stocks.id', '=', 'stocks_fundamental.stock_id')
            ->join('stocks_book', 'stocks.id', '=', 'stocks_book.stock_id')
            ->join('stocks_industry', 'stocks_company_profile.industry_id', '=', 'stocks_industry.id')
            ->join('stocks_sector', 'stocks_company_profile.sector_id', '=', 'stocks_sector.id');

        $this->sectorCriteria();
        $this->industryCriteria();
        $this->symbolCriteria();
        $this->nameCriteria();
        $this->setOrderDirection();
    }

    protected function sectorCriteria() {
        if ($this->criteria['sector'] > 0) {
            $this->searchObject->where('stocks_sector.id', '=', $this->criteria['sector']);
        }
    }

    protected function industryCriteria() {
        if ($this->criteria['industry'] > 0) {
            $this->searchObject->where('stocks_industry.id', '=', $this->criteria['industry']);
        }
    }

    protected function symbolCriteria() {
        if (strlen($this->criteria['symbol']) > 0) {
            $this->searchObject->where('stocks.symbol', '=', $this->criteria['symbol']);
        }
    }

    protected function nameCriteria() {
        if (strlen($this->criteria['name']) > 0) {
            $this->searchObject->where('stocks.name', 'like', '%' . $this->criteria['name'] . '%');
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
        return ($this->currentPage-1)*self::RESULT_COUNT;
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
