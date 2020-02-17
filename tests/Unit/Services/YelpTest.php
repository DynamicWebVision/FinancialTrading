<?php

namespace Tests\Unit\Services;

use Tests\TestCase;

use \App\Model\Exchange;
use \App\Services\BackTest;
use \App\Services\Yelp;

use App\Model\Yelp\States;
use App\Model\Yelp\Cities;

class YelpTest extends TestCase
{

    public function testYelp() {
        $textMessage = new Yelp();
        $textMessage->getUrlList();
    }

    public function testYelpCities() {
        $usCityFile = '/Users/boneill/Downloads/uscities_abbr - uscities.csv';
        $file = fopen($usCityFile,"r");

        while (($data = fgetcsv($file)) !== FALSE) {
            if ($data[0] != "city") {
                $newCity = new Cities();

                $state = States::where('postal_code','=', $data[2])->first();

                $newCity->name = $data[0];
                $newCity->state_id = $state->id;
                $newCity->lat = $data[8];
                $newCity->lng = $data[9];
                $newCity->population = $data[10];
                $newCity->density = $data[11];
                $newCity->ranking = $data[16];
                $newCity->zips = $data[17];

                $newCity->save();
            }
        }

    }

    public function testLoadStates() {

        $statesCsv = '/Users/boneill/ReferenceWorkspaces/FinancialTrading/app/Tmp/states.csv';
        $file = fopen($statesCsv,"r");


        while (($data = fgetcsv($file)) !== FALSE) {
            if ($data[0] != "State") {

            }
            echo "email address " . $data[0];
        }
    }

}
