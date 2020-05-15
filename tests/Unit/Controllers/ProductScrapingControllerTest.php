<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\CurrencyIndicators;
use App\Model\HistoricalRates;
use App\Model\TmpTestRates;
use App\Model\Servers\ServerEnvironmentDef;
use App\Http\Controllers\ProductScrapingController;
use Illuminate\Support\Facades\Config;
use App\Model\Servers;
use App\Broker\OandaV20;
use App\Services\StringHelpers;

class ProductScrapingControllerTest extends TestCase
{
    //https://www.travelnursesource.com/search-travel-nursing-recruiters/

    public $transactionController;
    public $oanda;


//    public function testGetRoot() {
//        $serversController = new ProductScrapingController();
//        $serversController->getRoot();
//    }
//
//    public function testParseJson() {
//        $serversController = new ProductScrapingController();
//        $serversController->parseJsonData();
//    }

//    public function testGetJsonData() {
//        $serversController = new ProductScrapingController();
//        $serversController->getJsonData();
//    }

//    public function testTwoStrings() {
//        $productType = \App\Model\ProductAdvice\ProductType::find(57);
//
//        $serversController = new ProductScrapingController();
//        $serversController->populatePriceLevels($productType);
//    }

    public function testLoadAllProductTypePricePoints() {
        $productTypes = \App\Model\ProductAdvice\ProductType::get();

        foreach ($productTypes as $productType) {
            sleep(rand(3, 27));
            $serversController = new ProductScrapingController();
            $serversController->populatePriceLevels($productType);
        }
    }
}
