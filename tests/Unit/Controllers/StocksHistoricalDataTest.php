<?php

namespace Tests\Unit\Controllers;

use App\Model\Stocks\Stocks;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\StringHelpers;
use App\Model\HistoricalRates;
use App\Model\TmpTestRates;
use App\Http\Controllers\Equity\StocksHistoricalDataController;
use App\Http\Controllers\ServersController;
use \App\Services\ProcessLogger;
use App\Broker\TDAmeritrade;
use App\Model\Servers;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Broker\OandaV20;

class StocksHistoricalDataTest extends TestCase
{
    public $transactionController;
    public $oanda;

//    public function testGetSymbolData() {
//        $stocksHistoricalDataTest = new StocksHistoricalDataController();
//        $stocksHistoricalDataTest->getStockData();
//    }

//    public function testKeepRunning() {
//        $serverController = new ServersController();
//        $serverController->setServerId();
//
//        $server = Servers::find(Config::get('server_id'));
//
//        $stocksHistoricalDataTest = new StocksHistoricalDataController();
//        $stocksHistoricalDataTest->keepRunning();
//    }

    public function testDailyUpdate() {
        $stocksHistoricalDataTest = new StocksHistoricalDataController();
        $stocksHistoricalDataTest->runAllStocksforCurrentRateData();
    }

    public function testSpecificStockDate() {
        $stocksHistoricalDataTest = new StocksHistoricalDataController();

        $this->logger = new ProcessLogger('stck_historical');

        $stocksHistoricalDataTest->tdAmeritrade = new TDAmeritrade($this->logger);

        $stockId = 6113;
        $stock = Stocks::find($stockId);
        $stocksHistoricalDataTest->symbol = $stock->symbol;
        $stocksHistoricalDataTest->stockId = $stock->id;

        $stocksHistoricalDataTest->startDate = strtotime('2013-04-22')*1000;
        $stocksHistoricalDataTest->endDate = strtotime('2013-07-22')*1000;

        $stocksHistoricalDataTest->getStockData();
    }

//    public function testLoadNurseJobs() {
//        $nurseJobs = ['Apple Valley, CA	20 Weeks',
//'New York, NY	13 Weeks',
//'Springfield, OR	13 Weeks',
//'Danville, VA	13 Weeks',
//'New York, NY	13 Weeks',
//'San Leandro, CA	13 Weeks',
//'Baltimore, MD	13 Weeks',
//'Johnson City, NY	13 Weeks',
//'Patchogue, NY	13 Weeks',
//'Baltimore, MD	13 Weeks',
//'Torrington, CT	13 Weeks',
//'Hyannis, MA	12 Weeks',
//'Bakersfield, CA	14 Weeks',
//'Federal Way, WA	13 Weeks',
//'Laguna Beach, CA	20 Weeks',
//'Washington, DC	13 Weeks',
//'Albany, GA	13 Weeks',
//'New York, NY	13 Weeks',
//'Brighton, MA	13 Weeks',
//'Manteca, CA	13 Weeks',
//'Stockton, CA	13 Weeks',
//'Fullerton, CA	13 Weeks',
//'Philadelphia, PA	13 Weeks',
//'Suffolk, VA	13 Weeks',
//'Springfield, MA	13 Weeks',
//'New York, NY	13 Weeks',
//'Lexington, KY	13 Weeks',
//'Seattle, WA	13 Weeks',
//'Washington, DC	13 Weeks',
//'Rosedale, MD	13 Weeks',
//'San Francisco, CA	13 Weeks',
//'Waterbury, CT	13 Weeks',
//'Santa Monica, CA	13 Weeks',
//'Antioch, CA	18 Weeks',
//'Gretna, LA	11 Weeks',
//'Newport News, VA	12 Weeks',
//'Tarzana, CA	20 Weeks',
//'Tarzana, CA	20 Weeks',
//'Charlottesville, VA	13 Weeks',
//'Gainesville, GA	13 Weeks',
//'Oakland, CA	7 Weeks',
//'Westerly, RI	12 Weeks',
//'San Pedro, CA	20 Weeks',
//'Oakland, CA	13 Weeks',
//'San Francisco, CA	13 Weeks',
//'Providence, RI	13 Weeks',
//'Athol, MA	13 Weeks',
//'Manteca, CA	13 Weeks',
//'Moreno Valley, CA	13 Weeks',
//'Mission Hills, CA	13 Weeks',
//'Dorchester Center, MA	12 Weeks',
//'Crystal City, MO	13 Weeks',
//'Dorchester Center, MA	12 Weeks',
//'Attleboro, MA	11 Weeks',
//'Chico, CA	13 Weeks',
//'Palmer, MA	26 Weeks',
//'Rogers, AR	13 Weeks',
//'Dorchester Center, MA	12 Weeks',
//'New Haven, CT	12 Weeks',
//'Yuma, CO	13 Weeks',
//'Lawrence, MA	12 Weeks',
//'New York, NY	13 Weeks',
//'Boston, MA	13 Weeks',
//'Akron, OH	8 Weeks',
//'Taunton, MA	10 Weeks',
//'Lawrence, MA	13 Weeks',
//'Kapaa, HI	13 Weeks',
//'Clinton, MD	13 Weeks',
//'Suffern, NY	15 Weeks',
//'Shreveport, LA	13 Weeks',
//'Springfield, MA	13 Weeks',
//'Saint Charles, MO	13 Weeks',
//'Stockton, CA	13 Weeks',
//'San Andreas, CA	13 Weeks',
//'New York, NY	13 Weeks',
//'Brooklyn, NY	13 Weeks',
//'Montgomery, AL	18 Weeks',
//'Hopewell, VA	13 Weeks',
//'Burbank, CA	20 Weeks',
//'Burbank, CA	20 Weeks',
//'Meriden, CT	13 Weeks',
//'Meriden, CT	13 Weeks',
//'Torrance, CA	20 Weeks',
//'Mission Viejo, CA	20 Weeks',
//'South Hill, VA	12 Weeks',
//'Redwood City, CA	13 Weeks',
//'Rosedale, MD	13 Weeks',
//'Las Cruces, NM	13 Weeks',
//'Castro Valley, CA	13 Weeks',
//'Castro Valley, CA	13 Weeks',
//'San Leandro, CA	13 Weeks',
//'San Leandro, CA	13 Weeks',
//'Bakersfield, CA	14 Weeks',
//'Westerly, RI	12 Weeks',
//'Bakersfield, CA	14 Weeks',
//'Waterloo, IA	13 Weeks',
//'Houston, TX	13 Weeks',
//'Grass Valley, CA	13 Weeks',
//'Detroit, MI	13 Weeks',
//'Chandler, AZ	13 Weeks',
//'Leonardtown, MD	13 Weeks',
//'Saint Louis, MO	13 Weeks',
//'Saint Johnsbury, VT	13 Weeks',
//'San Jose, CA	13 Weeks',
//'San Jose, CA	13 Weeks',
//'Oakland, CA	13 Weeks',
//'Langhorne, PA	13 Weeks',
//'Lebanon, PA	13 Weeks',
//'Albany, GA	17 Weeks',
//'Henderson, NC	13 Weeks',
//'Castro Valley, CA	18 Weeks',
//'Alturas, CA	13 Weeks',
//'Oakland, CA	13 Weeks',
//'High Point, NC	12 Weeks',
//'Tulsa, OK	13 Weeks',
//'Las Vegas, NM	8 Weeks',
//'Houston, TX	12 Weeks',
//'Philadelphia, PA	13 Weeks',
//'Baltimore, MD	13 Weeks',
//'Nashua, NH	13 Weeks',
//'Hiawatha, KS	13 Weeks',
//'Minneapolis, MN	13 Weeks',
//'Albany, GA	13 Weeks',
//'New Haven, CT	12 Weeks',
//'Springfield, MA	13 Weeks',
//'Springfield, MA	13 Weeks',
//'Las Cruces, NM	13 Weeks',
//'Las Cruces, NM	13 Weeks',
//'Needham, MA	13 Weeks',
//'Davenport, IA	13 Weeks',
//'Minneapolis, MN	13 Weeks',
//'Minneapolis, MN	13 Weeks',
//'Fremont, CA	13 Weeks',
//'Springfield, MA	13 Weeks',
//'Graceville, MN	13 Weeks',
//'Hyannis, MA	12 Weeks',
//'Lake Isabella, CA	13 Weeks',
//'Boston, MA	13 Weeks',
//'Langhorne, PA	13 Weeks',
//'Pahala, HI	13 Weeks',
//'Modesto, CA	12 Weeks',
//'Modesto, CA	12 Weeks',
//'Framingham, MA	13 Weeks',
//'Crete, NE	13 Weeks',
//'Portsmouth, VA	13 Weeks',
//'Portsmouth, VA	13 Weeks',
//'Lancaster, CA	13 Weeks',
//'Modesto, CA	12 Weeks',
//'Salem, MA	13 Weeks',
//'Torrington, CT	13 Weeks',
//'Elyria, OH	13 Weeks',
//'Modesto, CA	12 Weeks',
//'Novato, CA	13 Weeks',
//'Pottstown, PA	13 Weeks',
//'Fredericksburg, VA	13 Weeks',
//'Globe, AZ	13 Weeks',
//'Modesto, CA	12 Weeks',
//'San Angelo, TX	13 Weeks',
//'Allegan, MI	12 Weeks',
//'Wilkes Barre, PA	13 Weeks',
//'Wilkes Barre, PA	13 Weeks',
//'Apple Valley, CA	13 Weeks',
//'Oakland, CA	13 Weeks',
//'Rosedale, MD	13 Weeks',
//'Apple Valley, CA	20 Weeks',
//'Bossier City, LA	13 Weeks',
//'San Leandro, CA	13 Weeks',
//'Olney, MD	13 Weeks',
//'West Islip, NY	13 Weeks',
//'West Islip, NY	13 Weeks',
//'New York, NY	13 Weeks',
//'Oakland, CA	13 Weeks',
//'Antioch, CA	13 Weeks',
//'New Haven, CT	12 Weeks',
//'Modesto, CA	12 Weeks',
//'Suffern, NY	15 Weeks',
//'Hartford, CT	13 Weeks',
//'New York, NY	12 Weeks',
//'Roxboro, NC	13 Weeks',
//'Jersey Shore, PA	13 Weeks',
//'Derry, NH	13 Weeks',
//'Lawrence, MA	12 Weeks',
//'Suffern, NY	15 Weeks',
//'Clinton, MD	13 Weeks',
//'New York, NY	12 Weeks',
//'Lewiston, ME	13 Weeks',
//'Gainesville, GA	13 Weeks',
//'Hartford, CT	13 Weeks',
//'Hartford, CT	13 Weeks',
//'Kapaau, HI	26 Weeks',
//'Merced, CA	13 Weeks'];
//
//        $stringHelpers = new StringHelpers();
//
//        foreach ($nurseJobs as $job) {
//            $city = $stringHelpers->getAllValuesUntilString($job, ',');
//
//            $commaPosition = strpos($job, ',') +1;
//            $stateEnd = strpos($job, ',') +3;
//            $state = trim(substr($job, $commaPosition, 3));
//            $weeks = trim(substr($job, $stateEnd+1));
//
//            DB::table('nurse_jobs')->insert(
//                ['state' => $state, 'weeks' => $weeks, 'city' =>$city]
//            );
//        }
//    }

}
