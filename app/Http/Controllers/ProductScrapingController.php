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
use App\Model\ProductAdvice\ProductCategory;
use App\Model\ProductAdvice\ProductTypePricePoint;
use App\Model\Yelp\YelpLocationCategory;
use App\Model\Yelp\YelpLocationEmail;
use App\Services\ProcessLogger;

class ProductScrapingController extends Controller
{
    public $lustreObject;

    protected $products = [];

    public function __construct()
    {
        $this->scraper = new Scraper();
    }

    public function getRoot() {
        $contents = $this->scraper->getCurl('https://lustre.ai/');

        $debug = 1;
    }

    public function parseJsonData() {

        $decoded = json_decode($json);

        foreach ($decoded->entities->ml_category as $arr) {
            $productCategory = new ProductCategory();

            $productCategory->product_category_id = 1;
            $productCategory->name = $arr->name;
            $productCategory->plural_name = $arr->plural_name;
            $productCategory->url_slug = $arr->url_slug;

            $productCategory->save();
        }

        $debug = 1;
    }

    protected function getProductTypeIds() {
            foreach ($this->lustreObject->entities->product_type as $key => $value) {
                $prod_node_id = $this->scraper->getTextAfterString('nod_prod_', $key);

                $this->products[$prod_node_id] = [
                    'node_product_id' => $prod_node_id,
                    'name' => $value->name
                ];
            }
    }

    protected function getProductTypeMediaIds() {
            $media =  $this->lustreObject->attributes->product_type->product_type_media_ids;
            foreach ($media as $key => $value) {
                $prod_node_id = $this->scraper->getTextAfterString('nod_prod_', $key);

                if (isset($this->lustreObject->entities->media->{$value[0]})) {
                    $this->products[$prod_node_id]['image_url'] = $this->lustreObject->entities->media->{$value[0]}->source_url;
                }
            }
    }

    protected function getRatings() {
            $ratingsProducts =  $this->lustreObject->attributes->product_type->product_type_latest_rating_ids;
            foreach ($ratingsProducts as $key => $value) {

                $prod_node_id = $this->scraper->getTextAfterString('nod_prod_', $key);

                $this->products[$prod_node_id]['ratings'] = [];

                foreach ($value as $rating) {
                    $rating = $this->lustreObject->entities->rating->{$rating};

                    $crawl = $this->lustreObject->entities->crawl->{$rating->crawl_id};
                    $source = $this->lustreObject->entities->source->{$crawl->source_id};
                    $this->products[$prod_node_id]['ratings'][] = [
                        'type' => $rating->rating_type,
                        'value' => $rating->value,
                        'source' => $source,
                        'crawl' => $crawl
                        ];
                }
            }
    }

    protected function getProductPages() {
         $products_page_sets =  $this->lustreObject->attributes->product_type->product_type_preferred_store_pages_set;

            foreach ($products_page_sets as $key => $value) {
                $prod_node_id = $this->scraper->getTextAfterString('nod_prod_', $key);
                $this->products[$prod_node_id]['pages'] = [];

                foreach ($value as $page) {
                    $fullPageData = $this->lustreObject->entities->store_page->{$page->store_page_id};
                    $this->products[$prod_node_id]['pages'][] = [
                        'amount' => $page->price->amount,
                        'url' => $fullPageData->url,
                        'store' => $fullPageData->store,
                        'sku' => $fullPageData->sku,
                        'product_name' => $fullPageData->product_name,
                        'is_api_restricted' => $fullPageData->is_api_restricted,
                        'is_invalid' => $fullPageData->is_invalid,
                        'out_of_stock_at' => $fullPageData->out_of_stock_at
                    ];

                }
            }
    }

    public function getJsonData() {
        $contents = $this->scraper->getCurl('https://lustre.ai/air-purifier');

        $jsonString = $this->scraper->getInBetween($contents, 'window.jsonData = ','</script>');

        $this->lustreObject = json_decode($jsonString);

        $this->getProductTypeIds();
        $this->getProductTypeMediaIds();
        $this->getRatings();
        $this->getProductPages();

        $debug=1;
    }

    public function testTwoStrings() {
        $cheap = $this->scraper->getCurl('https://lustre.ai/air-purifier?price_intent=9000');

        $jsonStringCheap = $this->scraper->getInBetween($cheap, 'window.jsonData = ','</script>');

        $expensive = $this->scraper->getCurl('https://lustre.ai/air-purifier?price_intent=50900');

        $jsonStringExpensive = $this->scraper->getInBetween($expensive, 'window.jsonData = ','</script>');



        $this->getProductTypeIds();
        $this->getProductTypeMediaIds();
        $this->getRatings();
        $this->getProductPages();

        $debug=1;
    }

    public function populatePriceLevels($productType) {
        $contents = $this->scraper->getCurl('https://lustre.ai/'.$productType->url_slug);

        $jsonString = $this->scraper->getInBetween($contents, 'window.jsonData = ','</script>');

        $this->lustreObject = json_decode($jsonString);

        foreach ($this->lustreObject->responses->categoryRankings as $rankingEntities) {
            foreach ($rankingEntities as $entity) {
                $productTypePricePoint = ProductTypePricePoint::firstOrCreate(
                    ['price_point' => $entity->groupLabelPrice->amount,
                    'product_type_id' => $productType->id]
                );
            }
        }
    }
}
