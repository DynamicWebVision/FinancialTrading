<?php namespace App\Model;
/**
 * Created by PhpStorm.
 * User: boneill
 * Date: 12/29/18
 * Time: 6:54 PM
 */

//server_tasks
//
//id	int(2)	NO	PRI	NULL	auto_increment
//name	varchar(50)	YES		NULL
//description	varchar(300)	YES		NULL
//task_code	varchar(30)	YES		NULL
use Illuminate\Database\Eloquent\Model;

//task_code and task_id

class ServerTasks extends Model {

    protected $table = 'server_tasks';
}

//1	Forex Backtesting	This is backtesting forex systems.	fx_backtest
//2	Forex Maitenance	This is for forex maintenance.	fx_maintenance
//                                     3	Live FX Practice	This is Live Forex Practice Trading	live_fx_practice
//4	Live FX Trading	Live Forex Trading with Real Money	live_fx_trading
//5	Populating Stock Historical Data	Populating Stock Historical Data	stock_hist_data