<?php namespace App\Model\Stocks;

use Illuminate\Database\Eloquent\Model;

class StocksCompanyFinancialsAnnual extends Model {
    protected $table = 'stocks_financials_annual';
    protected $fillable = ['stock_id'];
}