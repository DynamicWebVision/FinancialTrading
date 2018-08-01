<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OandaOpenPositions extends Model {
    protected $table = 'oanda_open_positions';
    protected $fillable = [
        'oanda_account_id', 'exchange_id'
    ];
}