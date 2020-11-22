<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;

//task_code and task_id

class ReColoradoListing extends Model {

    protected $table = 're_colorado_listings';

    public $fillable = ['re_colorado_url'];
}
