<?php namespace App\Model\Stocks;

use Illuminate\Database\Eloquent\Model;

class StocksTechnicalCheckResult extends Model {
    protected $table = 'stocks_technical_check_result';
    protected $fillable = ['stock_id', 'result_d'];
}