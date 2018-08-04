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
use \App\Strategy\EmaMomentum\EmaXAdxConfirmWithMarketIfTouched;
use \App\Http\Controllers\AutomatedBackTestController;

class AutomatedBTTest extends TestCase
{

    public $frequencyId;
    public $currencyId;

    public $backtest;


    public function testSpecificProcessId() {
        $automatedBackTestController = new AutomatedBackTestController();

        $automatedBackTestController->environmentVariableDriveProcessId(180513);
    }
}
