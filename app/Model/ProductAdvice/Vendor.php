<?php
namespace App\Model\ProductAdvice;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model {
    protected $connection = 'product_advice_prod';

    protected $table = 'vendors';

    protected $fillable = ['name'];
}