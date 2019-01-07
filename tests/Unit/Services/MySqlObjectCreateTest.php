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

        $jsonObject = '{
          "stockId": "564564654654564564564564",
          "high52": 89.3,
          "low52": 64.65,
          "dividendAmount": 0.82,
          "dividendYield": 4.78,
          "dividendDate": "2018-11-09 00:00:00.0",
          "peRatio": 17.58492,
          "pegRatio": 0.55006,
          "pbRatio": 1.58248,
          "prRatio": 1.15014,
          "pcfRatio": 8.08881,
          "grossMarginTTM": 33.23075,
          "grossMarginMRQ": 31.42599,
          "netProfitMarginTTM": 6.75115,
          "netProfitMarginMRQ": 8.68885,
          "operatingMarginTTM": 6.92793,
          "operatingMarginMRQ": 9.2496,
          "returnOnEquity": 9.27327,
          "returnOnAssets": 5.0229,
          "returnOnInvestment": 6.18682,
          "quickRatio": 0.53936,
          "currentRatio": 0.82672,
          "interestCoverage": 0,
          "totalDebtToCapital": 16.90266,
          "ltDebtToEquity": 10.83392,
          "totalDebtToEquity": 21.0317,
          "epsTTM": 4.04608,
          "epsChangePercentTTM": 31.96909,
          "epsChangeYear": 57.18005,
          "epsChange": 0,
          "revChangeYear": 0,
          "revChangeTTM": 23.39973,
          "revChangeIn": 3.82193,
          "sharesOutstanding": 4233.807,
          "marketCapFloat": 4229.401,
          "marketCap": 301235.4,
          "bookValuePerShare": 3.74467,
          "shortIntToFloat": 0,
          "shortIntDayToCover": 0,
          "divGrowthRate3Year": 0,
          "dividendPayAmount": 0.82,
          "dividendPayDate": "2018-06-11 00:00:00.0",
          "beta": 0.87175,
          "vol1DayAvg": 21.93932,
          "vol10DayAvg": 21939319,
          "vol3MonthAvg": 324.54222
    }';

        //$mysqlObjectCreate->createTableFromJson('stocks_fundamental', $jsonObject);
        $mysqlObjectCreate->createDbSaveStatement($jsonObject, 'newStockFundamentalData', 'response');
    }

}
