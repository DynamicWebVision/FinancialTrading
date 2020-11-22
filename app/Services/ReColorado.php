<?php namespace App\Services;

use App\Services\Scraper;
use App\Model\ReColoradoListing;
use App\Model\ReColoradoListingPriceDrop;

class ReColorado  {

    protected $scraper;

    public $symbol;
    public $start_date;
    public $end_date;
    public $logger;
    public $listingId;

    public $listingResponse;

    public function __construct() {
        $this->scraper = new Scraper();
    }

    public function getSearchUrls($response) {
        $rawListings = $this->scraper->getInBetween($response, '<div class="photo-results " data-id="photo-results">', '<nav class="pagination" data-id="results-pagination"');

        $urls = $this->scraper->getAllLinksInText($rawListings);

        return array_unique($urls);
    }

    public function initialSearch() {
        $pageNumber = 1;

        $lower = 300000;

        while ($lower < 825000) {
            $higher = $lower + 25000;
            while ($pageNumber < 4) {
                if ($pageNumber == 1) {
                    $url = 'https://www.recolorado.com/find-real-estate/co/denver/type-single_family/type-multi_family/from-'.$higher.'/to-800000/1-pg/newestfirst-dorder/for_sale-listingstatus/photo-tab/';
                }
                else {
                    $url = 'https://www.recolorado.com/find-real-estate/co/denver/type-single_family/type-multi_family/from-400000/to-800000/'.$pageNumber.'-pg/newestfirst-dorder/for_sale-listingstatus/photo-tab/';
                }

                $response = $this->scraper->getCurl($url);

                $uniqueUrls = $this->getSearchUrls($response);

                $this->saveUrls($uniqueUrls);

                $pageNumber = $pageNumber + 1;

                sleep(rand(10, 24));
            }
            $lower = $lower + 25000;
        }
    }

    public function saveUrls($urls) {
        foreach ($urls as $listingUrl) {
            $newPossibleRental = ReColoradoListing::firstOrNew(['re_colorado_url' => $listingUrl]);
            $newPossibleRental->save();
        }
    }

    public function parseDataItem($DataItem) {
        if ($this->scraper->inString($this->listingResponse, $DataItem)) {
            $firstStep = $this->scraper->getInBetween($this->listingResponse, $DataItem.':</strong>', '</span>');
            return trim($this->scraper->getToEnd($firstStep, 'details--item___description">'));
        }
        else {
            return null;
        }
    }

    public function parseHtmlProp($DataItem) {
        if ($this->scraper->inString($this->listingResponse, $DataItem)) {
            return trim($this->scraper->getInBetween($this->listingResponse, '<span hmsitemprop="'.$DataItem.'">', '</span>'));
        }
        else {
            return null;
        }
    }

    public function getListedOnDate() {
        $date = trim($this->scraper->getInBetween($this->listingResponse, 'Listed on</span>', '</li>'));
        return date("Y-m-d", strtotime($date));
    }

    public function getListingStatus() {
        return trim($this->scraper->getInBetween($this->listingResponse, 'ListingStatus":"', '",'));
    }

    public function fetchOneListing($re_colorado_listing_id) {
        $listing = ReColoradoListing::find($re_colorado_listing_id);

        $this->listingResponse = $this->scraper->getCurl('https://www.recolorado.com'.$listing->re_colorado_url);
        $this->listingResponse = $this->scraper->getCurl('https://www.recolorado.com'.$listing->re_colorado_url);

        $listing->street_address = $this->parseHtmlProp('streetAddress');
        $listing->city = $this->parseHtmlProp('addressLocality');
        $listing->zip = $this->parseHtmlProp('postalCode');
        $listing->mls_number = $this->parseHtmlProp('MlsNumber');
        $listing->price = $this->parseHtmlProp('Price');
        $listing->latitude = $this->parseHtmlProp('Latitude');
        $listing->longitude = $this->parseHtmlProp('Longitude');
        $listing->listing_date = $this->getListedOnDate();

        $listing->property_type = $this->parseDataItem('Property Type');


        $listing->property_type = $this->parseDataItem('Property Type');
        $listing->propert_sub_type = $this->parseDataItem('Property Subtype');
        $listing->ownership = $this->parseDataItem('Ownership');
        $listing->structure_type = $this->parseDataItem('Structure Type');
        $listing->architecture = $this->parseDataItem('Architecture');
        $listing->subdivision = $this->parseDataItem('Subdivision');
        $listing->levels = $this->parseDataItem('Levels');
        $listing->garage = $this->parseDataItem('Garage');
        $listing->beds = $this->parseDataItem('Beds');
        $listing->stories = $this->parseDataItem('Stories');
        $listing->hoa = $this->parseDataItem('HOA YN');
        $listing->full_baths = $this->parseDataItem('Full Baths');
        $listing->three_fourth_baths = $this->parseDataItem('3/4 Baths');
        $listing->half_baths = $this->parseDataItem('1/2 Baths');
        $listing->one_fourth_baths = $this->parseDataItem('1/4 Baths');
        $listing->parking_type = $this->parseDataItem('Parking Type');
        $listing->parking_spaces = $this->parseDataItem('Parking Spaces Number Of');
        $listing->parking_total = $this->parseDataItem('Parking Total');
        $listing->total_offstreet = $this->parseDataItem('Total Offstreet Spaces');
        $listing->has_basement = $this->parseDataItem('Basement YN');
        $listing->basement_finished = $this->parseDataItem('Basement');
        $listing->sqft_finished = $this->parseDataItem('SqFt Finished');
        $listing->sqft_above = $this->parseDataItem('SqFt Above');
        $listing->sqft_bsmmt_finished = $this->parseDataItem('SqFt Basement Finished');
        $listing->sqft_bsmmt_unfinished = $this->parseDataItem('SqFt Basement Unfinished');
        $listing->psf_total = $this->parseDataItem('PSF Total');
        $listing->psf_finished = $this->parseDataItem('PSF Finished');
        $listing->psf_above_grade = $this->parseDataItem('PSF Above Grade');
        $listing->price_per_acre = $this->parseDataItem('Price Per Acre');
        $listing->year_built = $this->parseDataItem('Year Built');
        $listing->construction_materials = $this->parseDataItem('Construction Materials');
        $listing->special_listing_cond = $this->parseDataItem('Special Listing Conditions');
        $listing->cooling = $this->parseDataItem('Cooling');
        $listing->heating = $this->parseDataItem('Heating');
        $listing->zoning = $this->parseDataItem('Zoning');
        $listing->faces = $this->parseDataItem('Faces');
        $listing->status = $this->getListingStatus();

        $listing->lost_size = trim($this->scraper->getInBetween($this->listingResponse, 'listing--detail__lot-size">', '<span'));

        $listing->save();

    }

    public function checkPriceHistory() {
        if ($this->scraper->inString($this->listingResponse, 'table__pricehistory')) {
            $text = $this->scraper->getInBetween($this->listingResponse, 'table__pricehistory', '</section>');

            $blocks = $this->scraper->getInBetweenSubBlocks($text, 'table--row__pricehistory', '</ul>');

            foreach ($blocks as $block) {
                $block = $this->scraper->getToEnd($block, 'table--row__pricehistory');
                $singlePriceChangeSet = $this->scraper->getInBetweenSubBlocks($block, 'table--field__pricehistory table--field__body">', '</li>');

                $changeDate = date("Y-m-d", strtotime($singlePriceChangeSet[0]));


                $existCount = ReColoradoListingPriceDrop::where('re_colorado_listing_id', '=', $this->listingId)
                                    ->where('event_date', '=', $changeDate)->count();

                if ($existCount <1) {
                    $listingDrop = new ReColoradoListingPriceDrop();

                    $listingDrop->re_colorado_listing_id = $this->listingId;
                    $listingDrop->event_date = $changeDate;
                    $listingDrop->event_type = $singlePriceChangeSet[1];
                    $listingDrop->price = preg_replace("/[^0-9.]/", "", $singlePriceChangeSet[2]);
                    $listingDrop->change_amount = preg_replace("/[^0-9.]/", "", $this->scraper->getInBetween($singlePriceChangeSet[3], '$', '<span'));
                    $listingDrop->change_percent = $this->scraper->getInBetween($singlePriceChangeSet[3], '>', '%</span');

                    $listingDrop->save();
                }
            }

        }
    }
}