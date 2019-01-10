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

        $jsonObject = '{"reportDate":"2017-12-31","grossProfit":33912000000,"costOfRevenue":54381000000,"operatingRevenue":88293000000,"totalRevenue":88293000000,"operatingIncome":26274000000,"netIncome":20065000000,"researchAndDevelopment":3407000000,"operatingExpense":7638000000,"currentAssets":143810000000,"totalAssets":406794000000,"totalLiabilities":266595000000,"currentCash":27491000000,"currentDebt":18478000000,"totalCash":77153000000,"totalDebt":122400000000,"shareholderEquity":140199000000,"cashChange":7202000000,"cashFlow":28293000000,"operatingGainsLosses":null}';

        $mysqlObjectCreate->createTableFromJson('stocks_financials_quarter', $jsonObject);
        //$mysqlObjectCreate->createDbSaveStatement($jsonObject, 'newStockCompanyProfile', 'response');
    }
    public function testCreateObjectSaveFromJson() {
        $mysqlObjectCreate = new MySqlObjectCreate();

        $jsonObject = '{"reportDate":"2018-09-30","grossProfit":101839000000,"costOfRevenue":163756000000,"operatingRevenue":265595000000,"totalRevenue":265595000000,"operatingIncome":70898000000,"netIncome":59531000000,"researchAndDevelopment":14236000000,"operatingExpense":30941000000,"currentAssets":131339000000,"totalAssets":365725000000,"totalLiabilities":258578000000,"currentCash":25913000000,"currentDebt":20748000000,"totalCash":66301000000,"totalDebt":114483000000,"shareholderEquity":107147000000,"cashChange":5624000000,"cashFlow":77434000000,"operatingGainsLosses":null}';

        //$mysqlObjectCreate->createTableFromJson('stocks_', $jsonObject);
        $mysqlObjectCreate->createDbSaveStatement($jsonObject, 'newStockFinnancialsAnnual', 'financial');
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
