<?php

namespace Tests\Unit\Services;

use Tests\TestCase;

use \App\Model\Exchange;
use \App\Services\AirbnbService;

class AirbnbServiceTest extends TestCase
{

    public function testSearch() {
        $tiingo = new AirbnbService();
        $tiingo->search2();
    }

//    public function testUserInfo() {
//        $tiingo = new AirbnbService();
//        $tiingo->userInfo();
//    }
//
//    public function testReviews() {
//        $tiingo = new AirbnbService();
//        $tiingo->getListingReviews();
//    }
//
//    public function testVanityUrl() {
//        $tiingo = new AirbnbService();
//        $tiingo->vanityUrl();
//    }

}
