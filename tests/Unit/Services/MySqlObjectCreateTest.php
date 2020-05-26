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

        $mysqlObjectCreate->createTableFromJson('validated_emails', '{"email":"catering@sushieatstation.com","did_you_mean":"","user":"catering","domain":"sushieatstation.com","format_valid":true,"mx_found":true,"smtp_check":true,"catch_all":null,"role":false,"disposable":false,"free":false,"score":0.96}', false);

    }
    public function testCreateObjectSaveFromJson() {
        $mysqlObjectCreate = new MySqlObjectCreate();

        $jsonObject = '{"open":174.32,"close":174.87, "high":174.91,"low":173.4,"latestPrice":173.1,"latestVolume":27693778,"changePrice":-1.77,"changePercent":-0.01012,"avgTotalVolume":28272517,"marketCap":816214968000,"peRatio":14.58,"week52High":233.47,"week52Low":142,"ytdChange":0.10196269870997905}';

        //$mysqlObjectCreate->createTableFromJson('stocks_', $jsonObject);
        $mysqlObjectCreate->createDbSaveStatement($jsonObject, 'newIexBook', 'response');
    }

    public function testCreateObjectSaveFromJsonSpaces() {
        $mysqlObjectCreate = new MySqlObjectCreate();

        $jsonObject = '{
            "date" : "2019-09-28",
            "Revenue" : "260174000000.0",
            "Revenue Growth" : "-0.0204",
            "Cost of Revenue" : "161782000000.0",
            "Gross Profit" : "98392000000.0",
            "R&D Expenses" : "16217000000.0",
            "SG&A Expense" : "18245000000.0",
            "Operating Expenses" : "34462000000.0",
            "Operating Income" : "63930000000.0",
            "Interest Expense" : "0.0",
            "Earnings before Tax" : "65737000000.0",
            "Income Tax Expense" : "10481000000.0",
            "Net Income - Non-Controlling int" : "0.0",
            "Net Income - Discontinued ops" : "0.0",
            "Net Income" : "55256000000.0",
            "Preferred Dividends" : "0.0",
            "Net Income Com" : "55256000000.0",
            "EPS" : "11.97",
            "EPS Diluted" : "11.89",
            "Weighted Average Shs Out" : "4519180000.0",
            "Weighted Average Shs Out (Dil)" : "4617834000.0",
            "Dividend per Share" : "3.0",
            "Gross Margin" : "0.3782",
            "EBITDA Margin" : "0.301",
            "EBIT Margin" : "0.2527",
            "Profit Margin" : "0.212",
            "Free Cash Flow margin" : "0.2264",
            "EBITDA" : "78284000000.0",
            "EBIT" : "65737000000.0",
            "Consolidated Income" : "55256000000.0",
            "Earnings Before Tax Margin" : "0.2527",
            "Net Profit Margin" : "0.2124"
          }';

        //$mysqlObjectCreate->createTableFromJson('stocks_', $jsonObject);
        $mysqlObjectCreate->createDbSaveStatementJsonSpaces($jsonObject, 'newIncomeStatement', 'incomeStatement');
    }

    public function testCreateIndexesFromJson() {
        $mysqlObjectCreate = new MySqlObjectCreate();

        $jsonObject = '{"open":174.32,"close":174.87, "high":174.91,"low":173.4,"latestPrice":173.1,"latestVolume":27693778,"changePrice":-1.77,"changePercent":-0.01012,"avgTotalVolume":28272517,"marketCap":816214968000,"peRatio":14.58,"week52High":233.47,"week52Low":142,"ytdChange":0.10196269870997905}';

        //$mysqlObjectCreate->createTableFromJson('stocks_', $jsonObject);
        $mysqlObjectCreate->createIndexes('stocks_book', 'sbk', $jsonObject);
    }

    public function testCreateTypeDecodeTable() {
        $mysqlObjectCreate = new MySqlObjectCreate();

        $mysqlObjectCreate->createDecodeTable('stocks_sector');
        //$mysqlObjectCreate->createDbSaveStatement($jsonObject, 'newStockFundamentalData', 'response');
    }

}
