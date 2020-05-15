<?php
namespace App\Model\ProductAdvice;

use Illuminate\Database\Eloquent\Model;

class ProductTypePricePoint extends Model {
    protected $connection = 'product_advice_prod';

    protected $table = 'product_type_price_points';

    protected $fillable = ['product_type_id', 'price_point'];
}