<?php

namespace Tests\Unit\Services;

use App\Services\Scraper;
use Tests\TestCase;

use \App\Model\Exchange;
use \App\Model\Stocks\Stocks;
use \App\Services\BackTest;
use \App\Services\YahooFinance;
use \App\Http\Controllers\Equity\YahooFinanceController;
use \App\Http\Controllers\ProcessScheduleController;

class ShopifyTest extends TestCase
{


    public function testProcessOneStock() {
        $yahooFinanceController = new YahooFinanceController();

        $yahooFinanceController->createRecentUpdateRecords();
    }

    public function __construct() {
        $this->curl = curl_init();
//        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , 'Authorization: Bearer 1d95d8dd88b59a1f7c53e7cb2886df89-3f40f99e79545ae6539aabd8b718cbb0' ));
        // curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer 1d95d8dd88b59a1f7c53e7cb2886df89-3f40f99e79545ae6539aabd8b718cbb0' ));
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.env('OANDA_AUTHORIZATION_TOKEN') ));
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
    }

    public function apiGetRequest() {

        curl_setopt($this->curl, CURLOPT_URL, $this->apiUrl);
        $resp = curl_exec($this->curl);

        return $resp;
    }

    public function testGetProducts() {
        $this->curl = curl_init();

        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($this->curl, CURLOPT_URL, 'https://abnb.me/LFyhcEYxl1');
        $resp = curl_exec($this->curl);

        $resp = json_decode($resp);

        return $resp;
    }

    public function testCreateProduct() {
        $product = [
            'product'=>[
                'title'=> 'Really Fancy Toothbrush',
                'vendor'=> 'good_teeth',
                'product_type'=>'tooth_brush',
                'tags'=> ['clean']
            ]

        ];


        $this->curl = curl_init();


        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($this->curl,CURLOPT_POSTFIELDS, json_encode($product));

        curl_setopt($this->curl,CURLOPT_POST, 1);

        curl_setopt($this->curl, CURLOPT_URL, 'https://d16645b06c184c7b658c8e18cebbc41b:cf068e17357eb14d16ecd57094d9fc5a@showplacehq.myshopify.com/admin/api/2020-01/products.json');
        $resp = curl_exec($this->curl);

        $resp = json_decode($resp);

        return $resp;
    }


}
