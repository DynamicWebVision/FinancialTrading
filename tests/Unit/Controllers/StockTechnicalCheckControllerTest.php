<?php

namespace Tests\Unit\Controllers;

use App\Model\ProcessLog\ProcessLog;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\CurrencyIndicators;
use App\Model\HistoricalRates;
use App\Model\TmpTestRates;
use App\Http\Controllers\Equity\StockTechnicalCheckController;
use App\Http\Controllers\ServersController;
use App\Services\ProcessLogger;
use Illuminate\Support\Facades\Config;
use App\Broker\OandaV20;

class StockTechnicalCheckControllerTest extends TestCase
{
    public function testKeepRunning() {
        $stockTechnicalCheckController = new StockTechnicalCheckController();
        $stockTechnicalCheckController->logger = new ProcessLogger('stc_hma_rev');
        $stockTechnicalCheckController->hmaReversalCheck(33);
    }
}