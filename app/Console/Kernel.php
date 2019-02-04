<?php

namespace App\Console;
use Illuminate\Support\Facades\Config;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Model\Servers;
use App\Http\Controllers\ServersController;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */

    //Four Hour Interval
//            $schedule->call('App\Http\Controllers\LivePracticeController@HmaAdxStayInFourHour')->dailyAt('1:00');
//            $schedule->call('App\Http\Controllers\LivePracticeController@HmaAdxStayInFourHour')->dailyAt('5:00');
//            $schedule->call('App\Http\Controllers\LivePracticeController@HmaAdxStayInFourHour')->dailyAt('9:00');
//            $schedule->call('App\Http\Controllers\LivePracticeController@HmaAdxStayInFourHour')->dailyAt('13:00');
//            $schedule->call('App\Http\Controllers\LivePracticeController@HmaAdxStayInFourHour')->dailyAt('17:00');
//            $schedule->call('App\Http\Controllers\LivePracticeController@HmaAdxStayInFourHour')->dailyAt('21:00');

    protected $commands = [
//        Commands\Inspire::class,
        'App\Console\Commands\Inspire',
        'App\Console\Commands\CallRoute',
        'App\Console\Commands\ProcessBackTestStats',
        'App\Console\Commands\PopulateHistoricalData',
        'App\Console\Commands\EnvironmentVariableBackTest',
        'App\Console\Commands\HistoricalRatesVolume',
        'App\Console\Commands\TestHistoricalDataIntegrity',
        'App\Console\Commands\AutoBackTestWithoutReRun',
        'App\Console\Commands\UpdateServers',
        'App\Console\Commands\UpdateDBHost',
        'App\Console\Commands\UpdateGitPullTime',
    ];

    public $everyFifteenMinuteEarlyInterval = '59,14,29,44 * * * * *';
    public $everyFifteenMinutesInterval = '00,15,30,45 * * * * *';
    public $everyHourEarlyInterval = '00 * * * * *';

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        if (env('APP_ENV') == 'live_trading') {
            $schedule->call('App\Http\Controllers\LiveTradingController@hmaFifteenMinutes')->cron($this->everyFifteenMinutesInterval);
        }
        elseif (env('APP_ENV') == 'live_practice') {
            $schedule->call('App\Http\Controllers\LivePracticeController@emaXAdxConfirmWithMarketIfTouched')->cron($this->everyFifteenMinutesInterval);
            $schedule->call('App\Http\Controllers\LivePracticeController@hmaFifteenMinutes')->cron($this->everyFifteenMinutesInterval);

            $schedule->call('App\Http\Controllers\LivePracticeController@hmaThirty')->everyThirtyMinutes();

            $schedule->call('App\Http\Controllers\LivePracticeController@emaXAdxConfirmWithMarketIfTouchedHr')->hourly();
            $schedule->call('App\Http\Controllers\LivePracticeController@hmaHour')->hourly();

            //
            $schedule->call('App\Http\Controllers\LivePracticeController@dailyPreviousPriceBreakout')->dailyAt('22:01');
        }
        elseif (env('APP_ENV') == 'utility') {
            $serverController = new ServersController();
            $serverController->setServerId();

            $server = Servers::find(Config::get('server_id'));

            //$schedule->call('App\Http\Controllers\HistoricalDataController@initialLoad')->cron($this->everyFifteenMinutesInterval);

            if (!isset($server->task_code)) {
                \Log::emergency('Server Task Code Not Set');
                \Log::emergency('Server Id: '.Config::get('server_id'));
                \Log::emergency(json_encode($server));
                return false;
            }

            if ($server->task_code == 'fx_backtest') {
                $schedule->call('App\Http\Controllers\AutomatedBackTestController@runAutoBackTestIfFailsUpdate')->hourly();
            }
            elseif ($server->task_code == 'fx_maintenance') {
                $schedule->call('App\Http\Controllers\BackTestStatsController@rollBackReviewedNonProfitableProcesses')->hourly();

                $schedule->call('App\Http\Controllers\TransactionController@saveLiveTransactions')->hourly();
                $schedule->call('App\Http\Controllers\TransactionController@savePracticeTransactions')->cron($this->everyFifteenMinutesInterval);

                $schedule->call('App\Http\Controllers\BackTestingController@deleteDevTestOnlyBackTestGroups')->tuesdays();

                $schedule->call('App\Http\Controllers\HistoricalDataController@populateHistoricalData')->hourly();

                $schedule->call('App\Http\Controllers\AccountsController@createNewLiveAccounts')->daily();
                $schedule->call('App\Http\Controllers\AccountsController@createNewPracticeAccounts')->daily();
            }
            elseif ($server->task_code == 'stock_hist_data') {
                //$schedule->call('App\Http\Controllers\Equity\StocksHistoricalDataController@keepRunning')->hourly();
                $schedule->call('App\Http\Controllers\Equity\StocksIexRatesController@keepRunning')->hourly();
            }
            elseif ($server->task_code == 'stock_fund_data') {
                $schedule->call('App\Http\Controllers\Equity\StocksInformationController@runNextTask')->hourly();
            }
        }
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
