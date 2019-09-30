<?php namespace App\Http\Controllers\Equity;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ProcessScheduleController;

use App\EquityTechnicalCheck\HmaReversal\HmaReversal;
use App\Model\Stocks\StocksTechnicalCheckResult;
use App\Model\Stocks\Stocks;
use App\Model\Stocks\StocksDailyPrice;
use App\Model\Stocks\StocksPriceIssues;
use App\Services\YahooFinance;
use App\Services\Utility;
use App\Services\ProcessLogger;

class YahooFinanceController extends Controller {

    public $logger;
    protected $stock_id;
    protected $utility;

    public function __construct()
    {
        $this->utility = new Utility();
    }


    public function checkPricesOneStock($stockId) {

        $this->logger = new ProcessLogger('yahoo_price');

        $this->stock_id = $stockId;
        $stock = Stocks::find($stockId);

        $this->logger->logMessage($stock->id.'-'.$stock->symbol.'-'.$stock->name);

        $minDate = StocksDailyPrice::where('stock_id', '=', $stockId)
                        ->min('date_time_unix');

        $year = date('Y', $minDate);

        $currentYear = date('Y');

        $yahooFinance = new YahooFinance();
        $yahooFinance->logger = $this->logger;
        $yahooFinance->symbol = $stock->symbol;

        while ($year <= $currentYear) {
            sleep(rand(5, 21));

            $dates = [
                'start_date'=> $year.'-01-01',
                'end_date'=> $year.'-12-31'
            ];

            $prices = $yahooFinance->getHistoricalRates($dates);


            if ($prices) {
                foreach ($prices as $price) {
                    if (isset($price->high)) {
                        $this->evaluateOnePrice($price);
                    }
                }
            }
            else {
                $this->logger->logMessage('prices response false');
            }
            $year++;
        }

        $scheduleController = new ProcessScheduleController();

        $nextStock = Stocks::where('initial_daily_load','=',1)->where('yahoo_prices_check','=',0)->first();

        $scheduleController->createQueueRecordsWithVariableIds('yahoo_price', [$nextStock->id]);
    }

    protected function evaluateOnePrice($price) {
        $date = $this->utility->unixToMysqlDateNoTime($price->date);

        $tdPrice = StocksDailyPrice::where('stock_id','=', $this->stock_id)
                        ->where('price_date_time', '=', $date)
                        ->first();

        if (is_null($tdPrice)) {
            $priceIssue = new StocksPriceIssues();
            $priceIssue->stock_id = $this->stock_id;
            $priceIssue->price_date = $date;
            $priceIssue->no_td_record = 1;
            $priceIssue->open = round($price->open, 4);
            $priceIssue->high = round($price->high, 4);
            $priceIssue->low = round($price->low, 4);
            $priceIssue->close = round($price->close, 4);
            $priceIssue->volume = round($price->volume, 4);
            $priceIssue->save();
        }
        else {
            if ($tdPrice->open != round($price->open, 4) ||
                $tdPrice->close != round($price->close, 4) ||
                $tdPrice->high != round($price->high, 4) ||
                $tdPrice->low != round($price->low, 4)
            ) {
                $priceIssue = new StocksPriceIssues();
                $priceIssue->stock_id = $this->stock_id;
                $priceIssue->price_date = $date;
                $priceIssue->price_mis_match = 1;
                $priceIssue->open = round($price->open, 4);
                $priceIssue->high = round($price->high, 4);
                $priceIssue->low = round($price->low, 4);
                $priceIssue->close = round($price->close, 4);
                $priceIssue->volume = round($price->volume, 4);
                $priceIssue->save();
            }
        }
    }
}