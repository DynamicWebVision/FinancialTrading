<?php namespace App\Broker;
use \Log;
use App\Model\TdAmeritradeAccount;
use App\Model\ApiErrorLog;


class IexTrading extends \App\Broker\Base  {

    public $baseUrl = 'https://api.iextrading.com/1.0/';
    public $processLogger;

    public function __construct()
    {
        $this->curl = curl_init();
        //curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.env('TD_AMERITRADE_REFRESH_TOKEN') ));
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
    }

    public function getCompanyProfile($symbol) {
        $this->apiUrl = $this->baseUrl.'stock/'.trim($symbol).'/company';
        $response = $this->apiGetRequest();

        if (!isset($response->companyName)) {
            $this->createApiErrorLogRecord('IexTrading', 'getCompanyProfile', $symbol);
            return false;
        }
        else {
            return $response;
        }
    }

    public function getCompanyFinancials($symbol, $period) {
        $this->apiUrl = $this->baseUrl.'stock/'.trim($symbol).'/financials?period='.$period;
        $response = $this->apiGetRequest();

        if (!isset($response->financials)) {
            $this->createApiErrorLogRecord('IexTrading', 'getCompanyFinancials', $symbol);
            return false;
        }
        else {
            return $response->financials;
        }
    }

    public function getFiveYearRates($symbol) {
        $this->apiUrl = $this->baseUrl.'stock/'.trim($symbol).'/chart/5y';
        $response = $this->apiGetRequest();

        if (!is_array($response)) {
            $this->createApiErrorLogRecord('IexTrading', 'getFiveYearRates', $symbol);
            return false;
        }
        else {
            return $response;
        }
    }

    public function getBook($symbol) {
        $this->apiUrl = $this->baseUrl.'stock/'.trim($symbol).'/book';
        $response = $this->apiGetRequest();

        if (!is_object($response)) {
            $this->createApiErrorLogRecord('IexTrading', 'getBook', $symbol);
            return false;
        }
        else {
            return $response->quote;
        }
    }

    public function getChart($symbol, $charPeriod) {
        $this->apiUrl = $this->baseUrl.'stock/'.trim($symbol).'/chart/'.$charPeriod;
        $response = $this->apiGetRequest();
        return $response;
    }
}
