<?php namespace App\Model\Yelp;

use Illuminate\Database\Eloquent\Model;

class YelpLocation extends Model {
    protected $table = 'yelp_location';

    public $fillable = ['yelp_id'];
}