<?php

namespace Tests\Unit\BacktestDebug;

use Tests\TestCase;
use App\Http\Controllers\AutomatedBackTestController;
use App\Http\Controllers\BackTestStatsController;
use App\Http\Controllers\ProcessScheduleController;
use App\Http\Controllers\BackTestingController;
use App\Broker\OandaV20;
use App\Services\ProcessLogger;
use App\Model\ProcessScheduleDefTimes;

class BackTestDebugTest extends TestCase
{

//LONDON SESSION – open between 8 am GMT – 5 pm GMT; EUR, GBP, USD are the most active currencies;
//
//US SESSION – open between 1 pm GMT – 10 pm GMT 12 pm GMT – 9 pm Daylight Savings GMT; USD, EUR, GBP, AUD, JPY are the most active currencies;
//
//ASIAN SESSION – opens at about 10 pm GMT on Sunday afternoon, goes into the European trading session at about 9 am GMT; not very suitable for day trading.

//http://militarytimegeek.com/military-time-chart.html

//Oanda Aligns D's by default at NY Forex Close, 5PM. 10pm regular 9pm Daylight Savings Time
//0 Sunday - 6 Friday

    public $sundayHours = [22, 23];
    public $allDayHours = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23];
    public $marketMoverHours = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 23];



    public $daysBesidesSundaySaturday = [1,2,3,4,5];

    public function testCreateDefTimes() {
        $id = 1;

        foreach ($this->daysBesidesSundaySaturday as $day) {
            foreach ($this->marketMoverHours as $hour) {
                $def_time = new ProcessScheduleDefTimes();

                $def_time->process_schedule_def_id = $id;
                $def_time->day_of_week = $day;
                $def_time->hours = $hour;
                $def_time->minutes = '00';

                $def_time->save();
            }
        }
    }

    public function testCreateMarketCloseBeforeNYOpen() {
        $id = 2;

        foreach ($this->daysBesidesSundaySaturday as $day) {

                $def_time = new ProcessScheduleDefTimes();

                $def_time->process_schedule_def_id = $id;
                $def_time->day_of_week = $day;
                $def_time->hours = 11;
                $def_time->minutes = 59;

                $def_time->save();

        }
    }

    public function testCheckNextSchules() {
        $processScheduleController = new ProcessScheduleController();

        $processScheduleController->checkForDueProcesses();
    }
}
