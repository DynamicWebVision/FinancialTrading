<?php namespace App\Services;

use App\Services\Scraper;
use App\Model\Yelp\YelpApi;

class Yelp  {

    protected $scraper;

    public $symbol;
    public $start_date;
    public $end_date;
    public $logger;

    public $urlParams = [];

    protected $yelpApi;


// API constants, you shouldn't have to change these.
CONST API_HOST = "https://api.yelp.com";
CONST SEARCH_PATH = "/v3/businesses/search";
CONST BUSINESS_PATH = "/v3/businesses/";  // Business ID will come after slash.

//// Defaults for our simple example.
//$DEFAULT_TERM = "dinner";
//$DEFAULT_LOCATION = "San Francisco, CA";
//$SEARCH_LIMIT = 3;

    public function __construct($yelpId)
    {
        $this->yelpApi = YelpApi::find($yelpId);
    }


    public function apiRequest($path, $url_params = array()) {
        // Send Yelp API Call
        try {
            $curl = curl_init();
            if (FALSE === $curl)
                throw new Exception('Failed to initialize');

            $url = self::API_HOST . $path . "?" . http_build_query($this->urlParams);
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,  // Capture response.
                CURLOPT_ENCODING => "",  // Accept gzip/deflate/whatever.
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "authorization: Bearer " . $this->yelpApi->api_key,
                    "cache-control: no-cache",
                ),
            ));

            $response = curl_exec($curl);

            if (FALSE === $response)
                throw new \Exception(curl_error($curl), curl_errno($curl));
            $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if (200 != $http_status)
                throw new \Exception($response, $http_status);

            curl_close($curl);
        } catch(\Exception $e) {
            $this->logger->logMessage($e->getMessage(), 4);
            return false;
        }
        $this->logger->logMessage(substr($response, 0, 200));
        return json_decode($response);
    }

    public function getBusiness($business_id) {
        $business_path = self::BUSINESS_PATH . urlencode($business_id);

        $response = $this->apiRequest($business_path);
    }

    function search() {
        return $this->apiRequest(self::SEARCH_PATH);
    }

}