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
        'App\Console\Commands\GetServersRunning',
    ];

    public $everyFifteenMinuteEarlyInterval = '59,14,29,44 * * * * *';
    public $everyFifteenMinutesInterval = '00,15,30,45 * * * * *';
    public $everyHourEarlyInterval = '00 * * * * *';
    public $fridaysBeforeMarketsClose = '0 40 21 ? * FRI *';
    public $sundayMarketsOpen = '0 5 23 ? * SUN *';
    public $tuesdayBeforeNewYorkOpens = '0 28 9 ? * TUE *';

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        if (env('APP_ENV') == 'live_trading') {
            //$schedule->call('App\Http\Controllers\LiveTradingController@marketIfTouchedReturnToOpenWeekly')->weeklyOn(2, '9:28');

            $schedule->call('App\Http\Controllers\ProcessScheduleController@checkForDueProcesses')->everyTenMinutes();

            $schedule->call('App\Http\Controllers\ServersController@backupDbWithImageDeleteOld')->dailyAt('5:00');
        }
        elseif (env('APP_ENV') == 'live_practice') {
            /*********************************************************************
             * SCHEDULE PROCESS JOBS
             *********************************************************************/

            $schedule->call('App\Http\Controllers\LivePracticeController@marketIfTouchedReturnToOpenHour')->hourlyAt(15);

            $schedule->call('App\Http\Controllers\LivePracticeController@emaXAdxConfirmWithMarketIfTouched')->cron($this->everyFifteenMinutesInterval);
            $schedule->call('App\Http\Controllers\LivePracticeController@hmaFifteenMinutes')->cron($this->everyFifteenMinutesInterval);

            $schedule->command('schedule_process lp_close_weekly_accounts 9')->weeklyOn(5, '21:40');


            $schedule->call('App\Http\Controllers\LivePracticeController@marketIfTouchedReturnToOpenWeekly')->weeklyOn(2, '9:28');

            $schedule->call('App\Http\Controllers\LivePracticeController@priceBreakoutHourly')->hourlyAt(2);
            $schedule->call('App\Http\Controllers\LivePracticeController@amazingCrossoverTrailingStop')->hourly();

            $schedule->call('App\Http\Controllers\LivePracticeController@emaXAdxConfirmWithMarketIfTouchedHr')->hourly();
            $schedule->call('App\Http\Controllers\LivePracticeController@weeklyPriceBreakout')->sundays()->at('17:00');

            //
            $schedule->call('App\Http\Controllers\LivePracticeController@dailyPreviousPriceBreakout')->dailyAt('21:02');
            $schedule->call('App\Http\Controllers\LivePracticeController@dailyPreviousPriceBreakoutTpSl')->dailyAt('21:02');
            $schedule->call('App\Http\Controllers\LivePracticeController@marketIfTouchedReturnToOpen')->dailyAt('13:00');
            $schedule->call('App\Http\Controllers\LivePracticeController@marketIfTouchedReturnToOpenTpSl')->dailyAt('13:00');
            
        }
        elseif (env('APP_ENV') == 'fin_master') {
            $schedule->command('schedule_process eq_fundamental_td 3')->dailyAt('23:00');
            $schedule->command('schedule_process fx_delete_dev_bts 2')->tuesdays()
                ->at('17:00');

            $schedule->command('schedule_process fx_live_transactions 3')->hourly();
            $schedule->command('schedule_process historical_fx_rates 3')->hourly();
        }
        elseif (env('APP_ENV') == 'utility') {
            $schedule->call('App\Http\Controllers\ProcessController@serverRunCheck')->hourly();

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
