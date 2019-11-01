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

        $jsonObject = '{"AGENT_PHOTO_NUM":null,"HASOPENHOUSE":null,"MLSNUM":"RFGC21-JV4EKB","SHOWADDRESSONLINE":1,"LISTINGID":1308583,"LISTSTATUS":"A","STREETNUM":null,"STREETNAME":"SE Intersection US 290 7D Road","LISTPRICE":4000000,"PROPTYPE":"Lots And Land","BEDROOM":0,"BATHHALF":0,"PHOTOPRIMARY":"https:\/\/pics.harstatic.com\/lr\/RFGC21\/JV4EKB\/1.jpg","BATHFULL":0,"ACRES":null,"AGENTLISTNAME":"robert sadler","AGENTLISTID":"RFGC21-b78c34cb-d446-4e42-935d-4a7f29565728","BLDGSQFT":null,"BLDGSQFTSRC":null,"KEYMAP":null,"LOTSIZE":387,"LOTSIZESRC":null,"STORIES":null,"SUBDIVISION":null,"YEARBUILT":null,"YEARBUILTSRC":null,"CITY":"Fort Stockton","ZIP":79735,"OFFICELISTNAME":"century 21 sadler & associates","OFFICELISTID":"RFGC21-216161-0001","PROPSUBTYPE":"Other","LONGITUDE":-102.802841187,"LATITUDE":30.683643341,"FULLSTREETADDRESS":"SE Intersection US 290 7D Road IH-10","LISTDATE":"2017-12-14 00:00:00","PROPERTY_CLASS_ID":3,"LASTREDUCED":null,"LISTPRICEORI":1935000,"REGION_ID":10,"LOTSIZEUNIT":"squar","IMPROVEMENT":null,"RESTRICTION":null,"LANDUSE":null,"DOH":324,"STATE":"TX","HARID":14195005,"SHOW_LOT":1,"LISTSTATUS_CLASS":"active","LISTSTATUS_TEXT":"For Sale","LISTPRICE_HTML":"4,000,000","LOTSIZE_FORMAT":387,"MLS_AREA":" ( in RFGC21 ) ","MLS_NUM":"JV4EKB","ADDRESSURL":"se-intersection-us-290-7d-road-ih-10"}';

        $mysqlObjectCreate->createTableFromJson('possible_rentals', $jsonObject, false);
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
