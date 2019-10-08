<?php namespace App\Services;

use App\Services\Scraper;

class Yelp  {

    protected $scraper;

    public $symbol;
    public $start_date;
    public $end_date;
    public $logger;

    public function __construct() {
        $this->scraper = new Scraper();
    }

    public function getUrlList() {
        $url = 'https://www.yelp.com/search?find_desc=Restaurants&find_loc=San%20Francisco%2C%20CA';

        $yelp_response = $this->scraper->getCurl($url);



    }
}