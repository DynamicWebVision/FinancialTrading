<?php

namespace App\Model\Craigslist;

use Illuminate\Database\Eloquent\Model;

class Item extends Model {
    protected $connection = 'craigslist';
    protected $table = 'items';
}