<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;

//task_code and task_id

class ReColoradoListingPriceDrop extends Model {

    protected $table = 're_colorado_listing_price_drops';

    public $fillable = ['event_date', 're_colorado_listing_id'];
}
