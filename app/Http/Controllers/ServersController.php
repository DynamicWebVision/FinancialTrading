<?php namespace App\Http\Controllers;

use App\Model\BackTestGroup;
use App\Model\ProcessLog\ProcessLog;
use \App\Model\Servers;
use \App\Model\Strategy;
use \App\Model\StrategySystem;
use \App\Model\ServerTasks;
use \App\Model\Servers\ServerEnvironmentDef;
use App\Model\BackTestToBeProcessed;
use App\Services\Utility;
use App\Services\FileHandler;
use \DB;
use Illuminate\Support\Str;
use \Log;
use Request;
use App\Services\AwsService;
use Illuminate\Support\Facades\Config;
use App\Services\StringHelpers;
use App\Services\ProcessLogger;

class ServersController extends Controller {

    const MINUTE_RUN_THRESHOLD = 60;

    public $serverId = null;
    public $logger;

    public function index() {
        $servers = Servers::with('task')->get()->toArray();

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

        $serverTasks = ServerTasks::get()->toArray();

        return ['servers'=>$servers, 'serverTasks'=>$serverTasks];
    }

    public function updateServers() {
        $post = Request::all();

        foreach ($post as $server) {
            $dbServer = Servers::find($server['id']);

            $task = ServerTasks::find($server['task']['id']);

            $dbServer->name = $server['name'];
            $dbServer->current_task = $server['current_task'];
            $dbServer->task_id = $server['task']['id'];
            $dbServer->task_code = $task->task_code;
            $dbServer->ip_address = $server['ip_address'];
            $dbServer->command_start = $server['command_start'];

            $dbServer->current_back_test_group_id = $server['current_back_test_group_id'];
            $dbServer->current_back_test_strategy = $server['current_back_test_strategy'];
            $dbServer->stock_id = $server['stock_id'];
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
            }))->where('strategy_system_id', '!=', 0)->where('dev_testing_only', '!=', 1)->orderBy('priority', 'desc')->first();
        }

        if (is_null($backTestGroup)) {
            $this->logger->logMessage('Next Backtest Group is null. Sleeping for 1 minute.');
            sleep(60);
        }
        else {
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

        //
        $task = ServerTasks::find($post['task']['id']);

        $server->current_task = $task->task_code;
        $server->task_id = $post['task']['id'];

        $server->task_code = $task->task_code;
        $server->stock_id = $post['stock_id'];

        $server->save();

        return $server;
    }

    public function setServerId() {

        if (env('APP_ENV') == 'local') {
            Config::set('server_id', 6);
            $this->serverId = 6;
        }
        else {

            if (is_null($this->serverId)) {

                $serverId = Config::get('server_id');

                if (is_null($serverId)) {

                    $awsService = new AwsService();
                    $ip_address = $awsService->getCurrentInstanceIp();

                    $server = Servers::firstOrNew([
                        'ip_address' => $ip_address
                    ]);

                    if ($server->exists) {
                        // user already exists
                        Config::set('server_id', $server->id);
                    } else {
                        $server->save();

                        Config::set('server_id', $server->id);

                        $awsService->setCurrentServerAttributes();
                    }
                    $this->serverId = $server->id;

                    try {
                        \DB::connection()->getPdo();
                    } catch (\Exception $e) {
                        $this->updateEnvironmentDBHost();
                    }
                }
                else {
                    $this->serverId = $serverId;
                }

            }
        }
        \Log::emergency("End setServerId");
        return true;
    }

    public function setServerEnvironment() {
        $awsService = new AwsService();
        $awsService->setCurrentServerAttributes();
        $this->setServerId();
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

        $server = Servers::find(100);
        $text .= "live_trading_ip='".$server->ip_address."'\n";
        $text .= 'alias open_live_trading=\'/usr/bin/open -a "/Applications/Google Chrome.app" "http://'.$server->ip_address.'"\'';
        $text .= "\n";

        $server = Servers::find(150);
        $text .= "practice_ip='".$server->ip_address."'\n";
        $text .= 'alias open_practice=\'/usr/bin/open -a "/Applications/Google Chrome.app" "http://'.$server->ip_address.'"\'';
        $text .= "\n";

        fwrite($handle, $text);
        fclose($handle);
    }

    public function createEnvironmentVariableFile($dbHost = false) {
        \Log::emergency("Top of createEnvironmentVariableFile");
        $awsService = new AwsService();

        $awsService->setCurrentServerAttributes();

        \Log::emergency("About to try to get type");

        $type = $awsService->getInstanceTagValue('type');

        \Log::emergency("createEnvironmentVariableFile with type ".$type);

        $environmentVariables = ServerEnvironmentDef::where('type', '=', $type)->get()->toArray();

        $fileHandler = new FileHandler();
        $fileHandler->filePath = env('APP_ROOT').'.env';

        foreach ($environmentVariables as $variable) {
            if ($variable['env_variable'] == 'DB_HOST') {
                if ($dbHost) {
                    $this->setConfigDBHost($dbHost);
                }
                else {
                    $dbHost = $variable['env_variable_value'];
                }
                $fileHandler->addLineToLineGroup($variable['env_variable'].'='.$dbHost);
            }
            else {
                $fileHandler->addLineToLineGroup($variable['env_variable'].'='.$variable['env_variable_value']);
            }
        }
        $fileHandler->clearFileAndWriteNewText();

        \Log::emergency("createEnvironmentVariableFile with type ".$type);
    }

    public function setConfigDBHost($dbHost) {
        config(['database.connections.mysql.host' => $dbHost]);
    }

    public function getCurrentDBHostFromAws() {
        $awsService = new AwsService();
        $instances = $awsService->getAllInstances();

        $dbIpAddress = $awsService->getReservationIPWithTag($instances, 'finance_db');
        return $dbIpAddress;
    }

    public function updateEnvDBRecord($newDBRecord) {
        $environmentVariables = ServerEnvironmentDef::where('env_variable', '=', 'DB_HOST')->get()->toArray();

        foreach ($environmentVariables as $envVariable) {
            $updateEnvVar = ServerEnvironmentDef::find($envVariable['id']);
            $updateEnvVar->env_variable_value = $newDBRecord;
            $updateEnvVar->save();
        }
    }

    public function updateEnvironmentDBHost() {
        \Log::emergency("updateEnvironmentDBHost");



        $dbHost = $this->getCurrentDBHostFromAws();
        \Log::emergency('Got DB Host '.$dbHost);

        $this->setConfigDBHost($dbHost);
        \Log::emergency("set DB Host");
        $this->updateEnvDBRecord($dbHost);
        \Log::emergency("successfully updated host");



        $this->createEnvironmentVariableFile($dbHost);
    }

    public function updateGitPullTime() {
        $file = '/home/ec2-user/event_times/last_git_pull.json';
        unlink($file);
        $handle = fopen($file, 'w');
        fwrite($handle, '{ "pull_time":"'.time().'"}');
        fclose($handle);
    }

    public function getLastGitPullTime() {
        if (env('APP_ENV') != 'local') {
            $gitPullData = file_get_contents('/home/ec2-user/event_times/last_git_pull.json');
            $gitPullData = json_decode($gitPullData);
            return $gitPullData->pull_time;
        }
        else {
            return 0;
        }
    }

    public function killIfProcessOverMinuteThreshold() {
        $myPid = getmypid();

        $this->logger->logMessage('Got my linux pid of '.$myPid);

        $startDateTime = ProcessLog::where('linux_pid', '=', $myPid)->where('server_id', '=', $this->serverId)->min('start_date_time');
        $timeRunningInMinutes = (time() - strtotime($startDateTime))/60;

        if ($timeRunningInMinutes >= self::MINUTE_RUN_THRESHOLD) {
            $this->logger->logMessage('Process has been running over '.self::MINUTE_RUN_THRESHOLD." minutes. Killing it");
            die();
        }
        else {
            $this->logger->logMessage('Process has been running less than '.self::MINUTE_RUN_THRESHOLD." minutes at $timeRunningInMinutes. Going to next job.");
        }
    }

    public function serverAlreadyRunningCheck() {
        $this->setServerId();

        $server = Servers::find($this->serverId);
        $timeSinceLastRunUpdateInMinutes = (time() - $server->last_process_run)/60;

        if ($timeSinceLastRunUpdateInMinutes <= 90 && $server->last_run_killed == 0) {
            $this->logger->logMessage('$timeSinceLastRunUpdateInMinutes value of '.$timeSinceLastRunUpdateInMinutes.' is less than 90 minutes. Server is Running so killing process');
            $this->logger->processEnd();
            die();
        }
        else {
            if ($server->last_run_killed == 1) {
                $server->last_run_killed = 0;
                $server->save();
                $this->logger->logMessage('Last Server Killed due to Git. Running this Process');
            }
            else {
                $this->logger->logMessage('$timeSinceLastRunUpdateInMinutes value of '.$timeSinceLastRunUpdateInMinutes.' is greater than 90 minutes. Continuing with Process.');
            }

        }
    }

    public function updateProcessRun() {
        $server = Servers::find(Config::get('server_id'));
        $server->last_process_run = time();
        $server->save();
    }

    public function gitPullCheck() {
        $lastActualGitPullTime = $this->getLastGitPullTime();

        $server = Servers::find(Config::get('server_id'));

        if ($server->last_git_pull_time != $lastActualGitPullTime) {
            $server->last_git_pull_time = $lastActualGitPullTime;
            $server->last_run_killed = 1;
            $server->save();
            return 'invalid_git_pull_time';
        }
        else {
            return 'git_pull_times_match';
        }
    }

    public function seeIfPidIsRunning($pid) {
        $output = shell_exec('ps -p '.$pid);
        $this->logger->logMessage('pid check output :'.$output);
        $stringHelpers = new StringHelpers();
        return $stringHelpers->stringContainsString($output, $pid);
    }

    public function backupDbWithImageDeleteOld() {
        Log::info('Starting backupDbWithImageDeleteOld');
        $awsService = new AwsService();
        $instances = $awsService->getAllInstances();
        $imageId = $awsService->getReservationIdWithTag($instances,'finance_db');

        Log::info('Found Image ID '.$imageId);

        try {
            $awsService->createImage($imageId);
        }
        catch (\Exception $e) {
            Log::info('Creating DB Image Failed');
            Log::info($e->getMessage());
            die($e->getMessage());
        }

        Log::info('Successfully created DB image ');

        sleep(300);

        $ip_address = $awsService->getReservationIPWithTag($instances,'finance_db');

        Log::info('Got IP Address '.$ip_address);

        shell_exec("cd /home/ec2-user/keys && ssh -i Currency.pem ec2-user@".$ip_address." 'sudo service mysqld start'");

        Log::info('Executed command');

        sleep(60);

        Log::info('About to request small fleet of servers for 23 hours');

        $this->requestSmallMiniFleetFor23Hours();

        Log::info('Creating createContinuousToRunRecords');

        $processController = new ProcessController();
        $processController->createContinuousToRunRecords();
    }

    public function requestSmallMiniFleetFor23Hours() {
        $this->logger = new ProcessLogger('create_mini_utility');

        $awsService = new AwsService();
        $awsService->logger = $this->logger;
        $utility = new Utility();


        $validUntil = time() + $utility->hoursInSeconds(23);

        $params = [
            'server_count' => 15,
            'interruption_behavior'=>'terminate',
            'image_id' => 'ami-0bf51fd46fb140e1d',
            'template_id'=> 'lt-084f84871df31725a',
            'template_version'=> '6',
            'tags'=> [
                'Key' => 'server_type',
                'Value' => 'utility',
            ],
            'valid_until' => $validUntil
        ];
        $awsService->requestSpotFleet($params);
    }
}