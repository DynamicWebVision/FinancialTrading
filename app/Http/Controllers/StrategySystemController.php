<?php namespace App\Http\Controllers;

use \DB;
use \Log;
use Request;

use \App\Model\StrategySystem;
use \App\Model\Strategy;
use \App\Services\FileHandler;
use \App\Model\Indicators;

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

        $newPositionIndicators = [];
        $openPositionIndicators = [];
        $allIndicators = [];
        $allIndicatorEvents = [];

        foreach ($post['newPositionConditions'] as $index => $newPositionCondition) {
            $post['newPositionConditions'][$index]['fullIndicator'] = Indicators::find($newPositionCondition['indicator_id']);
            $newPositionIndicators[] = $post['newPositionConditions'][$index]['fullIndicator'];
            $allIndicators[] = $post['newPositionConditions'][$index]['fullIndicator'];
            $allIndicatorEvents[] = $post['newPositionConditions'][$index];
        }

        foreach ($post['newPositionPriceTargets'] as $index => $newPositionPriceTarget) {
            $post['newPositionPriceTargets'][$index]['fullIndicator'] = Indicators::find($newPositionPriceTarget['indicator_id']);
            $newPositionIndicators[] = $post['newPositionPriceTargets'][$index]['fullIndicator'];
            $allIndicators[] = $post['newPositionPriceTargets'][$index]['fullIndicator'];
            $allIndicatorEvents[] = $post['newPositionPriceTargets'][$index];
        }

        $newPositionIndicators = array_unique($newPositionIndicators);

        foreach ($post['openPositionConditions'] as $index => $openPositionCondition) {
            $post['openPositionConditions'][$index]['fullIndicator'] = Indicators::find($openPositionCondition['indicator_id']);
            $openPositionIndicators[] = $post['openPositionConditions'][$index]['fullIndicator'];
            $allIndicators[] = $post['openPositionConditions'][$index]['fullIndicator'];
            $allIndicatorEvents[] = $post['openPositionConditions'][$index];
        }

        foreach ($post['openPositionPriceTargets'] as $index => $openPositionPriceTarget) {
            $post['openPositionPriceTargets'][$index]['fullIndicator'] = Indicators::find($openPositionPriceTarget['indicator_id']);
            $openPositionIndicators[] = $post['openPositionPriceTargets'][$index]['fullIndicator'];
            $allIndicators[] = $post['openPositionPriceTargets'][$index]['fullIndicator'];
            $allIndicatorEvents[] = $post['openPositionPriceTargets'][$index];
        }

        $openPositionIndicators = array_unique($openPositionIndicators);
        $allIndicators = array_unique($allIndicators);
        $allIndicatorEvents = array_unique($allIndicatorEvents, SORT_REGULAR);

        $newStrategySystem->strategy_id = $post['strategy_id'];
        $newStrategySystem->name = $post['name'];
        $newStrategySystem->method = $post['name'];
        $newStrategySystem->strategy_iteration_variable = $post['strategy_iteration_variable'];
        $newStrategySystem->description = $post['description'];

        $newStrategySystem->save();

        $strategy = Strategy::find($post['strategy_id']);

        $fileHandler = new FileHandler();
        $fileHandler->filePath = env('APP_ROOT').'app/Strategy/'.$strategy->name.'/'.$post['name'].'.php';

        $fileHandler->createFile();

        $fileHandler->addLineToLineGroup('<?php');
        $fileHandler->emptyLine();

        $fileHandler->addLineToLineGroup('/********************************************');
        $fileHandler->addLineToLineGroup($post['name'].' Strategy System under '.$strategy->name.' Strategy');
        $fileHandler->addLineToLineGroup('Created at: '.date("m/d/y", time()).'by Brian O\'Neill');
        $fileHandler->addLineToLineGroup('Description: '.$post['description']);
        $fileHandler->addLineToLineGroup('********************************************/');
        $fileHandler->emptyLine();

        $fileHandler->addLineToLineGroup('namespace App\Strategy\\'.$strategy->name.';');


        $fileHandler->addLineToLineGroup('use \\Log;');

        foreach ($allIndicators as $indicator) {
            $fileHandler->addLineToLineGroup('use \\'.$indicator->class.';');
        }

        $fileHandler->emptyLine();

        $fileHandler->addLineToLineGroup('class '.$post['name'].' extends \\App\\Strategy\\Strategy  {');

        $fileHandler->emptyLine();

        foreach ($allIndicatorEvents as $indicatorEvent) {
            $variableDeclarations = trim(implode(',',$indicatorEvent['variable_declarations']));
            foreach ($variableDeclarations as $variableDeclaration) {
                $fileHandler->addLineToLineGroup('public $'.$variableDeclaration.';', 1);
            }
        }
        $fileHandler->emptyLine();

        $fileHandler->addLineToLineGroup('public function setEntryIndicators() {', 1);

        foreach ($newPositionIndicators as $newPositionIndicator) {
            $fileHandler->addLineToLineGroup('$'.$newPositionIndicator->class_variable_name.' = new \\'.$newPositionIndicator->class.';', 2);
            $fileHandler->addLineToLineGroup('$'.$newPositionIndicator->class_variable_name.'->strategyLogger = $this->strategyLogger;', 2);
            $fileHandler->emptyLine();
        }
        $fileHandler->emptyLine();

        foreach ($post['newPositionConditions'] as $newPositionIndicator) {
            $fileHandler->addLineToLineGroup('$'.$newPositionIndicator->class_variable_name.'->'.$newPositionIndicator['method_call'], 2);
            $fileHandler->emptyLine();
        }

        $fileHandler->addLineToLineGroup('}', 1);

        $fileHandler->emptyLine();
        $fileHandler->addLineToLineGroup('public function getEntryDecision() {', 1);
        $fileHandler->addLineToLineGroup('$this->setEntryIndicators();', 2);
        $fileHandler->addLineToLineGroup('$this->strategyLogger->logIndicators($this->decisionIndicators);', 2);
        $fileHandler->emptyLine();

        $fileHandler->addLineToLineGroup('if (', 2);

        $count = 0;
        foreach ($post['newPositionConditions'] as $newPositionIndicator) {
            if ($count > 0) {
                $fileHandler->addLineToLineGroup(" && ".$newPositionIndicator['opposing_condition_a'], 2);
            }
            else {
                $fileHandler->addLineToLineGroup($newPositionIndicator['opposing_condition_a'], 2);
            }
        }
        $fileHandler->addLineToLineGroup(') {', 2);

        $fileHandler->addLineToLineGroup("\$this->newLongPosition();", 3);
        $fileHandler->addLineToLineGroup("//\$this->marketIfTouchedOrderPrice = ;", 3);
        $fileHandler->addLineToLineGroup('}', 2);

        $fileHandler->addLineToLineGroup('elseif (', 2);

        $count = 0;
        foreach ($post['newPositionConditions'] as $newPositionIndicator) {

            if (strlen($newPositionIndicator['opposing_condition_b']) > 0) {
                $condition = $newPositionIndicator['opposing_condition_b'];
            }
            else {
                $condition = $newPositionIndicator['opposing_condition_a'];
            }

            if ($count > 0) {
                $fileHandler->addLineToLineGroup(" && ".$condition, 2);
            }
            else {
                $fileHandler->addLineToLineGroup($condition, 2);
            }
        }

        $fileHandler->addLineToLineGroup(') {', 2);

        $fileHandler->addLineToLineGroup("\$this->newShortPosition();", 3);
        $fileHandler->addLineToLineGroup("//\$this->marketIfTouchedOrderPrice = ;", 3);

        $fileHandler->addLineToLineGroup('}', 2);

        $fileHandler->addLineToLineGroup('}', 1);

        $fileHandler->emptyLine();
        $fileHandler->addLineToLineGroup('public function inPositionDecision() {', 1);

        foreach ($openPositionIndicators as $openPositionIndicator) {
            $fileHandler->addLineToLineGroup('$'.$openPositionIndicator->class_variable_name.' = new \\'.$openPositionIndicator->class.';', 2);
            $fileHandler->addLineToLineGroup('$'.$openPositionIndicator->class_variable_name.'->strategyLogger = $this->strategyLogger;', 2);
            $fileHandler->emptyLine();
        }
        $fileHandler->emptyLine();
        $fileHandler->addLineToLineGroup('$this->strategyLogger->logIndicators($this->decisionIndicators);', 2);
        $fileHandler->emptyLine();

        foreach ($post['openPositionConditions'] as $openPositionIndicator) {
            $fileHandler->addLineToLineGroup('$'.$openPositionIndicator->class_variable_name.'->'.$openPositionIndicator['method_call'], 2);
            $fileHandler->emptyLine();
        }


        $fileHandler->addLineToLineGroup('if ($this->openPosition[\'side\'] == \'long\') {', 2);

        $fileHandler->emptyLine();

        $aConditions = [];
        $bConditions = [];
        $bothConditions = [];

        foreach ($post['openPositionConditions'] as $openPositionIndicator) {

            if (strlen($newPositionIndicator['opposing_condition_b']) > 0) {
                $aConditions[] = $openPositionIndicator['opposing_condition_a'];
                $bConditions[] = $openPositionIndicator['opposing_condition_b'];
            }
            else {
                $bothConditions[] = $openPositionIndicator['opposing_condition_a'];
            }
        }

        $fileHandler->addLineToLineGroup('//A Conditions', 3);

        foreach ($aConditions as $aCondition) {
            $fileHandler->addLineToLineGroup('// '.$aCondition, 3);
        }

        foreach ($bConditions as $bCondition) {
            $fileHandler->addLineToLineGroup('// '.$bCondition, 3);
        }

        foreach ($bothConditions as $bothCondition) {
            $fileHandler->addLineToLineGroup('// '.$bothCondition, 3);
        }

        $fileHandler->emptyLine();

        $fileHandler->addLineToLineGroup('//if ( CONDITIONS THAT CONTRADICT LONG ) {', 3);
        $fileHandler->addLineToLineGroup('//$this->strategyLogger->logMessage("WE NEED TO CLOSE", 1);', 4);
        $fileHandler->addLineToLineGroup('//$this->closePosition();', 4);
        $fileHandler->addLineToLineGroup('//$this->marketIfTouchedOrderPrice = ;', 4);
        $fileHandler->addLineToLineGroup('//$this->newLongPosition();', 4);
        $fileHandler->addLineToLineGroup('//}', 3);

        $fileHandler->addLineToLineGroup('//else {', 3);
        $fileHandler->addLineToLineGroup('//$this->marketIfTouchedOrderPrice = ;', 4);
        $fileHandler->addLineToLineGroup('//$this->newShortPosition();', 4);
        $fileHandler->addLineToLineGroup('//}', 3);

        $fileHandler->addLineToLineGroup('}', 2);

        $fileHandler->addLineToLineGroup('elseif ($this->openPosition[\'side\'] == \'short\') {', 2);

        $fileHandler->addLineToLineGroup('//if ( CONDITIONS THAT CONTRADICT SHORT ) {', 3);
        $fileHandler->addLineToLineGroup('//$this->strategyLogger->logMessage("WE NEED TO CLOSE", 1);', 4);
        $fileHandler->addLineToLineGroup('//$this->closePosition();', 4);
        $fileHandler->addLineToLineGroup('//$this->marketIfTouchedOrderPrice = ;', 4);
        $fileHandler->addLineToLineGroup('//$this->newShortPosition();', 4);
        $fileHandler->addLineToLineGroup('//}', 3);

        $fileHandler->addLineToLineGroup('//else {', 3);
        $fileHandler->addLineToLineGroup('//$this->marketIfTouchedOrderPrice = ;', 4);
        $fileHandler->addLineToLineGroup('//$this->newLongPosition();', 4);
        $fileHandler->addLineToLineGroup('//}', 3);

        $fileHandler->addLineToLineGroup('}', 2);

        $fileHandler->addLineToLineGroup('}', 1);

        $fileHandler->addLineToLineGroup('public function checkForNewPosition() {', 1);
        $fileHandler->addLineToLineGroup('$this->setOpenPosition();', 2);
        $fileHandler->emptyLine();
        $fileHandler->addLineToLineGroup('if (!$this->openPosition) {', 2);
        $fileHandler->addLineToLineGroup('$this->decision = $this->getEntryDecision();', 3);
        $fileHandler->addLineToLineGroup('}', 2);
        $fileHandler->addLineToLineGroup('else {', 2);
        $fileHandler->addLineToLineGroup('$this->inPositionDecision();', 3);
        $fileHandler->addLineToLineGroup('}', 2);
        $fileHandler->addLineToLineGroup('}', 1);
        $fileHandler->addLineToLineGroup('}', 0);

        $fileHandler->writeToNewFile();

        $fileHandler->filePath = env('APP_ROOT').'app/BackTest/BackTestToBeProcessed/Strategy/'.$strategy->name.'/'.$strategy->name.'BackTestToBeProcessed.php';

        $fileHandler->addLineToLineGroup("use \\App\\Strategy\\".$strategy->name."\\".$post['name'].";");

        $fileHandler->addLinesAboveText('END STRATEGY DECLARATIONS');

        $fileHandler->addLineToLineGroup("elseif (\$this->server->strategy_iteration == '".$post['strategy_iteration_variable']."') {", 0);
        $fileHandler->addLineToLineGroup("\$backTest->rateLevel = 'both';", 1);
        $fileHandler->emptyLine();
        $fileHandler->addLineToLineGroup("\$strategy = new ".$post['name']."(1,1,true);", 1);
        $fileHandler->emptyLine();
        $fileHandler->addLineToLineGroup("//\$strategy->orderType = 'MARKET_IF_TOUCHED';", 1);

        foreach ($allIndicatorEvents as $indicatorEvent) {
            $fileHandler->addLineToLineGroup('$strategy->'.$indicatorEvent['variable_declarations'].' = intval($this->backTestToBeProcessed->variable_1);', 1);
        }
        $fileHandler->emptyLine();

        $fileHandler->addLineToLineGroup("\$strategy->takeProfitPipAmount = 0;", 1);

        $fileHandler->addLineToLineGroup("\$multiplyValue = max([intval(\$this->backTestToBeProcessed->variable_1), intval(\$this->backTestToBeProcessed->variable_2), 
        intval(\$this->backTestToBeProcessed->variable_3), intval(\$this->backTestToBeProcessed->variable_4), intval(\$this->backTestToBeProcessed->variable_5)]);", 1);

        $fileHandler->emptyLine();

        $fileHandler->addLineToLineGroup("//Values for Getting Rates", 1);
        $fileHandler->addLineToLineGroup("\$backTest->rateCount = intval(\$multiplyValue)*10;", 1);
        $fileHandler->addLineToLineGroup("\$backTest->rateIndicatorMin = intval(\$multiplyValue)*3;", 1);
        $fileHandler->addLineToLineGroup("\$backTest->currentRatesProcessed = \$backTest->rateCount;", 1);
        $fileHandler->addLineToLineGroup('}', 0);

        $fileHandler->addLinesAboveText('END OF SYSTEM STRATEGY IFS');


        return $newStrategySystem;

    }
}