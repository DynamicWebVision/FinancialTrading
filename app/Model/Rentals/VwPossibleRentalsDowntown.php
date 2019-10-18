<?php namespace App\Model\Rentals;

use Illuminate\Database\Eloquent\Model;

class VwPossibleRentalsDowntown extends Model {

    protected $table = 'vw_possible_rentals_downtown';
    protected $fillable = ['MLS_NUMBER'];

}
