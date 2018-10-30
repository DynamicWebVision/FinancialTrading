<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\CurrencyIndicators;
use App\Model\HistoricalRates;
use App\Model\TmpTestRates;
use App\Http\Controllers\BackTestStatsController;
use App\Broker\OandaV20;

class BackTestStatsControllerTest extends TestCase
{

    public $transactionController;
    public $oanda;

    public function testBacktestProcessStatsSpecificProcess() {
        $backTestStatsController = new BackTestStatsController();

        $processId = 196173;

        $backTestStatsController->backtestProcessStatsSpecificProcess($processId);
    }
}
