<?php

namespace Tests\Unit\Services;

use Tests\TestCase;

use \App\Model\Exchange;
use \App\Services\BackTest;
use \App\Services\MySqlObjectCreate;

class MySqlObjectCreateTest extends TestCase
{

    public function testCreateTableFromJson() {
        $mysqlObjectCreate = new MySqlObjectCreate();

        $jsonObject = '{"date":"2019-01-10","open":152.5,"high":153.97,"low":150.86,"close":153.8,"volume":35780670,"unadjustedVolume":35780670,"change":0.49,"changePercent":0.32,"vwap":152.7021,"changeOverTime":1.1896417700505981}';

        $mysqlObjectCreate->createTableFromJson('stock_daily_rates_5y', $jsonObject);
        //$mysqlObjectCreate->createDbSaveStatement($jsonObject, 'newStockCompanyProfile', 'response');
    }
    public function testCreateObjectSaveFromJson() {
        $mysqlObjectCreate = new MySqlObjectCreate();

        $jsonObject = '{"date":"2019-01-10","open":152.5,"high":153.97,"low":150.86,"close":153.8,"volume":35780670,"unadjustedVolume":35780670,"change":0.49,"changePercent":0.32,"vwap":152.7021,"changeOverTime":1.1896417700505981}';

        //$mysqlObjectCreate->createTableFromJson('stocks_', $jsonObject);
        $mysqlObjectCreate->createDbSaveStatement($jsonObject, 'newIexDailyRate', 'rate');
    }

    public function testCreateIndexesFromJson() {
        $mysqlObjectCreate = new MySqlObjectCreate();

        $jsonObject = '{"reportDate":"2018-09-30","grossProfit":101839000000,"costOfRevenue":163756000000,"operatingRevenue":265595000000,"totalRevenue":265595000000,"operatingIncome":70898000000,"netIncome":59531000000,"researchAndDevelopment":14236000000,"operatingExpense":30941000000,"currentAssets":131339000000,"totalAssets":365725000000,"totalLiabilities":258578000000,"currentCash":25913000000,"currentDebt":20748000000,"totalCash":66301000000,"totalDebt":114483000000,"shareholderEquity":107147000000,"cashChange":5624000000,"cashFlow":77434000000,"operatingGainsLosses":null}';

        //$mysqlObjectCreate->createTableFromJson('stocks_', $jsonObject);
        $mysqlObjectCreate->createIndexes('stocks_financials_annual', 'sfa', $jsonObject);
    }

    public function testCreateTypeDecodeTable() {
        $mysqlObjectCreate = new MySqlObjectCreate();

        $mysqlObjectCreate->createDecodeTable('stocks_sector');
        //$mysqlObjectCreate->createDbSaveStatement($jsonObject, 'newStockFundamentalData', 'response');
    }

}
