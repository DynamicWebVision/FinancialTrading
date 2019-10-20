<?php

namespace App\Model\Craigslist;

use Illuminate\Database\Eloquent\Model;

class Section extends Model {
    protected $connection = 'craigslist';
    protected $table = 'sections';
}