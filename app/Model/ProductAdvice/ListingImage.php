<?php
namespace App\Model\ProductAdvice;

use Illuminate\Database\Eloquent\Model;

class ListingImage extends Model {
    protected $connection = 'showplace_prod';

    protected $table = 'listing_images';
}