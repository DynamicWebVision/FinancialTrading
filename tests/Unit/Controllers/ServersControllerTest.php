<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\CurrencyIndicators;
use App\Model\HistoricalRates;
use App\Model\TmpTestRates;
use App\Model\Servers\ServerEnvironmentDef;
use App\Http\Controllers\ServersController;
use Illuminate\Support\Facades\Config;
use App\Model\Servers;
use App\Broker\OandaV20;
use App\Services\StringHelpers;

class ServersControllerTest extends TestCase
{
    //https://www.travelnursesource.com/search-travel-nursing-recruiters/

    public $transactionController;
    public $oanda;

//    public function testUpdateIpAddresses() {
//        $serversController = new ServersController();
//
//        $serversController->reWriteServerIpLocal();
//    }

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

//    public function testAbc() {
//        $tesCont = new ServersController();
//        $check = $tesCont->seeIfPidIsRunning(3196);
//        dd($check);
//    }
//
//    public function testGetNext() {
//        $tesCont = new ServersController();
//        $check = $tesCont->getNextBackTestGroupForServer();
//    }
//
//    public function testAbcd() {
//        $tesCont = new ServersController();
//        $check = $tesCont->killIfProcessOverMinuteThreshold();
//    }
//
//    public function testCreateEnvironmentRecords() {
//        $envVariables = [['APP_ENV','utility'],
//            ['APP_DEBUG','true'],
//            ['APP_KEY','base64:dTSRA0qwpFb0tKWsQyGdiup3U2vKfKuAZ7CYm5lGW3Q='],
//            ['APP_LOG_LEVEL','emergency'],
//            ['DB_HOST','35.174.153.6'],
//            ['DB_DATABASE','currency'],
//            ['DB_USERNAME','admin'],
//            ['DB_PASSWORD','BalencestoHockey96321'],
//            ['APP_LOG','daily'],
//            ['APP_ROOT','/var/www/FinancialTrading/'],
//            ['LOG_STRATEGY','0'],
//            ['SERVER_ID','3'],
//            ['SERVER_NAME','LIVE_PRACTICE'],
//            ['OANDA_API_URL','https://api-fxpractice.oanda.com/v3/'],
//            ['OANDA_AUTHORIZATION_TOKEN','a2ffe2a48d4bda741341a65abf86789c-f299b9116316898d11e3a8fc86e74b36'],
//            ['HISTORICAL_DATA_CURRENCY','4'],
//            ['HISTORICAL_DATA_FREQUENCY','3'],
//            ['UTILITY_DB_HOST','52.88.231.119'],
//            ['UTILITY_DB_DATABASE','utility'],
//            ['UTILITY_DB_USERNAME','utility_mgmt'],
//            ['UTILITY_DB_PASSWORD','BalencestoHockey96321'],
//            ['CACHE_DRIVER','file'],
//            ['SESSION_DRIVER','file'],
//            ['QUEUE_DRIVER','sync'],
//            ['REDIS_HOST','localhost'],
//            ['REDIS_PASSWORD','null'],
//            ['REDIS_PORT','6379'],
//            ['MAIL_DRIVER','smtp'],
//            ['MAIL_HOST','mailtrap.io'],
//            ['MAIL_PORT','2525']];
//
//        foreach ($envVariables as $envVar) {
//            $eVarRecord = new ServerEnvironmentDef();
//
//            $eVarRecord->type = 'worker';
//            $eVarRecord->env_variable = $envVar[0];
//            $eVarRecord->env_variable_value = $envVar[1];
//
//            $eVarRecord->save();
//        }
//    }

//    public function testRecreateStuff() {
//        $serversController = new ServersController();
//        $test = $serversController->updateEnvironmentDBHost();
//        $this->assertEquals('currency', $test);
//    }

//    public function testSetServerId() {
//        $serversController = new ServersController();
//        $serversController->requestSmallMiniFleetFor23Hours();
//    }

    public function testSetServerId() {
        $serversController = new ServersController();
        //$serversController->backupDbWithImageDeleteOld();

        $debug = file_get_contents('http://169.254.169.254/latest/meta-data/instance-id');
        echo $debug;
    }
}
