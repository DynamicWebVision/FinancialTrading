<?php namespace App\Model\Stocks;

use Illuminate\Database\Eloquent\Model;

class StocksDailyPricesYahoo extends Model {
    protected $table = 'stocks_daily_prices';
    protected $fillable = ['price_date_time', 'stock_id'];
}