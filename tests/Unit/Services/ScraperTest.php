<?php

namespace Tests\Unit\Services;

use App\Services\Scraper;
use Tests\TestCase;

use \App\Model\Exchange;
use \App\Model\Stocks\Stocks;
use \App\Services\YahooFinance;
use \App\Http\Controllers\Equity\YahooFinanceController;
use \App\Http\Controllers\ProcessScheduleController;

class ScraperTest extends TestCase
{


    public function testProcessOneStock() {
        $scraper = new Scraper();

        $methodResponse = $scraper->getHostPeriodPosition('ajskd_lfajskldf@gmail.com');
        $strposResponse = strpos('ajskdl.fajskldf@gmail.com', '.');

        $this->assertEquals($methodResponse, $strposResponse);
    }

    public function testCheckEmail() {
        $scraper = new Scraper();

        $methodResponse = $scraper->checkEmailExtension('ajskd_lfajskldf@gmail.com');
        $strposResponse = strpos('ajskdl.fajskldf@gmail.com', '.');

        $this->assertEquals($methodResponse, $strposResponse);
    }


}
