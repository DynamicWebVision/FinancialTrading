<?php

namespace Tests\Unit\OandaTests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\CurrencyIndicators;
use App\Model\HistoricalRates;
use App\Model\TmpTestRates;
use App\Http\Controllers\Nursing\FlexcareController;
use App\Http\Controllers\Nursing\MedproController;
use App\Broker\OandaV20;
use Illuminate\Support\Facades\DB;

class FlexcareControllerTest extends TestCase
{

    public $transactionController;
    public $oanda;

    public function testGet() {
        $controller = new FlexcareController();
        $controller->getAllLinks();
    }

    public function testTemp() {
        $controller = new FlexcareController();
        $controller->tempFlexcareUrls();
    }

    public function testMedproLoad() {

        $fileHandle = fopen("/Users/boneill/Downloads/cto_data.csv", "r");

        //Loop through the CSV rows.
        while (($row = fgetcsv($fileHandle, 0, ",")) !== FALSE) {
            //Dump out the row for the sake of clarity.
            DB::table('cto_stats')->insert(
                [
                    'company_title' => $row[0],
                    'founder_status' => $row[1],
                    'employee_region' => $row[3],
                    'base_salary' => $row[5],
                    'tbd_for_calculation' => $row[6],
                    'tgt_bonus_or_commission' => $row[7],
                    'ttp_for_calculation' => $row[6],
                    'total_target_pay' => $row[8],
                    'fds_for_calculation' => $row[10],
                    'percent_deluted_shares' => $row[11],
                    'company_founded' => $row[12],
                    'industry' => $row[13],
                    'dev_stage' => $row[14],
                    'capital_raised' => $row[16],
                    'revenue' => $row[17],
                    'head_count' => $row[18],
                ]
            );
        }


        $controller = new MedproController();
        $controller->loadFromFile();
    }
}
