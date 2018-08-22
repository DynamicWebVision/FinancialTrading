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

class OrderManagementTest extends TestCase
{

    public $transactionController;
    public $oanda;

    public function __construct() {
        $this->transactionController = new TransactionController();
        $this->oanda = new OandaV20();
    }

    public function testGetOandaTransactions() {
        $this->transactionController->getOandaTransactions(7);
    }

    public function testPlayWithJson() {
        $this->transactionController->playWithOandaJsonData();
    }

    public function testOandaGetSpecificTransaction() {
        $this->oanda->accountId = '101-001-7608904-008';
        $this->oanda->getSpecificTransaction(2932);
    }

    public function testCOAbc() {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer '.env('OANDA_AUTHORIZATION_TOKEN'),'Content-Type: application/json', 'Accept-Datetime-Format: UNIX']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_URL, 'https://www.flexcarestaff.com/jobs/search?specialty%5B%5D=Emergency+Room&state%5B%5D=CO&query=&shift=All');
        $resp = curl_exec($ch);
    }
}
