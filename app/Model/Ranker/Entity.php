<?php
namespace App\Model\Ranker;

use Illuminate\Database\Eloquent\Model;

class Entity extends Model {
    protected $connection = 'homestead';

    protected $table = 'entities';
}