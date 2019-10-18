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

        $jsonObject = '{"ANSWERS":null,"AGENTKEY":"OBORTX-19_204","AGENTID":null,"MEMBER_NUMBER":null,"MLSORGID":31,"AGENTNAME":"Cindy Brown","FULLNAME":"Cindy Brown","AGENTEMAIL":"cindyvbrown@yahoo.com","AGENTPHONE":"(432) 352-3566","AGENTWEBSITE":null,"TOTAL":null,"AGENTPHOTO":"https:\/\/content.harstatic.com\/img\/common\/agentDefaultPhoto.jpg","LEADCALLPHONE":null,"ACCEPTPHONEFROM":null,"ACCEPTPHONETO":null,"HIDEMYLISTING":0,"BROKERCODE":"OBORTX-19","OFFICE_NUMBER":null,"OFFICE_NAME":"REALTY UNLIMITED","OFFICE_EMAIL":"fredna78@cableone.net","OFFICE_PHONE":"(432) 550-5609","OFFICE_WEBSITE":null,"OFFICE_PHOTO":"https:\/\/content.harstatic.com\/img\/common\/nologo.png","OFFICE_ADDRESS":"4060 Faudree Rd","OFFICEADDRESS2":"Odessa, TX, 79765","OFFICE_FULLADDRESS":"4060 Faudree Rd, Odessa, TX, 79765","OFFICE_NAME_NOQUOTE":"REALTY UNLIMITED","OFFICE_CITY":"Odessa","OFFICE_STATE":"TX","OFFICE_ZIP":79765,"REGIONID":10,"MLS_PREMIUM":null}';

        $mysqlObjectCreate->createTableFromJson('possible_rental_agents', $jsonObject, false);
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
