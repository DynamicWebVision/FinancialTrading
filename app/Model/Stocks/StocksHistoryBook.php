<?php namespace App\Model\Stocks;

use Illuminate\Database\Eloquent\Model;

class StocksHistoryBook extends Model {
    protected $table = 'stocks_history_book';
    protected $fillable = ['stock_id', 'change_percent', 'ytd_change', 'week_change', 'month_change', 'book_date', 'book_date_unix', 'last_5_change', 'change_price'];
}