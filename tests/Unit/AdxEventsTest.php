<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\CurrencyIndicators;
use App\Model\HistoricalRates;
use App\Model\TmpTestRates;

class AdxEventsTest extends TestCase
{

    public $fullRates = '[{"highMid":0.95904,"closeMid":0.95836,"lowMid":0.95776,"openMid":0.95904,"volume":347},{"highMid":0.95897,"closeMid":0.95835,"lowMid":0.9579,"openMid":0.9584,"volume":322},{"highMid":0.95835,"closeMid":0.95835,"lowMid":0.95835,"openMid":0.95835,"volume":1},{"highMid":0.95835,"closeMid":0.95835,"lowMid":0.95835,"openMid":0.95835,"volume":1},{"highMid":0.95835,"closeMid":0.95835,"lowMid":0.95835,"openMid":0.95835,"volume":1},{"highMid":0.95835,"closeMid":0.95835,"lowMid":0.95835,"openMid":0.95835,"volume":1},{"highMid":0.95835,"closeMid":0.95835,"lowMid":0.95835,"openMid":0.95835,"volume":1},{"highMid":0.95835,"closeMid":0.95835,"lowMid":0.95835,"openMid":0.95835,"volume":1},{"highMid":0.95835,"closeMid":0.95835,"lowMid":0.95835,"openMid":0.95835,"volume":1},{"highMid":0.95835,"closeMid":0.95835,"lowMid":0.95835,"openMid":0.95835,"volume":1},{"highMid":0.95835,"closeMid":0.95835,"lowMid":0.95835,"openMid":0.95835,"volume":1},{"highMid":0.95835,"closeMid":0.95835,"lowMid":0.95835,"openMid":0.95835,"volume":1},{"highMid":0.95835,"closeMid":0.95835,"lowMid":0.95835,"openMid":0.95835,"volume":1},{"highMid":0.95835,"closeMid":0.95835,"lowMid":0.95835,"openMid":0.95835,"volume":1},{"highMid":0.95835,"closeMid":0.95835,"lowMid":0.95835,"openMid":0.95835,"volume":1},{"highMid":0.95835,"closeMid":0.95835,"lowMid":0.95835,"openMid":0.95835,"volume":1},{"highMid":0.95835,"closeMid":0.95835,"lowMid":0.95835,"openMid":0.95835,"volume":1},{"highMid":0.95835,"closeMid":0.95835,"lowMid":0.95835,"openMid":0.95835,"volume":1},{"highMid":0.95835,"closeMid":0.95835,"lowMid":0.95835,"openMid":0.95835,"volume":1},{"highMid":0.95835,"closeMid":0.95835,"lowMid":0.95835,"openMid":0.95835,"volume":1},{"highMid":0.95835,"closeMid":0.95835,"lowMid":0.95835,"openMid":0.95835,"volume":1},{"highMid":0.95835,"closeMid":0.95835,"lowMid":0.95835,"openMid":0.95835,"volume":1},{"highMid":0.95835,"closeMid":0.95835,"lowMid":0.95835,"openMid":0.95835,"volume":1},{"highMid":0.95919,"closeMid":0.95891,"lowMid":0.95838,"openMid":0.95871,"volume":105},{"highMid":0.95917,"closeMid":0.95884,"lowMid":0.95865,"openMid":0.95896,"volume":80},{"highMid":0.95906,"closeMid":0.95906,"lowMid":0.95779,"openMid":0.95889,"volume":125},{"highMid":0.95937,"closeMid":0.95609,"lowMid":0.95593,"openMid":0.9591,"volume":428},{"highMid":0.95848,"closeMid":0.95827,"lowMid":0.95607,"openMid":0.95615,"volume":653},{"highMid":0.95889,"closeMid":0.95842,"lowMid":0.95782,"openMid":0.95831,"volume":362},{"highMid":0.95996,"closeMid":0.9596,"lowMid":0.95803,"openMid":0.95837,"volume":646},{"highMid":0.95999,"closeMid":0.95936,"lowMid":0.9588,"openMid":0.95956,"volume":416},{"highMid":0.95958,"closeMid":0.95901,"lowMid":0.95826,"openMid":0.95931,"volume":259},{"highMid":0.96006,"closeMid":0.95942,"lowMid":0.95868,"openMid":0.95904,"volume":459},{"highMid":0.96004,"closeMid":0.95952,"lowMid":0.95903,"openMid":0.95947,"volume":225},{"highMid":0.95969,"closeMid":0.9591,"lowMid":0.95852,"openMid":0.95949,"volume":214},{"highMid":0.96013,"closeMid":0.95988,"lowMid":0.95856,"openMid":0.95913,"volume":292},{"highMid":0.96112,"closeMid":0.95994,"lowMid":0.95947,"openMid":0.95984,"volume":754},{"highMid":0.9623,"closeMid":0.96155,"lowMid":0.95987,"openMid":0.95998,"volume":1176},{"highMid":0.96219,"closeMid":0.9595,"lowMid":0.9595,"openMid":0.96159,"volume":925},{"highMid":0.95986,"closeMid":0.95753,"lowMid":0.95718,"openMid":0.95946,"volume":855},{"highMid":0.95947,"closeMid":0.95869,"lowMid":0.95635,"openMid":0.95749,"volume":1106},{"highMid":0.95927,"closeMid":0.95792,"lowMid":0.9577,"openMid":0.95873,"volume":829},{"highMid":0.95788,"closeMid":0.95586,"lowMid":0.95548,"openMid":0.95788,"volume":866},{"highMid":0.95582,"closeMid":0.95284,"lowMid":0.95188,"openMid":0.95582,"volume":1597},{"highMid":0.95428,"closeMid":0.94768,"lowMid":0.94712,"openMid":0.9528,"volume":2058},{"highMid":0.9496,"closeMid":0.94902,"lowMid":0.94764,"openMid":0.94772,"volume":969},{"highMid":0.94934,"closeMid":0.94912,"lowMid":0.9485,"openMid":0.94906,"volume":404},{"highMid":0.94966,"closeMid":0.94828,"lowMid":0.94824,"openMid":0.94908,"volume":448},{"highMid":0.94976,"closeMid":0.94942,"lowMid":0.94832,"openMid":0.94832,"volume":347},{"highMid":0.9497,"closeMid":0.94876,"lowMid":0.9484,"openMid":0.94938,"volume":229},{"highMid":0.94927,"closeMid":0.94921,"lowMid":0.94858,"openMid":0.9488,"volume":394},{"highMid":0.94928,"closeMid":0.94881,"lowMid":0.94861,"openMid":0.94911,"volume":198},{"highMid":0.94987,"closeMid":0.94935,"lowMid":0.94835,"openMid":0.94882,"volume":656},{"highMid":0.95036,"closeMid":0.94968,"lowMid":0.94868,"openMid":0.94931,"volume":1231},{"highMid":0.94979,"closeMid":0.94788,"lowMid":0.94774,"openMid":0.94964,"volume":442},{"highMid":0.94854,"closeMid":0.94798,"lowMid":0.94707,"openMid":0.94785,"volume":394},{"highMid":0.9486,"closeMid":0.94838,"lowMid":0.94768,"openMid":0.94802,"volume":1324},{"highMid":0.94878,"closeMid":0.9479,"lowMid":0.9478,"openMid":0.9484,"volume":716},{"highMid":0.94878,"closeMid":0.94806,"lowMid":0.94734,"openMid":0.94794,"volume":777},{"highMid":0.9491,"closeMid":0.94864,"lowMid":0.94802,"openMid":0.94802,"volume":374},{"highMid":0.94974,"closeMid":0.94912,"lowMid":0.94836,"openMid":0.9486,"volume":747},{"highMid":0.95184,"closeMid":0.95152,"lowMid":0.94888,"openMid":0.94908,"volume":1069},{"highMid":0.95228,"closeMid":0.94814,"lowMid":0.94604,"openMid":0.95148,"volume":1632},{"highMid":0.94882,"closeMid":0.9478,"lowMid":0.94762,"openMid":0.94818,"volume":1087},{"highMid":0.94794,"closeMid":0.94598,"lowMid":0.94598,"openMid":0.94784,"volume":930},{"highMid":0.94934,"closeMid":0.94892,"lowMid":0.94602,"openMid":0.94602,"volume":847},{"highMid":0.94952,"closeMid":0.94378,"lowMid":0.9424,"openMid":0.94888,"volume":1445},{"highMid":0.94476,"closeMid":0.94432,"lowMid":0.9425,"openMid":0.94383,"volume":1322},{"highMid":0.94586,"closeMid":0.94322,"lowMid":0.94206,"openMid":0.94436,"volume":1979},{"highMid":0.94734,"closeMid":0.94528,"lowMid":0.94324,"openMid":0.94326,"volume":1258}]';


    public function testAdx() {
        $currencyIndicators = new CurrencyIndicators();

        $historicalRates = new \App\Model\HistoricalRates();
        $rates = $historicalRates->getRatesSpecificTimeFull(1,3,100,'2018-06-27 9:00:00');

        $response = $currencyIndicators->adx($rates, 14);

        //dd($response);
    }

    public function testSimpleAdx() {
        $currencyIndicators = new CurrencyIndicators();

        $tmpTestRates = TmpTestRates::where('test', '=', 'adx')->orderBy('id')->get()->toArray();

        $rates = $fullRates = array_map(function($rate) {

            $stdRate = new \StdClass();

            $stdRate->highMid = (float) $rate['high_mid'];
            $stdRate->closeMid = (float) $rate['close_mid'];
            $stdRate->lowMid = (float) $rate['low_mid'];
            $stdRate->openMid = (float) $rate['open_mid'];
            //$stdRate->volume = (float) $rate['volume'];

            return $stdRate;
        }, $tmpTestRates);

        $adx = $currencyIndicators->adx($rates, 14);

        //dd($adx);
    }
}
