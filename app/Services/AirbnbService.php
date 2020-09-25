<?php namespace App\Services;

use App\Services\Scraper;
use App\Model\Yelp\YelpApi;
use \Log;

class AirbnbService  {

    protected $scraper;

    public $symbol;
    public $start_date;
    public $end_date;
    public $logger;
    public $invalidResponse;

    public $urlParams = [];

    protected $yelpApi;


// API constants, you shouldn't have to change these.
    CONST API_HOST = "https://www.airbnb.com/api/v2";
    CONST SEARCH_PATH = "/v3/businesses/search";
    CONST BUSINESS_PATH = "/v3/businesses/";  // Business ID will come after slash.


    public function __construct()
    {
        $this->urlParams['api_key'] = 'd306zoyjsyarp7ifhu67rjxn52tv0t20';
    }


    public function apiRequest($path) {
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
            ));

            $response = curl_exec($curl);

            if (FALSE === $response)
                throw new \Exception(curl_error($curl), curl_errno($curl));
            $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if (200 != $http_status)
                throw new \Exception($response, $http_status);
            $this->invalidResponse = false;
            curl_close($curl);
        } catch(\Exception $e) {
            $this->logger->logMessage($e->getMessage(), 4);
            $this->invalidResponse = true;
            return $response;
        }
        $this->logger->logMessage(substr($response, 0, 200));
        return json_decode($response);
    }

    public function searchListings($business_id) {
        $business_path = self::BUSINESS_PATH . urlencode($business_id);

        $response = $this->apiRequest($business_path);
    }

    function search() {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://www.airbnb.com/api/v3/ExploreSearch?locale=en&operationName=ExploreSearch&currency=USD&&variables=%7B%22request%22%3A%7B%22metadataOnly%22%3Afalse%2C%22version%22%3A%221.7.8%22%2C%22itemsPerGrid%22%3A20%2C%22refinementPaths%22%3A%5B%22%2Fhomes%22%5D%2C%22tabId%22%3A%22home_tab%22%2C%22checkin%22%3A%222020-10-08%22%2C%22checkout%22%3A%222020-10-22%22%2C%22adults%22%3A2%2C%22searchType%22%3A%22autocomplete_click%22%2C%22placeId%22%3A%22ChIJHxiiuRvTD4gRkjkPmU415xk%22%2C%22source%22%3A%22structured_search_input_header%22%2C%22query%22%3A%22Lincoln%20Park%2C%20Chicago%2C%20IL%2C%20United%20States%22%2C%22cdnCacheSafe%22%3Afalse%2C%22simpleSearchTreatment%22%3A%22simple_search_only%22%2C%22treatmentFlags%22%3A%5B%22monthly_stays_literal_translations%22%2C%22simple_search_1_1%22%2C%22simple_search_desktop_v3_full_bleed%22%5D%2C%22screenSize%22%3A%22large%22%7D%7D&extensions=%7B%22persistedQuery%22%3A%7B%22version%22%3A1%2C%22sha256Hash%22%3A%22fa1fc5308ea22a16971559bfe58de902cfb007c7296f74da80ec69148714d107%22%7D%7D',
            CURLOPT_RETURNTRANSFER => true,  // Capture response.
            CURLOPT_HTTPHEADER =>
                        [   'content-type: application/json',
                            'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.102 Safari/537.36',
                            'X-Airbnb-API-Key: d306zoyjsyarp7ifhu67rjxn52tv0t20',
                            'X-Airbnb-GraphQL-Platform: web',
                            'X-Airbnb-GraphQL-Platform-Client: apollo-niobe',
                            'X-CSRF-Token: V4$.airbnb.com$P3sXYxao1HM$dfAx0bOvpU1TRwsiCSwkKj2uVD7V3CunorLDf6KmJ9E=',
                            'X-CSRF-Without-Token: 1'
        ]
                ,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);

//        https://www.airbnb.com/api/v1/listings/'+airbnb_listing_id+'/reviews?api_key=d306zoyjsyarp7ifhu67rjxn52tv0t20
//        $this->urlParams['user_lat'] = 'https://www.airbnb.com/api/v1/listings/35966611/reviews?api_key=d306zoyjsyarp7ifhu67rjxn52tv0t20';
//        $this->urlParams['user_lng'] = '-95.3698';
//
//        return $this->apiRequest('/search_results');

    }

    function search2() {


        $curl = curl_init();

        $url = 'https://www.airbnb.com/api/v3/ExploreSearch?locale=en&operationName=ExploreSearch&currency=USD&variables=%7B%22request%22%3A%7B%22metadataOnly%22%3Afalse%2C%22version%22%3A%221.7.8%22%2C%22itemsPerGrid%22%3A20%2C%22refinementPaths%22%3A%5B%22%2Fhomes%22%5D%2C%22tabId%22%3A%22home_tab%22%2C%22adults%22%3A2%2C%22searchType%22%3A%22autocomplete_click%22%2C%22placeId%22%3A%22ChIJjy7R3biStocR92rZG8gQaec%22%2C%22source%22%3A%22structured_search_input_header%22%2C%22query%22%3A%22Atlanta%2C%20GA%2C%20United%20States%22%2C%22cdnCacheSafe%22%3Afalse%2C%22simpleSearchTreatment%22%3A%22simple_search_only%22%2C%22treatmentFlags%22%3A%5B%22monthly_stays_literal_translations%22%2C%22simple_search_1_1%22%2C%22simple_search_desktop_v3_full_bleed%22%5D%2C%22screenSize%22%3A%22large%22%7D%7D&extensions=%7B%22persistedQuery%22%3A%7B%22version%22%3A1%2C%22sha256Hash%22%3A%22fa1fc5308ea22a16971559bfe58de902cfb007c7296f74da80ec69148714d107%22%7D%7D';

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,  // Capture response.
            CURLOPT_HTTPHEADER =>
                        [   'content-type: application/json',
                            'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.102 Safari/537.36',
                            'X-Airbnb-API-Key: d306zoyjsyarp7ifhu67rjxn52tv0t20',
                            'X-Airbnb-GraphQL-Platform: web',
                            'X-Airbnb-GraphQL-Platform-Client: apollo-niobe',
                            'X-CSRF-Token: V4$.airbnb.com$P3sXYxao1HM$dfAx0bOvpU1TRwsiCSwkKj2uVD7V3CunorLDf6KmJ9E=',
                            'X-CSRF-Without-Token: 1'
        ]
                ,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = json_decode(curl_exec($curl));

        Log::emergency('!!!AirBnB Curl Response: '.$response);

//        https://www.airbnb.com/api/v1/listings/'+airbnb_listing_id+'/reviews?api_key=d306zoyjsyarp7ifhu67rjxn52tv0t20
//        $this->urlParams['user_lat'] = 'https://www.airbnb.com/api/v1/listings/35966611/reviews?api_key=d306zoyjsyarp7ifhu67rjxn52tv0t20';
//        $this->urlParams['user_lng'] = '-95.3698';
//
//        return $this->apiRequest('/search_results');

    }



    function userListings() {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.airbnb.com/v2/listings?api_key=d306zoyjsyarp7ifhu67rjxn52tv0t20&user_id=143717562',
            CURLOPT_RETURNTRANSFER => true,  // Capture response.
            CURLOPT_ENCODING => "",  // Accept gzip/deflate/whatever.
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = json_decode(curl_exec($curl));
        $debug = 1;
    }

    function listingInfo($listingId) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://www.airbnb.com/api/v1/listings/'.$listingId.'?api_key=d306zoyjsyarp7ifhu67rjxn52tv0t20',
            CURLOPT_RETURNTRANSFER => true,  // Capture response.
            CURLOPT_ENCODING => "",  // Accept gzip/deflate/whatever.
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = json_decode(curl_exec($curl));
        $debug = 1;
    }

    function getListingReviews() {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.airbnb.com/v2/reviews?api_key=d306zoyjsyarp7ifhu67rjxn52tv0t20&listing_id=23291336&role=all',
            CURLOPT_RETURNTRANSFER => true,  // Capture response.
            CURLOPT_ENCODING => "",  // Accept gzip/deflate/whatever.
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = json_decode(curl_exec($curl));
        $debug = 1;
    }

    function vanityUrl() {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://airbnb.com/h/hualapai-mountain-cabin',
            CURLOPT_FOLLOWLOCATION => true,  // Capture response.
            CURLOPT_RETURNTRANSFER => true,  // Capture response.
            CURLOPT_ENCODING => "",  // Accept gzip/deflate/whatever.
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $redirectURL = curl_getinfo($curl,CURLINFO_EFFECTIVE_URL );

        $response = curl_exec($curl);
        $debug = 1;
    }

}