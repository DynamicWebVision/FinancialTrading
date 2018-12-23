<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\CurrencyIndicators;
use App\Model\HistoricalRates;
use App\Model\TmpTestRates;
use App\Http\Controllers\ServersController;
use App\Broker\OandaV20;

class ServersControllerTest extends TestCase
{

    public $transactionController;
    public $oanda;

    public function testUpdateIpAddresses() {
        $serversController = new ServersController();

        $serversController->reWriteServerIpLocal();
    }

    public function testGetLastGitPull() {
        $serversController = new ServersController();
        $lastGitPullTime = $serversController->getLastGitPullTime();
        $this->assertEquals(1);
    }
}
