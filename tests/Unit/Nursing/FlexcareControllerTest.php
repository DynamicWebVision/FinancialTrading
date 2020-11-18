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
                    'employee_region' => $row[2],
                    'base_salary' => $row[3],
                    'tbd_for_calculation' => $row[4],
                    'tgt_bonus_or_commission' => $row[5],
                    'ttp_for_calculation' => $row[6],
                    'total_target_pay' => $row[7],
                    'fds_for_calculation' => $row[8],
                    'percent_deluted_shares' => $row[9],
                    'company_founded' => $row[10],
                    'industry' => $row[11],
                    'dev_stage' => $row[12],
                    'capital_raised' => $row[13],
                    'revenue' => $row[14],
                    'head_count' => $row[15],
                ]
            );
        }


        $controller = new MedproController();
        $controller->loadFromFile();
    }
}
