<?php namespace App\Http\Controllers;

use \DB;
use \Log;
use Request;

use \App\Model\Strategy;
use \App\Services\FileHandler;


class StrategyController extends Controller {

    public $logQuery;
    public $orderDesc = true;

    public $pageCount = 10;

    public function index() {
        return Strategy::all();
    }

    public function store() {
        $post = Request::all();
//
        $newStrategy = new Strategy();
//
//        $newStrategy->name = $post['name'];
//        $newStrategy->description = $post['description'];
//        $newStrategy->back_test_strategy_variable = $post['back_test_strategy_variable'];
//        $newStrategy->namespace = 'App\Strategy\\'.$post['name'];
//
//        $newStrategy->save();
//
//        mkdir(env('APP_ROOT').'app/Strategy/'.preg_replace("/[^A-Za-z0-9 ]/", '', $post['name']));
//        mkdir(env('APP_ROOT').'app/BackTest/BackTestToBeProcessed/Strategy/'.preg_replace("/[^A-Za-z0-9 ]/", '', $post['name']));

        $fileHandler = new FileHandler();
        $fileHandler->filePath = env('APP_ROOT').'app/Http/Controllers/TestController.php';

        $fileHandler->findLineOfTextInFile('END OF Backtest Declarations');
        $fileHandler->addLineToLineGroup("use App\\BackTest\\BackTestToBeProcessed\\Strategy\\".$post['name']."\\".$post['name']."BackTestToBeProcess");

        $fileHandler->findLineOfTextInFile('END OF STRATEGY IFS');

        $fileHandler->addLineToLineGroup("elseif (\$server->current_back_test_strategy == '".$post['namespace']."') {");
        $fileHandler->addLineToLineGroup("\$backTestStrategy = new ".$post['name']."(\$processId, \$server);", 1);
        $fileHandler->addLineToLineGroup("\$backTestStrategy->callProcess();", 1);
        $fileHandler->addLineToLineGroup("}");

        $fileHandler->addLinesAboveText('END OF STRATEGY IFS');

        return $newStrategy;
    }
}