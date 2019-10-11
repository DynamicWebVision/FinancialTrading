<?php namespace App\Services;

use App\Services\Scraper;

class YahooFinance  {

    protected $scraper;

    public $symbol;
    public $start_date;
    public $end_date;
    public $logger;

    public function __construct() {
        $this->scraper = new Scraper();
    }

    public function getHistoricalRates($dates = false) {
        if ($dates) {
            $period1 = strtotime($dates['start_date']);
            $period2 = strtotime($dates['end_date']);
            $url = 'https://finance.yahoo.com/quote/'.$this->symbol.'/history?period1='.$period1.'&period2='.$period2.'&interval=1d&filter=history&frequency=1d';
        }
        else {
            $url = 'https://finance.yahoo.com/quote/'.$this->symbol.'/history?p='.$this->symbol;
        }
        $historicalRates = $this->scraper->getCurl($url);

        try {

            $inBetween = '[{'.$this->scraper->getInBetween($historicalRates, '"prices":[{', '}]').'}]';

            return json_decode($inBetween);
        }
        catch (\Exception $e) {
            $this->logger->logMessage('Error Exception!');
            $this->logger->logMessage('Response: '.$historicalRates);
            return false;
        }
    }
}