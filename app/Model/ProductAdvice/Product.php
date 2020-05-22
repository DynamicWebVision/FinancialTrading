<?php
namespace App\Model\ProductAdvice;

use Illuminate\Database\Eloquent\Model;

class Product extends Model {
    protected $connection = 'product_advice_prod';

    protected $table = 'products';

    protected $fillable = ['third_party_id'];
}