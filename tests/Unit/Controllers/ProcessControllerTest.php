<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\CurrencyIndicators;
use App\Model\HistoricalRates;
use App\Model\TmpTestRates;
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\ProcessController;
use App\Http\Controllers\ServersController;
use App\Broker\OandaV20;

class ProcessControllerTest extends TestCase
{

    public $transactionController;
    public $oanda;

    public function testRunOne() {
        $livePracticeController = new ProcessController();

        $livePracticeController->serverRunCheck();
    }

//    public function testABcXyz() {
//        $livePracticeController = new ProcessController();
//
//        $livePracticeController->currentRunningProcessThresholdCheck();
//
//    }
}
