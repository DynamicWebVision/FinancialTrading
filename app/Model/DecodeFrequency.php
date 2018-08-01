<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DecodeFrequency extends Model {
    protected $table = 'decode_frequency';
    public $timestamps = false;

    public $secondFrequencyCutoffs = [
        'M15'=>2700,
        'M30'=>5400,
        'H1'=>10800,
        'H4'=>43200,
        'D'=>259200
        ];
}