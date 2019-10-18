<?php namespace App\Model\Rentals;

use Illuminate\Database\Eloquent\Model;

class PossibleRental extends Model {

    protected $table = 'possible_rentals';
    protected $fillable = ['MLS_NUMBER'];

}
