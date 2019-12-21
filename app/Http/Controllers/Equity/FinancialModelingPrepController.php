<?php namespace App\Http\Controllers\Equity;


use \DB;
use \Log;
use Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ProcessScheduleController;
use App\Model\Stocks\StocksIndustry;
use App\Model\Stocks\Stocks;
use App\Model\Stocks\StocksIncomeStatements;
use App\Services\FinancialModelingPrep;
use App\Services\ProcessLogger;

class FinancialModelingPrepController extends Controller {
    protected $logger;

    public function stockIncomeStatements($stockId) {
        $this->logger = new ProcessLogger('fmp_income_statements');

        $stock = Stocks::find($stockId);

        $this->logger->logMessage('IncomeStatement For '.$stock->id.' '.$stock->symbol.' '.$stock->name);

        $financialModelingPrep = new FinancialModelingPrep();

        $incomeStatements = $financialModelingPrep->annualIncomeStatements($stock->symbol);

        foreach ($incomeStatements as $incomeStatement) {
            $this->saveIncomeStatement($stock, $incomeStatement);

        }
    }

    public function createWeeklyFinancialJobs() {
        $this->createProcessForAllStocksAndGivenCode('fmp_income_statements');
    }

    public function createProcessForAllStocksAndGivenCode($code) {
        $this->logger = new ProcessLogger('fmp_income_statements');

        $stocks = Stocks::where('initial_daily_load','=', 1)->get()->toArray();

        $ids = array_column($stocks, 'id');

        $scheduler = new ProcessScheduleController();

        $scheduler->createQueueRecordsWithVariableIds($code, $ids);
    }

    protected function saveIncomeStatement($stock, $incomeStatement) {
        try {
            $newIncomeStatement = StocksIncomeStatements::firstOrNew(['statement_date'=>$incomeStatement->{"date"}, 'stock_id'=>$stock->id]);

            if (isset($incomeStatement->{"date"})) {
                $newIncomeStatement->statement_date = $incomeStatement->{"date"};
            }

            if (isset($incomeStatement->{"Revenue"})) {
                if (is_numeric($incomeStatement->{"Revenue"})) {
                    $newIncomeStatement->revenue = $incomeStatement->{"Revenue"};
                }

            }

            if (isset($incomeStatement->{"Revenue Growth"})) {
                if (is_numeric($incomeStatement->{"Revenue Growth"})) {
                    $newIncomeStatement->revenue_growth = $incomeStatement->{"Revenue Growth"};
                }

            }

            if (isset($incomeStatement->{"Cost of Revenue"})) {
                if (is_numeric($incomeStatement->{"Cost of Revenue"})) {
                    $newIncomeStatement->cost_of_revenue = $incomeStatement->{"Cost of Revenue"};
                }

            }

            if (isset($incomeStatement->{"Gross Profit"})) {
                if (is_numeric($incomeStatement->{"Gross Profit"})) {
                    $newIncomeStatement->gross_profit = $incomeStatement->{"Gross Profit"};
                }

            }

            if (isset($incomeStatement->{"R&D Expenses"})) {
                if (is_numeric($incomeStatement->{"R&D Expenses"})) {
                    $newIncomeStatement->r_d_expenses = $incomeStatement->{"R&D Expenses"};
                }

            }

            if (isset($incomeStatement->{"SG&A Expense"})) {
                if (is_numeric($incomeStatement->{"SG&A Expense"})) {
                    $newIncomeStatement->sga_expense = $incomeStatement->{"SG&A Expense"};
                }

            }

            if (isset($incomeStatement->{"Operating Expenses"})) {
                if (is_numeric($incomeStatement->{"Operating Expenses"})) {
                    $newIncomeStatement->operating_expenses = $incomeStatement->{"Operating Expenses"};
                }

            }

            if (isset($incomeStatement->{"Operating Income"})) {
                if (is_numeric($incomeStatement->{"Operating Income"})) {
                    $newIncomeStatement->operating_income = $incomeStatement->{"Operating Income"};
                }

            }

            if (isset($incomeStatement->{"Interest Expense"})) {
                if (is_numeric($incomeStatement->{"Interest Expense"})) {
                    $newIncomeStatement->interest_expense = $incomeStatement->{"Interest Expense"};
                }

            }

            if (isset($incomeStatement->{"Earnings before Tax"})) {
                if (is_numeric($incomeStatement->{"Earnings before Tax"})) {
                    $newIncomeStatement->earnings_before_tax = $incomeStatement->{"Earnings before Tax"};
                }

            }

            if (isset($incomeStatement->{"Income Tax Expense"})) {
                if (is_numeric($incomeStatement->{"Income Tax Expense"})) {
                    $newIncomeStatement->income_tax_expense = $incomeStatement->{"Income Tax Expense"};
                }

            }

            if (isset($incomeStatement->{"Net Income - Non-Controlling int"})) {
                if (is_numeric($incomeStatement->{"Net Income - Non-Controlling int"})) {
                    $newIncomeStatement->net_income_non_controlling = $incomeStatement->{"Net Income - Non-Controlling int"};
                }

            }

            if (isset($incomeStatement->{"Net Income - Discontinued ops"})) {
                if (is_numeric($incomeStatement->{"Net Income - Discontinued ops"})) {
                    $newIncomeStatement->net_income_discontinued_ops = $incomeStatement->{"Net Income - Discontinued ops"};
                }

            }

            if (isset($incomeStatement->{"Net Income"})) {
                if (is_numeric($incomeStatement->{"Net Income"})) {
                    $newIncomeStatement->net_income = $incomeStatement->{"Net Income"};
                }

            }

            if (isset($incomeStatement->{"Preferred Dividends"})) {
                if (is_numeric($incomeStatement->{"Preferred Dividends"})) {
                    $newIncomeStatement->preferred_divs = $incomeStatement->{"Preferred Dividends"};
                }

            }

            if (isset($incomeStatement->{"Net Income Com"})) {
                if (is_numeric($incomeStatement->{"Net Income Com"})) {
                    $newIncomeStatement->net_income_com = $incomeStatement->{"Net Income Com"};
                }

            }

            if (isset($incomeStatement->{"EPS"})) {
                if (is_numeric($incomeStatement->{"EPS"})) {
                    $newIncomeStatement->eps = $incomeStatement->{"EPS"};
                }

            }

            if (isset($incomeStatement->{"EPS Diluted"})) {
                if (is_numeric($incomeStatement->{"EPS Diluted"})) {
                    $newIncomeStatement->eps_deluted = $incomeStatement->{"EPS Diluted"};
                }

            }

            if (isset($incomeStatement->{"Weighted Average Shs Out"})) {
                if (is_numeric($incomeStatement->{"Weighted Average Shs Out"})) {
                    $newIncomeStatement->weighted_avg_shs = $incomeStatement->{"Weighted Average Shs Out"};
                }

            }

//            if (isset($incomeStatement->{"Weighted Average Shs Out (Dil)"})) {
//                if (is_numeric($incomeStatement->{"Weighted Average Shs Out (Dil)"})) {
//                    $newIncomeStatement->weighted_average_shs_out_dil = $incomeStatement->{"Weighted Average Shs Out (Dil)"};
//                }
//
//            }

            if (isset($incomeStatement->{"Dividend per Share"})) {
                if (is_numeric($incomeStatement->{"Dividend per Share"})) {
                    $newIncomeStatement->dividend_per = $incomeStatement->{"Dividend per Share"};
                }

            }

            if (isset($incomeStatement->{"Gross Margin"})) {
                if (is_numeric($incomeStatement->{"Gross Margin"})) {
                    $newIncomeStatement->gross_margin = $incomeStatement->{"Gross Margin"};
                }

            }

            if (isset($incomeStatement->{"EBITDA Margin"})) {
                if (is_numeric($incomeStatement->{"EBITDA Margin"})) {
                    $newIncomeStatement->ebitda_margin = $incomeStatement->{"EBITDA Margin"};
                }

            }

            if (isset($incomeStatement->{"EBIT Margin"})) {
                if (is_numeric($incomeStatement->{"EBIT Margin"})) {
                    $newIncomeStatement->ebit_margin = $incomeStatement->{"EBIT Margin"};
                }

            }

            if (isset($incomeStatement->{"Profit Margin"})) {
                if (is_numeric($incomeStatement->{"Profit Margin"})) {
                    $newIncomeStatement->profit_margin = $incomeStatement->{"Profit Margin"};
                }

            }

            if (isset($incomeStatement->{"Free Cash Flow margin"})) {
                if (is_numeric($incomeStatement->{"Free Cash Flow margin"})) {
                    $newIncomeStatement->free_cash_flow_margin = $incomeStatement->{"Free Cash Flow margin"};
                }

            }

            if (isset($incomeStatement->{"EBITDA"})) {
                if (is_numeric($incomeStatement->{"EBITDA"})) {
                    $newIncomeStatement->ebitda = $incomeStatement->{"EBITDA"};
                }

            }

            if (isset($incomeStatement->{"EBIT"})) {
                if (is_numeric($incomeStatement->{"EBIT"})) {
                    $newIncomeStatement->ebit = $incomeStatement->{"EBIT"};
                }

            }

            if (isset($incomeStatement->{"Consolidated Income"})) {
                if (is_numeric($incomeStatement->{"Consolidated Income"})) {
                    $newIncomeStatement->consolidated_income = $incomeStatement->{"Consolidated Income"};
                }

            }

            if (isset($incomeStatement->{"Earnings Before Tax Margin"})) {
                if (is_numeric($incomeStatement->{"Earnings Before Tax Margin"})) {
                    $newIncomeStatement->earnings_before_tax_margin = $incomeStatement->{"Earnings Before Tax Margin"};
                }

            }

            if (isset($incomeStatement->{"Net Profit Margin"})) {
                if (is_numeric($incomeStatement->{"Net Profit Margin"})) {
                    $newIncomeStatement->net_profit_margin = $incomeStatement->{"Net Profit Margin"};
                }

            }

            $newIncomeStatement->save();
        }
        catch (\Exception $e) {
            $this->logger->logMessage('error incomeStatementObject: '.json_encode($incomeStatement));
            $this->logger->logMessage('error'.$e->getMessage());
        }
    }
}