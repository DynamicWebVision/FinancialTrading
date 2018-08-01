<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Model\Exchange;

use App\Services\BackTest;

class BackTestTest extends TestCase
{

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
        $indicators = new CurrencyIndicators();

        $response = $indicators->positiveNegativeCheck(1);

        $this->assertEquals($response, 'positive');

        $response = $indicators->positiveNegativeCheck(-1);

        $this->assertEquals($response, 'negative');

        $response = $indicators->positiveNegativeCheck(0);

        $this->assertEquals($response, 'none');

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

        $response = $indicators->linearRegression([88.23,
            75.2,
            78,
            65,
            60,
            49]);

        $this->assertEquals(round($response['m'], 3), -7.279);
    }


}
