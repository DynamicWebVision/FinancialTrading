<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\CurrencyIndicators;
use App\Model\HistoricalRates;
use App\Model\TmpTestRates;
use App\Model\Servers\ServerEnvironmentDef;
use App\Http\Controllers\ProcessScheduleController;
use Illuminate\Support\Facades\Config;
use App\Model\Servers;
use App\Broker\OandaV20;
use App\Services\StringHelpers;

class ProcessScheduleControllerTest extends TestCase
{

    public $transactionController;
    public $oanda;

    public function testCheckForDueProcesses() {
        $serversController = new ProcessScheduleController();
        $serversController->checkForDueProcesses();
    }

    public function testCreateMultiRecords() {
        $serversController = new ProcessScheduleController();
        $serversController->createQueueRecordsWithVariableIds('yahoo_price',
            [1]
            );
    }
}
