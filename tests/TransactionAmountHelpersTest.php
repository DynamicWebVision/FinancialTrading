<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Services\TransactionAmountHelpers;

class TransactionAmountHelpersTest extends TestCase
{

    public function testKellyCriterion()
    {
        $transactionHelpers = new TransactionAmountHelpers();

        $result = $transactionHelpers->kellyCriterion(1, 1, .52);

        $this->assertEquals($result, .04);
    }

    public function testCalculatePositionAmount()
    {
        $transactionHelpers = new TransactionAmountHelpers();

        $result = $transactionHelpers->calculatePositionAmount(1.21, .0001, 10, 450);

        $this->assertEquals($result, 375000);
    }
}