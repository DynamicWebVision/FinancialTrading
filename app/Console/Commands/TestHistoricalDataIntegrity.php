<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use \App\Http\Controllers\HistoricalDataController;

class TestHistoricalDataIntegrity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'historical_integrity_test';

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
        $count = 1;

        while ($count < 100) {
            $historicalDataController = new HistoricalDataController();
            $historicalDataController->historicalDataTest();
            $count++;
            $minutes = rand(1,3)*60;
            sleep($minutes);
        }


    }
}
