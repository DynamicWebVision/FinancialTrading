<?php namespace App\Http\Controllers;

use App\BackTest\BackTestToBeProcessed\FiftyOneHundredEmaTBP;
use App\BackTest\BackTestToBeProcessed\HmaTrendTBP;
use App\BackTest\BackTestToBeProcessed\HighLowBreakoutTBP;
use App\BackTest\BackTestToBeProcessed\Strategy\Hma\HmaTpSlTBP;
use App\BackTest\BackTestToBeProcessed\Strategy\EmaMomentum\EmaMomentumBackTest;
use App\BackTest\BackTestToBeProcessed\Strategy\Hma\HmaStayInStopLoss;
use App\BackTest\BackTestToBeProcessed\Strategy\TwoTier\EmaFastHmaSlowBT;
use App\BackTest\BackTestToBeProcessed\Strategy\HmaCrossover\HmaCrossTPSL;
use App\BackTest\BackTestToBeProcessed\Strategy\HmaCrossover\HmaCrossoverStayIn;
use App\BackTest\BackTestToBeProcessed\Strategy\Bollinger\BollingerSlTp;
use App\BackTest\BackTestToBeProcessed\Strategy\TwoTier\ThreeMaSystem\StayIn;
use App\BackTest\BackTestToBeProcessed\Strategy\TwoTier\PivotPoint\PivotPointTestTPSl;
use App\BackTest\BackTestToBeProcessed\Strategy\MacdMomentum\MacdStayInOrClose;
use App\BackTest\BackTestToBeProcessed\Strategy\Stochastic\StochasticTPSl;
use App\BackTest\BackTestToBeProcessed\Strategy\TwoTier\SlowOverBoughtFastMomentum\SlowOverboughtFastMomentumTpSL;
use App\BackTest\BackTestToBeProcessed\Strategy\Hlhb\HlhbTpWTrailingStop;
use App\BackTest\BackTestToBeProcessed\Strategy\BollingerMomentum\BollingerMomentumBackTest;
use App\BackTest\BackTestToBeProcessed\Strategy\Hma\HmaRev;
use App\BackTest\BackTestToBeProcessed\Strategy\ThreeDucks\ThreeDucks;
use App\BackTest\BackTestToBeProcessed\Strategy\PreviousPeriodPriceBreakout\PreviousPeriodPriceBreakout;

use App\BackTest\BackTestToBeProcessed\Strategy\TestRadsfs\TestRadsfsBackTestToBeProcess;
use App\BackTest\BackTestToBeProcessed\Strategy\TestBabyDog\TestBabyDogBackTestToBeProcess;
use App\BackTest\BackTestToBeProcessed\Strategy\TestBlondeGirl\TestBlondeGirlBackTestToBeProcess;
use App\BackTest\BackTestToBeProcessed\Strategy\TestBrunette\TestBrunetteBackTestToBeProcess;
use App\BackTest\BackTestToBeProcessed\Strategy\TestWeirdGuy\TestWeirdGuyBackTestToBeProcess;
use App\BackTest\BackTestToBeProcessed\Strategy\TestHairryDog\TestHairryDogBackTestToBeProcess;
use App\BackTest\BackTestToBeProcessed\Strategy\TestGreenButton\TestGreenButtonBackTestToBeProcess;
use App\BackTest\BackTestToBeProcessed\Strategy\TestBlueBar\TestBlueBarBackTestToBeProcess;
use App\BackTest\BackTestToBeProcessed\Strategy\TestSideNav\TestSideNavBackTestToBeProcess;
use App\BackTest\BackTestToBeProcessed\Strategy\TestNascar\TestNascarBackTestToBeProcess;
use App\BackTest\BackTestToBeProcessed\Strategy\TestOrangeBall\TestOrangeBallBackTestToBeProcess;
use App\BackTest\BackTestToBeProcessed\Strategy\TestAbcEight\TestAbcEightBackTestToBeProcess;
use App\BackTest\BackTestToBeProcessed\Strategy\TestAbcEE\TestAbcEEBackTestToBeProcess;
use App\BackTest\BackTestToBeProcessed\Strategy\TestCowork\TestCoworkBackTestToBeProcess;
use App\BackTest\BackTestToBeProcessed\Strategy\TestCoworkWood\TestCoworkWoodBackTestToBeProcess;
use App\BackTest\BackTestToBeProcessed\Strategy\TestCoworkMouse\TestCoworkMouseBackTestToBeProcess;
use App\BackTest\BackTestToBeProcessed\Strategy\TestCoworkWindow\TestCoworkWindowBackTestToBeProcess;
use App\BackTest\BackTestToBeProcessed\Strategy\TESTCoworkOutlet\TESTCoworkOutletBackTestToBeProcess;
use App\BackTest\BackTestToBeProcessed\Strategy\TestCoworkKey\TestCoworkKeyBackTestToBeProcess;
use App\BackTest\BackTestToBeProcessed\Strategy\TestCoworkChair\TestCoworkChairBackTestToBeProcess;
use App\BackTest\BackTestToBeProcessed\Strategy\TestCoworkLogitech\TestCoworkLogitechBackTestToBeProcess;
use App\BackTest\BackTestToBeProcessed\Strategy\TestCoworkeLogitech\TestCoworkeLogitechBackTestToBeProcess;
use App\BackTest\BackTestToBeProcessed\Strategy\TestCoworkBlue\TestCoworkBlueBackTestToBeProcess;
use App\BackTest\BackTestToBeProcessed\Strategy\TestCoworkBlue3\TestCoworkBlue3BackTestToBeProcess;
use App\BackTest\BackTestToBeProcessed\Strategy\TestCoworkRestroom\TestCoworkRestroomBackTestToBeProcess;
use App\BackTest\BackTestToBeProcessed\Strategy\TestCoworkBladder\TestCoworkBladderBackTestToBeProcess;
use App\BackTest\BackTestToBeProcessed\Strategy\TestCoworkdBladder\TestCoworkdBladderBackTestToBeProcess;
use App\BackTest\BackTestToBeProcessed\Strategy\TestCoworkRoller\TestCoworkRollerBackTestToBeProcess;
use App\BackTest\BackTestToBeProcessed\Strategy\TestCow22orkRoller\TestCow22orkRollerBackTestToBeProcess;
use App\BackTest\BackTestToBeProcessed\Strategy\TestCoworkMetal\TestCoworkMetalBackTestToBeProcess;
use App\BackTest\BackTestToBeProcessed\Strategy\TestBlackChaira\TestBlackChairaBackTestToBeProcess;
use App\BackTest\BackTestToBeProcessed\Strategy\TestNotAgain\TestNotAgainBackTestToBeProcess;
use App\BackTest\BackTestToBeProcessed\Strategy\TestIIIBBB\TestIIIBBBBackTestToBeProcess;
//END OF Backtest Declarations

use \Log;

use App\Model\BackTest;
use App\Model\BackTestToBeProcessed;
use App\Model\BackTestGroup;
use App\Http\Controllers\ServersController;
use Illuminate\Support\Facades\Config;
use App\Model\Servers;


class AutomatedBackTestController extends Controller {

    public function __construct() {
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $serverController = new ServersController();
        $serverController->setServerId();
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
        Log::info('runAutoBackTestIfFailsUpdate starting');

        $server = Servers::find(Config::get('server_id'));

        $firstCount = BackTestToBeProcessed::where('back_test_group_id', '=', $server->current_back_test_group_id)->where('start', '=', 0)->where('finish', '=', 0)->count();

        Log::info('runAutoBackTestIfFailsUpdate first count '.$firstCount);

        if ($firstCount == 0) {
            $backTestGroup = BackTestGroup::find($server->current_back_test_group_id);
            $backTestGroup->process_run = 1;
            $backTestGroup->save();

            $statCount = BackTestToBeProcessed::where('stats_finish', '=', 0)->where('stats_start', '=', 0)
                ->where('hung_up', '=', 0)
                ->where('finish', '=', 1)->where('start', '=', 1)->where('back_test_group_id', '=', $server->current_back_test_group_id)->count();

            if ($statCount == 0) {
                $backTestGroup = BackTestGroup::find($server->current_back_test_group_id);
                $backTestGroup->stats_run = 1;
                $backTestGroup->save();

                $serverController = new ServersController();
                $serverController->getNextBackTestGroupForServer();

                $this->runAutoBackTestIfFailsUpdate();
            }
            else {
                $this->processBackTestStats();
            }
        }
        else {

            $inProcessCount = BackTestToBeProcessed::where('back_test_group_id', '=', $server->current_back_test_group_id)->where('start', '=', 1)->where('finish', '=', 0)->where('hung_up', '=', 0)->count();

            if ($inProcessCount == 0) {
                //No Tests In Process, Start Running
                $this->keepBackTestRunning();
                }
            else {
                //Check Test That's In Process
                $runningProcess = BackTestToBeProcessed::where('back_test_group_id', '=', $server->current_back_test_group_id)->where('start', '=', 1)->where('finish', '=', 0)->where('hung_up', '=', 0)->first();
                $last_update_time = $runningProcess->in_process_unix_time;

                $fifteenMinutes = 30*60;
                sleep($fifteenMinutes);

                //Get Same Process After 20 Minutes
                $runningProcess = BackTestToBeProcessed::find($runningProcess->id);

                //If the Process has not gotten any more rates in 20 minutes, something is almost definitely up
                if ($last_update_time == $runningProcess->in_process_unix_time && $runningProcess->finish != 1) {
                    //Delete BackTest Record because it needs to be rolled back since it's hung up
                    BackTest::where('process_id', '=', $runningProcess->id)->delete();

                    //Save the Process as Hung Up To Review Later
                    $runningProcess->hung_up = 1;
                    $runningProcess->save();

                    $this->keepBackTestRunning();
                }
                else {
                    return true;
                }
            }
        }
    }

    public function processBackTestStats() {
        $recordCount = BackTestToBeProcessed::where('stats_finish', '=', 0)->where('stats_start', '=', 0)->where('finish', '=', 1)->where('start', '=', 1)
            ->count();

        if ($recordCount > 0) {
            while ($recordCount > 0) {
                $autoController = new BackTestStatsController();
                $autoController->backtestProcessStats();

                $recordCount = BackTestToBeProcessed::where('stats_finish', '=', 0)->where('stats_start', '=', 0)->where('finish', '=', 1)->where('start', '=', 1)
                    ->count();
            }
        }
        else {
            $serverController = new ServersController();
            $serverController->getNextBackTestGroupForServer();
            $this->runAutoBackTestIfFailsUpdate();

        }
    }

    public function keepBackTestRunning() {
        $recordCount = 1;
        $server = Servers::find(Config::get('server_id'));

        $groupId = $server->current_back_test_group_id;

        while ($recordCount > 0) {
            try {
                $this->environmentVariableDriveProcess();
            }
            catch (\Exception $e) {
                Log::critical('BT Exception '.$e);
            }

            $recordCount = BackTestToBeProcessed::where('back_test_group_id', '=', $groupId)
                ->where('finish', '=', 0)
                ->where('start', '=', 0)
                ->where('hung_up', '=', 0)
                ->count();
        }
    }

    public function environmentVariableDriveProcessId($processId) {
        $this->environmentVariableDriveProcess($processId);
    }

    public function environmentVariableDriveProcess($processId = false) {
        $server = Servers::find(Config::get('server_id'));

        if ($server->current_back_test_strategy == 'HMA') {
            $fiftyOneHundredToBeProcessed = new HmaTrendTBP($processId, $server);
            $fiftyOneHundredToBeProcessed->callProcess();
        }
        elseif ($server->current_back_test_strategy == 'HIGH_LOW_BREAKOUT') {
            $fiftyOneHundredToBeProcessed = new HighLowBreakoutTBP($processId, $server);
            $fiftyOneHundredToBeProcessed->callProcess();
        }
        elseif ($server->current_back_test_strategy == 'EMA_MOMENTUM') {
            $fiftyOneHundredToBeProcessed = new EmaMomentumBackTest($processId, $server);
            $fiftyOneHundredToBeProcessed->callProcess();
        }
        elseif ($server->current_back_test_strategy == 'HMA_TPSL') {
            $hmaTakeProfitStopLoss = new HmaTpSlTBP($processId, $server);
            $hmaTakeProfitStopLoss->callProcess();
        }
        elseif ($server->current_back_test_strategy == 'EMA_MOMENTUM_TPSL') {
            $emaMomentumProcess = new EmaMomentumSlTP($processId, $server);
            $emaMomentumProcess->callProcess();
        }
        elseif ($server->current_back_test_strategy == 'EMA_MOMENTUM_TPSL_WITH_TRAILING_STOP') {
            $emaMomentumProcess = new EmaMomentumTPSLAndTrailingStop($processId, $server);
            $emaMomentumProcess->callProcess();
        }
        elseif ($server->current_back_test_strategy == 'HMA_STOP_OR_STRATEGY_CLOSE') {
            $emaMomentumProcess = new HmaStayInStopLoss($processId, $server);
            $emaMomentumProcess->callProcess();
        }
        elseif ($server->current_back_test_strategy == 'TWO_TIER_EMA_HMA') {
            $emaMomentumProcess = new EmaFastHmaSlowBT($processId, $server);
            $emaMomentumProcess->callProcess();
        }
        elseif ($server->current_back_test_strategy == 'HMA_CROSS') {
            $emaMomentumProcess = new HmaCrossTPSL($processId, $server);
            $emaMomentumProcess->callProcess();
        }
//        elseif ($server->current_back_test_strategy == 'BOLLINGER_PULLBACK') {
//            $emaMomentumProcess = new BollingerSlTp($processId, $server);
//            $emaMomentumProcess->callProcess();
//        }
        elseif ($server->current_back_test_strategy == 'THREE_STAY_IN') {
            $emaMomentumProcess = new StayIn($processId, $server);
            $emaMomentumProcess->callProcess();
        }
        elseif ($server->current_back_test_strategy == 'PIVOT_TPSL') {
            $emaMomentumProcess = new PivotPointTestTPSl($processId, $server);
            $emaMomentumProcess->callProcess();
        }
        elseif ($server->current_back_test_strategy == 'MACD_MOMENTUM_STAYIN') {
            $emaMomentumProcess = new MacdStayInOrClose($processId, $server);
            $emaMomentumProcess->callProcess();
        }
        elseif ($server->current_back_test_strategy == 'STOCH_TPSL') {
            $emaMomentumProcess = new StochasticTPSl($processId, $server);
            $emaMomentumProcess->callProcess();
        }
        elseif ($server->current_back_test_strategy == 'TT_OVBT_MOM_TPSL') {
            $slowOverboughtFastMomentumTpSl = new SlowOverboughtFastMomentumTpSL($processId, $server);
            $slowOverboughtFastMomentumTpSl->callProcess();
        }
        elseif ($server->current_back_test_strategy == 'HMA_X_STAYIN') {
            $slowOverboughtFastMomentumTpSl = new HmaCrossoverStayIn($processId, $server);
            $slowOverboughtFastMomentumTpSl->callProcess();
        }
        elseif ($server->current_back_test_strategy == 'HLHB') {
            $slowOverboughtFastMomentumTpSl = new HlhbTpWTrailingStop($processId, $server);
            $slowOverboughtFastMomentumTpSl->callProcess();
        }
        elseif ($server->current_back_test_strategy == 'BOLLINGER' || $server->current_back_test_strategy == 'BOLLINGER_PULLBACK') {
            $bolingerMomentumTest = new BollingerMomentumBackTest($processId, $server);
            $bolingerMomentumTest->callProcess();
        }
        elseif ($server->current_back_test_strategy == 'HMA_CHANGE_DIRECTION') {
            $hmaReverseTest = new HmaRev($processId, $server);
            $hmaReverseTest->callProcess();
        }
        elseif ($server->current_back_test_strategy == 'THREE_DUCKS') {
            $hmaReverseTest = new ThreeDucks($processId, $server);
            $hmaReverseTest->callProcess();
        }
        elseif ($server->current_back_test_strategy == 'PREVIOUS_PRICE_BREAKOUT') {
            $hmaReverseTest = new PreviousPeriodPriceBreakout($processId, $server);
            $hmaReverseTest->callProcess();
        }
        elseif ($server->current_back_test_strategy == 'basdf') {
            $backTestStrategy = new TestRadsfs($processId, $server);
            $backTestStrategy->callProcess();
        }
        elseif ($server->current_back_test_strategy == 'TEST_BABY_DOG') {
            $backTestStrategy = new TestBabyDog($processId, $server);
            $backTestStrategy->callProcess();
        }
        elseif ($server->current_back_test_strategy == 'TEST_BLONDE_GIRL') {
            $backTestStrategy = new TestBlondeGirl($processId, $server);
            $backTestStrategy->callProcess();
        }
        elseif ($server->current_back_test_strategy == 'TEST_BRUNETTE') {
            $backTestStrategy = new TestBrunette($processId, $server);
            $backTestStrategy->callProcess();
        }
        elseif ($server->current_back_test_strategy == 'TEST_WEIRD_GUY') {
            $backTestStrategy = new TestWeirdGuyBackTestToBeProcessed($processId, $server);
            $backTestStrategy->callProcess();
        }
        elseif ($server->current_back_test_strategy == 'TEST_HAIRRY_DOG') {
            $backTestStrategy = new TestHairryDogBackTestToBeProcessed($processId, $server);
            $backTestStrategy->callProcess();
        }
        elseif ($server->current_back_test_strategy == 'TEST_GREEN_BUTTON') {
            $backTestStrategy = new TestGreenButtonBackTestToBeProcessed($processId, $server);
            $backTestStrategy->callProcess();
        }
        elseif ($server->current_back_test_strategy == 'TEST_BLUE_BAR') {
            $backTestStrategy = new TestBlueBarBackTestToBeProcessed($processId, $server);
            $backTestStrategy->callProcess();
        }
        elseif ($server->current_back_test_strategy == 'TEST_SIDE_NAV') {
            $backTestStrategy = new TestSideNavBackTestToBeProcessed($processId, $server);
            $backTestStrategy->callProcess();
        }
        elseif ($server->current_back_test_strategy == 'TEST_NASCAR') {
            $backTestStrategy = new TestNascarBackTestToBeProcessed($processId, $server);
            $backTestStrategy->callProcess();
        }
        elseif ($server->current_back_test_strategy == 'TEST_ORANGE_BALL') {
            $backTestStrategy = new TestOrangeBallBackTestToBeProcessed($processId, $server);
            $backTestStrategy->callProcess();
        }
        elseif ($server->current_back_test_strategy == 'TEST_ABC_EIGHT') {
            $backTestStrategy = new TestAbcEightBackTestToBeProcessed($processId, $server);
            $backTestStrategy->callProcess();
        }
        elseif ($server->current_back_test_strategy == 'TEST_ABC_EIGHT') {
            $backTestStrategy = new TestAbcEEBackTestToBeProcessed($processId, $server);
            $backTestStrategy->callProcess();
        }
        elseif ($server->current_back_test_strategy == 'TEST_COWORK') {
            $backTestStrategy = new TestCoworkBackTestToBeProcessed($processId, $server);
            $backTestStrategy->callProcess();
        }
        elseif ($server->current_back_test_strategy == 'TEST_COWORK_WOOD') {
            $backTestStrategy = new TestCoworkWoodBackTestToBeProcessed($processId, $server);
            $backTestStrategy->callProcess();
        }
        elseif ($server->current_back_test_strategy == 'TEST_COWORK_MOUSE') {
            $backTestStrategy = new TestCoworkMouseBackTestToBeProcessed($processId, $server);
            $backTestStrategy->callProcess();
        }
        elseif ($server->current_back_test_strategy == 'TEST_COWORK_WINDOW') {
            $backTestStrategy = new TestCoworkWindowBackTestToBeProcessed($processId, $server);
            $backTestStrategy->callProcess();
        }
        elseif ($server->current_back_test_strategy == 'TEST_COWORK_OUTLET') {
            $backTestStrategy = new TESTCoworkOutletBackTestToBeProcessed($processId, $server);
            $backTestStrategy->callProcess();
        }
        elseif ($server->current_back_test_strategy == 'TEST_COWORK_KEy') {
            $backTestStrategy = new TestCoworkKeyBackTestToBeProcessed($processId, $server);
            $backTestStrategy->callProcess();
        }
        elseif ($server->current_back_test_strategy == 'TEST_COWORK_CHAIR') {
            $backTestStrategy = new TestCoworkChairBackTestToBeProcessed($processId, $server);
            $backTestStrategy->callProcess();
        }
        elseif ($server->current_back_test_strategy == 'TEST_COWORK_LOGITECH') {
            $backTestStrategy = new TestCoworkLogitechBackTestToBeProcessed($processId, $server);
            $backTestStrategy->callProcess();
        }
        elseif ($server->current_back_test_strategy == 'TEST_COWORKE_LOGITECH') {
            $backTestStrategy = new TestCoworkeLogitechBackTestToBeProcessed($processId, $server);
            $backTestStrategy->callProcess();
        }
        elseif ($server->current_back_test_strategy == 'TEST_COWORK_BLUE') {
            $backTestStrategy = new TestCoworkBlueBackTestToBeProcessed($processId, $server);
            $backTestStrategy->callProcess();
        }
        elseif ($server->current_back_test_strategy == 'TEST_COWOR3K_BLUE') {
            $backTestStrategy = new TestCoworkBlue3BackTestToBeProcessed($processId, $server);
            $backTestStrategy->callProcess();
        }
        elseif ($server->current_back_test_strategy == 'TEST_COWORK_RESTROOM') {
            $backTestStrategy = new TestCoworkRestroomBackTestToBeProcessed($processId, $server);
            $backTestStrategy->callProcess();
        }
        elseif ($server->current_back_test_strategy == 'TEST_COWORK_BLADDER') {
            $backTestStrategy = new TestCoworkBladderBackTestToBeProcessed($processId, $server);
            $backTestStrategy->callProcess();
        }
        elseif ($server->current_back_test_strategy == 'TEST_CEEEOWORK_BLADDER') {
            $backTestStrategy = new TestCoworkdBladderBackTestToBeProcessed($processId, $server);
            $backTestStrategy->callProcess();
        }
        elseif ($server->current_back_test_strategy == 'TEST_COWORK_ROLLER') {
            $backTestStrategy = new TestCoworkRollerBackTestToBeProcessed($processId, $server);
            $backTestStrategy->callProcess();
        }
        elseif ($server->current_back_test_strategy == 'TEST_COEWORK_ROLLER') {
            $backTestStrategy = new TestCow22orkRollerBackTestToBeProcessed($processId, $server);
            $backTestStrategy->callProcess();
        }
        elseif ($server->current_back_test_strategy == 'TEST_COWORK_METAL') {
            $backTestStrategy = new TestCoworkMetalBackTestToBeProcessed($processId, $server);
            $backTestStrategy->callProcess();
        }
        elseif ($server->current_back_test_strategy == 'TEST_BLACK_CHAIR') {
            $backTestStrategy = new TestBlackChairaBackTestToBeProcessed($processId, $server);
            $backTestStrategy->callProcess();
        }
        elseif ($server->current_back_test_strategy == 'TEST_NOT_AGAIN') {
            $backTestStrategy = new TestNotAgainBackTestToBeProcessed($processId, $server);
            $backTestStrategy->callProcess();
        }
        elseif ($server->current_back_test_strategy == 'TEST_ABCeeDEFG') {
            $backTestStrategy = new TestIIIBBBBackTestToBeProcessed($processId, $server);
            $backTestStrategy->callProcess();
        }
        //END OF STRATEGY IFS
    }
}