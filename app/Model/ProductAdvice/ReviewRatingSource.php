<?php
namespace App\Model\ProductAdvice;

use Illuminate\Database\Eloquent\Model;

class ReviewRatingSource extends Model {
    protected $connection = 'product_advice_prod';

    protected $table = 'review_rating_sources';

    protected $fillable = ['third_party_id'];
}