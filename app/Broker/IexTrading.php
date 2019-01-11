<?php namespace App\Broker;
use \Log;
use App\Model\TdAmeritradeAccount;
use App\Model\ApiErrorLog;


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

        if (!isset($response->companyName)) {
            $this->createApiErrorLogRecord('IexTrading', 'getCompanyProfile');
            return false;
        }
        else {
            return $response;
        }
    }

    public function getCompanyFinancials($symbol, $period) {
        $this->apiUrl = $this->baseUrl.'stock/'.$symbol.'/financials?period='.$period;
        $response = $this->apiGetRequest();

        if (!isset($response->financials)) {
            $this->createApiErrorLogRecord('IexTrading', 'getCompanyFinancials');
            return false;
        }
        else {
            return $response->financials;
        }
    }

}
