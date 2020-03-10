<?php namespace App\Model\Yelp;

use Illuminate\Database\Eloquent\Model;

class YelpLocationEmail extends Model {
    protected $table = 'yelp_location_email';

    public $fillable = ['yelp_location_id', 'email'];
}