<?php

namespace Tests\Unit\Services;

use Tests\TestCase;

use \App\Model\Exchange;
use \App\Services\BackTest;
use \App\Services\Yelp;

class YelpTest extends TestCase
{

    public function testYelp() {
        $textMessage = new Yelp();
        $textMessage->getUrlList();
    }
    public function testYelp() {
        $textMessage = new Yelp();
        $textMessage->loadUsCities();
    }

}
