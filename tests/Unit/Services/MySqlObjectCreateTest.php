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

        $jsonObject = '{"symbol":"XOM","companyName":"Exxon Mobil Corporation","exchange":"New York Stock Exchange","industry":"Oil & Gas - Integrated","website":"http:\/\/www.exxonmobil.com","description":"Exxon Mobil Corp is an integrated oil and gas company. It is engaged in exploration for, and production of, crude oil and natural gas. It is also engaged in manufacturing, transportation and sale of crude oil, natural gas and petroleum products.","CEO":"Darren W. Woods","issueType":"cs","sector":"Energy"}';

        //$mysqlObjectCreate->createTableFromJson('stocks_', $jsonObject);
        $mysqlObjectCreate->createDbSaveStatement($jsonObject, 'newStockCompanyProfile', 'response');
    }

    public function testCreateTypeDecodeTable() {
        $mysqlObjectCreate = new MySqlObjectCreate();

        $mysqlObjectCreate->createDecodeTable('stocks_sector');
        //$mysqlObjectCreate->createDbSaveStatement($jsonObject, 'newStockFundamentalData', 'response');
    }

}
