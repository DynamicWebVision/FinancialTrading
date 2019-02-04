<?php

namespace Tests\Unit\OandaTests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\CurrencyIndicators;
use App\Model\HistoricalRates;
use App\Model\TmpTestRates;
use App\Http\Controllers\TransactionController;
use App\Broker\OandaV20;
use App\Services\TransactionAmountHelpers;


class TransactionAmountHelpersTest extends TestCase
{

    public $transactionController;
    public $oanda;

    public function testKellyCriterion() {
        $t = new TransactionAmountHelpers();

        $test = $t->kellyCriterion(10, -8, .5);
    }

    public function testExpectedGainFromOneTransactionTenK() {
        $t = new TransactionAmountHelpers();

        $test = $t->expectedGainFromOneTransactionTenK(.1, 17, 10, .51);
    }
}
