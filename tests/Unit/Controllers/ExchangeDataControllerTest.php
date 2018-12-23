<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\CurrencyIndicators;
use App\Model\HistoricalRates;
use App\Model\TmpTestRates;
use App\Http\Controllers\Equity\ExchangeDataController;
use App\Broker\OandaV20;

class ExchangeDataControllerTest extends TestCase
{

    public $transactionController;
    public $oanda;

    public function testRefreshDumpTable() {
        $exchangeDumpTest = new ExchangeDataController();

        $exchangeDumpTest->refreshDumpTable();
    }

    public function testNasdaqDump() {
        $exchangeDumpTest = new ExchangeDataController();

        $exchangeDumpTest->nasdaqDump();
    }

    public function testNyseDump() {
        $exchangeDumpTest = new ExchangeDataController();

        $exchangeDumpTest->nyseDump();
    }
}
