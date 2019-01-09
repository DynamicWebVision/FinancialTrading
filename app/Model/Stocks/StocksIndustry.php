<?php namespace App\Model\Stocks;

use Illuminate\Database\Eloquent\Model;

class StocksIndustry extends Model {
    protected $table = 'stocks_industry';
    protected $fillable = ['stock_id', 'name'];
}