<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\CurrencyIndicators;
use App\Model\HistoricalRates;
use App\Model\TmpTestRates;
use App\Http\Controllers\AccountsController;
use App\Broker\OandaV20;

class AccountsControllerTest extends TestCase
{
    public $transactionController;
    public $oanda;

    public function testCreateLiveAccounts() {
        $accountsController = new AccountsController();

        $accountsController->createNewLiveAccounts();
    }

    public function testCreateTestAccounts() {
        $accountsController = new AccountsController();

        $accountsController->createNewPracticeAccounts();
    }
}
