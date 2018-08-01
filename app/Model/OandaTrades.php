<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OandaTrades extends Model {
    protected $table = 'oanda_trades';

    protected $fillable = [
        'oanda_open_id',
    ];
}