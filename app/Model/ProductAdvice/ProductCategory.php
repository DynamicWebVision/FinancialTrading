<?php
namespace App\Model\ProductAdvice;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model {
    protected $connection = 'product_advice_local';

    protected $table = 'product_types';
}