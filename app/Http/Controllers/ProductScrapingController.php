<?php

namespace App\Http\Controllers;

use Request;
use App\Http\Controllers\ProcessScheduleController;
use App\Model\ProductAdvice\ListingImage;
use App\Model\ProductAdvice\ProductImage;
use App\Model\ProductAdvice\ReviewRatingSource;
use App\Model\ProductAdvice\ReviewRatingType;
use App\Model\ProductAdvice\Review;

use App\Services\Yelp;
use App\Services\Scraper;
use App\Model\Yelp\YelpCategories;
use App\Model\Yelp\Cities;
use App\Model\Yelp\YelpCityTracker;
use App\Model\Yelp\YelpLocation;
use App\Model\ProductAdvice\ProductCategory;
use App\Model\ProductAdvice\ProductTypePricePoint;
use App\Model\ProductAdvice\Product;
use App\Model\ProductAdvice\Vendor;
use App\Model\ProductAdvice\ProductVendor;
use App\Model\ProductAdvice\ProductType;
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

                if (isset($value[0])) {
                    if (isset($this->lustreObject->entities->media->{$value[0]})) {
                        $this->products[$prod_node_id]['image_url'] = $this->lustreObject->entities->media->{$value[0]}->source_url;
                    }
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

    public function getJsonData($productTypePricePointId) {
        $productTypePricePoint = ProductTypePricePoint::find($productTypePricePointId);
        $productType = ProductType::find($productTypePricePoint->product_type_id);


        $contents = $this->scraper->getCurl('https://lustre.ai/'.$productType->url_slug.'?price_intent='.$productTypePricePoint->price_point);

        $jsonString = $this->scraper->getInBetween($contents, 'window.jsonData = ','</script>');

        $this->lustreObject = json_decode($jsonString);

        $this->getProductTypeIds();
        $this->getProductTypeMediaIds();
        $this->getRatings();
        $this->getProductPages();

        $this->storeProducts($productTypePricePoint->product_type_id);
    }

    public function lustreDump() {
        $post = Request::all();

        $this->processJsonObject($post['jsonObject'], $post['productTypeId']);
    }

    public function processJsonObject($json, $productTypeId) {
        $this->lustreObject = json_decode($json);

        $this->getProductTypeIds();
        $this->getProductTypeMediaIds();
        $this->getRatings();
        $this->getProductPages();

        $this->storeProducts($productTypeId);
    }

    public function storeProducts($productTypeId) {
        foreach ($this->products as $product) {
            if (sizeof($product) > 2) {
                $dbProduct = Product::firstOrNew(['third_party_id' => $product['node_product_id']]);

                if (!$dbProduct->exists) {
                    $dbProduct->name = $product['name'];
                    $dbProduct->product_type_id = $productTypeId;

                    $dbProduct->save();

                    $productImage = new ProductImage();

                    $productImage->product_id = $dbProduct->id;
                    $productImage->image_url = $product['image_url'];

                    $productImage->save();
                }

                if (isset($product['pages'])) {
                    foreach ($product['pages'] as $page) {
                        $vendor = Vendor::firstOrNew(['name' => $page['store']]);

                        if (!$vendor->exists) {
                            $vendor->save();
                        }

                        $productVendor = ProductVendor::firstOrNew(['product_id' => $dbProduct->id, 'vendor_id'=>$vendor->id]);

                        if (!$productVendor->exists) {
                            $productVendor->save();
                        }

                        $productVendor->price = $page['amount']/100;
                        $productVendor->url = $page['url'];
                        $productVendor->sku = $page['sku'];
                        $productVendor->vendor_desc = $page['product_name'];
                        //$productVendor->out_of_stock = $page['out_of_stock_at'];
                        //$productVendor->invalid = $page['is_invalid'];
                        //$productVendor->api_restricted = $page['is_api_restricted'];

                        $productVendor->save();

                    }
                }

                if (isset($product['ratings'])) {
                    foreach ($product['ratings'] as $rating) {
                        $ratingType = ReviewRatingType::firstOrNew(['code' => $rating['type']]);

                        if (!$ratingType->exists) {
                            $ratingType->save();
                        }

                        $source = ReviewRatingSource::firstOrNew(['third_party_id' => $rating['source']->source_id]);

                        $source->review_rating_type_id = 1;
                        $source->name = $rating['source']->name;
                        $source->host = $rating['source']->host;
                        $source->favicon_url = $rating['source']->favicon_url;
                        $source->logo_url = $rating['source']->logo_url;

                        $source->save();

                        $review = Review::firstOrNew(['third_party_id' => $rating['crawl']->crawl_id]);

                        $review->review_rating_type_id = $ratingType->id;
                        $review->url = $rating['crawl']->url;
                        $review->title = $rating['crawl']->title;
                        $review->published_date = $rating['crawl']->published_at;
                        $review->value = json_encode($rating['value']);
                        $review->product_id = $dbProduct->id;
                        $review->review_rating_source_id = $source->id;

                        $review->save();
                    }
                }
            }
        }
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
        $this->storeProducts();

        $debug=1;
    }

    public function populatePriceLevels($productType) {
        $contents = $this->scraper->getCurl('https://lustre.ai/'.$productType->url_slug);

        $jsonString = $this->scraper->getInBetween($contents, 'window.jsonData = ','</script>');

        $this->lustreObject = json_decode($jsonString);

        if (isset($this->lustreObject->responses->categoryRankings)) {
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

    public function listingImages() {
        $listings = \App\Model\ProductAdvice\Listing::get();


        foreach ($listings as $listing) {
            if ($this->scraper->inString($listing->airbnb_data, 'jpg') ) {
                $count = ListingImage::where('listing_id', '=', $listing->id)->count();

                if ($count == 0) {
                    $airbnb_data = json_decode($listing->airbnb_data);


                    $listingImage = new ListingImage();

                    $listingImage->listing_id = $listing->id;
                    $listingImage->image_url = $airbnb_data->images[0];

                    $listingImage->save();
                }
            }
        }
        $debug=1;
    }
}
