<?php namespace App\Http\Controllers;

use App\Model\BackTestGroup;
use \App\Model\Servers;
use \App\Model\Strategy;
use \App\Model\StrategySystem;
use App\Model\BackTestToBeProcessed;
use App\Services\Utility;
use \DB;
use \Log;
use Request;
use App\Services\AwsService;
use Illuminate\Support\Facades\Config;

class ServersController extends Controller {

    public $serverId;

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
        $this->setServerId();
        
        $server = Servers::find(Config::get('server_id'));

        $notFinishedCount = BackTestGroup::where((function ($query) {
            $query->where('process_run', '=', 0)
                ->orWhere('stats_run', '=', 0);
        }))->where('server', '=', Config::get('server_id'))->count();

        if ($notFinishedCount > 0) {
            $backTestGroup = BackTestGroup::where((function ($query) {
                $query->where('process_run', '=', 0)
                    ->orWhere('stats_run', '=', 0);
            }))->where('server', '=', Config::get('server_id'))->orderBy('priority', 'desc')->first();
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

        $serverBackTestGroup->server = Config::get('server_id');

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

    public function setServerId() {
        if (env('APP_ENV') == 'local') {
            Config::set('server_id', 6);
        }
        else {
            $awsService = new AwsService();
            $awsService->setCurrentServerAttributes();
            $this->serverId = $awsService->getInstanceTagValue('server_id');

            Config::set('server_id', $this->serverId);

            $this->updateEnvironmentDBHost();
        }
    }

    public function setServerEnvironment() {
        $awsService = new AwsService();
        $awsService->setCurrentServerAttributes();
        $this->serverId = $awsService->getInstanceTagValue('server_id');


        $server = Servers::find($this->serverId);
        $server->ip_address = $awsService->currentInstance['PublicIpAddress'];
        $server->save();
    }

    public function reWriteServerIpLocal() {
        $file = '/Users/boneill/BashAliases/FinancialServers';
        unlink($file);
        $handle = fopen($file, 'w');


        $server = Servers::find(1);
        $text = "jordan_ip='".$server->ip_address."'\n";
        $text .= 'alias open_jordan=\'/usr/bin/open -a "/Applications/Google Chrome.app" "http://'.$server->ip_address.'"\'';
        $text .= "\n";

        $server = Servers::find(2);
        $text .= "bill_ip='".$server->ip_address."'\n";
        $text .= 'alias open_bill=\'/usr/bin/open -a "/Applications/Google Chrome.app" "http://'.$server->ip_address.'"\'';
        $text .= "\n";

        $server = Servers::find(3);
        $text .= "martha_ip='".$server->ip_address."'\n";
        $text .= 'alias open_martha=\'/usr/bin/open -a "/Applications/Google Chrome.app" "http://'.$server->ip_address.'"\'';
        $text .= "\n";

        $server = Servers::find(4);
        $text .= "musk_ip='".$server->ip_address."'\n";
        $text .= 'alias open_musk=\'/usr/bin/open -a "/Applications/Google Chrome.app" "http://'.$server->ip_address.'"\'';
        $text .= "\n";

        $server = Servers::find(5);
        $text .= "tywin_ip='".$server->ip_address."'\n";
        $text .= 'alias open_tywin=\'/usr/bin/open -a "/Applications/Google Chrome.app" "http://'.$server->ip_address.'"\'';
        $text .= "\n";

        fwrite($handle, $text);
        fclose($handle);
    }

    public function updateEnvironmentDBHost() {
        $utility = new Utility();
        $awsService = new AwsService();
        $instances = $awsService->getAllInstances();

        $dbIpAddress = $awsService->getReservationIPWithTag($instances, 'finance_db');

        $utility->writeToLine('/var/www/FinancialTrading/.env',5,'DB_HOST='.$dbIpAddress);
    }

    public function updateGitPullTime() {
        $file = '/home/ec2-user/event_times/last_git_pull.json';
        unlink($file);
        $handle = fopen($file, 'w');
        fwrite($handle, '{ "pull_time":"'.time().'"}');
        fclose($handle);
    }
}