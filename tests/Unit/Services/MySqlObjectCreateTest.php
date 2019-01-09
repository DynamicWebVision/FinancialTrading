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

        $jsonObject = '{"reportDate":"2018-09-30","grossProfit":101839000000,"costOfRevenue":163756000000,"operatingRevenue":265595000000,"totalRevenue":265595000000,"operatingIncome":70898000000,"netIncome":59531000000,"researchAndDevelopment":14236000000,"operatingExpense":30941000000,"currentAssets":131339000000,"totalAssets":365725000000,"totalLiabilities":258578000000,"currentCash":25913000000,"currentDebt":20748000000,"totalCash":66301000000,"totalDebt":114483000000,"shareholderEquity":107147000000,"cashChange":5624000000,"cashFlow":77434000000,"operatingGainsLosses":null}';

        $mysqlObjectCreate->createTableFromJson('stocks_financials_annual', $jsonObject);
        //$mysqlObjectCreate->createDbSaveStatement($jsonObject, 'newStockCompanyProfile', 'response');
    }
    public function testCreateObjectSaveFromJson() {
        $mysqlObjectCreate = new MySqlObjectCreate();

        $jsonObject = '{"symbol":"XOM","companyName":"Exxon Mobil Corporation","exchange":"New York Stock Exchange","industry":"Oil & Gas - Integrated","website":"http:\/\/www.exxonmobil.com","description":"Exxon Mobil Corp is an integrated oil and gas company. It is engaged in exploration for, and production of, crude oil and natural gas. It is also engaged in manufacturing, transportation and sale of crude oil, natural gas and petroleum products.","CEO":"Darren W. Woods","issueType":"cs","sector":"Energy"}';

        //$mysqlObjectCreate->createTableFromJson('stocks_', $jsonObject);
        $mysqlObjectCreate->createDbSaveStatement($jsonObject, 'newStockCompanyProfile', 'response');
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
