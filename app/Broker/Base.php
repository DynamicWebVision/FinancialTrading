<?php namespace App\Broker;

use \Log;
use \App\Model\ApiErrorLog;

abstract class Base  {

    public $apiUrl;
    public $getVariables = [];
    public $postVariables = [];
    public $unitAmount;
    public $rawResponse;

    public function apiGetRequest($setGet = true) {
        if ($setGet) {
            $getQueryString = http_build_query($this->getVariables);
            $this->apiUrl = $this->apiUrl."?".$getQueryString;
            $this->apiUrl = 'https://cloud.iexapis.com/v1/stock/XOM/chart/5Y?token=sk_151e3d1400954c39a1b425156ac80cf5';
        }

        curl_setopt($this->curl,CURLOPT_POST, 0);
        curl_setopt($this->curl, CURLOPT_URL, $this->apiUrl);
        $resp = curl_exec($this->curl);

        $this->getVariables = [];
        $this->rawResponse = $resp;
        return json_decode($resp);
    }

    public function apiPostRequest($fields) {
        $this->postVariables = $fields;

        curl_setopt($this->curl, CURLOPT_URL, $this->apiUrl);
        curl_setopt($this->curl,CURLOPT_POST, 1);
        curl_setopt($this->curl,CURLOPT_POSTFIELDS, json_encode($fields));

        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, NULL);

        //Added to Get Curl Info
        //curl_setopt($this->curl, CURLINFO_HEADER_OUT, true);

        $resp = curl_exec($this->curl);

        // $headers = curl_getinfo($this->curl, CURLINFO_HEADER_OUT);
        $this->rawResponse = $resp;
        return json_decode($resp);
    }

    public function apiPostRequestHttpFields($fields) {
        $this->postVariables = $fields;

        curl_setopt($this->curl, CURLOPT_URL, $this->apiUrl);
        curl_setopt($this->curl,CURLOPT_POST, 1);
        curl_setopt($this->curl,CURLOPT_POSTFIELDS, http_build_query($fields));

        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, NULL);

        //Added to Get Curl Info
        //curl_setopt($this->curl, CURLINFO_HEADER_OUT, true);

        $resp = curl_exec($this->curl);

        // $headers = curl_getinfo($this->curl, CURLINFO_HEADER_OUT);
        $this->rawResponse = $resp;
        return json_decode($resp);
    }

    public function apiDeleteRequest() {
        curl_setopt($this->curl, CURLOPT_URL, $this->apiUrl);
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, "DELETE");
        $resp = curl_exec($this->curl);

        $this->rawResponse = $resp;
        return json_decode($resp);
    }

    public function apiPatchRequest($data) {

        curl_setopt($this->curl, CURLOPT_URL, $this->apiUrl);
        curl_setopt($this->curl,CURLOPT_POST, count($data));
        curl_setopt($this->curl,CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, "PUT");
        $resp = curl_exec($this->curl);

        $this->rawResponse = $resp;
        return json_decode($resp);
    }

    public function createApiErrorLogRecord($api, $method, $relevantVariable) {
        $apiErrorLog = new ApiErrorLog();

        $apiErrorLog->api = $api;
        $apiErrorLog->get_variables = json_encode($this->getVariables);
        $apiErrorLog->post_variables = json_encode($this->postVariables);
        $apiErrorLog->url = $this->apiUrl;
        $apiErrorLog->method = $method;
        $apiErrorLog->relevant_variable = $relevantVariable;
        $apiErrorLog->response = $this->rawResponse;

        $apiErrorLog->save();
    }
}
