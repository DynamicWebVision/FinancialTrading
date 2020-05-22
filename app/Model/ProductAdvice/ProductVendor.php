<?php
namespace App\Model\ProductAdvice;

use Illuminate\Database\Eloquent\Model;

class ProductVendor extends Model {
    protected $connection = 'product_advice_prod';

    protected $table = 'product_vendors';

    protected $fillable = ['product_id', 'vendor_id'];
}