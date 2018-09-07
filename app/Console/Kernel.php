<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
//        Commands\Inspire::class,
        'App\Console\Commands\Inspire',
        'App\Console\Commands\CallRoute',
        'App\Console\Commands\ProcessEmaMomentum',
        'App\Console\Commands\ProcessBackTestStats',
        'App\Console\Commands\PopulateHistoricalData',
        'App\Console\Commands\EnvironmentVariableBackTest',
        'App\Console\Commands\HistoricalRatesVolume',
        'App\Console\Commands\TestHistoricalDataIntegrity',
        'App\Console\Commands\AutoBackTestWithoutReRun',
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
        if (env('APP_ENV') == 'live_practice') {
            //$schedule->call('App\Http\Controllers\LivePracticeController@twoLevelHmaDaily')->dailyAt('00:00');

            //$schedule->call('App\Http\Controllers\LivePracticeController@emaMomentumAdx15MinutesTPSL')->cron($this->everyFifteenMinutesInterval);
            $schedule->call('App\Http\Controllers\LivePracticeController@emaXAdxConfirmWithMarketIfTouched')->cron($this->everyFifteenMinutesInterval);
            $schedule->call('App\Http\Controllers\LivePracticeController@hmaFifteenMinutes')->cron($this->everyFifteenMinutesInterval);
            //$schedule->call('App\Http\Controllers\LivePracticeController@fifteenMinuteStochPullback')->cron($this->everyFifteenMinutesInterval);

            $schedule->call('App\Http\Controllers\LivePracticeController@emaXAdxConfirmWithMarketIfTouchedHr')->hourly();


            //$schedule->call('App\Http\Controllers\LivePracticeController@fifteenEmaFiveTenAfter')->cron($this->everyFifteenMinutesInterval);

            //$schedule->call('App\Http\Controllers\LivePracticeController@hourStochFastOppositeSlow')->hourly();
            //$schedule->call('App\Http\Controllers\LivePracticeController@hmaHourlyAfterHour')->hourly();

            //Four Hour Interval
//            $schedule->call('App\Http\Controllers\LivePracticeController@HmaAdxStayInFourHour')->dailyAt('1:00');
//            $schedule->call('App\Http\Controllers\LivePracticeController@HmaAdxStayInFourHour')->dailyAt('5:00');
//            $schedule->call('App\Http\Controllers\LivePracticeController@HmaAdxStayInFourHour')->dailyAt('9:00');
//            $schedule->call('App\Http\Controllers\LivePracticeController@HmaAdxStayInFourHour')->dailyAt('13:00');
//            $schedule->call('App\Http\Controllers\LivePracticeController@HmaAdxStayInFourHour')->dailyAt('17:00');
//            $schedule->call('App\Http\Controllers\LivePracticeController@HmaAdxStayInFourHour')->dailyAt('21:00');

            $schedule->call('App\Http\Controllers\TransactionController@getOandaTransactions')->cron($this->everyFifteenMinutesInterval);


            //$schedule->call('App\Http\Controllers\LivePracticeController@hmaHourlyBeforeHour')->cron($this->everyHourEarlyInterval);
            $schedule->call('App\Http\Controllers\AccountsController@createNewAccounts')->sundays();

            //$schedule->call('App\Http\Controllers\LivePracticeController@fifteenEarly')->cron($this->everyFifteenMinuteEarlyInterval);

            //$schedule->call('App\Http\Controllers\LivePracticeController@emaMomentumHourly')->hourly();

            $schedule->call('App\Http\Controllers\HistoricalDataController@populateHistoricalData')->hourly();
        }
        elseif (env('APP_ENV') == 'historical_data') {
            $schedule->call('App\Http\Controllers\HistoricalDataController@initialLoad')->cron($this->everyFifteenMinutesInterval);
        }
        elseif (env('APP_ENV') == 'backtest') {
            $schedule->call('App\Http\Controllers\AutomatedBackTestController@runAutoBackTestIfFailsUpdate')->hourly();
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
