<?php namespace App\Model\Stocks;

use Illuminate\Database\Eloquent\Model;

class StocksIncomeStatements extends Model {
    protected $table = 'stocks_income_statements';
    protected $fillable = ['stock_id', 'statement_date'];
}