<?php

namespace App\Model\Craigslist;

use Illuminate\Database\Eloquent\Model;

class City extends Model {
    protected $connection = 'craigslist';
    protected $table = 'cities';
}