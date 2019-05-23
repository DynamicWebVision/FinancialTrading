<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\CurrencyIndicators;
use App\Model\HistoricalRates;
use App\Model\TmpTestRates;
use App\Http\Controllers\LivePracticeController;
use App\Broker\OandaV20;

class LivePracticeControllerTest extends TestCase
{

    public $transactionController;
    public $oanda;

    public function testBacktestProcessStatsSpecificProcess() {
        $livePracticeController = new LivePracticeController();

        $livePracticeController->hmaHour();
    }

    public function testDailyPreviousPriceBreakoutCheck() {
        $livePracticeController = new LivePracticeController();

        $livePracticeController->fourHourPriceBreakout();
    }

    public function testDailyPreviousPriceBreakout() {
        $livePracticeController = new LivePracticeController();

        $livePracticeController->hma4HSetHoldPeriods();
    }

    public function testSDFSDF() {
        $livePracticeController = new LivePracticeController();

        $livePracticeController->dailyRatesCheck();
    }

    public function testSDFSDDF() {
        $livePracticeController = new LivePracticeController();

        $livePracticeController->closeWeeklyAccounts();
    }
}
