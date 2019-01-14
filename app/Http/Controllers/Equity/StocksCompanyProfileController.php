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
use App\Model\Stocks\StocksSector;
use App\Model\Stocks\StocksTags;
use App\Model\Stocks\StocksIndustry;
use App\Model\Stocks\StocksFundamentalData;
use App\Model\Servers;
use App\Services\Csv;

class StocksCompanyProfileController extends Controller {

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

            if (($this->keepRunningStartTime + (45*60)) < time()) {
                $this->keepRunningCheck = false;
            }
        }
        Log::emergency('Keep Running Stock Company Profile Data End');
    }

    public function pullOneStock() {
        $stock = Stocks::orderBy('last_company_profile_pull')->first();
        $this->updateCompanyProfileData($stock);
        $stock->last_company_profile_pull = time();
        $stock->save();
    }

    public function updateCompanyProfileData($stock) {
        $ieTrading = new IexTrading();

        $response = $ieTrading->getCompanyProfile($stock->symbol);

        $newStockCompanyProfile = StocksCompanyProfile::firstOrNew(['stock_id'=> $stock->id]);

        if (isset($response->symbol)) {
            $newStockCompanyProfile->symbol = $response->symbol;
        }

        if (isset($response->companyName)) {
            $newStockCompanyProfile->company_name = $response->companyName;
        }

        if (isset($response->exchange)) {
            $newStockCompanyProfile->exchange = $response->exchange;
        }

        if (isset($response->industry)) {
            $industry = StocksIndustry::firstOrNew(['name'=>trim($response->industry)]);
            $industry->save();
            $newStockCompanyProfile->industry_id = $industry->id;
        }

        if (isset($response->website)) {
            $newStockCompanyProfile->website = $response->website;
        }

        if (isset($response->description)) {
            $newStockCompanyProfile->description = $response->description;
        }

        if (isset($response->CEO)) {
            $newStockCompanyProfile->ceo = $response->CEO;
        }

        if (isset($response->issueType)) {
            $newStockCompanyProfile->issue_type = $response->issueType;
        }

        if (isset($response->sector)) {
            $sector = StocksSector::firstOrNew(['name'=>trim($response->sector)]);
            $sector->save();
            $newStockCompanyProfile->sector_id = $sector->id;
        }

        if (isset($response->tags)) {
            foreach ($response->tags as $tag) {
                $tag = StocksTags::firstOrNew(['name'=>trim($tag)]);
                $tag->save();

                DB::table('stocks_tag_xref')->insert(
                    [
                        'stock_id'=> $stock->id,
                        'tag_id'=> $tag->id,
                    ]
                );
            }
        }
        $newStockCompanyProfile->save();
    }
}