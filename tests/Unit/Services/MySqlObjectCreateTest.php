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

        $jsonObject = '{"open":174.32,"close":174.87, "high":174.91,"low":173.4,"latestPrice":173.1,"latestVolume":27693778,"changePrice":-1.77,"changePercent":-0.01012,"avgTotalVolume":28272517,"marketCap":816214968000,"peRatio":14.58,"week52High":233.47,"week52Low":142,"ytdChange":0.10196269870997905}';

        $mysqlObjectCreate->createTableFromJson('stocks_book', $jsonObject);
        //$mysqlObjectCreate->createDbSaveStatement($jsonObject, 'newStockCompanyProfile', 'response');
    }
    public function testCreateObjectSaveFromJson() {
        $mysqlObjectCreate = new MySqlObjectCreate();

        $jsonObject = '{"open":174.32,"close":174.87, "high":174.91,"low":173.4,"latestPrice":173.1,"latestVolume":27693778,"changePrice":-1.77,"changePercent":-0.01012,"avgTotalVolume":28272517,"marketCap":816214968000,"peRatio":14.58,"week52High":233.47,"week52Low":142,"ytdChange":0.10196269870997905}';

        //$mysqlObjectCreate->createTableFromJson('stocks_', $jsonObject);
        $mysqlObjectCreate->createDbSaveStatement($jsonObject, 'newIexBook', 'response');
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
