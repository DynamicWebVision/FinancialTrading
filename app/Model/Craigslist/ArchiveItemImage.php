<?php

namespace App\Model\Craigslist;

use Illuminate\Database\Eloquent\Model;

class ArchiveItemImage extends Model {
    protected $connection = 'craigslist';
    protected $table = 'archive_item_images';
}