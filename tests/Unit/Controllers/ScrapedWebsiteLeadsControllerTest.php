<?php

namespace Tests\Unit\Controllers;

use App\Model\Yelp\YelpLocation;
use App\Services\Scraper;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\CurrencyIndicators;
use App\Model\HistoricalRates;
use App\Model\TmpTestRates;
use App\Http\Controllers\Equity\StocksInformationController;
use App\Http\Controllers\YelpController;
use App\Model\Servers;
use Illuminate\Support\Facades\Config;
use App\Broker\OandaV20;
use App\Model\Yelp\Cities;
use App\Model\Yelp\States;
use App\Model\Yelp\YelpCategories;
use App\Model\Yelp\YelpCityTracker;
use App\Model\Marketing\ScrapedWebsite;

class ScrapedWebsiteLeadsControllerTest extends TestCase
{
    public $transactionController;
    public $oanda;

//    public function testGetSymbolData() {
//        $stocksHistoricalDataTest = new StocksHistoricalDataController();
//        $stocksHistoricalDataTest->getStockData();
//    }

    public function testScrapeRakutenBrands() {
        $scraper = new Scraper();

        $text = $scraper->getCurl('https://www.rakuten.com/stores/all/index.htm');

        $text = $scraper->getToEnd($text, 'm-store-row-s');

        $sitesFound = [];

        while ($scraper->inString($text,'m-store-row-s')) {
            $webSiteUrl = $scraper->getInBetween($text, '<a href="/', '" data-nav');
            $companyName = $scraper->getInBetween($text, 'class="store-name blk f-norm f-16 lh-20">', '</');

            $scrapedWebsite = new ScrapedWebsite();

            $scrapedWebsite->business_name = $companyName;
            $scrapedWebsite->website = 'https://'.$webSiteUrl;
            $scrapedWebsite->save();

            $text = $scraper->getToEnd($text, 'Shop Now</a>');
        }
        $debug = 1;
    }
}