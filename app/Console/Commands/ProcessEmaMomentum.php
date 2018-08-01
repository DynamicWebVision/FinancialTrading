<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use \App\Model\BackTestToBeProcessed;
use \App\Http\Controllers\AutomatedBackTestController;

class ProcessEmaMomentum extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process_ema_momentum';

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
        $groupId = $this->server->current_back_test_group_id;

        while ($recordCount > 0) {
            $autoController = new AutomatedBackTestController();
            $autoController->processOneTestFiftyOneHundred();


            $recordCount = BackTestToBeProcessed::where('back_test_group_id', '=', $groupId)
                ->where('finish', '=', 0)
                ->where('start', '=', 0)
                ->count();
        }
    }
}
