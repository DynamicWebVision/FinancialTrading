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

        mkdir(env('APP_ROOT').'app/Strategy'.preg_replace("/[^A-Za-z0-9 ]/", '', $post['name']));
        mkdir(env('APP_ROOT').'app/BackTest/BackTestToBeProcessed/Strategy'.preg_replace("/[^A-Za-z0-9 ]/", '', $post['name']));

        return $newStrategy;
    }
}