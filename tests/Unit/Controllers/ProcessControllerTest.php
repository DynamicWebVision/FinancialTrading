<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\CurrencyIndicators;
use App\Model\HistoricalRates;
use App\Model\TmpTestRates;
use App\Http\Controllers\ProcessController;
use App\Broker\OandaV20;

class ProcessControllerTest extends TestCase
{

    public $transactionController;
    public $oanda;

    public function testRunOne() {
        $livePracticeController = new ProcessController();

        $livePracticeController->serverRunCheck();
    }
}
