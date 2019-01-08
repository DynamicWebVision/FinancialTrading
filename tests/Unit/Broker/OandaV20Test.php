<?php

namespace Tests\Unit\Broker;

use Tests\TestCase;

use \App\Model\Exchange;
use \App\Broker\OandaV20;
use \App\Services\StrategyLogger;

class OandaV20Test extends TestCase
{

    public function testCheckOpenPosition() {
        $oandaV20 = new OandaV20('practice');
        $strategyLogger = new StrategyLogger();

        $oandaV20->accountId = '101-001-7608904-007';
        $oandaV20->exchange = 'GBP_USD';
        $oandaV20->strategyLogger = $strategyLogger;

        $test = $oandaV20->checkOpenPosition();

        $debug=1;
    }

}