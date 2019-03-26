<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\CurrencyIndicators;
use App\Model\HistoricalRates;
use App\Model\TmpTestRates;
use App\Http\Controllers\ServersController;
use Illuminate\Support\Facades\Config;
use App\Model\Servers;
use App\Broker\OandaV20;
use App\Services\StringHelpers;

class ServersControllerTest extends TestCase
{

    public $transactionController;
    public $oanda;

//    public function testUpdateIpAddresses() {
//        $serversController = new ServersController();
//
//        $serversController->reWriteServerIpLocal();
//    }
//
//    public function testGetLastGitPull() {
//        $serversController = new ServersController();
//        $lastGitPullTime = $serversController->getLastGitPullTime();
//        $this->assertEquals(1546457963, $lastGitPullTime);
//    }

//    public function testSetServerEnvironmentTest() {
//        $serversController = new ServersController();
//        $serversController->setServerEnvironment();
//    }

//    public function testTaskCode() {
//        $server_id  = Config::get('server_id');
//
//        $server = Servers::find(Config::get('server_id'));
//
//        $this->assertEquals('fx_maintenance', $server->task_code);
//    }
//    public function testTaskCode() {
//        $controller = new ServersController();
//        $controller->setServerId();
//    }

//    public function testStatusCode() {
//        $tesCont = new ServersController();
//        $tesCont->killIfProcessOverMinuteThreshold();
//    }

    public function testAbc() {
        $tesCont = new ServersController();
        $check = $tesCont->seeIfPidIsRunning(3196);
        dd($check);
    }

    public function testGetNext() {
        $tesCont = new ServersController();
        $check = $tesCont->getNextBackTestGroupForServer();
    }
}
