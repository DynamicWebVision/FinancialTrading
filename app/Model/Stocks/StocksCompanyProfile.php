<?php namespace App\Model\Stocks;

use Illuminate\Database\Eloquent\Model;

class StocksCompanyProfile extends Model {
    protected $table = 'stocks_company_profile';
    protected $fillable = ['stock_id'];
}