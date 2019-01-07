<?php namespace App\Model\Stocks;

use Illuminate\Database\Eloquent\Model;

class StocksFundamentalData extends Model {
    protected $table = 'stocks_fundamental';
    protected $fillable = ['stock_id'];
}