<?php namespace App\Broker;
use \Log;

class TDAmeritrade extends \App\Broker\Base  {
    public $frequency;
    public $exchange;

    public $takeProfit;
    public $takeProfitPipAmount;

    public $stopLoss;
    public $stopLossPipAmount;

    public $trailingStop;

    public $startDate;

    public $runId;
    public $accountId;

    public $positionAmount;

    public $rateCount = 5000;

    public $addCurrentPriceToRates;

    public $strategyLogger;

    public function __construct() {
        $this->apiUrl = 'https://api.tdameritrade.com/v1/';
        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
    }

    public function getAuthorizationToken() {
        ['code: '.env('TD_AMERITRADE_CODE'),'Content-Type: application/json', 'access_type: offline', ];
    }
}
