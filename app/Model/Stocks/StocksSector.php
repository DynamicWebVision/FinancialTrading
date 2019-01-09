<?php namespace App\Model\Stocks;

use Illuminate\Database\Eloquent\Model;

class StocksSector extends Model {
    protected $table = 'stocks_sector';
    protected $fillable = ['stock_id', 'name'];
}