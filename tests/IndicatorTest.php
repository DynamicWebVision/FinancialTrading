<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Services\CurrencyIndicators;
use App\Model\HistoricalRates;

class IndicatorTest extends TestCase
{

    public $fullRates = '[{"time":"2018-01-22T03:15:00.000000Z","openMid":1.223,"highMid":1.22311,"lowMid":1.22248,"closeMid":1.22262,"volume":214,"complete":true},{"time":"2018-01-22T03:30:00.000000Z","openMid":1.222665,"highMid":1.22291,"lowMid":1.2225,"closeMid":1.22289,"volume":271,"complete":true},{"time":"2018-01-22T03:45:00.000000Z","openMid":1.22287,"highMid":1.22308,"lowMid":1.22262,"closeMid":1.2229,"volume":250,"complete":true},{"time":"2018-01-22T04:00:00.000000Z","openMid":1.22288,"highMid":1.223,"lowMid":1.22171,"closeMid":1.221725,"volume":618,"complete":true},{"time":"2018-01-22T04:15:00.000000Z","openMid":1.221755,"highMid":1.2225,"lowMid":1.22165,"closeMid":1.221775,"volume":532,"complete":true},{"time":"2018-01-22T04:30:00.000000Z","openMid":1.221835,"highMid":1.2225,"lowMid":1.22179,"closeMid":1.222415,"volume":352,"complete":true},{"time":"2018-01-22T04:45:00.000000Z","openMid":1.22242,"highMid":1.22249,"lowMid":1.22222,"closeMid":1.222395,"volume":139,"complete":true},{"time":"2018-01-22T05:00:00.000000Z","openMid":1.22237,"highMid":1.22249,"lowMid":1.22213,"closeMid":1.2223,"volume":228,"complete":true},{"time":"2018-01-22T05:15:00.000000Z","openMid":1.222275,"highMid":1.22229,"lowMid":1.22174,"closeMid":1.22229,"volume":488,"complete":true},{"time":"2018-01-22T05:30:00.000000Z","openMid":1.22227,"highMid":1.22296,"lowMid":1.22216,"closeMid":1.222545,"volume":343,"complete":true},{"time":"2018-01-22T05:45:00.000000Z","openMid":1.22259,"highMid":1.2233,"lowMid":1.22243,"closeMid":1.22319,"volume":386,"complete":true},{"time":"2018-01-22T06:00:00.000000Z","openMid":1.22316,"highMid":1.22342,"lowMid":1.2225,"closeMid":1.22257,"volume":474,"complete":true},{"time":"2018-01-22T06:15:00.000000Z","openMid":1.222625,"highMid":1.22286,"lowMid":1.22185,"closeMid":1.222075,"volume":575,"complete":true},{"time":"2018-01-22T06:30:00.000000Z","openMid":1.22208,"highMid":1.2227,"lowMid":1.22182,"closeMid":1.22245,"volume":397,"complete":true},{"time":"2018-01-22T06:45:00.000000Z","openMid":1.222445,"highMid":1.22276,"lowMid":1.22141,"closeMid":1.22161,"volume":679,"complete":true},{"time":"2018-01-22T07:00:00.000000Z","openMid":1.221625,"highMid":1.22255,"lowMid":1.22156,"closeMid":1.222085,"volume":691,"complete":true},{"time":"2018-01-22T07:15:00.000000Z","openMid":1.222035,"highMid":1.22308,"lowMid":1.22194,"closeMid":1.22303,"volume":919,"complete":true},{"time":"2018-01-22T07:30:00.000000Z","openMid":1.22308,"highMid":1.22394,"lowMid":1.22296,"closeMid":1.22332,"volume":670,"complete":true},{"time":"2018-01-22T07:45:00.000000Z","openMid":1.223375,"highMid":1.22427,"lowMid":1.22308,"closeMid":1.22374,"volume":867,"complete":true},{"time":"2018-01-22T08:00:00.000000Z","openMid":1.223795,"highMid":1.22458,"lowMid":1.22358,"closeMid":1.22415,"volume":962,"complete":true},{"time":"2018-01-22T08:15:00.000000Z","openMid":1.224115,"highMid":1.22446,"lowMid":1.22355,"closeMid":1.224105,"volume":678,"complete":true},{"time":"2018-01-22T08:30:00.000000Z","openMid":1.22415,"highMid":1.225,"lowMid":1.22406,"closeMid":1.224585,"volume":513,"complete":true},{"time":"2018-01-22T08:45:00.000000Z","openMid":1.22463,"highMid":1.22471,"lowMid":1.22404,"closeMid":1.22418,"volume":478,"complete":true},{"time":"2018-01-22T09:00:00.000000Z","openMid":1.224225,"highMid":1.2256,"lowMid":1.224205,"closeMid":1.2252,"volume":429,"complete":true},{"time":"2018-01-22T09:15:00.000000Z","openMid":1.22518,"highMid":1.22526,"lowMid":1.22475,"closeMid":1.22496,"volume":561,"complete":true},{"time":"2018-01-22T09:30:00.000000Z","openMid":1.224965,"highMid":1.22536,"lowMid":1.224735,"closeMid":1.224735,"volume":328,"complete":true},{"time":"2018-01-22T09:45:00.000000Z","openMid":1.22471,"highMid":1.22546,"lowMid":1.22438,"closeMid":1.22495,"volume":377,"complete":true},{"time":"2018-01-22T10:00:00.000000Z","openMid":1.22498,"highMid":1.22556,"lowMid":1.22478,"closeMid":1.22556,"volume":401,"complete":true},{"time":"2018-01-22T10:15:00.000000Z","openMid":1.225555,"highMid":1.22668,"lowMid":1.22525,"closeMid":1.226565,"volume":622,"complete":true},{"time":"2018-01-22T10:30:00.000000Z","openMid":1.22654,"highMid":1.22671,"lowMid":1.22576,"closeMid":1.22627,"volume":520,"complete":true},{"time":"2018-01-22T10:45:00.000000Z","openMid":1.226295,"highMid":1.22634,"lowMid":1.22579,"closeMid":1.22621,"volume":389,"complete":true},{"time":"2018-01-22T11:00:00.000000Z","openMid":1.226235,"highMid":1.2263,"lowMid":1.22588,"closeMid":1.226145,"volume":332,"complete":true},{"time":"2018-01-22T11:15:00.000000Z","openMid":1.2261,"highMid":1.22613,"lowMid":1.22549,"closeMid":1.225755,"volume":428,"complete":true},{"time":"2018-01-22T11:30:00.000000Z","openMid":1.225795,"highMid":1.22589,"lowMid":1.22513,"closeMid":1.22527,"volume":339,"complete":true},{"time":"2018-01-22T11:45:00.000000Z","openMid":1.2253,"highMid":1.22561,"lowMid":1.22518,"closeMid":1.22552,"volume":332,"complete":true},{"time":"2018-01-22T12:00:00.000000Z","openMid":1.225495,"highMid":1.22567,"lowMid":1.22482,"closeMid":1.22498,"volume":318,"complete":true},{"time":"2018-01-22T12:15:00.000000Z","openMid":1.225,"highMid":1.22524,"lowMid":1.22456,"closeMid":1.224595,"volume":337,"complete":true},{"time":"2018-01-22T12:30:00.000000Z","openMid":1.22464,"highMid":1.22486,"lowMid":1.22392,"closeMid":1.22429,"volume":321,"complete":true},{"time":"2018-01-22T12:45:00.000000Z","openMid":1.224245,"highMid":1.2243,"lowMid":1.2239,"closeMid":1.22414,"volume":338,"complete":true},{"time":"2018-01-22T13:00:00.000000Z","openMid":1.224135,"highMid":1.22418,"lowMid":1.22336,"closeMid":1.22391,"volume":352,"complete":true},{"time":"2018-01-22T13:15:00.000000Z","openMid":1.22387,"highMid":1.2246,"lowMid":1.22374,"closeMid":1.2246,"volume":530,"complete":true},{"time":"2018-01-22T13:30:00.000000Z","openMid":1.2246,"highMid":1.225,"lowMid":1.22428,"closeMid":1.224715,"volume":418,"complete":true},{"time":"2018-01-22T13:45:00.000000Z","openMid":1.22474,"highMid":1.22519,"lowMid":1.2247,"closeMid":1.224945,"volume":391,"complete":true},{"time":"2018-01-22T14:00:00.000000Z","openMid":1.224985,"highMid":1.22536,"lowMid":1.22498,"closeMid":1.22529,"volume":575,"complete":true},{"time":"2018-01-22T14:15:00.000000Z","openMid":1.225265,"highMid":1.22587,"lowMid":1.22449,"closeMid":1.22472,"volume":800,"complete":true},{"time":"2018-01-22T14:30:00.000000Z","openMid":1.22476,"highMid":1.22478,"lowMid":1.22396,"closeMid":1.22407,"volume":664,"complete":true},{"time":"2018-01-22T14:45:00.000000Z","openMid":1.22402,"highMid":1.22454,"lowMid":1.22384,"closeMid":1.22402,"volume":482,"complete":true},{"time":"2018-01-22T15:00:00.000000Z","openMid":1.224045,"highMid":1.22465,"lowMid":1.223865,"closeMid":1.224585,"volume":418,"complete":true},{"time":"2018-01-22T15:15:00.000000Z","openMid":1.224635,"highMid":1.22523,"lowMid":1.2243,"closeMid":1.224875,"volume":620,"complete":true},{"time":"2018-01-22T15:30:00.000000Z","openMid":1.22492,"highMid":1.22592,"lowMid":1.22491,"closeMid":1.225715,"volume":575,"complete":true},{"time":"2018-01-22T15:45:00.000000Z","openMid":1.225695,"highMid":1.22608,"lowMid":1.22462,"closeMid":1.22537,"volume":1064,"complete":true},{"time":"2018-01-22T16:00:00.000000Z","openMid":1.22535,"highMid":1.22541,"lowMid":1.22458,"closeMid":1.225105,"volume":1199,"complete":true},{"time":"2018-01-22T16:15:00.000000Z","openMid":1.225135,"highMid":1.22543,"lowMid":1.22483,"closeMid":1.22543,"volume":480,"complete":true},{"time":"2018-01-22T16:30:00.000000Z","openMid":1.225435,"highMid":1.22549,"lowMid":1.22421,"closeMid":1.224285,"volume":436,"complete":true},{"time":"2018-01-22T16:45:00.000000Z","openMid":1.224265,"highMid":1.22444,"lowMid":1.2228,"closeMid":1.222805,"volume":819,"complete":true},{"time":"2018-01-22T17:00:00.000000Z","openMid":1.22281,"highMid":1.22342,"lowMid":1.2224,"closeMid":1.223225,"volume":933,"complete":true},{"time":"2018-01-22T17:15:00.000000Z","openMid":1.22323,"highMid":1.2237,"lowMid":1.2224,"closeMid":1.22355,"volume":1002,"complete":true},{"time":"2018-01-22T17:30:00.000000Z","openMid":1.2235,"highMid":1.22424,"lowMid":1.22346,"closeMid":1.223995,"volume":463,"complete":true},{"time":"2018-01-22T17:45:00.000000Z","openMid":1.224015,"highMid":1.22448,"lowMid":1.22394,"closeMid":1.224375,"volume":462,"complete":true},{"time":"2018-01-22T18:00:00.000000Z","openMid":1.22438,"highMid":1.224575,"lowMid":1.22392,"closeMid":1.22456,"volume":313,"complete":true},{"time":"2018-01-22T18:15:00.000000Z","openMid":1.224565,"highMid":1.22485,"lowMid":1.22433,"closeMid":1.22451,"volume":406,"complete":true},{"time":"2018-01-22T18:30:00.000000Z","openMid":1.224515,"highMid":1.2251,"lowMid":1.22427,"closeMid":1.224925,"volume":348,"complete":true},{"time":"2018-01-22T18:45:00.000000Z","openMid":1.224955,"highMid":1.224985,"lowMid":1.22456,"closeMid":1.224935,"volume":228,"complete":true},{"time":"2018-01-22T19:00:00.000000Z","openMid":1.22498,"highMid":1.22512,"lowMid":1.22478,"closeMid":1.225025,"volume":258,"complete":true},{"time":"2018-01-22T19:15:00.000000Z","openMid":1.225075,"highMid":1.22557,"lowMid":1.22496,"closeMid":1.22533,"volume":304,"complete":true},{"time":"2018-01-22T19:30:00.000000Z","openMid":1.22531,"highMid":1.22582,"lowMid":1.22514,"closeMid":1.22579,"volume":352,"complete":true},{"time":"2018-01-22T19:45:00.000000Z","openMid":1.225765,"highMid":1.22614,"lowMid":1.22554,"closeMid":1.225845,"volume":411,"complete":true},{"time":"2018-01-22T20:00:00.000000Z","openMid":1.225855,"highMid":1.22588,"lowMid":1.22546,"closeMid":1.225845,"volume":331,"complete":true},{"time":"2018-01-22T20:15:00.000000Z","openMid":1.225875,"highMid":1.22603,"lowMid":1.22568,"closeMid":1.22592,"volume":281,"complete":true},{"time":"2018-01-22T20:30:00.000000Z","openMid":1.22594,"highMid":1.22602,"lowMid":1.225805,"closeMid":1.22587,"volume":138,"complete":true},{"time":"2018-01-22T20:45:00.000000Z","openMid":1.225865,"highMid":1.22606,"lowMid":1.225675,"closeMid":1.22588,"volume":252,"complete":true},{"time":"2018-01-22T21:00:00.000000Z","openMid":1.225875,"highMid":1.226,"lowMid":1.2257,"closeMid":1.22586,"volume":126,"complete":true},{"time":"2018-01-22T21:15:00.000000Z","openMid":1.22585,"highMid":1.22588,"lowMid":1.22574,"closeMid":1.225785,"volume":100,"complete":true},{"time":"2018-01-22T21:30:00.000000Z","openMid":1.225845,"highMid":1.22624,"lowMid":1.22578,"closeMid":1.226195,"volume":160,"complete":true},{"time":"2018-01-22T21:45:00.000000Z","openMid":1.22614,"highMid":1.226295,"lowMid":1.22608,"closeMid":1.226135,"volume":178,"complete":true},{"time":"2018-01-22T22:00:00.000000Z","openMid":1.226135,"highMid":1.22625,"lowMid":1.2258,"closeMid":1.226055,"volume":86,"complete":true},{"time":"2018-01-22T22:15:00.000000Z","openMid":1.225995,"highMid":1.22648,"lowMid":1.22597,"closeMid":1.22639,"volume":286,"complete":true},{"time":"2018-01-22T22:30:00.000000Z","openMid":1.22645,"highMid":1.22646,"lowMid":1.2261,"closeMid":1.226245,"volume":106,"complete":true},{"time":"2018-01-22T22:45:00.000000Z","openMid":1.22622,"highMid":1.22626,"lowMid":1.22571,"closeMid":1.226,"volume":489,"complete":true},{"time":"2018-01-22T23:00:00.000000Z","openMid":1.22598,"highMid":1.226,"lowMid":1.225645,"closeMid":1.225645,"volume":686,"complete":true},{"time":"2018-01-22T23:15:00.000000Z","openMid":1.22564,"highMid":1.2258,"lowMid":1.22556,"closeMid":1.225595,"volume":321,"complete":true},{"time":"2018-01-22T23:30:00.000000Z","openMid":1.22555,"highMid":1.22603,"lowMid":1.22555,"closeMid":1.225985,"volume":235,"complete":true},{"time":"2018-01-22T23:45:00.000000Z","openMid":1.225955,"highMid":1.225955,"lowMid":1.22557,"closeMid":1.225645,"volume":57,"complete":true},{"time":"2018-01-23T00:00:00.000000Z","openMid":1.225625,"highMid":1.22608,"lowMid":1.22556,"closeMid":1.22598,"volume":275,"complete":true},{"time":"2018-01-23T00:15:00.000000Z","openMid":1.22594,"highMid":1.22632,"lowMid":1.225705,"closeMid":1.22622,"volume":176,"complete":true},{"time":"2018-01-23T00:30:00.000000Z","openMid":1.22627,"highMid":1.22732,"lowMid":1.22614,"closeMid":1.227,"volume":580,"complete":true},{"time":"2018-01-23T00:45:00.000000Z","openMid":1.22696,"highMid":1.22696,"lowMid":1.22636,"closeMid":1.226735,"volume":282,"complete":true},{"time":"2018-01-23T01:00:00.000000Z","openMid":1.22672,"highMid":1.2268,"lowMid":1.22652,"closeMid":1.226555,"volume":229,"complete":true},{"time":"2018-01-23T01:15:00.000000Z","openMid":1.226585,"highMid":1.22723,"lowMid":1.22656,"closeMid":1.227195,"volume":344,"complete":true},{"time":"2018-01-23T01:30:00.000000Z","openMid":1.227245,"highMid":1.227555,"lowMid":1.22684,"closeMid":1.226865,"volume":536,"complete":true},{"time":"2018-01-23T01:45:00.000000Z","openMid":1.22689,"highMid":1.22699,"lowMid":1.22626,"closeMid":1.22664,"volume":406,"complete":true},{"time":"2018-01-23T02:00:00.000000Z","openMid":1.226615,"highMid":1.22674,"lowMid":1.22622,"closeMid":1.226375,"volume":343,"complete":true},{"time":"2018-01-23T02:15:00.000000Z","openMid":1.22635,"highMid":1.22635,"lowMid":1.22512,"closeMid":1.22544,"volume":532,"complete":true},{"time":"2018-01-23T02:30:00.000000Z","openMid":1.22547,"highMid":1.22577,"lowMid":1.22525,"closeMid":1.22544,"volume":194,"complete":true},{"time":"2018-01-23T02:45:00.000000Z","openMid":1.22547,"highMid":1.22617,"lowMid":1.22542,"closeMid":1.22615,"volume":181,"complete":true},{"time":"2018-01-23T03:00:00.000000Z","openMid":1.22611,"highMid":1.22615,"lowMid":1.22548,"closeMid":1.225995,"volume":234,"complete":true},{"time":"2018-01-23T03:15:00.000000Z","openMid":1.22602,"highMid":1.22759,"lowMid":1.22556,"closeMid":1.225705,"volume":2189,"complete":true},{"time":"2018-01-23T03:30:00.000000Z","openMid":1.2257,"highMid":1.22664,"lowMid":1.22552,"closeMid":1.226355,"volume":1158,"complete":true},{"time":"2018-01-23T03:45:00.000000Z","openMid":1.226385,"highMid":1.22638,"lowMid":1.22554,"closeMid":1.22578,"volume":543,"complete":true},{"time":"2018-01-23T04:00:00.000000Z","openMid":1.22573,"highMid":1.22586,"lowMid":1.22568,"closeMid":1.2258,"volume":50,"complete":false}]';

    public $rates = [1.12154,
            1.12151,
            1.12163,
            1.12186,
            1.12187,
            1.12184,
            1.12203,
            1.12223,
            1.12236,
            1.12213,
            1.12187,
            1.12200,
            1.12193,
            1.12200,
            1.12204,
            1.12218,
            1.12233,
            1.12226,
            1.12226,
            1.12200,
            1.12186,
            1.12165,
            1.12184,
            1.12161,
            1.12159,
            1.12131,
            1.12081,
            1.12082,
            1.12054,
            1.12102,
            1.12163,
            1.12138,
            1.12232,
            1.12443,
            1.12432,
            1.12440,
            1.12444,
            1.12418,
            1.12428,
            1.12292,
            1.12298,
            1.12355,
            1.12317,
            1.12262,
            1.12156,
            1.12113,
            1.12100,
            1.12025,
            1.12030,
            1.12074];

    /**
     * A basic functional test example.
     *
     * @return void
     */
//    public function testBasicExample()
//    {
//        $indicators = new CurrencyIndicators();
//
//        $array = [1.12154,
//            1.12151,
//            1.12163,
//            1.12186,
//            1.12187,
//            1.12184,
//            1.12203,
//            1.12223,
//            1.12236,
//            1.12213,
//            1.12187,
//            1.12200,
//            1.12193,
//            1.12200,
//            1.12204,
//            1.12218,
//            1.12233,
//            1.12226,
//            1.12226,
//            1.12200,
//            1.12186,
//            1.12165,
//            1.12184,
//            1.12161,
//            1.12159,
//            1.12131,
//            1.12081,
//            1.12082,
//            1.12054,
//            1.12102,
//            1.12163,
//            1.12138,
//            1.12232,
//            1.12443,
//            1.12432,
//            1.12440,
//            1.12444,
//            1.12418,
//            1.12428,
//            1.12292,
//            1.12298,
//            1.12355,
//            1.12317,
//            1.12262,
//            1.12156,
//            1.12113,
//            1.12100,
//            1.12025,
//            1.12030,
//            1.12074];
//
//        $ema = $indicators->calculateEMA($array, 10);
//
//        $this->assertEquals(67.093 ,end($ema));
//    }


    public function testMedian()
    {
        $indicators = new CurrencyIndicators();



        $median = $indicators->median($this->rates);

        $this->assertEquals($median, 1.1219);
    }

    public function testAverage()
    {
        $indicators = new CurrencyIndicators();

        $average = $indicators->average($this->rates);

        $this->assertEquals($average, 1.1220904);
    }

    public function testPositiveNegativeCheck()
    {
//        $indicators = new CurrencyIndicators();
//
//        $response = $indicators->positiveNegativeCheck(1);
//
//        $this->assertEquals($response, 'positive');
//
//        $response = $indicators->positiveNegativeCheck(-1);
//
//        $this->assertEquals($response, 'negative');
//
//        $response = $indicators->positiveNegativeCheck(0);
//
//        $this->assertEquals($response, 'none');

    }

    public function testCalculateEMA()
    {
        $indicators = new CurrencyIndicators();

        $response = $indicators->ema($this->rates, 10);

        $lastEmaRounded = round(end($response), 9);

        $this->assertEquals($lastEmaRounded, 1.121461849);
    }

    public function testRsi()
    {
        $indicators = new CurrencyIndicators();

        $response = $indicators->rsi($this->rates, 10);

        $this->assertEquals(round($response, 8), 24.31192661);
    }

    public function testLinearRegression()
    {
        $indicators = new CurrencyIndicators();

        $response = $indicators->linearRegression([10,
        12,
        16,
        15,
        18], 5);

        $this->assertEquals($response['m'], 1.9);
    }

    public function testLinearRegressionTwo()
    {
        $indicators = new CurrencyIndicators();

        $response = $indicators->linearRegression([10,
        12,
        16,
        15,
        18], 2);

        $this->assertEquals($response['m'], 3);
    }

    public function testArrayDiffChange()
    {
        $indicators = new CurrencyIndicators();

        $array = [-1,-2,-3,-4,-9];

        $arrayDiff = $indicators->arrayDiff($array);

        $this->assertEquals(end($arrayDiff), -5);
    }

//    public function testRateOfChange()
//    {
//        $indicators = new CurrencyIndicators();
//
//        $array = [-1,-2,-3,-4,-9];
//
//        $rateOfChange = $indicators->rateOfChange($array,3);
//
//        $this->assertEquals($rateOfChange, -2);
//    }

    public function testReverseDirection()
    {
        $indicators = new CurrencyIndicators();

        //Cross Up
        $array = [955, 985, 990, 1000,1023,1000];

        //Simple
        $response = $indicators->reverseDirection($array);

        $this->assertEquals($response, 'crossedDown');

        //With Min Fail
        $response = $indicators->reverseDirection($array, 50);

        $this->assertEquals($response, 'none');

        //With Min Fail
        $response = $indicators->reverseDirection($array, 10);

        $this->assertEquals($response, 'crossedDown');

        //Cross Up
        $array = [800, 775, 760, 750,720,750];

        //Simple
        $response = $indicators->reverseDirection($array);

        $this->assertEquals($response, 'crossedUp');

        //With Min Fail
        $response = $indicators->reverseDirection($array, 50);

        $this->assertEquals($response, 'none');

        //With Min Fail
        $response = $indicators->reverseDirection($array, 10);

        $this->assertEquals($response, 'crossedUp');
    }

    public function testStandardDeviation() {
        $indicators = new CurrencyIndicators();

        $standardDeviation = $indicators->standardDeviation($this->rates);

        $this->assertEquals(.0010750927060168, $standardDeviation);
    }

    public function testBollingerBands() {
        $indicators = new CurrencyIndicators();

        $bollingerBand = $indicators->bollingerBands($this->rates, 10, 1.5);

        $this->assertEquals(1.12173, $bollingerBand['average']);
        $this->assertEquals(1.123592646, $bollingerBand['high']);
        $this->assertEquals(1.119867354, $bollingerBand['low']);
    }

    public function testUpwardSlopeOrHardCutoff() {
        $indicators = new CurrencyIndicators();

        //$array, $hardCutoffPoint, $upwardSlopeCutoffPoint, $slopeMin

        $array = [12, 20,15,12];

        $failCompletelyTest = $indicators->upwardSlopeOrHardCutoff($array, 35, 20, 2);

        $this->assertFalse($failCompletelyTest);

        $array = [30, 28,27,25];

        $failOnSlope = $indicators->upwardSlopeOrHardCutoff($array, 35, 20, 2);

        $this->assertFalse($failOnSlope);

        $array = [30, 28,27,38];

        $hardPass = $indicators->upwardSlopeOrHardCutoff($array, 35, 20, 2);

        $this->assertTrue($hardPass);

        $array = [12, 14,18,22];

        $passOnSlope = $indicators->upwardSlopeOrHardCutoff($array, 35, 20, 2);

        $this->assertTrue($passOnSlope);

    }

    public function testDifferenceSlope() {
        $array1 = [10,8,6,4];
        $array2 = [0, 2, 4, 6];
        $numberOfPeriods = 4;

        $indicators = new CurrencyIndicators();

        $slope = $indicators->differenceSlope($array1, $array2, $numberOfPeriods);

        $this->assertEquals(4, $slope);
    }

    public function testSma() {
        $historicalRates = new App\Model\HistoricalRates();
        $rates = $historicalRates->getRatesSpecificTimeSimple(1,1,100,'2017-03-28 13:30:00');

        $indicators = new CurrencyIndicators();

        $sma20 = $indicators->sma($rates, 20);
        $sma40 = $indicators->sma($rates, 40);
        $sma80 = $indicators->sma($rates, 80);

        $this->assertEquals(end($sma20), 1.0858465);
        $this->assertEquals(end($sma40), 1.08591025);
        $this->assertEquals(end($sma80), 1.086154125);
    }

    public function testPivotPoints() {
        $rate = new \StdClass();

        $rate->closeMid = 1.61592;
        $rate->highMid = 1.61592;
        $rate->lowMid = 1.61578;

        $indicators = new CurrencyIndicators();

        $pivotPoints = $indicators->pivotPoints($rate);

        $this->assertEquals(round($pivotPoints['pivot'], 9), 1.615873333);
        $this->assertEquals(round($pivotPoints['r1'], 9), 1.615966667);
        $this->assertEquals(round($pivotPoints['r2'], 9), 1.616013333);
        $this->assertEquals(round($pivotPoints['s1'], 9), 1.615826667);
        $this->assertEquals(round($pivotPoints['s2'], 9), 1.615733333);
    }

    public function testMacd() {
        $indicators = new CurrencyIndicators();

        $macd = $indicators->macd($this->rates, 12, 26, 9);

        $this->assertEquals($macd['macd'][count($macd['macd']) -2], -0.0003605162601);
        $this->assertEquals(end($macd['macd']), -0.0004167855673);

        $this->assertEquals($macd['signal'][count($macd['signal']) -2], 0.00003449076743);
        $this->assertEquals(end($macd['signal']), -0.00005576449952);

        $this->assertEquals($macd['histogram'][count($macd['histogram']) -2], -0.0003950070276);
        $this->assertEquals(end($macd['histogram']), -0.0003610210678);
    }

    public function testStochastics() {

        $historicalRates = new App\Model\HistoricalRates();
        $rates = $historicalRates->getRatesSpecificTimeFull(1,3,100,'2018-03-07 03:00:00');

        $indicators = new CurrencyIndicators();

        $stochastics = $indicators->stochastics($rates, 14, 3, 3);

        $this->assertEquals(round(end($stochastics['fast']['k']), 8), 77.59433962);
        $this->assertEquals(round (end($stochastics['fast']['d']), 8), 71.14779874);

        $this->assertEquals(round($stochastics['fast']['k'][count($stochastics['fast']['k'])-2], 8), 71.46226415);
        $this->assertEquals(round ($stochastics['fast']['d'][count($stochastics['fast']['d'])-2], 8), 74.2057355);

        $this->assertEquals(round($stochastics['fast']['k'][count($stochastics['fast']['k'])-3], 8), 64.38679245);
        $this->assertEquals(round ($stochastics['fast']['d'][count($stochastics['fast']['d'])-3], 8), 79.06715132);
    }
}
