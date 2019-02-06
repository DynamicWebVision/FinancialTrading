<?php namespace App\EquityBacktest;
use \Log;
use App\Model\Stocks\StocksBackTestPositions;
use App\Model\Stocks\Stocks;
use App\Services\Utility;


class EquityBackTestBroker {
    public $currentAccountTotalValue = 50000;
    public $openPositions = [];
    public $currentAccountAvailableCapital = 50000;
    public $positionId = 0;

    public $backTestIterationId;

    public $utility;

    public function __construct() {

    }

    public function newLongPosition($rate, $symbol, $shareCount, $stopLossRate) {
        $this->newPosition($rate, $symbol, $shareCount, 1, $stopLossRate);
    }

    public function newShortPosition($rate, $symbol, $shareCount, $stopLossRate) {
        $this->newPosition($rate, $symbol, $shareCount, -1, $stopLossRate);
    }

    public function newPosition($rate, $symbol, $shareCount, $side, $stopLossRate) {
        $this->positionId = $this->positionId + 1;

        $stock = Stocks::where('symbol', '=', $symbol)->first();

        $newBackTestPosition = new StocksBackTestPositions();

        $newBackTestPosition->stocks_back_test_iteration_id = $this->backTestIterationId;

        $newBackTestPosition->stock_id = $stock->id;
        $newBackTestPosition->shares_count = $shareCount;
        $newBackTestPosition->open_date = $rate->dateTime;
        $newBackTestPosition->open_price = $rate->closeMid;

        $newBackTestPosition->stop_loss_rate = $stopLossRate;

        $newBackTestPosition->position_type = $side;

        $newBackTestPosition->save();
    }

    public function closePosition($id) {

    }

    public function setAccountInfo() {
        $openPositions = StocksBackTestPositions::where('stocks_back_test_iteration_id', '=', $this->backTestIterationId)
                            ->where('close_price', '=', 0)
                            ->get()
                            ->toArray();

        $this->openPositions = $openPositions;
    }

    public function endPeriod() {

    }
}
