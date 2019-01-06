<?php

namespace Tests\Unit\Services;

use Tests\TestCase;

use \App\Model\Exchange;
use \App\Services\BackTest;
use \App\Services\TextMessage;

class TextMessageTest extends TestCase
{

    public function testSendTextMessage() {
        $textMessage = new TextMessage();
        $textMessage->sendTextMessage('Test Brian Loves Emma');
    }

}
