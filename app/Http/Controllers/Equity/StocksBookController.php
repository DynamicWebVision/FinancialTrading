<?php namespace App\Http\Controllers\Equity;

use App\Model\Stocks\StocksCompanyProfile;
use \DB;
use \Log;
use Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ServersController;
use App\Broker\IexTrading;
use Illuminate\Support\Facades\Config;

use App\Model\Stocks\StocksDump;
use App\Model\Stocks\Stocks;
use App\Model\Stocks\StocksApiJobs;
use App\Model\Stocks\StocksBook;
use App\Model\Stocks\StocksTags;
use App\Model\Stocks\StocksIndustry;
use App\Model\Stocks\StocksFundamentalData;
use App\Model\Servers;
use App\Services\Csv;

class StocksBookController extends Controller {

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
        Log::emergency('Keep Running Stock Company Profile Data Start');
        $serverController = new ServersController();
        $lastGitPullTime = $serverController->getLastGitPullTime();
        Config::set('last_git_pull_time', $lastGitPullTime);
        $this->keepRunningStartTime = time();
        $currentDay = date('z') + 1;
        Log::emergency('Keep Running Book Start Time '.$this->keepRunningStartTime);
        $stocksToPullCount = 100;

        while ($stocksToPullCount > 0) {
            $this->lastPullTime = $currentDay;
            $this->pullOneStock($currentDay);

            $lastGitPullTime = $serverController->getLastGitPullTime();
            $configLastGitPullTime = Config::get('last_git_pull_time');

            if ($lastGitPullTime != $configLastGitPullTime) {
                $this->keepRunningCheck = false;
            }
            $stocksToPullCount = StocksApiJobs::where('last_book_pull', '!=', $currentDay)->count();
        }
        Log::emergency('Keep Running Book End Time '.$this->keepRunningStartTime);
    }

    public function pullOneStock($currentDay) {
        $stockApi = StocksApiJobs::where('last_book_pull', '!=', $currentDay)->first();
        $stock = Stocks::find($stockApi->stock_id);
        $this->updateBook($stock);
        $stockApi->last_book_pull = $currentDay;
        $stockApi->save();
    }

    public function updateBook($stock) {
        $ieTrading = new IexTrading();

        $response = $ieTrading->getBook($stock->symbol);

        if (!$response) {
            return;
        }

        $newIexBook = StocksBook::firstOrNew(['stock_id'=> $stock->id]);

        if (isset($response->open)) {
            if (is_numeric($response->open)) {
                $newIexBook->open = $response->open;
            }

        }

        if (isset($response->close)) {
            if (is_numeric($response->close)) {
                $newIexBook->close = $response->close;
            }

        }

        if (isset($response->high)) {
            if (is_numeric($response->high)) {
                $newIexBook->high = $response->high;
            }

        }

        if (isset($response->low)) {
            if (is_numeric($response->low)) {
                $newIexBook->low = $response->low;
            }

        }

        if (isset($response->latestPrice)) {
            if (is_numeric($response->latestPrice)) {
                $newIexBook->latest_price = $response->latestPrice;
            }

        }

        if (isset($response->latestVolume)) {
            if (is_numeric($response->latestVolume)) {
                $newIexBook->latest_volume = $response->latestVolume;
            }

        }

        if (isset($response->change)) {
            if (is_numeric($response->change)) {
                $newIexBook->change_price = $response->change;
            }

        }

        if (isset($response->changePercent)) {
            if (is_numeric($response->changePercent)) {
                $newIexBook->change_percent = $response->changePercent;
            }

        }

        if (isset($response->avgTotalVolume)) {
            if (is_numeric($response->avgTotalVolume)) {
                $newIexBook->avg_total_volume = $response->avgTotalVolume;
            }

        }

        if (isset($response->marketCap)) {
            if (is_numeric($response->marketCap)) {
                $newIexBook->market_cap = $response->marketCap;
            }

        }

        if (isset($response->peRatio)) {
            if (is_numeric($response->peRatio)) {
                $newIexBook->pe_ratio = $response->peRatio;
            }

        }

        if (isset($response->week52High)) {
            if (is_numeric($response->week52High)) {
                $newIexBook->week52_high = $response->week52High;
            }

        }

        if (isset($response->week52Low)) {
            if (is_numeric($response->week52Low)) {
                $newIexBook->week52_low = $response->week52Low;
            }

        }

        if (isset($response->ytdChange)) {
            if (is_numeric($response->ytdChange)) {
                $newIexBook->ytd_change = $response->ytdChange;
            }

        }

        $newIexBook->save();
    }
}