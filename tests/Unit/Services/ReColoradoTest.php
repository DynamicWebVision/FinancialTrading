<?php namespace App\Services;

use App\Services\ReColorado;

class ReColoradoTest  {

    protected $scraper;

    public $symbol;
    public $start_date;
    public $end_date;
    public $logger;

    public function __construct() {
        $this->scraper = new Scraper();
    }

    public function testInitialSearch() {
        $reColorado = new ReColorado();

        $reColorado->initialSearch();
    }

    public function testFetchOneListing() {
        $reColorado = new ReColorado();

        $reColorado->fetchOneListing(2);
    }
}