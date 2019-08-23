<?php namespace App\Model\Stocks;

use Illuminate\Database\Eloquent\Model;

class StocksBackTestIteration extends Model {
    protected $table = 'stocks_back_test_iteration';

    public function stock() {
        return $this->hasOne('App\Model\Stocks\Stocks', 'id', 'stock_id');
    }
}