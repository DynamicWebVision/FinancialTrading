<?php

namespace Tests\Unit\Controllers\Marketing;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\CurrencyIndicators;
use App\Model\HistoricalRates;
use App\Model\TmpTestRates;
use App\Http\Controllers\Marketing\HarRentalController;
use App\Broker\OandaV20;

class HarRentalControllerTest extends TestCase
{
    public $transactionController;
    public $oanda;

    public function testHarRentalController() {
        $harRentalController = new HarRentalController();

        $harRentalController->oneRentalAgent(4);
    }
}
