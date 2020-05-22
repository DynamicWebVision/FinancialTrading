<?php
namespace App\Model\ProductAdvice;

use Illuminate\Database\Eloquent\Model;

class Listing extends Model {
    protected $connection = 'showplace_prod';

    protected $table = 'listings';
}