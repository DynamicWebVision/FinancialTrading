<?php namespace App\Http\Controllers\Nursing;

use App\Services\Scraper;
use App\Model\NurseJobs;
use App\Http\Controllers\Controller;

class FlexcareController extends Controller {

    public $accountLive;
    public $environment;

    public $baseUrl;
    public $searchSection = 'fuo';
    public $scraper;

    public $allUrls = [];

    public function __construct()
    {
        $this->scraper = new Scraper();
        $this->scraper->baseUrl = 'https://www.flexcarestaff.com';
    }

    public function getS() {
        $url = 'https://www.flexcarestaff.com/jobs/search?specialty%5B%5D=Emergency+Room&state%5B%5D=CA&query=&shift=All';
        $fullHtml = $this->scraper->getCurl($url);
        $urls = $this->scraper->getAllLinksBetweenText($fullHtml, 'view-id-jobs', 'pagination');
        $d=1;
    }

    public function getAllLinks() {
        $page_url = '/jobs/search?specialty%5B%5D=Emergency+Room&state%5B%5D=CA&query=&shift=All';
        while ($page_url) {
            $fullHtml = $this->scraper->getCurl($this->scraper->baseUrl.$page_url);
            $urls = $this->scraper->getAllLinksBetweenText($fullHtml, 'view-id-jobs', 'pagination');
            $this->allUrls = array_merge($this->allUrls, $urls);
            if ($this->scraper->inString($fullHtml, 'Go to next page')) {
                $page_url = $this->scraper->getLinkBetweenText($fullHtml, 'Go to next page', 'next');
                $this->scraper->sleepRandomSeconds();
            }
            else {
                $page_url = false;
            }
        }
        $debug=1;
    }

    public function tempFlexcareUrls() {
        $urls = '["https:\/\/www.flexcarestaff.com\/job\/256750","https:\/\/www.flexcarestaff.com\/job\/256636","https:\/\/www.flexcarestaff.com\/job\/256635","https:\/\/www.flexcarestaff.com\/job\/256530","https:\/\/www.flexcarestaff.com\/job\/256498","https:\/\/www.flexcarestaff.com\/job\/256449","https:\/\/www.flexcarestaff.com\/job\/256379","https:\/\/www.flexcarestaff.com\/job\/256380","https:\/\/www.flexcarestaff.com\/job\/256195","https:\/\/www.flexcarestaff.com\/job\/256896","https:\/\/www.flexcarestaff.com\/job\/256884","https:\/\/www.flexcarestaff.com\/job\/256883","https:\/\/www.flexcarestaff.com\/job\/256829","https:\/\/www.flexcarestaff.com\/job\/256834","https:\/\/www.flexcarestaff.com\/job\/256830","https:\/\/www.flexcarestaff.com\/job\/256831","https:\/\/www.flexcarestaff.com\/job\/256805","https:\/\/www.flexcarestaff.com\/job\/256802","https:\/\/www.flexcarestaff.com\/job\/256803","https:\/\/www.flexcarestaff.com\/job\/256896","https:\/\/www.flexcarestaff.com\/job\/256884","https:\/\/www.flexcarestaff.com\/job\/256883","https:\/\/www.flexcarestaff.com\/job\/256829","https:\/\/www.flexcarestaff.com\/job\/256834","https:\/\/www.flexcarestaff.com\/job\/256830","https:\/\/www.flexcarestaff.com\/job\/256831","https:\/\/www.flexcarestaff.com\/job\/256805","https:\/\/www.flexcarestaff.com\/job\/256802","https:\/\/www.flexcarestaff.com\/job\/256803","https:\/\/www.flexcarestaff.com\/job\/256896","https:\/\/www.flexcarestaff.com\/job\/256884","https:\/\/www.flexcarestaff.com\/job\/256883","https:\/\/www.flexcarestaff.com\/job\/256829","https:\/\/www.flexcarestaff.com\/job\/256834","https:\/\/www.flexcarestaff.com\/job\/256830","https:\/\/www.flexcarestaff.com\/job\/256831","https:\/\/www.flexcarestaff.com\/job\/256805","https:\/\/www.flexcarestaff.com\/job\/256802","https:\/\/www.flexcarestaff.com\/job\/256803","https:\/\/www.flexcarestaff.com\/job\/256896","https:\/\/www.flexcarestaff.com\/job\/256884","https:\/\/www.flexcarestaff.com\/job\/256883","https:\/\/www.flexcarestaff.com\/job\/256829","https:\/\/www.flexcarestaff.com\/job\/256834","https:\/\/www.flexcarestaff.com\/job\/256830","https:\/\/www.flexcarestaff.com\/job\/256831","https:\/\/www.flexcarestaff.com\/job\/256805","https:\/\/www.flexcarestaff.com\/job\/256802","https:\/\/www.flexcarestaff.com\/job\/256803","https:\/\/www.flexcarestaff.com\/job\/256896","https:\/\/www.flexcarestaff.com\/job\/256884","https:\/\/www.flexcarestaff.com\/job\/256883","https:\/\/www.flexcarestaff.com\/job\/256829","https:\/\/www.flexcarestaff.com\/job\/256834","https:\/\/www.flexcarestaff.com\/job\/256830","https:\/\/www.flexcarestaff.com\/job\/256831","https:\/\/www.flexcarestaff.com\/job\/256805","https:\/\/www.flexcarestaff.com\/job\/256802","https:\/\/www.flexcarestaff.com\/job\/256803","https:\/\/www.flexcarestaff.com\/job\/256896","https:\/\/www.flexcarestaff.com\/job\/256884","https:\/\/www.flexcarestaff.com\/job\/256883","https:\/\/www.flexcarestaff.com\/job\/256829","https:\/\/www.flexcarestaff.com\/job\/256834","https:\/\/www.flexcarestaff.com\/job\/256830","https:\/\/www.flexcarestaff.com\/job\/256831","https:\/\/www.flexcarestaff.com\/job\/256805","https:\/\/www.flexcarestaff.com\/job\/256802","https:\/\/www.flexcarestaff.com\/job\/256803","https:\/\/www.flexcarestaff.com\/job\/256896","https:\/\/www.flexcarestaff.com\/job\/256884","https:\/\/www.flexcarestaff.com\/job\/256883","https:\/\/www.flexcarestaff.com\/job\/256829","https:\/\/www.flexcarestaff.com\/job\/256834","https:\/\/www.flexcarestaff.com\/job\/256830","https:\/\/www.flexcarestaff.com\/job\/256831","https:\/\/www.flexcarestaff.com\/job\/256805","https:\/\/www.flexcarestaff.com\/job\/256802","https:\/\/www.flexcarestaff.com\/job\/256803","https:\/\/www.flexcarestaff.com\/job\/256896","https:\/\/www.flexcarestaff.com\/job\/256884","https:\/\/www.flexcarestaff.com\/job\/256883","https:\/\/www.flexcarestaff.com\/job\/256829","https:\/\/www.flexcarestaff.com\/job\/256834","https:\/\/www.flexcarestaff.com\/job\/256830","https:\/\/www.flexcarestaff.com\/job\/256831","https:\/\/www.flexcarestaff.com\/job\/256805","https:\/\/www.flexcarestaff.com\/job\/256802","https:\/\/www.flexcarestaff.com\/job\/256803","https:\/\/www.flexcarestaff.com\/job\/256896","https:\/\/www.flexcarestaff.com\/job\/256884","https:\/\/www.flexcarestaff.com\/job\/256883","https:\/\/www.flexcarestaff.com\/job\/256829","https:\/\/www.flexcarestaff.com\/job\/256834","https:\/\/www.flexcarestaff.com\/job\/256830","https:\/\/www.flexcarestaff.com\/job\/256831","https:\/\/www.flexcarestaff.com\/job\/256805","https:\/\/www.flexcarestaff.com\/job\/256802","https:\/\/www.flexcarestaff.com\/job\/256803","https:\/\/www.flexcarestaff.com\/job\/256896","https:\/\/www.flexcarestaff.com\/job\/256884","https:\/\/www.flexcarestaff.com\/job\/256883","https:\/\/www.flexcarestaff.com\/job\/256829","https:\/\/www.flexcarestaff.com\/job\/256834","https:\/\/www.flexcarestaff.com\/job\/256830","https:\/\/www.flexcarestaff.com\/job\/256831","https:\/\/www.flexcarestaff.com\/job\/256805","https:\/\/www.flexcarestaff.com\/job\/256802","https:\/\/www.flexcarestaff.com\/job\/256803","https:\/\/www.flexcarestaff.com\/job\/256896","https:\/\/www.flexcarestaff.com\/job\/256884","https:\/\/www.flexcarestaff.com\/job\/256883","https:\/\/www.flexcarestaff.com\/job\/256829","https:\/\/www.flexcarestaff.com\/job\/256834","https:\/\/www.flexcarestaff.com\/job\/256830","https:\/\/www.flexcarestaff.com\/job\/256831","https:\/\/www.flexcarestaff.com\/job\/256805","https:\/\/www.flexcarestaff.com\/job\/256802","https:\/\/www.flexcarestaff.com\/job\/256803","https:\/\/www.flexcarestaff.com\/job\/256896","https:\/\/www.flexcarestaff.com\/job\/256884","https:\/\/www.flexcarestaff.com\/job\/256883","https:\/\/www.flexcarestaff.com\/job\/256829","https:\/\/www.flexcarestaff.com\/job\/256834","https:\/\/www.flexcarestaff.com\/job\/256830","https:\/\/www.flexcarestaff.com\/job\/256831","https:\/\/www.flexcarestaff.com\/job\/256805","https:\/\/www.flexcarestaff.com\/job\/256802","https:\/\/www.flexcarestaff.com\/job\/256803","https:\/\/www.flexcarestaff.com\/job\/256896","https:\/\/www.flexcarestaff.com\/job\/256884","https:\/\/www.flexcarestaff.com\/job\/256883","https:\/\/www.flexcarestaff.com\/job\/256829","https:\/\/www.flexcarestaff.com\/job\/256834","https:\/\/www.flexcarestaff.com\/job\/256830","https:\/\/www.flexcarestaff.com\/job\/256831","https:\/\/www.flexcarestaff.com\/job\/256805","https:\/\/www.flexcarestaff.com\/job\/256802","https:\/\/www.flexcarestaff.com\/job\/256803","https:\/\/www.flexcarestaff.com\/job\/256896","https:\/\/www.flexcarestaff.com\/job\/256884","https:\/\/www.flexcarestaff.com\/job\/256883","https:\/\/www.flexcarestaff.com\/job\/256829","https:\/\/www.flexcarestaff.com\/job\/256834","https:\/\/www.flexcarestaff.com\/job\/256830","https:\/\/www.flexcarestaff.com\/job\/256831","https:\/\/www.flexcarestaff.com\/job\/256805","https:\/\/www.flexcarestaff.com\/job\/256802","https:\/\/www.flexcarestaff.com\/job\/256803","https:\/\/www.flexcarestaff.com\/job\/256896","https:\/\/www.flexcarestaff.com\/job\/256884","https:\/\/www.flexcarestaff.com\/job\/256883","https:\/\/www.flexcarestaff.com\/job\/256829","https:\/\/www.flexcarestaff.com\/job\/256834","https:\/\/www.flexcarestaff.com\/job\/256830","https:\/\/www.flexcarestaff.com\/job\/256831","https:\/\/www.flexcarestaff.com\/job\/256805","https:\/\/www.flexcarestaff.com\/job\/256802","https:\/\/www.flexcarestaff.com\/job\/256803","https:\/\/www.flexcarestaff.com\/job\/256896","https:\/\/www.flexcarestaff.com\/job\/256884","https:\/\/www.flexcarestaff.com\/job\/256883","https:\/\/www.flexcarestaff.com\/job\/256829","https:\/\/www.flexcarestaff.com\/job\/256834","https:\/\/www.flexcarestaff.com\/job\/256830","https:\/\/www.flexcarestaff.com\/job\/256831","https:\/\/www.flexcarestaff.com\/job\/256805","https:\/\/www.flexcarestaff.com\/job\/256802","https:\/\/www.flexcarestaff.com\/job\/256803","https:\/\/www.flexcarestaff.com\/job\/256896","https:\/\/www.flexcarestaff.com\/job\/256884","https:\/\/www.flexcarestaff.com\/job\/256883","https:\/\/www.flexcarestaff.com\/job\/256829","https:\/\/www.flexcarestaff.com\/job\/256834","https:\/\/www.flexcarestaff.com\/job\/256830","https:\/\/www.flexcarestaff.com\/job\/256831","https:\/\/www.flexcarestaff.com\/job\/256805","https:\/\/www.flexcarestaff.com\/job\/256802","https:\/\/www.flexcarestaff.com\/job\/256803","https:\/\/www.flexcarestaff.com\/job\/256896","https:\/\/www.flexcarestaff.com\/job\/256884","https:\/\/www.flexcarestaff.com\/job\/256883","https:\/\/www.flexcarestaff.com\/job\/256829","https:\/\/www.flexcarestaff.com\/job\/256834","https:\/\/www.flexcarestaff.com\/job\/256830","https:\/\/www.flexcarestaff.com\/job\/256831","https:\/\/www.flexcarestaff.com\/job\/256805","https:\/\/www.flexcarestaff.com\/job\/256802","https:\/\/www.flexcarestaff.com\/job\/256803","https:\/\/www.flexcarestaff.com\/job\/256896","https:\/\/www.flexcarestaff.com\/job\/256884","https:\/\/www.flexcarestaff.com\/job\/256883","https:\/\/www.flexcarestaff.com\/job\/256829","https:\/\/www.flexcarestaff.com\/job\/256834","https:\/\/www.flexcarestaff.com\/job\/256830","https:\/\/www.flexcarestaff.com\/job\/256831","https:\/\/www.flexcarestaff.com\/job\/256805","https:\/\/www.flexcarestaff.com\/job\/256802","https:\/\/www.flexcarestaff.com\/job\/256803","https:\/\/www.flexcarestaff.com\/job\/256896","https:\/\/www.flexcarestaff.com\/job\/256884","https:\/\/www.flexcarestaff.com\/job\/256883","https:\/\/www.flexcarestaff.com\/job\/256829","https:\/\/www.flexcarestaff.com\/job\/256834","https:\/\/www.flexcarestaff.com\/job\/256830","https:\/\/www.flexcarestaff.com\/job\/256831","https:\/\/www.flexcarestaff.com\/job\/256805","https:\/\/www.flexcarestaff.com\/job\/256802","https:\/\/www.flexcarestaff.com\/job\/256803","https:\/\/www.flexcarestaff.com\/job\/256896","https:\/\/www.flexcarestaff.com\/job\/256884","https:\/\/www.flexcarestaff.com\/job\/256883","https:\/\/www.flexcarestaff.com\/job\/256829","https:\/\/www.flexcarestaff.com\/job\/256834","https:\/\/www.flexcarestaff.com\/job\/256830","https:\/\/www.flexcarestaff.com\/job\/256831","https:\/\/www.flexcarestaff.com\/job\/256805","https:\/\/www.flexcarestaff.com\/job\/256802","https:\/\/www.flexcarestaff.com\/job\/256803","https:\/\/www.flexcarestaff.com\/job\/256896","https:\/\/www.flexcarestaff.com\/job\/256884","https:\/\/www.flexcarestaff.com\/job\/256883","https:\/\/www.flexcarestaff.com\/job\/256829","https:\/\/www.flexcarestaff.com\/job\/256834","https:\/\/www.flexcarestaff.com\/job\/256830","https:\/\/www.flexcarestaff.com\/job\/256831","https:\/\/www.flexcarestaff.com\/job\/256805","https:\/\/www.flexcarestaff.com\/job\/256802","https:\/\/www.flexcarestaff.com\/job\/256803","https:\/\/www.flexcarestaff.com\/job\/256896","https:\/\/www.flexcarestaff.com\/job\/256884","https:\/\/www.flexcarestaff.com\/job\/256883","https:\/\/www.flexcarestaff.com\/job\/256829","https:\/\/www.flexcarestaff.com\/job\/256834","https:\/\/www.flexcarestaff.com\/job\/256830","https:\/\/www.flexcarestaff.com\/job\/256831","https:\/\/www.flexcarestaff.com\/job\/256805","https:\/\/www.flexcarestaff.com\/job\/256802","https:\/\/www.flexcarestaff.com\/job\/256803","https:\/\/www.flexcarestaff.com\/job\/256896","https:\/\/www.flexcarestaff.com\/job\/256884","https:\/\/www.flexcarestaff.com\/job\/256883","https:\/\/www.flexcarestaff.com\/job\/256829","https:\/\/www.flexcarestaff.com\/job\/256834","https:\/\/www.flexcarestaff.com\/job\/256830","https:\/\/www.flexcarestaff.com\/job\/256831","https:\/\/www.flexcarestaff.com\/job\/256805","https:\/\/www.flexcarestaff.com\/job\/256802","https:\/\/www.flexcarestaff.com\/job\/256803","https:\/\/www.flexcarestaff.com\/job\/256896","https:\/\/www.flexcarestaff.com\/job\/256884","https:\/\/www.flexcarestaff.com\/job\/256883","https:\/\/www.flexcarestaff.com\/job\/256829","https:\/\/www.flexcarestaff.com\/job\/256834","https:\/\/www.flexcarestaff.com\/job\/256830","https:\/\/www.flexcarestaff.com\/job\/256831","https:\/\/www.flexcarestaff.com\/job\/256805","https:\/\/www.flexcarestaff.com\/job\/256802","https:\/\/www.flexcarestaff.com\/job\/256803","https:\/\/www.flexcarestaff.com\/job\/256896","https:\/\/www.flexcarestaff.com\/job\/256884","https:\/\/www.flexcarestaff.com\/job\/256883","https:\/\/www.flexcarestaff.com\/job\/256829","https:\/\/www.flexcarestaff.com\/job\/256834","https:\/\/www.flexcarestaff.com\/job\/256830","https:\/\/www.flexcarestaff.com\/job\/256831","https:\/\/www.flexcarestaff.com\/job\/256805","https:\/\/www.flexcarestaff.com\/job\/256802","https:\/\/www.flexcarestaff.com\/job\/256803","https:\/\/www.flexcarestaff.com\/job\/256896","https:\/\/www.flexcarestaff.com\/job\/256884","https:\/\/www.flexcarestaff.com\/job\/256883","https:\/\/www.flexcarestaff.com\/job\/256829","https:\/\/www.flexcarestaff.com\/job\/256834","https:\/\/www.flexcarestaff.com\/job\/256830","https:\/\/www.flexcarestaff.com\/job\/256831","https:\/\/www.flexcarestaff.com\/job\/256805","https:\/\/www.flexcarestaff.com\/job\/256802","https:\/\/www.flexcarestaff.com\/job\/256803","https:\/\/www.flexcarestaff.com\/job\/256896","https:\/\/www.flexcarestaff.com\/job\/256884","https:\/\/www.flexcarestaff.com\/job\/256883","https:\/\/www.flexcarestaff.com\/job\/256829","https:\/\/www.flexcarestaff.com\/job\/256834","https:\/\/www.flexcarestaff.com\/job\/256830","https:\/\/www.flexcarestaff.com\/job\/256831","https:\/\/www.flexcarestaff.com\/job\/256805","https:\/\/www.flexcarestaff.com\/job\/256802","https:\/\/www.flexcarestaff.com\/job\/256803","https:\/\/www.flexcarestaff.com\/job\/256896","https:\/\/www.flexcarestaff.com\/job\/256884","https:\/\/www.flexcarestaff.com\/job\/256883","https:\/\/www.flexcarestaff.com\/job\/256829","https:\/\/www.flexcarestaff.com\/job\/256834","https:\/\/www.flexcarestaff.com\/job\/256830","https:\/\/www.flexcarestaff.com\/job\/256831","https:\/\/www.flexcarestaff.com\/job\/256805","https:\/\/www.flexcarestaff.com\/job\/256802","https:\/\/www.flexcarestaff.com\/job\/256803","https:\/\/www.flexcarestaff.com\/job\/256896","https:\/\/www.flexcarestaff.com\/job\/256884","https:\/\/www.flexcarestaff.com\/job\/256883","https:\/\/www.flexcarestaff.com\/job\/256829","https:\/\/www.flexcarestaff.com\/job\/256834","https:\/\/www.flexcarestaff.com\/job\/256830","https:\/\/www.flexcarestaff.com\/job\/256831","https:\/\/www.flexcarestaff.com\/job\/256805","https:\/\/www.flexcarestaff.com\/job\/256802","https:\/\/www.flexcarestaff.com\/job\/256803","https:\/\/www.flexcarestaff.com\/job\/256896","https:\/\/www.flexcarestaff.com\/job\/256884","https:\/\/www.flexcarestaff.com\/job\/256883","https:\/\/www.flexcarestaff.com\/job\/256829","https:\/\/www.flexcarestaff.com\/job\/256834","https:\/\/www.flexcarestaff.com\/job\/256830","https:\/\/www.flexcarestaff.com\/job\/256831","https:\/\/www.flexcarestaff.com\/job\/256805","https:\/\/www.flexcarestaff.com\/job\/256802","https:\/\/www.flexcarestaff.com\/job\/256803","https:\/\/www.flexcarestaff.com\/job\/256896","https:\/\/www.flexcarestaff.com\/job\/256884","https:\/\/www.flexcarestaff.com\/job\/256883","https:\/\/www.flexcarestaff.com\/job\/256829","https:\/\/www.flexcarestaff.com\/job\/256834","https:\/\/www.flexcarestaff.com\/job\/256830","https:\/\/www.flexcarestaff.com\/job\/256831","https:\/\/www.flexcarestaff.com\/job\/256805","https:\/\/www.flexcarestaff.com\/job\/256802","https:\/\/www.flexcarestaff.com\/job\/256803","https:\/\/www.flexcarestaff.com\/job\/256896","https:\/\/www.flexcarestaff.com\/job\/256884","https:\/\/www.flexcarestaff.com\/job\/256883","https:\/\/www.flexcarestaff.com\/job\/256829","https:\/\/www.flexcarestaff.com\/job\/256834","https:\/\/www.flexcarestaff.com\/job\/256830","https:\/\/www.flexcarestaff.com\/job\/256831","https:\/\/www.flexcarestaff.com\/job\/256805","https:\/\/www.flexcarestaff.com\/job\/256802","https:\/\/www.flexcarestaff.com\/job\/256803","https:\/\/www.flexcarestaff.com\/job\/256896","https:\/\/www.flexcarestaff.com\/job\/256884","https:\/\/www.flexcarestaff.com\/job\/256883","https:\/\/www.flexcarestaff.com\/job\/256829","https:\/\/www.flexcarestaff.com\/job\/256834","https:\/\/www.flexcarestaff.com\/job\/256830","https:\/\/www.flexcarestaff.com\/job\/256831","https:\/\/www.flexcarestaff.com\/job\/256805","https:\/\/www.flexcarestaff.com\/job\/256802","https:\/\/www.flexcarestaff.com\/job\/256803","https:\/\/www.flexcarestaff.com\/job\/256896","https:\/\/www.flexcarestaff.com\/job\/256884","https:\/\/www.flexcarestaff.com\/job\/256883","https:\/\/www.flexcarestaff.com\/job\/256829","https:\/\/www.flexcarestaff.com\/job\/256834","https:\/\/www.flexcarestaff.com\/job\/256830","https:\/\/www.flexcarestaff.com\/job\/256831","https:\/\/www.flexcarestaff.com\/job\/256805","https:\/\/www.flexcarestaff.com\/job\/256802","https:\/\/www.flexcarestaff.com\/job\/256803","https:\/\/www.flexcarestaff.com\/job\/256896","https:\/\/www.flexcarestaff.com\/job\/256884","https:\/\/www.flexcarestaff.com\/job\/256883","https:\/\/www.flexcarestaff.com\/job\/256829","https:\/\/www.flexcarestaff.com\/job\/256834","https:\/\/www.flexcarestaff.com\/job\/256830","https:\/\/www.flexcarestaff.com\/job\/256831","https:\/\/www.flexcarestaff.com\/job\/256805","https:\/\/www.flexcarestaff.com\/job\/256802","https:\/\/www.flexcarestaff.com\/job\/256803","https:\/\/www.flexcarestaff.com\/job\/256896","https:\/\/www.flexcarestaff.com\/job\/256884","https:\/\/www.flexcarestaff.com\/job\/256883","https:\/\/www.flexcarestaff.com\/job\/256829","https:\/\/www.flexcarestaff.com\/job\/256834","https:\/\/www.flexcarestaff.com\/job\/256830","https:\/\/www.flexcarestaff.com\/job\/256831","https:\/\/www.flexcarestaff.com\/job\/256805","https:\/\/www.flexcarestaff.com\/job\/256802","https:\/\/www.flexcarestaff.com\/job\/256803","https:\/\/www.flexcarestaff.com\/job\/256896","https:\/\/www.flexcarestaff.com\/job\/256884","https:\/\/www.flexcarestaff.com\/job\/256883","https:\/\/www.flexcarestaff.com\/job\/256829","https:\/\/www.flexcarestaff.com\/job\/256834","https:\/\/www.flexcarestaff.com\/job\/256830","https:\/\/www.flexcarestaff.com\/job\/256831","https:\/\/www.flexcarestaff.com\/job\/256805","https:\/\/www.flexcarestaff.com\/job\/256802","https:\/\/www.flexcarestaff.com\/job\/256803","https:\/\/www.flexcarestaff.com\/job\/256896","https:\/\/www.flexcarestaff.com\/job\/256884","https:\/\/www.flexcarestaff.com\/job\/256883","https:\/\/www.flexcarestaff.com\/job\/256829","https:\/\/www.flexcarestaff.com\/job\/256834","https:\/\/www.flexcarestaff.com\/job\/256830","https:\/\/www.flexcarestaff.com\/job\/256831","https:\/\/www.flexcarestaff.com\/job\/256805","https:\/\/www.flexcarestaff.com\/job\/256802","https:\/\/www.flexcarestaff.com\/job\/256803","https:\/\/www.flexcarestaff.com\/job\/256896","https:\/\/www.flexcarestaff.com\/job\/256884","https:\/\/www.flexcarestaff.com\/job\/256883","https:\/\/www.flexcarestaff.com\/job\/256829","https:\/\/www.flexcarestaff.com\/job\/256834","https:\/\/www.flexcarestaff.com\/job\/256830","https:\/\/www.flexcarestaff.com\/job\/256831","https:\/\/www.flexcarestaff.com\/job\/256805","https:\/\/www.flexcarestaff.com\/job\/256802","https:\/\/www.flexcarestaff.com\/job\/256803","https:\/\/www.flexcarestaff.com\/job\/256896","https:\/\/www.flexcarestaff.com\/job\/256884","https:\/\/www.flexcarestaff.com\/job\/256883","https:\/\/www.flexcarestaff.com\/job\/256829","https:\/\/www.flexcarestaff.com\/job\/256834","https:\/\/www.flexcarestaff.com\/job\/256830","https:\/\/www.flexcarestaff.com\/job\/256831","https:\/\/www.flexcarestaff.com\/job\/256805","https:\/\/www.flexcarestaff.com\/job\/256802","https:\/\/www.flexcarestaff.com\/job\/256803","https:\/\/www.flexcarestaff.com\/job\/256896","https:\/\/www.flexcarestaff.com\/job\/256884","https:\/\/www.flexcarestaff.com\/job\/256883","https:\/\/www.flexcarestaff.com\/job\/256829","https:\/\/www.flexcarestaff.com\/job\/256834","https:\/\/www.flexcarestaff.com\/job\/256830","https:\/\/www.flexcarestaff.com\/job\/256831","https:\/\/www.flexcarestaff.com\/job\/256805","https:\/\/www.flexcarestaff.com\/job\/256802","https:\/\/www.flexcarestaff.com\/job\/256803","https:\/\/www.flexcarestaff.com\/job\/256896","https:\/\/www.flexcarestaff.com\/job\/256884","https:\/\/www.flexcarestaff.com\/job\/256883","https:\/\/www.flexcarestaff.com\/job\/256829","https:\/\/www.flexcarestaff.com\/job\/256834","https:\/\/www.flexcarestaff.com\/job\/256830","https:\/\/www.flexcarestaff.com\/job\/256831","https:\/\/www.flexcarestaff.com\/job\/256805","https:\/\/www.flexcarestaff.com\/job\/256802","https:\/\/www.flexcarestaff.com\/job\/256803","https:\/\/www.flexcarestaff.com\/job\/256896","https:\/\/www.flexcarestaff.com\/job\/256884","https:\/\/www.flexcarestaff.com\/job\/256883","https:\/\/www.flexcarestaff.com\/job\/256829","https:\/\/www.flexcarestaff.com\/job\/256834","https:\/\/www.flexcarestaff.com\/job\/256830","https:\/\/www.flexcarestaff.com\/job\/256831","https:\/\/www.flexcarestaff.com\/job\/256805","https:\/\/www.flexcarestaff.com\/job\/256802","https:\/\/www.flexcarestaff.com\/job\/256803","https:\/\/www.flexcarestaff.com\/job\/256896","https:\/\/www.flexcarestaff.com\/job\/256884","https:\/\/www.flexcarestaff.com\/job\/256883","https:\/\/www.flexcarestaff.com\/job\/256829","https:\/\/www.flexcarestaff.com\/job\/256834","https:\/\/www.flexcarestaff.com\/job\/256830","https:\/\/www.flexcarestaff.com\/job\/256831","https:\/\/www.flexcarestaff.com\/job\/256805","https:\/\/www.flexcarestaff.com\/job\/256802","https:\/\/www.flexcarestaff.com\/job\/256803","https:\/\/www.flexcarestaff.com\/job\/256896","https:\/\/www.flexcarestaff.com\/job\/256884","https:\/\/www.flexcarestaff.com\/job\/256883","https:\/\/www.flexcarestaff.com\/job\/256829","https:\/\/www.flexcarestaff.com\/job\/256834","https:\/\/www.flexcarestaff.com\/job\/256830","https:\/\/www.flexcarestaff.com\/job\/256831","https:\/\/www.flexcarestaff.com\/job\/256805","https:\/\/www.flexcarestaff.com\/job\/256802","https:\/\/www.flexcarestaff.com\/job\/256803","https:\/\/www.flexcarestaff.com\/job\/256896","https:\/\/www.flexcarestaff.com\/job\/256884","https:\/\/www.flexcarestaff.com\/job\/256883","https:\/\/www.flexcarestaff.com\/job\/256829","https:\/\/www.flexcarestaff.com\/job\/256834","https:\/\/www.flexcarestaff.com\/job\/256830","https:\/\/www.flexcarestaff.com\/job\/256831","https:\/\/www.flexcarestaff.com\/job\/256805","https:\/\/www.flexcarestaff.com\/job\/256802","https:\/\/www.flexcarestaff.com\/job\/256803"]';
        $urls = json_decode($urls);

        foreach ($urls as $url) {
            $agency_id = $this->scraper->getToEnd($url, 'job/');
            $fullHtml = $this->scraper->getCurl($url, 'job/');

            $locationTextToEnd = $this->scraper->getToEnd($fullHtml, 'Location:');
            $cityState = $this->scraper->getNextTextBetweenTagsThatHasString(',', $locationTextToEnd);

            $city = trim($this->scraper->getTextBeforeString(',', $cityState));
            $state = trim($this->scraper->getTextAfterString(',', $cityState));

            $nurseJob = new NurseJobs();

            $nurseJob->agency = 'Flexcare';
            $nurseJob->agency_id = $agency_id;
            $nurseJob->agency_url = $url;

            $nurseJob->city = $city;
            $nurseJob->state = $state;

            $nurseJob->save();

            $this->scraper->sleepRandomSeconds([5, 20]);

        }
    }
//    public function scrapeBrandFurniture() {
//
//
//        $this->scraper = new Scraper();
//        $craigslistHelpers = new CraigslistHelpers();
//        $savedUrlsFoundInSearch = [];
//
//        $city = 'houston';
//
//        $this->scraper->baseUrl = 'https://'.$city.'.craigslist.org';
//        $this->baseUrl = 'https://'.$city.'.craigslist.org';
//
//        $brand = Brand::where('section', '=', 1)->orderBy('last_search')->take(1)->get()->toArray()[0];
//
//        $brands = Brand::get()->toArray();
//
//        $allBrands = array_column($brands, 'name');
//        $newItemCount = 0;
//
//        $responseUrls = $this->getAllSearchLinks($brand['search_text']);
//
//
//        $item_urls = Item::where('brand_id', '=', $brand['id'])->get(['craigslist_url'])->toArray();
//        $alreadySavedItems = array_column($item_urls, 'craigslist_url');
//
//        Log::info('Already Saved Items: '.json_encode($alreadySavedItems));
//
//        foreach ($responseUrls as $url) {
//
//            $craigslistId = $this->scraper->reverseGetInBetween($url, '.html', '/');
//            $craigslistUrl = $this->scraper->getToEnd($url, '=/fuo/d', '.html');
//            $craigslistDBUrl = $this->scraper->getToEnd($url, 'https://houston.craigslist.org/fuo/d');
//
//            if (!in_array(trim($craigslistDBUrl), $alreadySavedItems)) {
//                $newItemCount++;
//
//                $secondsDelay = rand(4, 10);
//                sleep($secondsDelay);
//
//                $itemHtmlContent = $this->scraper->getCurl($url);
//
//                $manufacturer = $this->scraper->returnIfExistsElseBlank($itemHtmlContent, '<span>make / manufacturer: <b>', '</b>');
//
//                $brandCount = $this->scraper->getCountOfValuesExistingInText($itemHtmlContent, $allBrands);
//
//                $deletedByAuthor = $this->scraper->inString($itemHtmlContent, 'This posting has been deleted by its author');
//
//                //Already Saved Check
//                if ($brandCount < 3 && strtoupper($manufacturer) != strtoupper($brand['name']) && !$deletedByAuthor) {
//
//                    $header = $this->scraper->getInBetween($itemHtmlContent , '<span id="titletextonly">', "</span>");
//
//                    $xmlDoc = new \DOMDocument();
//                    libxml_use_internal_errors(true);
//                    $xmlDoc->loadHTML($itemHtmlContent);
//
//                    $desc = $xmlDoc->getElementById("postingbody");
//
//                    if (isset($desc->textContent)) {
//                        $description = trim(str_replace('QR Code Link to This Post', '', $desc->textContent));
//                    }
//                    else {
//                        echo "lkajsdflkjasfd";
//                    }
//
//                    libxml_use_internal_errors(false);
//
//                    $condition = $this->scraper->returnIfExistsElseBlank($itemHtmlContent, '<span>condition: <b>', '</b>');
//                    $manufacturer = $this->scraper->returnIfExistsElseBlank($itemHtmlContent, '<span>make / manufacturer: <b>', '</b>');
//                    $dimensions = $this->scraper->returnIfExistsElseBlank($itemHtmlContent, '<span>size / dimensions: <b>', '</b>');
//                    $modelName = $this->scraper->returnIfExistsElseBlank($itemHtmlContent, '<span>model name / number: : <b>', '</b>');
//
//                    $postedDateString = $this->scraper->getWithinTagOpenBased($itemHtmlContent, '<time');
//                    $postedDate = $datetime = date("Y-m-d H:i:s", strtotime(trim($postedDateString)));
//                    $price = (float) str_replace('$', '', $this->scraper->getWithinTagOpenBased($itemHtmlContent, '<span class="price"'));
//
//                    $imageListText = $this->scraper->getInBetween($itemHtmlContent, 'var imgList = ', ';');
//
//                    $imageSearchValues = [
//                        [
//                            'name'=>'full',
//                            'start'=>'"url":"',
//                            'end'=>'",'
//                        ],
//                        [
//                            'name'=>'thumb',
//                            'start'=>'"thumb":"',
//                            'end'=>'",'
//                        ]
//                    ];
//
//                    $imagesValues = $this->scraper->stripSetsOfValuesWhereSetItemsRelated($imageListText, $imageSearchValues);
//
//                    if (!Item::where('craigslist_url', '=', trim($craigslistUrl))->exists()) {
//                        $newItem = new Item();
//
//                        $newItem->section = 1;
//                        $newItem->brand_id = $brand['id'];
//                        $newItem->title = trim($header);
//                        $newItem->description = trim($description);
//                        $newItem->item_condition = trim($condition);
//                        $newItem->manufacturer = trim($manufacturer);
//                        $newItem->model_name_number = trim($modelName);
//                        $newItem->dimensions = trim($dimensions);
//                        $newItem->craigslist_id = $craigslistId;
//                        $newItem->craigslist_url = trim($craigslistUrl);
//                        $newItem->posted_date = $postedDate;
//                        $newItem->price = $price;
//
//
//                        $newItem->save();
//
//                        $alreadySavedItems[] = trim($craigslistUrl);
//
//                        foreach ($imagesValues as $image) {
//                            $newItemImage = new ItemImage();
//
//                            $newItemImage->item_id = $newItem->id;
//                            $newItemImage->thumb = $image['thumb'];
//                            $newItemImage->main = $image['full'];
//
//                            $newItemImage->save();
//                        }
//                    }
//                }
//            }
//            else {
//                $savedUrlsFoundInSearch[] = trim($craigslistDBUrl);
//            }
//        }
//
//        try {
//            Log::info('Saved URLs Found in Search'.json_encode($savedUrlsFoundInSearch));
//            foreach($alreadySavedItems as $savedItemUrl) {
//
//                if (!in_array($savedItemUrl, $savedUrlsFoundInSearch)) {
//                    $item = Item::where('craigslist_url', '=', $savedItemUrl)->first()->toArray();
//
//                    $item_images = ItemImage::where('item_id', '=', $item['id'])->get()->toArray();
//
//                    foreach($item_images as $item_image) {
//                        ArchiveItemImage::insert($item_image);
//                        ItemImage::destroy($item_image['id']);
//                    }
//                    ArchiveItem::insert($item);
//                    Item::destroy($item['id']);
//                }
//            }
//        } catch (\Exception $e) {
//            Log::emergency($e);
//        }
//
//        $brand = Brand::find($brand['id']);
//
//        $brand->last_search = $utility->currentDate();
//
//        $brand->save();
//
//        $cronJob->end($newItemCount);
//    }

//    public function getAllSearchLinks($searchText) {
//
//        $searchUrl = $this->baseUrl.'/search/'.$this->searchSection."?query=".$searchText;
//        $htmlContent = $this->scraper->getCurl($searchUrl);
//
//        Log::info('Links Response: '.$htmlContent);
//
//        $searchUrls = [];
//        $start = 120;
//
//        while (strpos($htmlContent, 'result-row') !== false) {
//
//            $responseUrls = $this->scraper->getLinksInClass($htmlContent, 'result-row');
//
//            $searchUrls = array_merge($searchUrls, $responseUrls);
//
//            $searchUrl = $this->baseUrl.'/search/'.$this->searchSection."?s=".$start."&query=".$searchText;
//            $htmlContent = $this->scraper->getCurl($searchUrl);
//
//            $start = $start + 120;
//        }
//        return $searchUrls;
//    }
}