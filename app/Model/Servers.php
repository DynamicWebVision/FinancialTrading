<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;

//task_code and task_id

class Servers extends Model {

    protected $table = 'servers';

    public $fillable = ['ip_address'];

    public function task()
    {
        return $this->belongsTo('App\Model\ServerTasks', 'task_id');
    }
}
