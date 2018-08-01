<?php namespace App\Http\Controllers;

use \DB;
use \Log;
use Request;

use \App\Model\StrategySystem;


class StrategySystemController extends Controller {

    public $logQuery;
    public $orderDesc = true;

    public $pageCount = 10;

    public function strategySystems($strategyId) {
        return StrategySystem::where('strategy_id', '=', $strategyId)->get()->toArray();
    }

    public function store() {
        $post = Request::all();

        $newStrategySystem = new StrategySystem();

        $newStrategySystem->strategy_id = $post['strategy_id'];
        $newStrategySystem->name = $post['name'];
        $newStrategySystem->method = $post['method'];
        $newStrategySystem->strategy_iteration_variable = $post['strategy_iteration_variable'];
        $newStrategySystem->description = $post['description'];

        $newStrategySystem->save();

        return $newStrategySystem;

    }
}