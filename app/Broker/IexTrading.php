<?php namespace App\Broker;
use \Log;
use App\Model\TdAmeritradeAccount;


class IexTrading extends \App\Broker\Base  {

    public $baseUrl = 'https://api.iextrading.com/1.0/';

    public function __construct()
    {
        $this->curl = curl_init();
        //curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.env('TD_AMERITRADE_REFRESH_TOKEN') ));
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
    }

    public function getCompanyProfile($symbol) {
        $this->apiUrl = $this->baseUrl.'stock/'.$symbol.'/company';
        $response = $this->apiGetRequest();
        return $response;
    }

    public function getCompanyFinancials($symbol, $period) {

        $this->apiUrl = $this->baseUrl.'stock/'.$symbol.'/company';
        $response = $this->apiGetRequest();
        return $response;
    }

}
