<?php namespace App\Model\Stocks;

use Illuminate\Database\Eloquent\Model;

class StocksPriceIssues extends Model {
    protected $table = 'stocks_price_issues';
    protected $fillable = ['stock_id', 'price_date'];
}