<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use \App\Model\Exchange;
use \App\Services\BackTest;
use \App\BackTest\TakeProfitStopLossTest;
use \App\BackTest\IndicatorRunThroughTest;
use \App\Model\HistoricalRates;
use \App\Model\TmpTestRates;
use \App\Http\Controllers\LivePracticeController;

class LivePracticeTest extends TestCase
{

    public $livePracticeController;

    public function __construct()
    {
        $this->livePracticeController = new LivePracticeController();
    }

    public function testEmaXAdxConfirmWithMarketIfTouched() {
        $this->livePracticeController->emaXAdxConfirmWithMarketIfTouched();
    }

    public function testEmaXAdxConfirmWithMarketIfTouchedHr() {
        $this->livePracticeController->emaXAdxConfirmWithMarketIfTouchedHr();
    }
}
