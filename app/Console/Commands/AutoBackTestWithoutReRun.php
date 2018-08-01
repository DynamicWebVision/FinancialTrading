<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use \App\Http\Controllers\AutomatedBackTestController;

class AutoBackTestWithoutReRun extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto_bt_fail_update';

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
        $autoBackTestController = new AutomatedBackTestController();

        $autoBackTestController->runAutoBackTestIfFailsUpdate();
    }
}
