<?php namespace App\Broker;

use \Log;

abstract class Base  {

    public $apiUrl;
    public $getVariables = [];
    public $unitAmount;

    public function apiGetRequest($setGet = true) {
        if ($setGet) {
            $getQueryString = http_build_query($this->getVariables);
            $this->apiUrl = $this->apiUrl."?".$getQueryString;
        }

        curl_setopt($this->curl,CURLOPT_POST, 0);
        curl_setopt($this->curl, CURLOPT_URL, $this->apiUrl);
        $resp = curl_exec($this->curl);

        $this->getVariables = [];
        return json_decode($resp);
    }

    public function apiPostRequest($fields) {
        curl_setopt($this->curl, CURLOPT_URL, $this->apiUrl);
        curl_setopt($this->curl,CURLOPT_POST, 1);
        curl_setopt($this->curl,CURLOPT_POSTFIELDS, json_encode($fields));

        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, NULL);

        //Added to Get Curl Info
        //curl_setopt($this->curl, CURLINFO_HEADER_OUT, true);

        $resp = curl_exec($this->curl);

        // $headers = curl_getinfo($this->curl, CURLINFO_HEADER_OUT);
        return json_decode($resp);
    }

    public function apiPostRequestHttpFields($fields) {
        curl_setopt($this->curl, CURLOPT_URL, $this->apiUrl);
        curl_setopt($this->curl,CURLOPT_POST, 1);
        curl_setopt($this->curl,CURLOPT_POSTFIELDS, http_build_query($fields));

        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, NULL);

        //Added to Get Curl Info
        //curl_setopt($this->curl, CURLINFO_HEADER_OUT, true);

        $resp = curl_exec($this->curl);

        // $headers = curl_getinfo($this->curl, CURLINFO_HEADER_OUT);
        return json_decode($resp);
    }

    public function apiDeleteRequest() {
        curl_setopt($this->curl, CURLOPT_URL, $this->apiUrl);
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, "DELETE");
        $resp = curl_exec($this->curl);

        return $resp;
    }

    public function apiPatchRequest($data) {

        curl_setopt($this->curl, CURLOPT_URL, $this->apiUrl);
        curl_setopt($this->curl,CURLOPT_POST, count($data));
        curl_setopt($this->curl,CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, "PUT");
        $resp = curl_exec($this->curl);

        return json_decode($resp);
    }
}
