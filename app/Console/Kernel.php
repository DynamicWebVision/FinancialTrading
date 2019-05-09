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
        'App\Console\Commands\ScheduleProcessInQueue',
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
            /*********************************************************************
             * SCHEDULE PROCESS JOBS
             *********************************************************************/
            $schedule->command('schedule_process eq_book_iex 3')->dailyAt('21:30');
            $schedule->command('schedule_process eq_fundamental_td 3')->dailyAt('23:00');
            $schedule->command('schedule_process delete_process_logs 9')->dailyAt('9:30');
            $schedule->command('schedule_process fx_delete_dev_bts 2')->tuesdays();
            $schedule->command('schedule_process fx_live_transactions 3')->hourly();
            $schedule->command('schedule_process historical_fx_rates 3')->hourly();
            $schedule->command('schedule_process fx_practice_transactions 3')->cron($this->everyFifteenMinutesInterval);

            $schedule->call('App\Http\Controllers\LivePracticeController@marketIfTouchedReturnToOpenHour')->hourlyAt(15);
            //$schedule->call('App\Http\Controllers\LivePracticeController@hmaHourSetHoldPeriods')->hourly();

            $schedule->call('App\Http\Controllers\LivePracticeController@emaXAdxConfirmWithMarketIfTouched')->cron($this->everyFifteenMinutesInterval);
            $schedule->call('App\Http\Controllers\LivePracticeController@hmaFifteenMinutes')->cron($this->everyFifteenMinutesInterval);

            $schedule->call('App\Http\Controllers\LivePracticeController@priceBreakoutHourly')->hourlyAt(2);
            $schedule->call('App\Http\Controllers\LivePracticeController@amazingCrossoverTrailingStop')->hourly();

            $schedule->call('App\Http\Controllers\LivePracticeController@emaXAdxConfirmWithMarketIfTouchedHr')->hourly();

            //
            $schedule->call('App\Http\Controllers\LivePracticeController@dailyPreviousPriceBreakout')->dailyAt('21:02');
            $schedule->call('App\Http\Controllers\LivePracticeController@dailyRatesCheck')->dailyAt('21:00');
            $schedule->call('App\Http\Controllers\LivePracticeController@marketIfTouchedReturnToOpen')->dailyAt('13:00');

            $schedule->call('App\Http\Controllers\LivePracticeController@fourHourPriceBreakout')->dailyAt('1:02');
            $schedule->call('App\Http\Controllers\LivePracticeController@fourHourPriceBreakout')->dailyAt('5:02');
            $schedule->call('App\Http\Controllers\LivePracticeController@fourHourPriceBreakout')->dailyAt('9:02');
            $schedule->call('App\Http\Controllers\LivePracticeController@fourHourPriceBreakout')->dailyAt('13:02');
            $schedule->call('App\Http\Controllers\LivePracticeController@fourHourPriceBreakout')->dailyAt('17:02');
            $schedule->call('App\Http\Controllers\LivePracticeController@fourHourPriceBreakout')->dailyAt('21:02');

            $schedule->call('App\Http\Controllers\LivePracticeController@hma4HSetHoldPeriods')->dailyAt('1:00');
            $schedule->call('App\Http\Controllers\LivePracticeController@hma4HSetHoldPeriods')->dailyAt('5:00');
            $schedule->call('App\Http\Controllers\LivePracticeController@hma4HSetHoldPeriods')->dailyAt('9:00');
            $schedule->call('App\Http\Controllers\LivePracticeController@hma4HSetHoldPeriods')->dailyAt('13:00');
            $schedule->call('App\Http\Controllers\LivePracticeController@hma4HSetHoldPeriods')->dailyAt('17:00');
            $schedule->call('App\Http\Controllers\LivePracticeController@hma4HSetHoldPeriods')->dailyAt('21:00');



        }
        elseif (env('APP_ENV') == 'utility') {
            $schedule->call('App\Http\Controllers\ProcessController@serverRunCheck')->hourly();

//            if ($server->task_code == 'fx_maintenance') {

//                $schedule->call('App\Http\Controllers\AccountsController@createNewLiveAccounts')->dailyAt('0:00');
//                $schedule->call('App\Http\Controllers\AccountsController@createNewPracticeAccounts')->dailyAt('1:00');

//
//                $schedule->call('App\Http\Controllers\TransactionController@saveLiveTransactions')->hourly();
//                $schedule->call('App\Http\Controllers\TransactionController@savePracticeTransactions')->cron($this->everyFifteenMinutesInterval);
//
//                $schedule->call('App\Http\Controllers\HistoricalDataController@populateHistoricalData')->hourly();
//            }
//            elseif ($server->task_code == 'stock_fund_data') {
//            }
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
