<?php
namespace App\Model\ProductAdvice;

use Illuminate\Database\Eloquent\Model;

class ReviewRatingType extends Model {
    protected $connection = 'product_advice_prod';

    protected $table = 'review_rating_types';

    protected $fillable = ['third_party_id', 'code'];
}