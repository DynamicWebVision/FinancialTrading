<?php

namespace App\Model\Craigslist;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model {
    protected $connection = 'craigslist';
    protected $table = 'brands';
}