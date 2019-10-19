<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Http\Controllers\HistoricalDataController;
use \App\Http\Controllers\ProcessController;
use \App\Model\BackTestToBeProcessed;

class GetServersRunning extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get_servers_running';

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
        $processController = new ProcessController();

        $processController->serverRunCheck();
    }
}
