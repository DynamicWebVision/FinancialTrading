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

        $newStrategy = new Strategy();

        $newStrategy->name = $post['name'];
        $newStrategy->description = $post['description'];
        $newStrategy->back_test_strategy_variable = $post['back_test_strategy_variable'];
        $newStrategy->namespace = 'App\Strategy\\'.$post['name'];

        $newStrategy->save();

        $strategyFileName = preg_replace("/[^A-Za-z0-9 ]/", '', $post['name']);

        mkdir(env('APP_ROOT').'app/Strategy/'.$strategyFileName);
        //chmod(env('APP_ROOT').'app/Strategy/'.$strategyFileName);

        $backTestFilePath = env('APP_ROOT').'app/BackTest/BackTestToBeProcessed/Strategy/'.$strategyFileName;
        mkdir($backTestFilePath, 0777, true);
        //shell_exec('sudo chmod -R 777 '.env('APP_ROOT').'app/BackTest/BackTestToBeProcessed/Strategy/');
        //chmod(env('APP_ROOT').'app/BackTest/BackTestToBeProcessed/Strategy/'.$strategyFileName);

        $fileHandler = new FileHandler();
        $fileHandler->filePath = env('APP_ROOT').'app/Http/Controllers/AutomatedBackTestController.php';

        $fileHandler->findLineOfTextInFile('END OF Backtest Declarations');
        $fileHandler->addLineToLineGroup("use App\\BackTest\\BackTestToBeProcessed\\Strategy\\".$strategyFileName."\\".$strategyFileName."BackTestToBeProcessed;");

        $fileHandler->addLinesAboveText('END OF Backtest Declarations');

        $fileHandler->addLineToLineGroup("elseif (\$server->current_back_test_strategy == '".$post['back_test_strategy_variable']."') {");
        $fileHandler->addLineToLineGroup("\$backTestStrategy = new ".$strategyFileName."BackTestToBeProcessed(\$processId, \$server);", 1);
        $fileHandler->addLineToLineGroup("\$backTestStrategy->callProcess();", 1);
        $fileHandler->addLineToLineGroup("}");

        $fileHandler->addLinesAboveText('END OF STRATEGY IFS');

        $fileHandler->filePath = $backTestFilePath.'/'.$strategyFileName.'BackTestToBeProcessed.php';

        $fileHandler->createFile();

        $fileHandler->addLineToLineGroup('<?php namespace App\BackTest\BackTestToBeProcessed\Strategy\\'.$strategyFileName.';');
        $fileHandler->emptyLine();

        $fileHandler->addLineToLineGroup('/**********************');
        $fileHandler->addLineToLineGroup($strategyFileName.' Backtest Variable Definitions');
        $fileHandler->addLineToLineGroup('Created at: '.date("m/d/y", time()).'by Brian O\'Neill');
        $fileHandler->addLineToLineGroup('***********************/');
        $fileHandler->emptyLine();

        $fileHandler->addLineToLineGroup('use \DB;');
        $fileHandler->addLineToLineGroup('use App\Model\Exchange;');
        $fileHandler->addLineToLineGroup('use \App\BackTest\TakeProfitStopLossTest;');
        $fileHandler->addLineToLineGroup('use \App\Services\StrategyLogger;');

        $fileHandler->emptyLine();
        $fileHandler->addLineToLineGroup('//END STRATEGY DECLARATIONS');

        $fileHandler->emptyLine();

        $fileHandler->addLineToLineGroup('class '.$strategyFileName.'BackTestToBeProcessed extends \App\BackTest\BackTestToBeProcessed\Base');
        $fileHandler->addLineToLineGroup('{');

        $fileHandler->emptyLine();
        $fileHandler->addLineToLineGroup('public function callProcess() {', 1);

        $fileHandler->addLineToLineGroup('/******************************', 2);
        $fileHandler->addLineToLineGroup('* SET BACK TEST', 2);
        $fileHandler->addLineToLineGroup('******************************/', 2);
        $fileHandler->emptyLine();
        $fileHandler->addLineToLineGroup('//Set', 2);
        $fileHandler->addLineToLineGroup("\$backTest = new TakeProfitStopLossTest('Automated Backtest');", 2);
        $fileHandler->emptyLine();
        $fileHandler->addLineToLineGroup("\$backTest->strategyRunName = 'Ema Momentum 15 Minutes';", 2);
        $fileHandler->addLineToLineGroup("\$backTest->strategyId = 6;", 2);
        $fileHandler->emptyLine();
        $fileHandler->addLineToLineGroup("//All Back Tests", 2);
        $fileHandler->addLineToLineGroup("\$backTest->currencyId = \$this->backTestToBeProcessed->exchange_id;", 2);
        $fileHandler->addLineToLineGroup("\$fullExchange = Exchange::find(\$this->backTestToBeProcessed->exchange_id);", 2);
        $fileHandler->addLineToLineGroup("\$backTest->exchange = \$fullExchange;", 2);
        $fileHandler->addLineToLineGroup("\$backTest->frequencyId = \$this->backTestToBeProcessed->frequency_id;", 2);
        $fileHandler->emptyLine();
        $fileHandler->addLineToLineGroup("//Most Back Tests", 2);
        $fileHandler->addLineToLineGroup("\$backTest->stopLoss = \$this->backTestToBeProcessed->stop_loss_pips;", 2);
        $fileHandler->addLineToLineGroup("\$backTest->takeProfit = \$this->backTestToBeProcessed->take_profit_pips;", 2);
        $fileHandler->emptyLine();
        $fileHandler->addLineToLineGroup("\$backTest->recordBackTestStart(\$this->backTestToBeProcessed->id);", 2);
        $fileHandler->addLineToLineGroup("//Starting Unix Time to Run Strategy", 2);
        $fileHandler->addLineToLineGroup("\$backTest->rateUnixStart = \$this->server->rate_unix_time_start;", 2);

        $fileHandler->emptyLine();
        $fileHandler->addLineToLineGroup("/******************************", 2);
        $fileHandler->addLineToLineGroup("* SET STRATEGY", 2);
        $fileHandler->addLineToLineGroup("******************************/", 2);

        $fileHandler->addLineToLineGroup("if (1==2) {", 2);
        $fileHandler->emptyLine();
        $fileHandler->addLineToLineGroup("}", 2);
        $fileHandler->addLineToLineGroup("//END OF SYSTEM STRATEGY IFS", 2);
        $fileHandler->emptyLine();

        $fileHandler->addLineToLineGroup("\$strategy->strategyLogger = new StrategyLogger();", 2);
        $fileHandler->emptyLine();

        $fileHandler->addLineToLineGroup("\$strategy->strategyId = 6;", 2);
        $fileHandler->addLineToLineGroup("\$strategy->strategyDesc = 'Automated Test';", 2);
        $fileHandler->addLineToLineGroup("\$strategy->positionMultiplier = 5;", 2);
        $fileHandler->emptyLine();

        $fileHandler->addLineToLineGroup("//Most Back Tests", 2);
        $fileHandler->addLineToLineGroup("\$strategy->stopLossPipAmount = \$this->backTestToBeProcessed->stop_loss_pips;", 2);
        $fileHandler->emptyLine();

        $fileHandler->addLineToLineGroup("//ALL", 2);
        $fileHandler->addLineToLineGroup("\$strategy->exchange = \$fullExchange;", 2);
        $fileHandler->addLineToLineGroup("\$strategy->backTestHelpers->exchange = \$fullExchange;", 2);
        $fileHandler->addLineToLineGroup("\$strategy->maxPositions = 5;", 2);
        $fileHandler->addLineToLineGroup("\$backTest->strategy = \$strategy;", 2);
        $fileHandler->addLineToLineGroup("\$backTest->run();", 2);
        $fileHandler->emptyLine();

        $fileHandler->addLineToLineGroup("}", 1);
        $fileHandler->addLineToLineGroup("}");

        $fileHandler->writeToNewFile();

        return $newStrategy;
    }
}