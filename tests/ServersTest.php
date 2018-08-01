<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Services\Utility;
use App\StrategyEvents\RsiEvents;
use App\Http\Controllers\ServersController;

class ServersTest extends TestCase
{
    public function testGetNextBackTestGroupForServer()
    {
        $serversController = new ServersController();

        $response = $serversController->getNextBackTestGroupForServer();

        $this->assertEquals($response->current_back_test_group_id, 164);
    }
}
