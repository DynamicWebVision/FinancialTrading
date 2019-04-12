<?php namespace App\ForexBackTest;

use \DB;
use \App\Model\HistoricalRates;
use \App\Model\DebugLog;
use \App\ForexBackTest\BackTest;
use \App\Services\Utility;
use \Log;

class BackTestHelpers  {
    public $currentPriceData;
    public $exchange;

    public function __construct() {
        $this->utility = new Utility();
    }

    public function closeBackTestPosition($profitLoss, $index, $closeReason) {
        $this->strategy->backTestPositions[$index]['profitLoss'] = $profitLoss;
        $this->strategy->backTestPositions[$index]['closeDateTime'] = $this->currentPriceData->dateTime;
        $this->strategy->backTestPositions[$index]['closePrice'] = $this->currentPriceData->open;
        $this->strategy->backTestPositions[$index]['closeReason'] = $closeReason;
    }

    public function getClosedPositionProfitLoss($currentOpenPosition) {
        $currentPrice = $this->currentPriceData->open;
        if ($currentOpenPosition['positionType'] == 'short') {
            return $currentOpenPosition['amount'] - $currentPrice;
        }
        else {
            return $currentPrice - $currentOpenPosition['amount'];
        }
    }

    public function closeOpenPosition($possibleOpenPosition, $priceData) {

        if (!isset($possibleOpenPosition['closeDateTime'])) {
            $this->currentPriceData = $priceData;

            $profitLoss = $this->getClosedPositionProfitLoss($possibleOpenPosition);

            $possibleOpenPosition['profitLoss'] = $profitLoss;
            $possibleOpenPosition['closeDateTime'] = $this->currentPriceData->dateTime;
            $possibleOpenPosition['closePrice'] = $this->currentPriceData->open;
            $possibleOpenPosition['closeReason'] = 'Strategy Rules';

            return $possibleOpenPosition;
        }
        else {
            return $possibleOpenPosition;
        }
    }

    public function checkOpenPosition($possibleOpenPosition, $currentPriceData, $backTestTrailingStop) {
        if (!isset($possibleOpenPosition['closeDateTime'])) {
            if ($possibleOpenPosition['positionType'] == 'long') {
                $pl = ($currentPriceData->open - $possibleOpenPosition['amount'])/$this->exchange->pip;
            }
            elseif ($possibleOpenPosition['positionType'] == 'short') {
                $pl = ($possibleOpenPosition['amount'] - $currentPriceData->open)/$this->exchange->pip;
            }

            return [
              'side' => $possibleOpenPosition['positionType'],
              'openPrice' => $possibleOpenPosition['amount'],
              'gl' => $pl,
              'trailingStop' => $backTestTrailingStop,
              'openUnixTime'=>strtotime($possibleOpenPosition['dateTime'])
            ];
        }
        else {
            return false;
        }
    }
}