<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use \App\Model\BackTestToBeProcessed;
use \App\Http\Controllers\ServersController;

class UpdateGitPullTime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update_git_pull_time';

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
        $serversController = new ServersController();
        $serversController->updateGitPullTime();
    }
}
