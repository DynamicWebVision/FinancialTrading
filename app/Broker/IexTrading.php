<?php namespace App\Broker;
use \Log;
use App\Model\TdAmeritradeAccount;
use App\Model\ApiErrorLog;


class IexTrading extends \App\Broker\Base  {

    public $baseUrl = 'https://cloud.iexapis.com/';
    public $processLogger;

    public function __construct()
    {
        $this->curl = curl_init();
        //curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.env('TD_AMERITRADE_REFRESH_TOKEN') ));
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);

        $this->getVariables[] = ['token'=>env('IEX_API_TOKEN')];
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

    public function getBothRates($symbol) {
        $rates = $this->getFiveYearRates($symbol);

        $bothRates = [];

        $bothRates['full'] = array_map(function($rate) {
            $stdRate = new \StdClass();

            if (!isset($rate->high)) {
                $rate->high = $rate->close;
            }

            if (!isset($rate->low)) {
                $rate->low = $rate->close;
            }

            if (!isset($rate->open)) {
                $rate->open = $rate->close;
            }

            $stdRate->highMid = (float) $rate->high;
            $stdRate->closeMid = (float) $rate->close;
            $stdRate->lowMid = (float) $rate->low;
            $stdRate->openMid = (float) $rate->open;
            $stdRate->dateTime = $rate->date;
            $stdRate->dateUnixTime = strtotime($rate->date);
            $stdRate->volume = (float) $rate->volume;
            return $stdRate;
        }, $rates);

        $bothRates['simple'] = array_map(function($rate) {
            return $rate->close;
        }, $rates);
        return $bothRates;
    }
}
