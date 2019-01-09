<?php namespace App\Model\Stocks;

use Illuminate\Database\Eloquent\Model;

class StocksTags extends Model {
    protected $table = 'stocks_tags';
    protected $fillable = ['stock_id', 'name'];
}