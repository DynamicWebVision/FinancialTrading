<?php namespace App\Broker;
use \Log;
use App\Model\TdAmeritradeAccount;


class TDAmeritrade extends \App\Broker\Base  {
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

    public $accessToken;
    public $logger;

    public $firstAuthorization = true;

    public function __construct($logger) {

//        curl_setopt( $this->curl, CURLOPT_FOLLOWLOCATION, 1);
//        curl_setopt( $this->curl, CURLOPT_VERBOSE, 1);
        //curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);

        $this->tdAmeritradeBaseUrl = 'https://api.tdameritrade.com/v1/';
        $this->logger = $logger;

        $this->initiateCurl();
        $this->validateAccessToken();
    }

    public function initiateCurl() {
        $this->curl = curl_init();
        //curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.env('TD_AMERITRADE_REFRESH_TOKEN') ));
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
    }

//    public function getAuthorizationToken() {
//        $this->apiUrl = 'https://api.tdameritrade.com/v1/oauth2/token';
//
//        $data = [];
//        $data['code'] = 'NWbR79Q6dWAkENS7ReXBP173jqzvpb1kuwJaPATvV7he5zWFZ%2FDRulaQQFznDlRH3pxRKVT%2BGJf5IWFvp1wrfU4DBv8QSDFT0%2FZEe7UdlQocpazHYnqL4SI0smwe0eMH3rrYpNnPnkI%2FkwEF%2F1TM3FLEX6JpX52fCOUpT0kzEv7mFN9K2GIlvf%2FKsNt%2BEQE1ca8EVZd0Q%2BP%2BuZoT%2BGnEjr04g0LBAuYrP%2BzcH6P%2BcJK0fb99wnoX32GOgxueDYV0HoRQ0NnwBpJDAREiFxQOB1cm4knILK6UXaQRw1GcY1rCsGT3yRvg6xvWtPhYCqled1PQtujjdAqB%2Fjf8NCrEhazQQBM6UcIMm6h6iB23YopfZECmvpq6v75w8Q19pqohNgGvF%2F3azZQHF%2BOBs8ukm6OqFeVIaeS4%2B7HKiNSaQPxouEfFG285vu%2FSZaq100MQuG4LYrgoVi%2FJHHvlRnWxepdVBiXkqKKBs%2FL0bWnvxDca%2FaVtKPKJJ5Ygtsw%2FzsPSmDyBPqfuQtMzztn8bpJ%2Bht%2BuIAwIAKLxOHEXngURIoah32P2YBhWhoLC2Vt1sIOX97On4G%2BiHCtRjooehj9MJ%2FCRYu3Cff1UoKKkupSzVYEx8zDTf5JlG8w%2BH7qmZqdBoh3s%2FCeO9ffvt%2FCy7p3Mt5K8%2BM7Tt1by7q41oJUIRfO6bQQ73aI3slqNNsS5mpnFtGerUZbzga5S7WjHss6M0PD7CSlDY8GLuNK4sdOTJwDaiDMl9AhuN97PXtI330NzE8pQMxltmMvzMkUZjM1Bq9GQFMh2EZp538TQpVhQbHDdueecUbkIgQrIfLlbi%2FrxTIOt8kQpf2LVNBvTgzr9t4omHTvL%2BFB20DFi2vc%2F1jo37flL0hr5Vzv9H%2FmlyBlXNfktJueXqEg%3D212FD3x19z9sWBHDJACbC00B75E';
//        $data['client_id'] = 'BRIANO8520@AMER.OAUTHAP';
//        $data['grant_type'] = 'authorization_code';
//        $data['access_type'] = 'offline';
//        $data['redirect_url'] = 'http://localhost:8046/td_ameritrade_auth_code';
//
//        $this->apiPostRequest($data);
//    }

    public function getStockPriceHistory($symbol, $parameters) {
        $this->validateAccessToken();
        $this->apiUrl = $this->tdAmeritradeBaseUrl."marketdata/".$symbol."/pricehistory";

        //$parameters = json_decode('{"frequencyType":"daily","frequency":1,"periodType":"year","startDate":1609459200000,"endDate":1639094400000}');
        $this->getVariables = $parameters;

        //$this->apiUrl = 'https://api.tdameritrade.com/v1/marketdata/TPNL/pricehistory?frequencyType=daily&frequency=1&periodType=year&startDate=1609459200000&endDate=1639094400000';

        $this->logger->logMessage('TD_AMER API URL: '.$this->apiUrl);
        $this->logger->logMessage('TD_AMER API GET PARAM: '.json_encode($this->getVariables));

        $response = $this->apiGetRequest();

        $this->logger->logMessage('TD_AMER API Response: '.json_encode($response));

        return $response;
    }

    public function getStockFundamentalData($symbol) {
        $this->validateAccessToken();
        $this->apiUrl = $this->tdAmeritradeBaseUrl."instruments";

        $parameters = [
          'symbol'=>$symbol,
           'projection'=>'fundamental'
        ];
        $this->getVariables = $parameters;

        $response = $this->apiGetRequest();
        return $response;
    }

    public function validateAccessToken() {
        $tdAmeritradeAccount = TdAmeritradeAccount::find(1);

        if ($tdAmeritradeAccount->access_token_expiration < time()) {
            if (!$this->firstAuthorization) {
                sleep(60);
                $this->initiateCurl();
            }
            $this->refreshAuthorizationToken();
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$this->accessToken ));
        }
        else {
            $this->accessToken = $tdAmeritradeAccount->access_token;
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$this->accessToken ));
        }
        $this->firstAuthorization = false;
    }

    public function refreshAuthorizationToken() {
        $this->apiUrl = $this->tdAmeritradeBaseUrl."oauth2/token";

        $tdAmeritradeAccount = TdAmeritradeAccount::find(1);

        $data = [
            'grant_type'=>'refresh_token',
            'client_id'=> $tdAmeritradeAccount->client_id,
            'refresh_token'=> $tdAmeritradeAccount->refresh_token
        ];

        $response = $this->apiPostRequestHttpFields($data);

        $this->logger->logMessage('TD_AMER API URL: '.$this->apiUrl);
        $this->logger->logJson('TD_AMER API Data', $data);
        $this->logger->logJsonApiResponse($response);

        $tdAmeritradeAccount->access_token_expiration = time() + $response->expires_in - 60;
        $tdAmeritradeAccount->access_token = $response->access_token;

        $tdAmeritradeAccount->save();

        $this->accessToken = $response->access_token;
    }
}
