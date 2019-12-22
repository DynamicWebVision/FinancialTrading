<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\CurrencyIndicators;
use App\Model\HistoricalRates;
use App\Model\TmpTestRates;
use App\Http\Controllers\Equity\FinancialModelingPrepController;
use App\Broker\OandaV20;

class LivePracticeControllerTest extends TestCase
{

    public $transactionController;
    public $oanda;

    public function testBacktestProcessStatsSpecificProcess() {
        $financialModelingController = new FinancialModelingPrepController();

        $financialModelingController->stockIncomeStatements(4542);
    }
    public function testWeeklyJobs() {
        $financialModelingController = new FinancialModelingPrepController();

        $financialModelingController->createWeeklyFinancialJobs();
    }
}
