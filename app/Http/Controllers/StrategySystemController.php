<?php namespace App\Http\Controllers;

use \DB;
use \Log;
use Request;

use \App\Model\StrategySystem;
use \App\Model\Strategy;
use \App\Services\FileHandler;

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

        $strategy = Strategy::find($post['strategy_id']);

        $fileHandler = new FileHandler();
        $fileHandler->filePath = env('APP_ROOT').'app/Http/Controllers/AutomatedBackTestController.php';

        $fileHandler->filePath = env('APP_ROOT').'app/Strategy/'.$strategy->name.'/'.$post['method'].'.php';

        $fileHandler->createFile();

        $fileHandler->addLineToLineGroup('<?php');
        $fileHandler->emptyLine();

        $fileHandler->addLineToLineGroup('/**********************');
        $fileHandler->addLineToLineGroup($post['name'].' Strategy System under '.$strategy->name.' Strategy');
        $fileHandler->addLineToLineGroup('Created at: '.date("m/d/y", time()).'by Brian O\'Neill');
        $fileHandler->addLineToLineGroup('Description: '.$post['description']);
        $fileHandler->emptyLine();

        $fileHandler->addLineToLineGroup('namespace App\Strategy\\'.$strategy->name.';');
        $fileHandler->emptyLine();



        $fileHandler->writeToNewFile();

        return $newStrategySystem;

    }
}