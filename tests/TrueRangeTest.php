<?php
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\StrategyEvents\TrueRange;
use App\Model\HistoricalRates;
use App\Strategy\Hlhb\HlhbOnePeriodCrossover;
use App\Services\StrategyLogger;
use App\Services\CurrencyIndicators;

class TrueRangeTest extends TestCase
{

    public function testGetRatesSpecificTimeSimple() {
        //$historicalRates = new App\Model\HistoricalRates();
        //$rates = $historicalRates->getRatesSpecificTimeFull(1,3,1000,'2018-06-20 2:00:00');
        $indicators = new CurrencyIndicators();

        $rates = [];
$stdRate = new \StdClass(); $stdRate->highMid = (float) 48.70; $stdRate->lowMid = (float) 47.79; $stdRate->closeMid = (float) 48.16; $rates[] = $stdRate;
$stdRate = new \StdClass(); $stdRate->highMid = (float) 48.72; $stdRate->lowMid = (float) 48.14; $stdRate->closeMid = (float) 48.61; $rates[] = $stdRate;
$stdRate = new \StdClass(); $stdRate->highMid = (float) 48.90; $stdRate->lowMid = (float) 48.39; $stdRate->closeMid = (float) 48.75; $rates[] = $stdRate;
$stdRate = new \StdClass(); $stdRate->highMid = (float) 48.87; $stdRate->lowMid = (float) 48.37; $stdRate->closeMid = (float) 48.63; $rates[] = $stdRate;
$stdRate = new \StdClass(); $stdRate->highMid = (float) 48.82; $stdRate->lowMid = (float) 48.24; $stdRate->closeMid = (float) 48.74; $rates[] = $stdRate;
$stdRate = new \StdClass(); $stdRate->highMid = (float) 49.05; $stdRate->lowMid = (float) 48.64; $stdRate->closeMid = (float) 49.03; $rates[] = $stdRate;
$stdRate = new \StdClass(); $stdRate->highMid = (float) 49.20; $stdRate->lowMid = (float) 48.94; $stdRate->closeMid = (float) 49.07; $rates[] = $stdRate;
$stdRate = new \StdClass(); $stdRate->highMid = (float) 49.35; $stdRate->lowMid = (float) 48.86; $stdRate->closeMid = (float) 49.32; $rates[] = $stdRate;
$stdRate = new \StdClass(); $stdRate->highMid = (float) 49.92; $stdRate->lowMid = (float) 49.50; $stdRate->closeMid = (float) 49.91; $rates[] = $stdRate;
$stdRate = new \StdClass(); $stdRate->highMid = (float) 50.19; $stdRate->lowMid = (float) 49.87; $stdRate->closeMid = (float) 50.13; $rates[] = $stdRate;
$stdRate = new \StdClass(); $stdRate->highMid = (float) 50.12; $stdRate->lowMid = (float) 49.20; $stdRate->closeMid = (float) 49.53; $rates[] = $stdRate;
$stdRate = new \StdClass(); $stdRate->highMid = (float) 49.66; $stdRate->lowMid = (float) 48.90; $stdRate->closeMid = (float) 49.50; $rates[] = $stdRate;
$stdRate = new \StdClass(); $stdRate->highMid = (float) 49.88; $stdRate->lowMid = (float) 49.43; $stdRate->closeMid = (float) 49.75; $rates[] = $stdRate;
$stdRate = new \StdClass(); $stdRate->highMid = (float) 50.19; $stdRate->lowMid = (float) 49.72; $stdRate->closeMid = (float) 50.03; $rates[] = $stdRate;

        $trueRange = $indicators->averageTrueRange($rates, 14);

        dd($trueRange);


    }
}