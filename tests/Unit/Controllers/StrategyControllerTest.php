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

class StrategyControllerTest extends TestCase
{

    public $transactionController;
    public $oanda;

    public function testBacktestProcessStatsSpecificProcess() {
   $test =     $this->post('/strategy', [
            'back_test_strategy_variable'=> "TEST_ABCeeDEFG",
            'description'=>"TestGroupAbcderg",
            'name'=>"TestIIIBBB",
            'namespace'=> "asdfasdfasdf"
        ]);

   $test = 1;
    }
}
