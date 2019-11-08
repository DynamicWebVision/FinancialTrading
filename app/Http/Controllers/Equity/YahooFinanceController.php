<?php namespace App\Http\Controllers\Equity;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ProcessScheduleController;

use App\EquityTechnicalCheck\HmaReversal\HmaReversal;
use App\Model\Stocks\StocksTechnicalCheckResult;
use App\Model\Stocks\Stocks;
use App\Model\Stocks\StocksDailyPrice;
use App\Model\Stocks\StocksDailyPricesYahoo;
use App\Model\Stocks\StocksPriceIssues;
use App\Services\YahooFinance;
use App\Services\Utility;
use App\Services\ProcessLogger;
use App\Services\TextMessage;

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

        $year = 2012;

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
                        //$this->evaluateOnePrice($price);
                        $this->saveOnePrice($price);
                    }
                }
            }
            else {
                $this->logger->logMessage('prices response false');
            }
            $year++;
        }

        $scheduleController = new ProcessScheduleController();
        $scheduleController->createQueueRecordsWithVariableIds('eq_book_iex', [$stock->id]);
    }


    public function updateMostRecentPrices($stockId) {

        sleep(rand(2,7));

        $this->logger = new ProcessLogger('yahoo_price_recent');

        $this->stock_id = $stockId;
        $stock = Stocks::find($stockId);

        $mostRecentPriceUnix = StocksDailyPricesYahoo::where('stock_id','=', $stockId)->max('date_time_unix');

        $mostRecentPriceUnix = $mostRecentPriceUnix - 90000;

        $this->logger->logMessage($stock->id.'-'.$stock->symbol.'-'.$stock->name);

        $yahooFinance = new YahooFinance();
        $yahooFinance->logger = $this->logger;
        $yahooFinance->symbol = $stock->symbol;

        $prices = $yahooFinance->getHistoricalRates(false);

        if ($prices) {
            foreach ($prices as $price) {
                if (isset($price->high)) {
                    if ($price->date > $mostRecentPriceUnix) {
                        $this->saveOnePrice($price);
                    }
                }
            }
        }
        else {
            $this->logger->logMessage('prices response false');
        }

        $scheduleController = new ProcessScheduleController();
        $scheduleController->createQueueRecordsWithVariableIds('eq_book_iex', [$stock->id]);

        $this->logger->processEnd();
    }

    protected function saveOnePrice($price) {
        try {
            $stockPriceRecord = StocksDailyPricesYahoo::firstOrNew([
                'stock_id'=> $this->stock_id,
                'date_time_unix' => $price->date
            ]);

            $stockPriceRecord->stock_id = $this->stock_id;
            $stockPriceRecord->open = round($price->open, 4);
            $stockPriceRecord->high = round($price->high, 4);
            $stockPriceRecord->low = round($price->low, 4);
            $stockPriceRecord->close = round($price->close, 4);
            $stockPriceRecord->volume = round($price->volume, 4);

            $stockPriceRecord->date_time_unix = $price->date;
            $stockPriceRecord->price_date_time = date('Y-m-d', $price->date);

            $stockPriceRecord->save();
        }
        catch (\Exception $e) {
            $errorStock = Stocks::find($this->stock_id);
            $errorStock->price_error = 1;
            $errorStock->save();

            $this->logger->logMessage('Stock '.$this->stock_id.'-'.$this->symbol.' ran into save price error with message '.$e->getMessage());
        }
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


    public function createRecentUpdateRecords() {
        $this->logger = new ProcessLogger('yahoo_price_recent');

        $stocks = Stocks::where('initial_daily_upload','=', 1)->get()->toArray();

        $stockIDs = array_column($stocks, 'id');

        $scheduleController = new ProcessScheduleController();
        $scheduleController->createQueueRecordsWithVariableIds('yahoo_price_recent', [$stockIDs]);

        $this->logger->processEnd();
    }
}