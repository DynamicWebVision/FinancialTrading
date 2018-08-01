<?php
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\StrategyEvents\EmaEvents;
use App\Model\HistoricalRates;
use App\Strategy\Hlhb\HlhbOnePeriodCrossover;
use App\Http\Controllers\LivePracticeController;

class LivePracticeTest extends TestCase
{
    public $livePracticeController;

    public function __construct()
    {
        $this->livePracticeController = new LivePracticeController();
    }

    public function testEmaXAdxConfirmWithMarketIfTouched() {
        $this->livePracticeController->emaXAdxConfirmWithMarketIfTouched();
    }
}