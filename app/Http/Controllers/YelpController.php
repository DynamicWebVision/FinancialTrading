<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\ProcessScheduleController;
use Illuminate\Http\Request;
use App\Services\Yelp;
use App\Services\Scraper;
use App\Model\Yelp\YelpCategories;
use App\Model\Yelp\Cities;
use App\Model\Yelp\YelpCityTracker;
use App\Model\Yelp\YelpLocation;
use App\Model\Yelp\YelpLocationCategory;
use App\Model\Yelp\YelpLocationEmail;
use App\Services\ProcessLogger;

class YelpController extends Controller
{
    public $logger;
    protected $apiResponse;

    protected $yelp;

    public function search() {
        $this->apiResponse = $this->yelp->search('employmentagencies', 'San Francisco, California');
    }

    public function getBusiness() {
        $this->yelp->getBusiness('cHoNUKltOAJWSmNfsZ1h-w');
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

                $yelpLocation->phone_no = $business->display_phone;

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

    public function processOneSearch($yelpApiId) {
        $this->logger = new ProcessLogger('yelp_one_search');

        $yelp = new Yelp($yelpApiId);
        $yelp->logger = $this->logger;

        $priority = YelpCityTracker::where('completed','=', 0)->max('priority');

        $allOutstanding = YelpCityTracker::where('completed','=', 0)->where('priority', '=', $priority)->get(['id'])->toArray();

        $allIds = array_column($allOutstanding, 'id');

        $yelpCityTracker = YelpCityTracker::find(array_rand($allIds));

        $this->logger->logMessage('yelpCityTracker: '.$yelpCityTracker->id);

        if (!$yelpCityTracker) {
            $this->logger->logMessage('No non-completed records in Yelp City Tracker');
            return;
        }

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
                $yelpCityTracker->total_records = $this->apiResponse->total;
                $currentOffset = 20;
                if (20 >= $yelpCityTracker->total_records) {
                    $yelpCityTracker->completed = 1;
                }
            }
            else {
                $currentOffset = $yelpCityTracker->current_offset + 20;

                if ($currentOffset >= $yelpCityTracker->total_records || $currentOffset >= 980) {
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

        sleep(rand(60, 85));

        $scheduleController = new ProcessScheduleController();
        $scheduleController->createQueueRecord('yelp_one_search');

        sleep(60);
    }

    public function checkOneWebsiteUrl() {
        $this->logger = new ProcessLogger('yelp_website');

        $yelpLocation = YelpLocation::where('website_checked','=', 0)->first();

        $this->logger->logMessage('Checking Website for Yelp Location '.$yelpLocation->id.' '.$yelpLocation->name);

        $this->getWebsiteUrl($yelpLocation->id);
    }

    public function getWebsiteUrl($yelpLocationId) {

        $yelpLocation = YelpLocation::find($yelpLocationId);

        $scraper = new Scraper();

        $yelpHtml = $scraper->getCurl('https://www.yelp.com/biz/'.$yelpLocation->alias);

        if ($scraper->inString($yelpHtml, '"linkText":"')) {
            $this->logger->logMessage('Link Text Found');
            $webSiteUrl = $scraper->getInBetween($yelpHtml, '"href":"/biz_redir?url=', ';');
            $webSiteUrl = $scraper->getTextBeforeString('&', urldecode($webSiteUrl));
            $yelpLocation->website = $webSiteUrl;
            $hasWebsite = true;
        }
        else {
            $this->logger->logMessage('Link Text NOT Found');
            $hasWebsite = false;
        }

        $yelpLocation->website_checked = 1;

        $yelpLocation->save();

        sleep(22);

        $scheduleController = new ProcessScheduleController();

        $scheduleController->createQueueRecord('yelp_website');

        if ($hasWebsite) {
            $scheduleController->createQueueRecordsWithVariableIds('yelp_email', [$yelpLocationId]);
        }
    }

    protected function saveEmails($yelpLocation, $emails, $url) {
        foreach ($emails as $email) {
            $yelpLocationEmail = YelpLocationEmail::firstOrNew(
                ['email'=> $email],
                ['yelp_location_id'=> $yelpLocation->id]
            );

            $yelpLocationEmail->website_url = $url;

            $yelpLocationEmail->save();
        }
    }

    public function contactEmail($yelpLocationId) {
        $this->logger = new ProcessLogger('yelp_email');

        $yelpLocation = YelpLocation::find($yelpLocationId);

        $this->logger->logMessage('Checking for Email for '.$yelpLocation->id.'-'.$yelpLocation->name);

        $scraper = new Scraper();

        $website = $yelpLocation->website;

        $websiteText = $scraper->getCurl($website);

        $this->logger->logMessage($websiteText);

        $links = $scraper->getLinksWithWebsiteEndpoints($websiteText, $website);

        $this->logger->logMessage('Count of '.sizeof($links).' links found.');

        if (substr($website, -1) == '/') {
            $website = substr($website, 0, -1);
        }

        try {
            $this->logger->logMessage('Searching for Email for root '.$website);
            $rootEmails = $scraper->getEmailAddressesInLink($website);
            sleep(rand(2, 15));

            $this->saveEmails($yelpLocation, $rootEmails, $website);
        }
        catch (\Exception $e) {
            $this->logger->logMessage('Error for root '.$website);
            $this->logger->logMessage($e->getMessage());
        }

        foreach ($links as $link) {
            if ($scraper->inString($link, $website)) {
                $fullUrl = $link;
            }
            else {
                $fullUrl = $website.$link;
            }

            try{
                $this->logger->logMessage('Searching for Email for  '.$fullUrl);
                $emails = $scraper->getEmailAddressesInLink($fullUrl);

                $this->saveEmails($yelpLocation, $emails, $fullUrl);
            }
            catch ( \Exception $e) {
                $this->logger->logMessage('Error for link '.$link);
                $this->logger->logMessage($e->getMessage());
            }

            sleep(rand(2, 15));
        }
    }
}
