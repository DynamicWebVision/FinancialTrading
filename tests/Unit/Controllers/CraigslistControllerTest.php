<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\CurrencyIndicators;
use App\Model\HistoricalRates;
use App\Model\TmpTestRates;
use App\Http\Controllers\CraigslistController;
use App\Model\Craigslist\Brand;
use App\Model\Craigslist\City;
use App\Model\Craigslist\BrandCheckTracker;

class CraigslistControllerTest extends TestCase
{

    public $transactionController;
    public $oanda;

    public function testBacktestProcessStatsSpecificProcess() {
        $livePracticeController = new CraigslistController();

        $livePracticeController->scrapeBrandFurniture(2);
    }

    public function testCreateNewBrandTest() {
        $brand = new Brand();

        $brand->name = 'Ethan Allen';
        $brand->search_text = 'Ethan+Allen';
        $brand->section_id = 1;

        $brand->save();

        $cities = City::get()->toArray();

        foreach ($cities as $city) {
            $brandCityTracker = new BrandCheckTracker();

            $brandCityTracker->brand_id = $brand->id;
            $brandCityTracker->city_id = $city['id'];
            $brandCityTracker->section_id = $brand->section_id;

            $brandCityTracker->save();
        }
    }

    public function testCreateCity() {
        $city = new City();

        $city->name = 'Houston';
        $city->craigslist_string = 'houston';

        $city->save();

        $brands = Brand::get()->toArray();

        foreach ($brands as $brand) {
            $brandCityTracker = new BrandCheckTracker();

            $brandCityTracker->brand_id = $brand['id'];
            $brandCityTracker->city_id = $city->id;
            $brandCityTracker->section_id = $brand['section_id'];

            $brandCityTracker->save();
        }
    }
}
