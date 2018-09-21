<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use \App\Model\BackTestToBeProcessed;
use \App\Http\Controllers\AutomatedBackTestController;
use App\Model\Servers;
use App\Http\Controllers\ServersController;
use Illuminate\Support\Facades\Config;

class EnvironmentVariableBackTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process_env_backtest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $recordCount = 1;
        $serverController = new ServersController();
        $serverController->setServerId();

        $server = Servers::find(Config::get('server_id'));

        $groupId = $server->current_back_test_group_id;

        while ($recordCount > 0) {
            $autoController = new AutomatedBackTestController();
            $autoController->environmentVariableDriveProcess();


            $recordCount = BackTestToBeProcessed::where('back_test_group_id', '=', $groupId)
                ->where('finish', '=', 0)
                ->where('start', '=', 0)
                ->count();
        }
    }
}
