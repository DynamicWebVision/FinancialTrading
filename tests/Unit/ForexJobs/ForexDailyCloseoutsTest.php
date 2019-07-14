<?php

namespace Tests\Unit\ForexJobs;

use Tests\TestCase;

use \App\Model\Exchange;
use \App\Broker\IexTrading;
use \App\Http\Controllers\Equity\StocksCompanyProfileController;
use \App\ForexJobs\ForexDailyCloseouts;

class ForexDailyCloseoutsTest extends TestCase
{

    public function testGetCompanyProfile()
    {
        $fxDailyCloseouts = new ForexDailyCloseouts();
        $fxDailyCloseouts->closeAccounts();
    }
}