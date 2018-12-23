<?php namespace App\Services;

class Csv {

    public $path;

    public function saveRecordsInModel() {
        $test = fgetcsv($this->path);

    }
}
