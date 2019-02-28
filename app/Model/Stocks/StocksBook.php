<?php namespace App\Model\Stocks;

use Illuminate\Database\Eloquent\Model;

class StocksBook extends Model {
    protected $table = 'stocks_book';
    protected $fillable = ['stock_id'];
}