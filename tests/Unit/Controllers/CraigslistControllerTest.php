<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\CurrencyIndicators;
use App\Model\HistoricalRates;
use App\Model\TmpTestRates;
use App\Http\Controllers\CraigslistController;
use App\Broker\OandaV20;

class CraigslistControllerTest extends TestCase
{

    public $transactionController;
    public $oanda;

    public function testBacktestProcessStatsSpecificProcess() {
        $livePracticeController = new CraigslistController();

        $livePracticeController->scrapeBrandFurniture();
    }
}
