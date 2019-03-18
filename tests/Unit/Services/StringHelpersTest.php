<?php

namespace Tests\Unit\Services;

use Tests\TestCase;

use \App\Model\Exchange;
use \App\Services\BackTest;
use \App\Services\StringHelpers;


class StringHelpersTest extends TestCase
{

    public function testInstring() {
        $textMessage = new StringHelpers();
        $test = $textMessage->stringContainsString('pid check output :  PID TTY          TIME CMD
 4011 pts/1    00:00:00 php
', '4011');

        $this->assertEquals(true, $test);
    }

}
