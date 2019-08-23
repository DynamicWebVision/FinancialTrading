<?php

namespace Tests\Unit\OandaTests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\CurrencyIndicators;
use App\Model\HistoricalRates;
use App\Model\TmpTestRates;
use App\Http\Controllers\Nursing\FlexcareController;
use App\Http\Controllers\Nursing\MedproController;
use App\Broker\OandaV20;

class FlexcareControllerTest extends TestCase
{

    public $transactionController;
    public $oanda;

    public function testGet() {
        $controller = new FlexcareController();
        $controller->getAllLinks();
    }

    public function testTemp() {
        $controller = new FlexcareController();
        $controller->tempFlexcareUrls();
    }

    public function testMedproLoad() {
        $controller = new MedproController();
        $controller->loadFromFile();
    }
}
