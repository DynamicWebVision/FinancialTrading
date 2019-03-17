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

    public function testStatusCode() {
        $output = shell_exec('ps aux');

        $separator = "\r\n";
        $line = strtok($output, $separator);

        while ($line !== false) {
            # do something with $line
            $line = strtok( $separator );
            $lineAsArray = preg_split('/\s+/', $line);
            echo json_encode($lineAsArray);

//            if (isset($lineAsArray[10])) {
//                if ($lineAsArray[10] == 'php artisan schedule:run') {
//                    echo $lineAsArray[9];
//                }
//            }
//            else {
//                echo json_encode($lineAsArray);
//            }

        }
    }
}
