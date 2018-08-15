<?php

namespace Tests\Unit\Services;

use Tests\TestCase;

use \App\Model\Exchange;
use \App\Services\BackTest;
use \App\Services\Utility;

class UtilityTest extends TestCase
{

    public function testGetLastXElementsInArray()
    {
        $utility = new Utility();

        $array = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];

        $last2 = $utility->getLastXElementsInArray($array, 2);

        $this->assertEquals($last2, [9, 10]);

        $last3 = $utility->getLastXElementsInArray($array, 3);

        $this->assertEquals($last3, [8, 9, 10]);
    }


    public function testGetXFromLastValue() {
        $utility = new Utility();

        $array = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];

        $secondFromLast = $utility->getXFromLastValue($array, 1);

        $this->assertEquals($secondFromLast, 9);

        $thirdFromLast = $utility->getXFromLastValue($array, 2);

        $this->assertEquals($thirdFromLast, 8);
    }

    public function testHasNegative() {
        $utility = new Utility();

        $array = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];

        $shouldBeFalse = $utility->hasNegativeCheck($array);

        $this->assertFalse($shouldBeFalse);

        $array = [1, -2, 3, 4, 5, 6, 7, 8, 9, 10];

        $shouldBeTrue = $utility->hasNegativeCheck($array);

        $this->assertTrue($shouldBeTrue);
    }

    public function testHasPositive() {
        $utility = new Utility();

        $array = [-1, -2, -3, -5];

        $shouldBeFalse = $utility->hasPositiveCheck($array);

        $this->assertFalse($shouldBeFalse);

        $array = [1, -2, -3, -4, -5];

        $shouldBeTrue = $utility->hasPositiveCheck($array);

        $this->assertTrue($shouldBeTrue);
    }

    public function testGetMultipleArraySets() {
        $historicalRates = new \App\Model\HistoricalRates();
        $rates = $historicalRates->getRatesSpecificTimeSimple(1,1,100,'2018-05-22 19:15:00');

        $utility = new Utility();

        $arraySets = $utility->getMultipleArraySets($rates, 10, 2);

        $this->assertEquals(end($arraySets[0]), 1.17808);
        $this->assertEquals(end($arraySets[1]), 1.17846);
    }

    public function testSleepUntilAtLeastFiveSeconds() {
        $utility = new Utility();
        $utility->sleepUntilAtLeastFiveSeconds();

        $currentSeconds = (int) date('s');

        $this->assertGreaterThanOrEqual(5,$currentSeconds);

    }

}
