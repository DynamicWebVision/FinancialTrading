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

class YelpControllerTest extends TestCase
{
    public $transactionController;
    public $oanda;

//    public function testGetSymbolData() {
//        $stocksHistoricalDataTest = new StocksHistoricalDataController();
//        $stocksHistoricalDataTest->getStockData();
//    }

    public function testKeepRunning() {
        $yelpController = new YelpController();

        $yelpController->search();
    }

    public function testProcessOneSearch() {
        $yelpController = new YelpController();
        $yelpController->processOneSearch(1);
    }

    public function testProcessOneEmail() {
        $yelpController = new YelpController();
        $yelpController->getWebsiteUrl(6824);
    }

    public function testGetEmailAddress() {
        $yelpController = new YelpController();
        $yelpController->contactEmail(15611);
    }

    public function testBusiness() {
        $yelpController = new YelpController();

        $yelpController->getBusiness();
    }
    public function testCategories() {
        $yelpController = new YelpController();

        $yelpController->loadCategories();
    }

    public function testCities() {
        $yelpController = new YelpController();

        $yelpController->loadUsCities();
    }

    public function testStates() {
        $states = '[
    {
        "name": "Alabama",
        "abbreviation": "AL"
    },
    {
        "name": "Alaska",
        "abbreviation": "AK"
    },
    {
        "name": "American Samoa",
        "abbreviation": "AS"
    },
    {
        "name": "Arizona",
        "abbreviation": "AZ"
    },
    {
        "name": "Arkansas",
        "abbreviation": "AR"
    },
    {
        "name": "California",
        "abbreviation": "CA"
    },
    {
        "name": "Colorado",
        "abbreviation": "CO"
    },
    {
        "name": "Connecticut",
        "abbreviation": "CT"
    },
    {
        "name": "Delaware",
        "abbreviation": "DE"
    },
    {
        "name": "District Of Columbia",
        "abbreviation": "DC"
    },
    {
        "name": "Federated States Of Micronesia",
        "abbreviation": "FM"
    },
    {
        "name": "Florida",
        "abbreviation": "FL"
    },
    {
        "name": "Georgia",
        "abbreviation": "GA"
    },
    {
        "name": "Guam",
        "abbreviation": "GU"
    },
    {
        "name": "Hawaii",
        "abbreviation": "HI"
    },
    {
        "name": "Idaho",
        "abbreviation": "ID"
    },
    {
        "name": "Illinois",
        "abbreviation": "IL"
    },
    {
        "name": "Indiana",
        "abbreviation": "IN"
    },
    {
        "name": "Iowa",
        "abbreviation": "IA"
    },
    {
        "name": "Kansas",
        "abbreviation": "KS"
    },
    {
        "name": "Kentucky",
        "abbreviation": "KY"
    },
    {
        "name": "Louisiana",
        "abbreviation": "LA"
    },
    {
        "name": "Maine",
        "abbreviation": "ME"
    },
    {
        "name": "Marshall Islands",
        "abbreviation": "MH"
    },
    {
        "name": "Maryland",
        "abbreviation": "MD"
    },
    {
        "name": "Massachusetts",
        "abbreviation": "MA"
    },
    {
        "name": "Michigan",
        "abbreviation": "MI"
    },
    {
        "name": "Minnesota",
        "abbreviation": "MN"
    },
    {
        "name": "Mississippi",
        "abbreviation": "MS"
    },
    {
        "name": "Missouri",
        "abbreviation": "MO"
    },
    {
        "name": "Montana",
        "abbreviation": "MT"
    },
    {
        "name": "Nebraska",
        "abbreviation": "NE"
    },
    {
        "name": "Nevada",
        "abbreviation": "NV"
    },
    {
        "name": "New Hampshire",
        "abbreviation": "NH"
    },
    {
        "name": "New Jersey",
        "abbreviation": "NJ"
    },
    {
        "name": "New Mexico",
        "abbreviation": "NM"
    },
    {
        "name": "New York",
        "abbreviation": "NY"
    },
    {
        "name": "North Carolina",
        "abbreviation": "NC"
    },
    {
        "name": "North Dakota",
        "abbreviation": "ND"
    },
    {
        "name": "Northern Mariana Islands",
        "abbreviation": "MP"
    },
    {
        "name": "Ohio",
        "abbreviation": "OH"
    },
    {
        "name": "Oklahoma",
        "abbreviation": "OK"
    },
    {
        "name": "Oregon",
        "abbreviation": "OR"
    },
    {
        "name": "Palau",
        "abbreviation": "PW"
    },
    {
        "name": "Pennsylvania",
        "abbreviation": "PA"
    },
    {
        "name": "Puerto Rico",
        "abbreviation": "PR"
    },
    {
        "name": "Rhode Island",
        "abbreviation": "RI"
    },
    {
        "name": "South Carolina",
        "abbreviation": "SC"
    },
    {
        "name": "South Dakota",
        "abbreviation": "SD"
    },
    {
        "name": "Tennessee",
        "abbreviation": "TN"
    },
    {
        "name": "Texas",
        "abbreviation": "TX"
    },
    {
        "name": "Utah",
        "abbreviation": "UT"
    },
    {
        "name": "Vermont",
        "abbreviation": "VT"
    },
    {
        "name": "Virgin Islands",
        "abbreviation": "VI"
    },
    {
        "name": "Virginia",
        "abbreviation": "VA"
    },
    {
        "name": "Washington",
        "abbreviation": "WA"
    },
    {
        "name": "West Virginia",
        "abbreviation": "WV"
    },
    {
        "name": "Wisconsin",
        "abbreviation": "WI"
    },
    {
        "name": "Wyoming",
        "abbreviation": "WY"
    }
]';

        $states = json_decode($states);

        foreach ($states as $state) {
            $newState = new States();

            $newState->name = $state->name;
            $newState->postal_code = $state->abbreviation;

            $newState->save();
        }
    }

    public function testFixCities() {
        $cities = Cities::get()->toArray();

        foreach ($cities as $city) {
            $currentCity = Cities::find($city['id']);

            $state = States::where('state', '=', $city['state'])->first();

            $currentCity->state_postal = $state->postal_code;
            $currentCity->save();
        }
    }

    public function testLoadCategory() {

        $category_id = 1187;

        $cities = Cities::where('rank', '=', 1)->get()->toArray();

        foreach ($cities as $city) {
            $yelpCityTracker = new YelpCityTracker();

            $yelpCityTracker->yelp_category_id = $category_id;
            $yelpCityTracker->city_id = $city['id'];

            $yelpCityTracker->save();
        }
    }

    public function testLoadRestaurantsForCity() {
        $restaurantCategories = YelpCategories::where('parent','=', 'restaurants')->get()->toArray();

        $cities = Cities::where('ranking', '=', 1)->get()->toArray();

        foreach ($cities as $city) {
            foreach ($restaurantCategories as $category) {
                $yelpCityTracker = new YelpCityTracker();

                $yelpCityTracker->yelp_category_id = $category['id'];
                $yelpCityTracker->city_id = $city['id'];

                $yelpCityTracker->save();
            }
        }
    }
}