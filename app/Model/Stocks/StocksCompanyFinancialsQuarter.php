<?php namespace App\Model\Stocks;

use Illuminate\Database\Eloquent\Model;

class StocksCompanyFinancialsQuarter extends Model {
    protected $table = 'stocks_financials_quarter';
    protected $fillable = ['stock_id'];
}