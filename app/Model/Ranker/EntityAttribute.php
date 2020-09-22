<?php
namespace App\Model\Ranker;

use Illuminate\Database\Eloquent\Model;

class EntityAttribute extends Model {
    protected $connection = 'homestead';

    protected $table = 'entity_data_fields';
}