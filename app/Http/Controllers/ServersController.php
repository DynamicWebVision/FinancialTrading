<?php namespace App\Http\Controllers;

use App\Model\BackTestGroup;
use \App\Model\Servers;
use \App\Model\Strategy;
use \App\Model\StrategySystem;
use App\Model\BackTestToBeProcessed;
use \DB;
use \Log;
use Request;
use App\Services\AwsService;

class ServersController extends Controller {

    public function index() {
        $servers = Servers::get()->toArray();

        foreach ($servers as $index=>$server) {
            $servers[$index]['updated_at'] = date( 'm/d H:i', strtotime($server['updated_at']));

            if ($server['current_back_test_group_id'] != 0) {
                $servers[$index]['start_count'] = BackTestToBeProcessed::where('start', '=', 0)->where('finish', '=', 0)->where('back_test_group_id', '=', $server['current_back_test_group_id'])->count();
                $servers[$index]['finish_count'] = BackTestToBeProcessed::where('finish', '=', 1)->where('back_test_group_id', '=', $server['current_back_test_group_id'])->count();

                $servers[$index]['stats_left'] = BackTestToBeProcessed::where('stats_start', '=', 0)->where('hung_up', '!=', 1)->where('back_test_group_id', '=', $server['current_back_test_group_id'])->count();
            }
            else {
                $servers[$index]['start_count'] = 0;
                $servers[$index]['finish_count'] = 0;

                $servers[$index]['stats_left'] = 0;
            }
        }
        return $servers;
    }

    public function updateServers() {
        $post = Request::all();

        foreach ($post as $server) {
            $dbServer = Servers::find($server['id']);

            $dbServer->name = $server['name'];
            $dbServer->current_task = $server['current_task'];
            $dbServer->task_id = $server['task_id'];
            $dbServer->ip_address = $server['ip_address'];
            $dbServer->command_start = $server['command_start'];

            $dbServer->current_back_test_group_id = $server['current_back_test_group_id'];
            $dbServer->current_back_test_strategy = $server['current_back_test_strategy'];
            $dbServer->strategy_iteration = $server['strategy_iteration'];
            $dbServer->rate_unix_time_start = $server['rate_unix_time_start'];

            $dbServer->job_finished = 0;

            $dbServer->save();
        }
    }

    public function updateLocalToServer() {
        $post = Request::all();

        $dbServer = Servers::find(6);

        $dbServer->current_back_test_group_id = $post['current_back_test_group_id'];
        $dbServer->current_back_test_strategy = $post['current_back_test_strategy'];
        $dbServer->strategy_iteration = $post['strategy_iteration'];
        $dbServer->rate_unix_time_start = $post['rate_unix_time_start'];
        $dbServer->job_finished = 0;


        $dbServer->save();
    }

    public function updateSeverToBeLocal() {
        $post = Request::all();

        $localServer = Servers::find(6);

        $backTestServer = Servers::find($post['id']);

        $backTestServer->current_back_test_group_id = $localServer->current_back_test_group_id;
        $backTestServer->current_back_test_strategy = $localServer->current_back_test_strategy;
        $backTestServer->strategy_iteration = $localServer->strategy_iteration;
        $backTestServer->rate_unix_time_start = $localServer->rate_unix_time_start;

        $backTestServer->save();
    }

    public function getNextBackTestGroupForServer() {
        $server = Servers::find(env('SERVER_ID'));

        $notFinishedCount = BackTestGroup::where((function ($query) {
            $query->where('process_run', '=', 0)
                ->orWhere('stats_run', '=', 0);
        }))->where('server', '=', env('SERVER_ID'))->count();

        if ($notFinishedCount > 0) {
            $backTestGroup = BackTestGroup::where((function ($query) {
                $query->where('process_run', '=', 0)
                    ->orWhere('stats_run', '=', 0);
            }))->where('server', '=', env('SERVER_ID'))->orderBy('priority', 'desc')->first();
        }
        else {
            $backTestGroup = BackTestGroup::where((function ($query) {
                $query->where('server', '=', 0)
                    ->orWhere('server', '=', 6);
            }))->where('strategy_system_id', '!=', 0)->orderBy('priority', 'desc')->first();
        }

        $strategy = Strategy::find($backTestGroup->strategy_id);
        $strategySystem = StrategySystem::find($backTestGroup->strategy_system_id);

        $server->current_back_test_group_id = $backTestGroup->id;
        $server->current_back_test_strategy = $strategy->back_test_strategy_variable;
        $server->strategy_iteration = $strategySystem->strategy_iteration_variable;
        $server->rate_unix_time_start = $backTestGroup->rate_unix_time_start;

        $server->save();

        $serverBackTestGroup = BackTestGroup::find($backTestGroup->id);

        $serverBackTestGroup->server = env('SERVER_ID');

        $serverBackTestGroup->save();

        return $serverBackTestGroup;
    }

    public function updateSingleServer() {
        $post = Request::all();

        $backTestInfo = \DB::select("select strategy.back_test_strategy_variable, strategy_system.strategy_iteration_variable, back_test_group.rate_unix_time_start, back_test_group.id from back_test_group
                        join strategy on back_test_group.strategy_id = strategy.id
                        join strategy_system on back_test_group.strategy_system_id = strategy_system.id
                        where back_test_group.id = ".$post['current_back_test_group_id'].";");

        $backTestInfo = $backTestInfo[0];

        $server = Servers::find($post['id']);

        $server->current_back_test_group_id = $backTestInfo->id;

        $server->current_back_test_strategy = $backTestInfo->back_test_strategy_variable;

        $server->strategy_iteration = $backTestInfo->strategy_iteration_variable;

        $server->rate_unix_time_start = $backTestInfo->rate_unix_time_start;

        $server->save();

        return $server;
    }

    public function setServerEnvironment() {
        $awsService = new AwsService();
        $awsService->setCurrentServerAttributes();


    }
}