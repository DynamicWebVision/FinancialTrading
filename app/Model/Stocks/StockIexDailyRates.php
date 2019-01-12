<?php namespace App\Model\Stocks;

use Illuminate\Database\Eloquent\Model;

class StockIexDailyRates extends Model {
    protected $table = 'stock_iex_daily_rates';
    protected $fillable = ['price_date_time', 'stock_id'];
}