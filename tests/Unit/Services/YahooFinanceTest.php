<?php

namespace Tests\Unit\Services;

use App\Services\Recruiters;
use App\Services\Scraper;
use Tests\TestCase;

use \App\Model\Exchange;
use \App\Model\Stocks\Stocks;
use \App\Services\BackTest;
use \App\Services\YahooFinance;
use \App\Http\Controllers\Equity\YahooFinanceController;
use \App\Http\Controllers\ProcessScheduleController;

class YahooFinanceTest extends TestCase
{

//    public function testHistoricalRates() {
//        $textMessage = new YahooFinance();
//        $textMessage->getHistoricalRates();
//    }
//
//    public function testHistoricalRatesWithDates() {
//        $textMessage = new YahooFinance();
//        $textMessage->symbol = 'XOM';
//        $textMessage->getHistoricalRates(['start_date'=>'2016-01-01','end_date'=>'2017-01-01',]);
//    }

    public function testProcessOneStock() {
        $yahooFinanceController = new YahooFinanceController();

        $yahooFinanceController->createRecentUpdateRecords();
    }

    public function __construct() {
        $this->curl = curl_init();
//        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , 'Authorization: Bearer 1d95d8dd88b59a1f7c53e7cb2886df89-3f40f99e79545ae6539aabd8b718cbb0' ));
        // curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer 1d95d8dd88b59a1f7c53e7cb2886df89-3f40f99e79545ae6539aabd8b718cbb0' ));
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.env('OANDA_AUTHORIZATION_TOKEN') ));
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
    }

    public function apiGetRequest() {

        curl_setopt($this->curl, CURLOPT_URL, $this->apiUrl);
        $resp = curl_exec($this->curl);

        return $resp;
    }

    public function testFaraday() {
        $this->curl = curl_init();
//        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , 'Authorization: Bearer 1d95d8dd88b59a1f7c53e7cb2886df89-3f40f99e79545ae6539aabd8b718cbb0' ));
        // curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer 1d95d8dd88b59a1f7c53e7cb2886df89-3f40f99e79545ae6539aabd8b718cbb0' ));
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('X-Cisco-Meraki-API-Key: 79cb810aa1d55a8e2110ee0249dbb5e1c566ce06'));
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($this->curl, CURLOPT_URL, 'https://n66.meraki.com/api/v0/organizations/930117/networks');

        //curl_setopt($this->curl, CURLOPT_URL, 'https://n66.meraki.com/api/v0/networks/N_600104650347200424/devices');
        //curl_setopt($this->curl, CURLOPT_URL, 'https://n66.meraki.com/api/v0/networks/N_600104650347199903');
        //curl_setopt($this->curl, CURLOPT_URL, 'https://n66.meraki.com/api/v0//networks/'+network_id+'/devices/claim');
       // curl_setopt($this->curl, CURLOPT_URL, 'https://n66.meraki.com/api/v0/organizations/930117/devices');

        $resp = json_decode(curl_exec($this->curl));

        $scraper = new \App\Services\Scraper();
        $links = $scraper->getAllLinksInText($resp);

        curl_setopt($this->curl, CURLOPT_URL, $links[0]);
        $resp = curl_exec($this->curl);

        return $resp;
    }

    public function testNetworkTraffic() {
        $this->curl = curl_init();
//        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , 'Authorization: Bearer 1d95d8dd88b59a1f7c53e7cb2886df89-3f40f99e79545ae6539aabd8b718cbb0' ));
        // curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer 1d95d8dd88b59a1f7c53e7cb2886df89-3f40f99e79545ae6539aabd8b718cbb0' ));
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('X-Cisco-Meraki-API-Key: 79cb810aa1d55a8e2110ee0249dbb5e1c566ce06'));
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);

        //curl_setopt($this->curl, CURLOPT_URL, 'https://n66.meraki.com/api/v0/networks/N_600104650347186811/clients/kf45539/usageHistory');
        //curl_setopt($this->curl, CURLOPT_URL, 'https://n66.meraki.com/api/v0/networks/N_600104650347186811/clients/kf45539/events');
        curl_setopt($this->curl, CURLOPT_URL, 'https://n66.meraki.com/api/v0/networks/N_600104650347186811/clients/kf45539/trafficHistory');

        $resp = curl_exec($this->curl);

        $scraper = new \App\Services\Scraper();
        $links = $scraper->getAllLinksInText($resp);

        curl_setopt($this->curl, CURLOPT_URL, $links[0]);
        $resp = curl_exec($this->curl);

        return $resp;
    }

    public function testNetworks() {
        $this->curl = curl_init();
//        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , 'Authorization: Bearer 1d95d8dd88b59a1f7c53e7cb2886df89-3f40f99e79545ae6539aabd8b718cbb0' ));
        // curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer 1d95d8dd88b59a1f7c53e7cb2886df89-3f40f99e79545ae6539aabd8b718cbb0' ));
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('X-Cisco-Meraki-API-Key: 79cb810aa1d55a8e2110ee0249dbb5e1c566ce06'));
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);

        //curl_setopt($this->curl, CURLOPT_URL, 'https://api.meraki.com/api/v0/organizations/930117/networks');
        curl_setopt($this->curl, CURLOPT_URL, 'https://n66.meraki.com/api/v0/organizations/930117/devices');
        $resp = curl_exec($this->curl);
        $decoded = json_decode($resp);

        $scraper = new \App\Services\Scraper();
        $links = $scraper->getAllLinksInText($resp);

        curl_setopt($this->curl, CURLOPT_URL, $links[0]);
        $resp = curl_exec($this->curl);

        return $resp;
    }

    public function testOrgDevices() {
        $this->curl = curl_init();
//        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , 'Authorization: Bearer 1d95d8dd88b59a1f7c53e7cb2886df89-3f40f99e79545ae6539aabd8b718cbb0' ));
        // curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer 1d95d8dd88b59a1f7c53e7cb2886df89-3f40f99e79545ae6539aabd8b718cbb0' ));
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('X-Cisco-Meraki-API-Key: 79cb810aa1d55a8e2110ee0249dbb5e1c566ce06'));
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($this->curl, CURLOPT_URL, 'https://n66.meraki.com/api/v0/organizations/930117/devices');
        $resp = curl_exec($this->curl);
        $decoded = json_decode($resp);

        $scraper = new \App\Services\Scraper();
        $links = $scraper->getAllLinksInText($resp);

        curl_setopt($this->curl, CURLOPT_URL, $links[0]);
        $resp = curl_exec($this->curl);

        return $resp;
    }

    public function testClients() {
        $this->curl = curl_init();
//        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , 'Authorization: Bearer 1d95d8dd88b59a1f7c53e7cb2886df89-3f40f99e79545ae6539aabd8b718cbb0' ));
        // curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer 1d95d8dd88b59a1f7c53e7cb2886df89-3f40f99e79545ae6539aabd8b718cbb0' ));
//        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('X-Cisco-Meraki-API-Key: 79cb810aa1d55a8e2110ee0249dbb5e1c566ce06'));
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
       // curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($this->curl, CURLOPT_URL, 'https://d16645b06c184c7b658c8e18cebbc41b:cf068e17357eb14d16ecd57094d9fc5a@showplacehq.myshopify.com/admin/api/2020-01/products.json');
        //GET /networks/{networkId}/groupPolicies
        //curl_setopt($this->curl, CURLOPT_URL, 'https://api.meraki.com/api/v0//networks/N_600104650347186811/devices');
        $resp = curl_exec($this->curl);

        $scraper = new \App\Services\Scraper();
        $links = $scraper->getAllLinksInText($resp);

        curl_setopt($this->curl, CURLOPT_URL, $links[0]);
        $resp = curl_exec($this->curl);

        return $resp;
    }

    public function testAdmins() {
        $this->curl = curl_init();
//        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , 'Authorization: Bearer 1d95d8dd88b59a1f7c53e7cb2886df89-3f40f99e79545ae6539aabd8b718cbb0' ));
        // curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer 1d95d8dd88b59a1f7c53e7cb2886df89-3f40f99e79545ae6539aabd8b718cbb0' ));
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('X-Cisco-Meraki-API-Key: 79cb810aa1d55a8e2110ee0249dbb5e1c566ce06'));
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);

        //curl_setopt($this->curl, CURLOPT_URL, 'https://n66.meraki.com/api/v0/networks/N_600104650347191416');
        curl_setopt($this->curl, CURLOPT_URL, 'https://n66.meraki.com/api/v0/networks');
        //abcdefg

        $test = curl_exec($this->curl);

        $resp = json_decode($test);

        $links = $scraper->getAllLinksInText($resp);

        curl_setopt($this->curl, CURLOPT_URL, $links[0]);
        $resp = curl_exec($this->curl);

        return $resp;
    }

    public function testSerialClients() {
        $this->curl = curl_init();
//        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , 'Authorization: Bearer 1d95d8dd88b59a1f7c53e7cb2886df89-3f40f99e79545ae6539aabd8b718cbb0' ));
        // curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer 1d95d8dd88b59a1f7c53e7cb2886df89-3f40f99e79545ae6539aabd8b718cbb0' ));
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('X-Cisco-Meraki-API-Key: 79cb810aa1d55a8e2110ee0249dbb5e1c566ce06'));
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);

        //curl_setopt($this->curl, CURLOPT_URL, 'https://n66.meraki.com/api/v0/devices/Q2PD-PKMN-AFKF/clients');
        curl_setopt($this->curl, CURLOPT_URL, 'https://n66.meraki.com/api/v0/networks/N_600104650347200189/ssids/0/splashSettings');
        $resp = json_decode(curl_exec($this->curl));


        $scraper = new \App\Services\Scraper();
        $links = $scraper->getAllLinksInText($resp);

        curl_setopt($this->curl, CURLOPT_URL, $links[0]);

        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('X-Cisco-Meraki-API-Key: 79cb810aa1d55a8e2110ee0249dbb5e1c566ce06'));
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        $resp = curl_exec($this->curl);
        $curlInfo = curl_getinfo($this->curl);

        return $resp;
    }

    public function testContentFilteringCategories() {
        $this->curl = curl_init();
//        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , 'Authorization: Bearer 1d95d8dd88b59a1f7c53e7cb2886df89-3f40f99e79545ae6539aabd8b718cbb0' ));
         curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer sk_e9f902a77f356767c9914cf48a93d101' ));

        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, 1);

//        curl_setopt($this->curl, CURLOPT_URL, 'https://n66.meraki.com/api/v0/networks/N_600104650347186811/traffic?timespan=1000000');
        curl_setopt($this->curl, CURLOPT_URL, 'https://person.clearbit.com/v1/people/email/brian.oneill.tx@gmail.com');
        $resp = json_decode(curl_exec($this->curl));

        $scraper = new \App\Services\Scraper();
        $links = $scraper->getAllLinksInText($resp);

        curl_setopt($this->curl, CURLOPT_URL, $links[0]);

        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('X-Cisco-Meraki-API-Key: 79cb810aa1d55a8e2110ee0249dbb5e1c566ce06'));
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        $resp = curl_exec($this->curl);
        $curlInfo = curl_getinfo($this->curl);

        return $resp;
    }

    public function testLinkNetworkToDevice() {
        $this->curl = curl_init();
//        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , 'Authorization: Bearer 1d95d8dd88b59a1f7c53e7cb2886df89-3f40f99e79545ae6539aabd8b718cbb0' ));
        // curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer 1d95d8dd88b59a1f7c53e7cb2886df89-3f40f99e79545ae6539aabd8b718cbb0' ));
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('X-Cisco-Meraki-API-Key: 79cb810aa1d55a8e2110ee0249dbb5e1c566ce06'));
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
        //--url 'https://api.meraki.com/api/v0/networks/networkId4/clients' \

        //curl_setopt($this->curl, CURLOPT_URL, 'https://api.meraki.com/api/v0/N_600104650347204349/clients');
        //curl_setopt($this->curl, CURLOPT_URL, 'https://api.meraki.com/api/v0/devices/Q2XD-KGFH-LLDF/clients');

        curl_setopt($this->curl, CURLOPT_URL, 'https://n66.meraki.com/api/v0/networks/N_600104650347194146/clients');

        //curl_setopt($this->curl, CURLOPT_URL, 'https://api.meraki.com/api/v0//networks//devices');
        //v0/networks/networkId4/clients

        $resp = json_decode(curl_exec($this->curl));

        $scraper = new \App\Services\Scraper();
        $links = $scraper->getAllLinksInText($resp);

        curl_setopt($this->curl, CURLOPT_URL, $links[0]);

        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('X-Cisco-Meraki-API-Key: 79cb810aa1d55a8e2110ee0249dbb5e1c566ce06'));
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        $resp = curl_exec($this->curl);
        $curlInfo = curl_getinfo($this->curl);

        return $resp;
    }

    public function testGetNetworkSsids() {
        $this->curl = curl_init();
//        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , 'Authorization: Bearer 1d95d8dd88b59a1f7c53e7cb2886df89-3f40f99e79545ae6539aabd8b718cbb0' ));
        // curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer 1d95d8dd88b59a1f7c53e7cb2886df89-3f40f99e79545ae6539aabd8b718cbb0' ));
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('X-Cisco-Meraki-API-Key: 79cb810aa1d55a8e2110ee0249dbb5e1c566ce06'));
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);

        //curl_setopt($this->curl, CURLOPT_URL, 'https://n66.meraki.com/api/v0/networks/N_600104650347186811/ssids');
        curl_setopt($this->curl, CURLOPT_URL, 'https://n66.meraki.com/api/v0/networks');
        //curl_setopt($this->curl, CURLOPT_URL, 'https://api.meraki.com/api/v0//networks/N_600104650347186811/devices');
        $resp = curl_exec($this->curl);
        $d = json_decode($resp);

        $scraper = new \App\Services\Scraper();
        $links = $scraper->getAllLinksInText($resp);

        curl_setopt($this->curl, CURLOPT_URL, $links[0]);

        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('X-Cisco-Meraki-API-Key: 79cb810aa1d55a8e2110ee0249dbb5e1c566ce06'));
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        $resp = curl_exec($this->curl);
        $curlInfo = curl_getinfo($this->curl);

        return $resp;
    }

    public function testABC() {
        $this->curl = curl_init();
//        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , 'Authorization: Bearer 1d95d8dd88b59a1f7c53e7cb2886df89-3f40f99e79545ae6539aabd8b718cbb0' ));
        // curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer 1d95d8dd88b59a1f7c53e7cb2886df89-3f40f99e79545ae6539aabd8b718cbb0' ));
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('X-Cisco-Meraki-API-Key: 79cb810aa1d55a8e2110ee0249dbb5e1c566ce06'));
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);

        //curl_setopt($this->curl, CURLOPT_URL, 'https://n66.meraki.com/api/v0/networks/N_600104650347186811/ssids');
        curl_setopt($this->curl, CURLOPT_URL, 'https://n66.meraki.com/api/v0/networks/N_600104650347194146/devices/Q2XD-3PRE-3RWC/performance');
        //curl_setopt($this->curl, CURLOPT_URL, 'https://api.meraki.com/api/v0//networks/N_600104650347186811/devices');
        $resp = curl_exec($this->curl);
        $d = json_decode($resp);

        $scraper = new \App\Services\Scraper();
        $links = $scraper->getAllLinksInText($resp);

        curl_setopt($this->curl, CURLOPT_URL, $links[0]);

        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('X-Cisco-Meraki-API-Key: 79cb810aa1d55a8e2110ee0249dbb5e1c566ce06'));
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        $resp = curl_exec($this->curl);
        $curlInfo = curl_getinfo($this->curl);

        return $resp;
    }

    public function testRecruiters() {
        $recruiters = new \App\Services\Recruiters();
        $links = $recruiters->sendTextMessage();
    }

}
