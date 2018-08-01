<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OandaAccounts extends Model {
    protected $table = 'oanda_accounts';

    protected $fillable = [
        'oanda_id', 'id'
    ];
}