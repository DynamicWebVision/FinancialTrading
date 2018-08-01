<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Http\Controllers\BackTestingController;
use \App\Model\BackTestToBeProcessed;

class ProcessBackTestStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process_back_stats';

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

        while ($recordCount > 0) {
            $autoController = new BackTestingController();
            $autoController->backtestProcessStats();

            $recordCount = BackTestToBeProcessed::where('stats_finish', '=', 0)->where('stats_start', '=', 0)->where('finish', '=', 1)->where('start', '=', 1)
                ->count();
        }
    }
}
