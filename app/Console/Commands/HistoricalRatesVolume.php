<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Http\Controllers\HistoricalDataController;
use \App\Model\BackTestToBeProcessed;

class HistoricalRatesVolume extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'historical_rates_volume';

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

        $historicalDataController = new HistoricalDataController();

        while ($recordCount > 0) {

            $historicalDataController->populateHisoricalDataVolume();

            $seconds = rand(1,30);
            $sleepTime = 120 + $seconds;

            sleep($sleepTime);
        }
    }
}
