<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\CurrencyIndicators;
use App\Model\HistoricalRates;
use App\Model\TmpTestRates;
use App\Http\Controllers\LivePracticeController;
use App\Http\Controllers\TransactionController;
use App\Broker\OandaV20;

class TransactionControllerTest extends TestCase
{

    public $transactionController;
    public $oanda;

    public function testBacktestProcessStatsSpecificProcess() {
        $transactionController = new TransactionController();
        $transactionController->getOandaTransactions();
    }

    public function testSavePracticeTransactions() {
        $transactionController = new TransactionController();
        $transactionController->savePracticeTransactions();
    }

    public function testAAA() {
        $test = env('alksdfjaksldfjks');
    }
}
