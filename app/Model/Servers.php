<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;

//task_code and task_id

class Servers extends Model {

    protected $table = 'servers';

    public function task()
    {
        return $this->belongsTo('App\Model\ServerTasks', 'task_id');
    }
}
