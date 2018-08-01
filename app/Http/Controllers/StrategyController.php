<?php namespace App\Http\Controllers;

use \DB;
use \Log;
use Request;

use \App\Model\Strategy;


class StrategyController extends Controller {

    public $logQuery;
    public $orderDesc = true;

    public $pageCount = 10;

    public function index() {
        return Strategy::all();
    }

    public function store() {
        $post = Request::all();

        $newStrategy = new Strategy();

        $newStrategy->name = $post['name'];
        $newStrategy->description = $post['description'];
        $newStrategy->back_test_strategy_variable = $post['back_test_strategy_variable'];
        $newStrategy->namespace = $post['namespace'];

        $newStrategy->save();

        return $newStrategy;
    }
}