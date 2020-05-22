<?php
namespace App\Model\ProductAdvice;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model {
    protected $connection = 'product_advice_prod';

    protected $table = 'product_images';
}