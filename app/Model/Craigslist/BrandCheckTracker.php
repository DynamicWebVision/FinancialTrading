<?php

namespace App\Model\Craigslist;

use Illuminate\Database\Eloquent\Model;

class BrandCheckTracker extends Model {
    protected $connection = 'craigslist';
    protected $table = 'brand_check_tracker';
}