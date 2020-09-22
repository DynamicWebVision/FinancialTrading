<?php namespace App\Broker;
use \Log;
use App\Model\TdAmeritradeAccount;


class Tiingo extends \App\Broker\Base  {

    public $logger;
    protected $baseUrl;
    protected $curl;

    public $symbol;

    public function __construct($logger) {

//        curl_setopt( $this->curl, CURLOPT_FOLLOWLOCATION, 1);
//        curl_setopt( $this->curl, CURLOPT_VERBOSE, 1);
        //curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);

        $this->baseUrl = 'https://api.tiingo.com';
        $this->logger = $logger;

        $this->initiateCurl();
    }

    public function initiateCurl() {
        $this->curl = curl_init();
        //curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.env('TD_AMERITRADE_REFRESH_TOKEN') ));
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Authorization: Token 716643840829a16ac5077bdbd9ce72d689dd19db'));
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);

    }

    public function testApi() {
        //tiingo/fundamentals/<ticker>/daily
        $this->apiUrl = $this->baseUrl."/tiingo/daily/XOM";
        $this->apiUrl = $this->baseUrl."/tiingo/fundamentals/XOM/statements";

        //$parameters = json_decode('{"frequencyType":"daily","frequency":1,"periodType":"year","startDate":1609459200000,"endDate":1639094400000}');
           //$this->apiUrl = 'https://api.tdameritrade.com/v1/marketdata/TPNL/pricehistory?frequencyType=daily&frequency=1&periodType=year&startDate=1609459200000&endDate=1639094400000';

        $response = $this->apiGetRequest(false);

        return $response;
    }

    public function historicalPrices($dates) {
        //tiingo/fundamentals/<ticker>/daily
       // $this->apiUrl = $this->baseUrl."/tiingo/daily/XOM";
        $this->apiUrl = $this->baseUrl."/tiingo/daily/".$this->symbol."/prices?startDate=".$dates['start_date']."&endDate=".$dates['end_date'];
//        $this->apiUrl = $this->baseUrl."/tiingo/fundamentals/XOM/statements";


        //$parameters = json_decode('{"frequencyType":"daily","frequency":1,"periodType":"year","startDate":1609459200000,"endDate":1639094400000}');
           //$this->apiUrl = 'https://api.tdameritrade.com/v1/marketdata/TPNL/pricehistory?frequencyType=daily&frequency=1&periodType=year&startDate=1609459200000&endDate=1639094400000';

        $response = $this->apiGetRequest(false);

        return $response;
    }
}
