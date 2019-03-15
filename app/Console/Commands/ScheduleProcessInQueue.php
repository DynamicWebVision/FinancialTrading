<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use \App\Model\ProcessLog\Process;
use \App\Model\ProcessLog\ProcessQueue;

class ScheduleProcessInQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule_process {process_code} {priority}';

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
        $processCode = $this->argument('process_code');
        $priority = $this->argument('priority');

        $process = Process::where('code', '=', $processCode)->first();

        $newProcessQueue = new ProcessQueue();
        $newProcessQueue->process_id = $process->id;
        $newProcessQueue->priority = $priority;
        $newProcessQueue->save();
    }
}
