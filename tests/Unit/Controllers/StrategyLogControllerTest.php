<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\CurrencyIndicators;
use App\Model\HistoricalRates;
use App\Model\TmpTestRates;
use App\Http\Controllers\StrategyLogController;
use Illuminate\Support\Facades\Config;
use App\Model\Servers;
use App\Broker\OandaV20;

class StrategyLogControllerTest extends TestCase
{

    public $transactionController;
    public $oanda;

    public function testDeleteOldLogs() {
        $serversController = new StrategyLogController();

        $serversController->deleteOldStrategyLogs();
    }
}
