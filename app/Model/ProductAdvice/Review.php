<?php
namespace App\Model\ProductAdvice;

use Illuminate\Database\Eloquent\Model;

class Review extends Model {
    protected $connection = 'product_advice_prod';

    protected $table = 'reviews';

    protected $fillable = ['third_party_id'];
}