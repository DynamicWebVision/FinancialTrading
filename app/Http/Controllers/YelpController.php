<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Services\Yelp;
use App\Model\Yelp\YelpCategories;
use App\Model\Yelp\Cities;
use App\Model\Yelp\YelpCityTracker;
use App\Model\Yelp\YelpLocation;
use App\Model\Yelp\YelpLocationCategory;
use App\Services\ProcessLogger;

class YelpController extends Controller
{
    public $logger;
    protected $apiResponse;

    public function search() {
        $yelp = new Yelp();
        $this->apiResponse = $yelp->search('employmentagencies', 'San Francisco, California');
    }

    public function getBusiness() {
        $yelp = new Yelp();
        $yelp->getBusiness('cHoNUKltOAJWSmNfsZ1h-w');
    }

    public function loadCategories() {
        $yelpCategories = json_decode(file_get_contents('/Users/boneill/ReferenceWorkspaces/FinancialTrading/app/Tmp/yelp_categories.json'));

        foreach ($yelpCategories as $category) {

            $yelpCategory = new YelpCategories();

            $yelpCategory->name = $category->title;
            $yelpCategory->alias = $category->alias;

            if (isset($category->parents)) {
                if (isset($category->parents[0])) {
                    $yelpCategory->parent = $category->parents[0];
                }
            }
            $yelpCategory->save();
        }
    }

    public function saveBusinesses($city_id, $category_id) {
        foreach ($this->apiResponse->businesses as $business) {

            try {
                $yelpLocation = YelpLocation::firstOrCreate(['yelp_id' => $business->id]);

                $yelpLocation->city_id = $city_id;

                $yelpLocation->name = $business->name;
                $yelpLocation->alias = $business->alias;
                $yelpLocation->address1 = $business->location->address1;
                $yelpLocation->address2 = $business->location->address2;
                $yelpLocation->city = $business->location->city;
                $yelpLocation->state = $business->location->state;
                $yelpLocation->zip = $business->location->zip_code;
                $yelpLocation->yelp_id = $business->id;

                if (isset($business->price)) {
                    $yelpLocation->price = strlen($business->price);
                }

                $yelpLocation->phone_no = preg_replace("/[^0-9]/", "", $business->display_phone);

                $yelpLocation->save();

                if (sizeof($business->categories) > 1) {
                    foreach ($business->categories as $category) {
                        $dbCategory = YelpCategories::where('alias', '=', $category->alias)->first();

                        $yelpLocationCategory = new YelpLocationCategory();
                        $yelpLocationCategory->yelp_category_id = $dbCategory->id;
                        $yelpLocationCategory->yelp_location_id = $yelpLocation->id;

                        $yelpLocationCategory->save();
                    }
                }
                else {
                    $yelpLocationCategory = new YelpLocationCategory();
                    $yelpLocationCategory->yelp_category_id = $category_id;
                    $yelpLocationCategory->yelp_location_id = $yelpLocation->id;

                    $yelpLocationCategory->save();
                }
            }
            catch (\Exception $e) {
                $this->logger->logMessage('Error Trying to Save business '.json_encode($business));
                $this->logger->logMessage($e->getMessage());
            }

        }
    }

    public function processOneSearch() {
        $this->logger = new ProcessLogger('yelp_one_search');

        $yelp = new Yelp();
        $yelp->logger = $this->logger;

        $yelpCityTracker = YelpCityTracker::where('completed','=', 0)->first();

        $this->logger->logMessage('yelpCityTracker: '.$yelpCityTracker->id);

        if (!$yelpCityTracker) {
            $this->logger->logMessage('No non-completed records in Yelp City Tracker');
            return;
        }

        $yelpCityTracker = YelpCityTracker::find($yelpCityTracker['id']);

        $category = YelpCategories::find($yelpCityTracker['yelp_category_id']);

        $city = Cities::find($yelpCityTracker['city_id']);

        $yelp->urlParams['location'] = $city->name.', '.$city->state_postal;
        $yelp->urlParams['categories'] = $category->alias;

        $this->logger->logMessage('Starting Run for '.$yelp->urlParams['location'].' and category '.$category->alias);

        if ($yelpCityTracker->total_records != 0) {
            $first_search = false;
            $yelp->urlParams['offset'] = $yelpCityTracker->current_offset;
        }
        else {
            $first_search = true;
        }

        $this->apiResponse = $yelp->search();

        if ($this->apiResponse) {
            $this->saveBusinesses($yelpCityTracker['city_id'], $yelpCityTracker['yelp_category_id']);

            if ($first_search) {
                if (20 >= $yelpCityTracker->total_records) {
                    $yelpCityTracker->total_records = $this->apiResponse->total;
                    $currentOffset = 20;
                    $yelpCityTracker->completed = 1;
                }

            }
            else {
                $currentOffset = $yelpCityTracker->current_offset + 20;

                if ($currentOffset >= $yelpCityTracker->total_records) {
                    $currentOffset = $yelpCityTracker->total_records;
                    $yelpCityTracker->completed = 1;
                }

            }
            $yelpCityTracker->current_offset = $currentOffset;
            $yelpCityTracker->save();
        }
        else {
            $this->logger->logMessage('ERROR RESPONSE '.$this->apiResponse);
        }

        sleep(60);

        $scheduleController = new ProcessScheduleController();
        $scheduleController->createQueueRecord('yelp_one_search');

        sleep(60);
    }
}
