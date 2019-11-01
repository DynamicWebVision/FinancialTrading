<?php namespace App\Model\Messgen;

use Illuminate\Database\Eloquent\Model;

class MessgenMessage extends Model {
    protected $connection = 'messgen';

    protected $table = 'messages';
}
