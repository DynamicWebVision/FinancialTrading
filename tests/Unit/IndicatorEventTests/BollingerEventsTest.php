<?php

namespace Tests\Unit\IndicatorEventTests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\CurrencyIndicators;
use App\IndicatorEvents\Bollinger;
use App\Model\HistoricalRates;
use App\Model\TmpTestRates;

class BollingerEventsTest extends TestCase
{

    public function testGetOuterBandCrossEvent() {
        $historicalRates = new \App\Model\HistoricalRates();
        $bollinger = new Bollinger();


        //CROSSED ABOVE EVENT
        $rates = $historicalRates->getRatesSpecificTimeSimple(1,3,1000,'2018-05-31 7:00:00');

        $bollingerCross = $bollinger->getOuterBandCrossEvent($rates, 20, 2);

        $this->assertEquals($bollingerCross, 'crossedAbove');


        //CROSSED BELOW EVENT
        $rates = $historicalRates->getRatesSpecificTimeSimple(1,3,1000,'2018-05-29 6:00:00');

        $bollingerCross = $bollinger->getOuterBandCrossEvent($rates, 20, 2);

        $this->assertEquals($bollingerCross, 'crossedBelow');


        //NON EVENT
        $rates = $historicalRates->getRatesSpecificTimeSimple(1,3,1000,'2018-05-28 22:00:00');

        $bollingerCross = $bollinger->getOuterBandCrossEvent($rates, 20, 2);

        $this->assertEquals($bollingerCross, 'none');
    }

    public function testCloseAcrossCenterLine() {
        $historicalRates = new \App\Model\HistoricalRates();
        $bollinger = new Bollinger();

        //CROSSED BELOW EVENT
        $rates = $historicalRates->getRatesSpecificTimeSimple(1,3,1000,'2018-05-28 8:00:00');

        $bollingerCross = $bollinger->closeAcrossCenterLine($rates, 20, 2, 'long');

        $this->assertEquals($bollingerCross, 'close');

        //CROSSED ABOVE EVENT
        $rates = $historicalRates->getRatesSpecificTimeSimple(1,3,1000,'2018-05-30 5:00:00');

        $bollingerCross = $bollinger->closeAcrossCenterLine($rates, 20, 2, 'short');

        $this->assertEquals($bollingerCross, 'close');

        //Stay In
        $rates = $historicalRates->getRatesSpecificTimeSimple(1,3,1000,'2018-05-30 7:00:00');

        $bollingerCross = $bollinger->closeAcrossCenterLine($rates, 20, 2, 'long');

        $this->assertEquals($bollingerCross, 'none');

        //Stay In
        $rates = $historicalRates->getRatesSpecificTimeSimple(1,3,1000,'2018-05-29 12:00:00');

        $bollingerCross = $bollinger->closeAcrossCenterLine($rates, 20, 2, 'short');

        $this->assertEquals($bollingerCross, 'none');
    }

    public function testBollingerPriceMaxOutClosesInside() {
        $historicalRates = new \App\Model\HistoricalRates();
        $bollinger = new Bollinger();

        //CROSSED BELOW EVENT
        $rates = $historicalRates->getRatesSpecificTimeBoth(1,1,1000,'2018-07-12 15:45:00');

        $bollingerCross = $bollinger->bollingerPriceMaxOutClosesInside($rates['simple'], 20, 2, $rates['full']);

        $this->assertEquals($bollingerCross, 'short');

        //CROSSED BELOW EVENT
        $rates = $historicalRates->getRatesSpecificTimeBoth(1,1,1000,'2018-07-13 8:15:00');

        $bollingerCross = $bollinger->bollingerPriceMaxOutClosesInside($rates['simple'], 20, 2, $rates['full']);

        $this->assertEquals($bollingerCross, 'long');
    }
}
