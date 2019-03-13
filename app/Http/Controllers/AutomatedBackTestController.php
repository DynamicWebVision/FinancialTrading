<?php namespace App\Http\Controllers;

use App\ForexBackTest\BackTestToBeProcessed\FiftyOneHundredEmaTBP;
use App\ForexBackTest\BackTestToBeProcessed\HmaTrendTBP;
use App\Services\ProcessLogger;
use App\ForexBackTest\BackTestToBeProcessed\HighLowBreakoutTBP;
use App\ForexBackTest\BackTestToBeProcessed\ForexStrategy\EmaMomentum\EmaMomentumBackTest;
use App\ForexBackTest\BackTestToBeProcessed\ForexStrategy\TwoTier\EmaFastHmaSlowBT;
use App\ForexBackTest\BackTestToBeProcessed\ForexStrategy\Bollinger\BollingerSlTp;
use App\ForexBackTest\BackTestToBeProcessed\ForexStrategy\TwoTier\ThreeMaSystem\StayIn;
use App\ForexBackTest\BackTestToBeProcessed\ForexStrategy\TwoTier\PivotPoint\PivotPointTestTPSl;
use App\ForexBackTest\BackTestToBeProcessed\ForexStrategy\MacdMomentum\MacdStayInOrClose;
use App\ForexBackTest\BackTestToBeProcessed\ForexStrategy\Stochastic\StochasticTPSl;
use App\ForexBackTest\BackTestToBeProcessed\ForexStrategy\TwoTier\SlowOverBoughtFastMomentum\SlowOverboughtFastMomentumTpSL;
use App\ForexBackTest\BackTestToBeProcessed\ForexStrategy\Hlhb\HlhbTpWTrailingStop;
use App\ForexBackTest\BackTestToBeProcessed\ForexStrategy\BollingerMomentum\BollingerMomentumBackTest;
use App\ForexBackTest\BackTestToBeProcessed\ForexStrategy\ThreeDucks\ThreeDucks;
use App\ForexBackTest\BackTestToBeProcessed\ForexStrategy\PreviousPeriodPriceBreakout\PreviousPeriodPriceBreakout;
use App\ForexBackTest\BackTestToBeProcessed\ForexStrategy\NewIndicatorTesting\NewIndicatorTestingBackTestToBeProcessed;
use App\ForexBackTest\BackTestToBeProcessed\ForexStrategy\TestingSystems\TestingSystemsBackTestToBeProcessed;
use App\ForexBackTest\BackTestToBeProcessed\ForexStrategy\HmaReversal\HmaReversalBackTestToBeProcessed;
use App\ForexBackTest\BackTestToBeProcessed\ForexStrategy\RsiPullback\RsiPullbackBackTestToBeProcessed;
use App\ForexBackTest\BackTestToBeProcessed\ForexStrategy\HmaPricePoint\HmaPricePointBackTestToBeProcessed;
use App\ForexBackTest\BackTestToBeProcessed\ForexStrategy\EmaPriceCross\EmaPriceCrossBackTestToBeProcessed;
//END OF Backtest Declarations

use \Log;

use App\Model\BackTest;
use App\Model\BackTestToBeProcessed;
use App\Model\BackTestGroup;
use Illuminate\Support\Facades\Config;
use App\Model\Servers;
use App\Services\Utility;


class AutomatedBackTestController extends Controller {
    
    protected $server;
    public $logger;
    
    public function __construct() {
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $serverController = new ServersController();

        $test = $serverController->setServerId();
        $this->utility = new Utility();

    }

    public function sleepThirty() {
        sleep(30);
    }

    public function sleepTen() {
        sleep(10);
    }

    public function sleepSixty() {
        sleep(60);
    }

    public function runAutoBackTestIfFailsUpdate() {
        $this->logger = new ProcessLogger(3);

        $this->logger->logMessage('Starting');

        //Set Last Git Pull Time To Check Later
        $serverController = new ServersController();
        $lastGitPullTime = $serverController->getLastGitPullTime();
        Config::set('last_git_pull_time', $lastGitPullTime);

        $this->server = Servers::find(Config::get('server_id'));

        $firstCount = BackTestToBeProcessed::where('back_test_group_id', '=', $this->server->current_back_test_group_id)->where('start', '=', 0)->where('finish', '=', 0)->count();

        $this->logger->logMessage('runAutoBackTestIfFailsUpdate first count '.$firstCount);

        if ($firstCount == 0) {

            $statCount = $this->getBackTestGroupStatCount();

            if ($statCount == 0) {
                $this->markBackTestGroupStatsComplete();

                $serverController = new ServersController();
                $serverController->getNextBackTestGroupForServer();

                $this->runAutoBackTestIfFailsUpdate();
            }
            else {
                $this->processBackTestStats();
            }
        }
        else {

            $inProcessCount = BackTestToBeProcessed::where('back_test_group_id', '=', $this->server->current_back_test_group_id)->where('start', '=', 1)->where('finish', '=', 0)->where('hung_up', '=', 0)->count();

            $this->logger->logMessage('In Process Count '.$inProcessCount);

            if ($inProcessCount == 0) {

                //No Tests In Process, Start Running
                $this->keepBackTestRunning();
                }
            else {
                //Check Test That's In Process
                $runningProcess = BackTestToBeProcessed::where('back_test_group_id', '=', $this->server->current_back_test_group_id)->where('start', '=', 1)->where('finish', '=', 0)->where('hung_up', '=', 0)->first();
                $last_update_time = $runningProcess->in_process_unix_time;

                //If the Process has not gotten any more rates in 60 minutes, something is almost definitely up
                $timeSinceLastUpdate = time() - $last_update_time;
                if ($timeSinceLastUpdate > (60*60)) {
                    //Delete BackTest Record because it needs to be rolled back since it's hung up
                    BackTest::where('process_id', '=', $runningProcess->id)->delete();

                    $this->logger->logMessage('Time Since Last Process Update '.$timeSinceLastUpdate);

                    //Save the Process as Hung Up To Review Later
                    $runningProcess->hung_up = 1;
                    $runningProcess->save();

                    $this->keepBackTestRunning();
                }
                else {
                    $this->logger->logMessage('Current Backtest To Be Process Updated less than 20 minutes ago. Killing this Iteration');
                    return;
                }
            }
        }
    }

    protected function getBackTestGroupStatCount() {
        return BackTestToBeProcessed::where('stats_finish', '=', 0)->where('stats_start', '=', 0)
            ->where('hung_up', '=', 0)
            ->where('finish', '=', 1)->where('start', '=', 1)->where('back_test_group_id', '=', $this->server->current_back_test_group_id)->count();
    }

    protected function markBackTestGroupStatsComplete() {
        $backTestGroup = BackTestGroup::find($this->server->current_back_test_group_id);
        $backTestGroup->stats_run = 1;
        $backTestGroup->save();
    }

    public function markBackTestGroupAsProcessRunStarted() {
        $backTestGroup = BackTestGroup::find($this->server->current_back_test_group_id);
        $backTestGroup->process_run = 1;
        $backTestGroup->save();
    }

    public function processBackTestStats() {
        $this->logger->logMessage('Starting Stats');
        $this->server = Servers::find(Config::get('server_id'));

        $statsRecordCount = $this->getBackTestGroupStatCount();
        $this->logger->logMessage('Stat Count '.$statsRecordCount);
        while ($statsRecordCount > 0) {
            $autoController = new BackTestStatsController();
            $autoController->backtestProcessStats();

            $statsRecordCount = $this->getBackTestGroupStatCount();
        }
        $this->logger->logMessage('End Backtest Stats');
        $serverController = new ServersController();
        $this->logger->logMessage('Getting Next Server Backtest Group');
        $serverController->getNextBackTestGroupForServer();
        $this->logger->logMessage('Re-calling runAutoBackTestIfFailsUpdate');
        $this->runAutoBackTestIfFailsUpdate();
    }

    public function keepBackTestRunning() {
        $this->server = Servers::find(Config::get('server_id'));

        $groupId = $this->server->current_back_test_group_id;

        $recordCount = BackTestToBeProcessed::where('back_test_group_id', '=', $groupId)
            ->where('finish', '=', 0)
            ->where('start', '=', 0)
            ->where('hung_up', '=', 0)
            ->count();

        while ($recordCount > 0) {
            try {
                $this->logger->logMessage('Starting environmentVariableDriveProcess');
                $this->environmentVariableDriveProcess();
            }
            catch (\Exception $e) {
                $this->logger->logMessage('Backtest Exception: '.$e);
            }
            $recordCount = BackTestToBeProcessed::where('back_test_group_id', '=', $groupId)
                ->where('finish', '=', 0)
                ->where('start', '=', 0)
                ->where('hung_up', '=', 0)
                ->count();

            //Set Last Git Pull Time To Check Later
            $serverController = new ServersController();
            $lastGitPullTime = $serverController->getLastGitPullTime();
            $configLastGitPullTime = Config::get('last_git_pull_time');

            $this->server = Servers::find(Config::get('server_id'));

            if ($lastGitPullTime != $configLastGitPullTime || $this->server->current_back_test_group_id != $groupId) {
                $this->logger->logMessage('last_git_pull_time does not match, killing');
                $this->logger->processEnd();
                die();
            }
        }
        $this->logger->logMessage('Process Record Count 0. Now calling processBackTestStats');
        $this->processBackTestStats();
    }

    public function environmentVariableDriveProcessId($processId) {
        $this->environmentVariableDriveProcess($processId);
    }

    public function environmentVariableDriveProcess($processId = false) {
        $this->server = Servers::find(Config::get('server_id'));

        if ($this->server->current_back_test_strategy == 'HMA') {
            $backTestStrategy = new HmaTrendTBP($processId, $this->server, $this->logger);
            
        }
        elseif ($this->server->current_back_test_strategy == 'HIGH_LOW_BREAKOUT') {
            $backTestStrategy = new HighLowBreakoutTBP($processId, $this->server, $this->logger);
            
        }
        elseif ($this->server->current_back_test_strategy == 'EMA_MOMENTUM') {
            $backTestStrategy = new EmaMomentumBackTest($processId, $this->server, $this->logger);
            
        }
        elseif ($this->server->current_back_test_strategy == 'HMA_TPSL') {
            $backTestStrategy = new HmaTpSlTBP($processId, $this->server, $this->logger);
            
        }
        elseif ($this->server->current_back_test_strategy == 'EMA_MOMENTUM_TPSL') {
            $backTestStrategy = new EmaMomentumSlTP($processId, $this->server, $this->logger);
            
        }
        elseif ($this->server->current_back_test_strategy == 'EMA_MOMENTUM_TPSL_WITH_TRAILING_STOP') {
            $backTestStrategy = new EmaMomentumTPSLAndTrailingStop($processId, $this->server, $this->logger);
            
        }
        elseif ($this->server->current_back_test_strategy == 'HMA_STOP_OR_STRATEGY_CLOSE') {
            $backTestStrategy = new HmaStayInStopLoss($processId, $this->server, $this->logger);
            
        }
        elseif ($this->server->current_back_test_strategy == 'TWO_TIER_EMA_HMA') {
            $backTestStrategy = new EmaFastHmaSlowBT($processId, $this->server, $this->logger);
            
        }
        elseif ($this->server->current_back_test_strategy == 'HMA_CROSS') {
            $backTestStrategy = new HmaCrossTPSL($processId, $this->server, $this->logger);
            
        }
        elseif ($this->server->current_back_test_strategy == 'THREE_STAY_IN') {
            $backTestStrategy = new StayIn($processId, $this->server, $this->logger);
            
        }
        elseif ($this->server->current_back_test_strategy == 'PIVOT_TPSL') {
            $backTestStrategy = new PivotPointTestTPSl($processId, $this->server, $this->logger);
            
        }
        elseif ($this->server->current_back_test_strategy == 'MACD_MOMENTUM_STAYIN') {
            $backTestStrategy = new MacdStayInOrClose($processId, $this->server, $this->logger);
            
        }
        elseif ($this->server->current_back_test_strategy == 'STOCH_TPSL') {
            $backTestStrategy = new StochasticTPSl($processId, $this->server, $this->logger);
            
        }
        elseif ($this->server->current_back_test_strategy == 'TT_OVBT_MOM_TPSL') {
            $backTestStrategy = new SlowOverboughtFastMomentumTpSL($processId, $this->server, $this->logger);
            
        }
        elseif ($this->server->current_back_test_strategy == 'HMA_X_STAYIN') {
            $backTestStrategy = new HmaCrossoverStayIn($processId, $this->server, $this->logger);
            
        }
        elseif ($this->server->current_back_test_strategy == 'HLHB') {
            $backTestStrategy = new HlhbTpWTrailingStop($processId, $this->server, $this->logger);
            
        }
        elseif ($this->server->current_back_test_strategy == 'BOLLINGER' || $this->server->current_back_test_strategy == 'BOLLINGER_PULLBACK') {
            $backTestStrategy = new BollingerMomentumBackTest($processId, $this->server, $this->logger);
            
        }
        elseif ($this->server->current_back_test_strategy == 'HMA_CHANGE_DIRECTION') {
            $backTestStrategy = new HmaRev($processId, $this->server, $this->logger);
            
        }
        elseif ($this->server->current_back_test_strategy == 'THREE_DUCKS') {
            $backTestStrategy = new ThreeDucks($processId, $this->server, $this->logger);
            
        }
        elseif ($this->server->current_back_test_strategy == 'PREVIOUS_PRICE_BREAKOUT') {
            $backTestStrategy = new PreviousPeriodPriceBreakout($processId, $this->server, $this->logger);
            
        }
        elseif ($this->server->current_back_test_strategy == 'TEST_STUFFED_NOSE') {
            $backTestStrategy = new TestStuffedNoseBackTestToBeProcessed($processId, $this->server, $this->logger);
            
        }
        elseif ($this->server->current_back_test_strategy == 'HMA_QUICK_TEST') {
            $backTestStrategy = new HmaQuickTestBackTestToBeProcessed($processId, $this->server, $this->logger);
            
        }
        elseif ($this->server->current_back_test_strategy == 'HMA_TURN') {
            $backTestStrategy = new HmaTurnBackTestToBeProcessed($processId, $this->server, $this->logger);
            
        }
        elseif ($this->server->current_back_test_strategy == 'NEW_INDICATOR_TESTING') {
            $backTestStrategy = new NewIndicatorTestingBackTestToBeProcessed($processId, $this->server, $this->logger);
            
        }
        elseif ($this->server->current_back_test_strategy == 'TESTING_SYSTEMS') {
            $backTestStrategy = new TestingSystemsBackTestToBeProcessed($processId, $this->server, $this->logger);
            
        }
        elseif ($this->server->current_back_test_strategy == 'HMA_REVERSAL') {
            $backTestStrategy = new HmaReversalBackTestToBeProcessed($processId, $this->server, $this->logger);
            
        }
        elseif ($this->server->current_back_test_strategy == 'RSI_PULLBACK') {
            $backTestStrategy = new RsiPullbackBackTestToBeProcessed($processId, $this->server, $this->logger);
            
        }
        elseif ($this->server->current_back_test_strategy == 'HMA_PRICE_POINT') {
            $backTestStrategy = new HmaPricePointBackTestToBeProcessed($processId, $this->server, $this->logger);
            
        }
        elseif ($this->server->current_back_test_strategy == 'EMA_PRICE_X') {
            $backTestStrategy = new EmaPriceCrossBackTestToBeProcessed($processId, $this->server, $this->logger);
        }

        $backTestStrategy->callProcess();
        //END OF STRATEGY IFS
    }
}