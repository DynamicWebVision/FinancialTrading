<?php namespace App\Http\Controllers;

use \DB;
use \Log;
use Request;

use \App\Model\Indicators;


class IndicatorsController extends Controller {

    public $logQuery;
    public $orderDesc = true;

    public $pageCount = 10;

    public function index() {
        return Indicators::all();
    }

    public function store() {
        $post = Request::all();
        $newIndicator = new Indicators();

        $newIndicator->name = $post['name'];
        $newIndicator->description = $post['description'];
        $newIndicator->indicator_type = 1;
        $newIndicator->class = $post['class'];
        $newIndicator->class_variable_name = $post['class_variable_name'];

        $newIndicator->save();

        return $newIndicator;
    }
}