<?php

namespace App\Model\Craigslist;

use Illuminate\Database\Eloquent\Model;

class ItemImage extends Model {
    protected $connection = 'craigslist';
    protected $table = 'item_images';
}