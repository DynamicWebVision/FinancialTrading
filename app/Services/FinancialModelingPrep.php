<?php namespace App\Services;

use App\Services\Scraper;

class FinancialModelingPrep  {

    protected $scraper;

    public $symbol;
    public $start_date;
    public $end_date;
    public $logger;

    public function __construct() {
        $this->scraper = new Scraper();

        set_time_limit(0);

    }

    public function get($url) {
        $channel = curl_init();

        curl_setopt($channel, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($channel, CURLOPT_HEADER, 0);
        curl_setopt($channel, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($channel, CURLOPT_URL, $url);
        curl_setopt($channel, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($channel, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($channel, CURLOPT_TIMEOUT, 0);
        curl_setopt($channel, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($channel, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($channel, CURLOPT_SSL_VERIFYPEER, FALSE);

        return json_decode(curl_exec($channel));
    }

    public function annualIncomeStatements($symbol) {
        $url = 'https://financialmodelingprep.com/api/v3/financials/income-statement/'.$symbol;
        $response = $this->get($url);

        return $response->financials;
    }
}