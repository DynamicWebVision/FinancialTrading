<?php

namespace Tests\Unit\Services;

use App\Services\Scraper;
use Tests\TestCase;

use \App\Model\Exchange;
use \App\Services\BackTest;
use \App\Services\Yelp;
use \App\Services\ReColorado;
use \App\Model\ReColoradoListing;

use App\Model\Yelp\States;
use App\Model\Yelp\Cities;
use DB;

class YelpTest extends TestCase
{

//    public function testYelp() {
//        $textMessage = new Yelp();
//        $textMessage->getUrlList();
//    }
//
//    public function testYelpCities() {
//        $usCityFile = '/Users/boneill/Downloads/uscities_abbr - uscities.csv';
//        $file = fopen($usCityFile,"r");
//
//        while (($data = fgetcsv($file)) !== FALSE) {
//            if ($data[0] != "city") {
//                $newCity = new Cities();
//
//                $state = States::where('postal_code','=', $data[2])->first();
//
//                $newCity->name = $data[0];
//                $newCity->state_id = $state->id;
//                $newCity->lat = $data[8];
//                $newCity->lng = $data[9];
//                $newCity->population = $data[10];
//                $newCity->density = $data[11];
//                $newCity->ranking = $data[16];
//                $newCity->zips = $data[17];
//
//                $newCity->save();
//            }
//        }
//
//    }
//
//    public function testLoadStates() {
//
//        $statesCsv = '/Users/boneill/ReferenceWorkspaces/FinancialTrading/app/Tmp/states.csv';
//        $file = fopen($statesCsv,"r");
//
//
//        while (($data = fgetcsv($file)) !== FALSE) {
//            if ($data[0] != "State") {
//
//            }
//            echo "email address " . $data[0];
//        }
//    }
//
//    public function testZipImages() {
//
//
//        $images = ['https://showplacehq.s3-us-west-1.amazonaws.com/Gc4Lo7ERi9jyyf8uYLLR8Nig',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/hngZHRWvj5WjBXtJQFcgocWu',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/uAYrR6jYZ2PWKXm4gCicw2EJ',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/nbjzQ53f4CWTvab4tKT7BRbS',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/pfiyjL2amuJXGwHavUa7j1L4',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/CSXyfaks1vTmff6PrQ9HpGNh',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/2t2AqcCukkTER6c3X5fzBX6G',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/aeYXr65Lq1Xhh8tyaVBSmt5C',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/mBvasyHwjwHVeeo6BQRRteVo',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/FzoxcEVYgLaPFWkRY6FAZA9j',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/5GRvy13E6XhG5tFmaFJY2DQ3',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/mCLF64rp2YVJuRJV5p5Q6vXh',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/KE3apGhTwiygRuw86nUoMsLW',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/ERDjiQScLv3BhyrrFHxvSLD7',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/fbKw9PmwVLTpP7BZiSBKR2h3',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/6Pd6S4BexgDqvFNdby6aTWQK',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/2PeZ3R4eDwqrXn3u2H6hZimT',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/Gw6jBdVZDvw92AcnepEnBdsL',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/pYuoFH7j1no4aK6QPHeqrMdJ',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/KoiWDdALZ8taKA5fSqncpyct',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/ZnVy65K7jDZ2uWvoqFCSaqb9',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/2R6iKkiUU96CcHJuwDeNEo9w',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/3M3Mmtc9Mx5w9xYzEfPnrSU5',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/1iyX2T9vj621LYkmDz3SSPxc',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/t8LCfRqBFKgrTLPoFP6PrWqx',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/9iS8pqWJZXE55gfVT8dR6GzK',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/k3AApHkhTs3ACRyNfeHAuVcA',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/Du9m2nxa3g2kb8JpuRMiJkHh',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/h9s6DVqymbnjc3aGjuyVPnn2',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/nyuoSXLqHu4jGcHCJ2EotnT2',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/hHDzJTHph6D2vTgy5wDGcbym',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/Ri6XhUc1UfSuD3gHnV4JxLXM',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/3ENmfHRvT6NnFXybahdzTj55',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/2EF61Gx114vUd64o2yjBbMn5',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/YArZbYuG5xPv18Av9Fkte9HF',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/7Z6XvhWyXBsTGJmbCdxXGaCb',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/tEreZyVxpBojo4s6j1cK1kVu',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/JXFz7be4HMNqxQMpWGbQALzZ',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/cSGUHYqwPPoc1Lbb88brNoR6',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/UzfBZGvBoMK4pFFhs8iyS1Ry',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/cVUC2nakjDDWxdf1mFY4ytg3',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/qsH8pFHVZHRFH6DZmHGKooAG',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/mAjFMrUSbAq6XW9B9kQc4VoN',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/zSiyVrz9Cbr6qBfjHAdE3fKy',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/1B9EFvn6YrsdqqV5SosCMHpo',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/UjLNqbrqXAdJ7De8eV9m2hE9',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/yyFjxrNWzX6AwjoSBbbWT2HH',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/TJtVKJeFrsox3sKf6qPjQPdf',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/aPa6pfBS9B3R2hr89BzK26fd',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/CDoWm5gxxMtyMhqUXSCz3Qvu',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/M4XWAvEwCqVGULWgJmFCcGqb',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/5REtEaHw6STM5bHyRHLNDhYV',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/B6NBNFDiykMZm8WddNjP3TKT',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/xKCoPiYu5Sf2P693BwrB1eCN',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/5PfE5xVkEUDSHHneWP163sHa',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/HWQFhfh388H5D6LLZivXTHUJ',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/QBodX5jmhEvR1ThWWof2bnGC',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/omMjAZFfqeo4jV8ijHGic4sq',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/qV1ya1jxKBQtBctGj3Za1QQt',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/GLWrqa5UHHy37ECt5LdNM7S2',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/kCqKFnuuBxufSgcUwrRetWM6',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/kdJLZPXahea7jCX5zyqA2s6G',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/oSkzp2qxmopxNHqBioqxYjj4',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/4dvRCAaxhruYsaq8PofHaP4N',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/Gd5uEGdu6DCcv9j6HnWfQBnY',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/QSTNPgsH3UPNg9Q5VTAUsUgp',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/GdX1Mj11R33qgWKgrqYFNJfe',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/cH9TGU9cHNaranNz3xY3MqiW',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/sVksQUccszK2atWsydkgYZKX',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/8x4KRRVPxU2f2Sma1BU3AXnF',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/DwppRRuHNfb2CmgrNZbDq5tn',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/Yy287317YBKwidcUrdgnkTGz',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/axcW7kNP7qpfXQgG7eubuubu',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/atqzzfwcfYT8egC3GuUQrWBL',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/2d5V59tQyEgz2G6xW5kmgh9b',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/YgLbeSGW6hfX5DQXUDhHffWw',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/8GZv2JvydhPVyLJok3zEVXXj',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/Xye68dW4BzD1eX8ozW7xjisG',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/U7ZJv69asKtn1JPYUurAAVjP',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/MhAWVDK4AK8nFT45avhfs64D',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/LYQ4xaJSv7cqCkBVVLt34Ay2',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/BJwYLQMZD7nUwXCpqQty5xJw',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/by2joKSJyAij5rWpttNZB322',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/gaSfQzUE6GWqtTaXofAo6ruB',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/e8gBiQP29LNHqqP9RKRMfYJC',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/z7W2hpmNC2WUhQHFhA5mUnAq',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/ptEsqueeXCma9pp4N87xcLGh',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/cWdF54tAUrMoGQDWDM3y5J8d',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/fyr1RnSmKPFdVarpSkqaEFRZ',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/GjXEqRq7fxKfESKiHg5NWyWy',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/xEfr1DdGmB8tS7bK4n6bqZcQ',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/LhsBvGmWTowJ6daV1GPXXwLD',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/ALybZLQmBzPQ22Bae7mMPXDi',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/u3QLJr1WiXnE35FRtyL6gToj',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/ue24MQZpSHGyUbwtH32gU9eW',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/Nt2GTe35kkXZMS3XgCYMXL6C',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/7F2GKxLynp4kL725PuidLrKx',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/JxX8w6ZAon8vhg99pBymvCri',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/gvi1vhz3bCiXSfxfEBqsDcdE',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/zpgdeAsQiRnS9rvHyCsEqtQr',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/mWaMC5H5EGzarqQVwF1Mw14t',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/8JPgJYAR5idg9iWE4opzQdCi',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/8L63aHdcva2FGHYwcLvpN3Wh',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/uMx64DSZQuiDmVP3GTvednur',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/1e6dQ2nguWXvETXYERFYtmqf',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/PGkaig638tKeHHzJyYVRe5Kb',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/oxtEEKsNT1VkGzGC8aUvs9HD',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/erTsSJedSwZdfwiBP3uaWFJ3',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/YEHArMyjkZUn2y8m5SEJDRpu',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/2vnvpeo9qi1fMRM4eRbuTM96',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/EH8i6aM9yJo2P8vRD6BL2FVE',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/cj2wckoT69MXrCcWVXhjm9JX',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/76dknoJf5NCmpL8tbiiBRsif',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/DGzUtZdPrUGu6GgZcierGJv9',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/opEELBXuZhrzFaL7MBoF9zCA',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/2FSDAMjEefLDXCdFWdiFesQh',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/cxSvSr6LKcpeKVTHz1FoWN5c',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/GTdBzzHg2QPkCSDJ3DHXg2Xb',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/8Qci7EdxyKDwyrambh4qLiBM',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/PWqxj3ab75At21eVoBpauAGT',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/3ronDoLSJajWD9HzuiUjFF6v',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/fUmjopTUyizPChAxM8yrkUrb',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/FFanAFA6XgWHN2XZ4K2KniJg',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/67frqGZKAKZff3NEtvT5eGfg',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/sGQi66i7CLwh5yatXc16KQ5f',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/RdUhcvZ3g9gS5m7QqAXuUZ4Z',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/ZvKdccwx37a2M2YBQgbR6x8Q',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/XNh2ok7JDunCtTzrJtu68JyC',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/1zsXijc9ycTeMPCPSBN8vKX3',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/15oB2Zm53fbScmkEJ8beg3CC',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/p2J3dA8oTTmRPaGehmdQZmCw',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/4rx2VZrrmr2GBkMf1iBykBPQ',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/oceyyWjE1HTTdDwgtQfMnxwm',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/Ug5RJqYuE1qmkoMjLS9MvLDn',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/LjjuuiqUn1S2gyuvctvAvHZS',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/A7DVXvuoVWG9oyjAAJLgG9NH',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/qhTk9pvDTkUsU5tbfv47tHTT',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/7uuSJhXqAMfcKcqfdrLKmxvC',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/KSxwGJgEiEucUfRSYdAgTrqp',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/4NakYty1oQQeCVeEyaG6bsCQ',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/8toraV2CUrQv3hnLrQsYQqHs',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/obQFvrVmYV36zAdU8kHczSYp',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/b3S3hAsKtpSjM7XkkWdx2Y9Q',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/QidBqAFaLByxTHU3BmrkLgK1',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/RkBNiw5u6EUECKzJLuAxLwJV',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/E8nGNbVWJpLxiYdwmeKHSPPE',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/D5YtPySWkDkFYTqmNxECbVLr',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/gWtuSDxjAz1NJ8YdM8B72RBz',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/YLoVFPdoKrJC2rLVdmYxmTcX',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/2wNkUM8NHF7fkpaxx4Hf2BPY',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/zzFq3PcAMa9aCwi6oxpa1pDx',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/MErL87gGd6HcSgsFBRuwZfg2',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/spD2Z6CHV2WayCznG5gYpVux',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/tumg7BA45a8da59nvV4HasHB',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/4BuTSzCQQ1DLo2hLf8PG5aPY',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/z6dSKx8JV5p7XaaXq11Nn8po',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/sQCGFb1ye9pzw6d9Dg5szJnz',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/1fQBuDwF14u2qVvwDtRnMFBH',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/Se9k77XygWPCkdARiTfx9ZuT',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/vPShb5udM921YwSqSJKF3Etf',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/GbFqLZsGnH9saEUbJGuJwzww',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/D8cQfPQ8CMobvJTDx58mj7sP',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/hGZNpT6YX62Qemnc6v5jE1yQ',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/ZxgxVVVMJ3QMoscZn5vw2Fym',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/zZ3nZyXKLRyMn71dWGVrmdfn',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/Gq8CgHFB1uZtbRtfB6RvCPAm',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/ME67McYayw8ky2sVaiVySWPd',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/gTrfJ1e9s7TMKiZH4bWqvGPz',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/pk1kiuXBZiFvwHPmkaLJFMjW',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/t1i6Fvd2okBoXaAUwZPry35T',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/wXw1AZjtVh9w67z5ibweSNXv',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/Xyrpq2tderWDWqkrA4LD2Xbd',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/cpty9LexZHajakCBnTV7Atwb',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/RcyLPfzA7VEhWudsgp9DEmW4',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/pVwA9P2RJofSJVvmQQc7CRXL',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/WdChe4g7PMQP78MqxLRKPVNK',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/WhqGZ4xx9CWAWFWcfkKuhRxr',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/u5v9kz7Brc7LrPpeDf2HNzRT',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/89GuQqJLwfmP9yEi4PZg5KF5',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/prCoQ9Bq7AathgUC5WeTaDH8',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/eSimRMGoSxjk6G64qbr3ZNDt',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/NygvsvAHG9vGcMJHcMBVb8nG',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/9dKAfJwRsiyKpAQLyqjiAySc',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/8nqTf2Xb8YWNnGtxwQppLpDw',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/HCdMrfoTfutdMxm9aMtsVybd',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/A7kfucy7K44mjfd8FQysXuHA',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/Cuy8ymuH62cgPQ9KD3eY7rBz',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/bpKfi7hCPwRaAjcnYTdLpNzS',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/ZagwpXtDwxcWtDevoXUr1CCb',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/oH3mwtBJH3JB1FD6F7JJF4L9',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/sxNmoMdu9RAg2wxAF1ZHjwA5',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/2JUAoP44MSLy38Btu6YKAmG7',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/9QyPepzp91k4cvRkLtzQXfB1',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/8javU7HDRb8K1FBk3w1hiLcD',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/3RR8LtXTa9WGWWdebqfZFcoP',
//            'https://showplacehq.s3-us-west-1.amazonaws.com/rownEPdqzFF3vsVEbfd55iUE'];
//
//        foreach ($images as $image) {
//            $image_link = $image;//Direct link to image
//            $split_image = pathinfo($image_link);
//
//            $ch = curl_init();
//            curl_setopt($ch, CURLOPT_URL , $image_link);
//            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13");
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//            $response= curl_exec ($ch);
//            curl_close($ch);
//
//            $file_name = "/Users/boneill/Documents/Showplace/temp/real_snacks_deliverables/real_snacks_host_deliverable_".$split_image['filename'];
//
//            $file = fopen($file_name , 'w') or die("X_x");
//
//            fwrite($file, $response);
//
//            fclose($file);
//        }
//
//
//    }

//    public function testReColorado() {
//        $reColorado = new ReColorado();
//
//        $reColorado->initialSearch();
//    }

//    public function testPopulateAllListings() {
//        $listings = ReColoradoListing::all();
//
//        $reColorado = new ReColorado();
//
//        foreach ($listings as $listing) {
//            $reColorado->fetchOneListing($listing->id);
//            $reColorado->listingId = $listing->id;
//            $reColorado->checkPriceHistory();
//        }
//
//    }

    public function testFonts() {
        $colors = ['gold', 'blue', 'orange', 'fuschia', 'green', 'yellow', 'neutral'];

        $text = '';


        foreach ($colors as $color) {
            $counter = 1;

            while ($counter < 10) {
                $text .= '.font-color-'.$color.'-'.$counter.' {<BR>';
                $text .= 'color: $'.$color.'-'.$counter.';<BR>';
                $text .= '}<BR>';

                $counter = $counter + 1;
            }
            $text.= '<BR><BR>';
        }
        $asdf=1;

    }

//    public function testScraper() {
//        $test = '{"listing":{"id":44250006,"city":"Pittsburgh","thumbnail_url":"https://a0.muscache.com/im/pictures/b76dca74-839c-4ace-8012-abc1a649e461.jpg?aki_policy=small","medium_url":"https://a0.muscache.com/im/pictures/b76dca74-839c-4ace-8012-abc1a649e461.jpg?aki_policy=medium","user_id":7556262,"picture_url":"https://a0.muscache.com/im/pictures/b76dca74-839c-4ace-8012-abc1a649e461.jpg?aki_policy=large","xl_picture_url":"https://a0.muscache.com/im/pictures/b76dca74-839c-4ace-8012-abc1a649e461.jpg?aki_policy=x_large","price":69,"native_currency":"USD","price_native":69,"price_formatted":"$69","lat":40.45566,"lng":-80.00106,"country":"United States","name":"Pro Cleaning★King Bed★Free Parking★Great Location","smart_location":"Pittsburgh, PA","has_double_blind_reviews":false,"instant_bookable":false,"bathrooms":1.0,"bedrooms":2,"beds":2,"market":"Pittsburgh","min_nights":3,"neighborhood":"East Allegheny","person_capacity":5,"state":"PA","zipcode":"15212","user":{"user":{"id":7556262,"first_name":"Chad","picture_url":"https://a0.muscache.com/im/pictures/user/4b15b39a-9cdc-4f74-9450-6f348ae16543.jpg?aki_policy=profile_x_medium","thumbnail_url":"https://a0.muscache.com/im/pictures/user/4b15b39a-9cdc-4f74-9450-6f348ae16543.jpg?aki_policy=profile_small","has_profile_pic":true,"created_at":"2013-07-17T23:43:11Z","reviewee_count":668,"recommendation_count":0,"last_name":"","thumbnail_medium_url":"https://a0.muscache.com/im/pictures/user/4b15b39a-9cdc-4f74-9450-6f348ae16543.jpg?aki_policy=profile_medium","picture_large_url":"https://a0.muscache.com/im/pictures/user/4b15b39a-9cdc-4f74-9450-6f348ae16543.jpg?aki_policy=profile_large","response_time":"within an hour","response_rate":"100%","acceptance_rate":"98%","wishlists_count":15,"publicly_visible_wishlists_count":1,"is_superhost":true}},"hosts":[{"id":347007598,"first_name":"Stephanie","picture_url":"https://a0.muscache.com/im/pictures/user/d88963d5-f8d3-4345-8a51-4de45c175967.jpg?aki_policy=profile_x_medium","thumbnail_url":"https://a0.muscache.com/im/pictures/user/d88963d5-f8d3-4345-8a51-4de45c175967.jpg?aki_policy=profile_small","has_profile_pic":true},{"id":7556262,"first_name":"Chad","picture_url":"https://a0.muscache.com/im/pictures/user/4b15b39a-9cdc-4f74-9450-6f348ae16543.jpg?aki_policy=profile_x_medium","thumbnail_url":"https://a0.muscache.com/im/pictures/user/4b15b39a-9cdc-4f74-9450-6f348ae16543.jpg?aki_policy=profile_small","has_profile_pic":true}],"primary_host":{"id":7556262,"first_name":"Chad","picture_url":"https://a0.muscache.com/im/pictures/user/4b15b39a-9cdc-4f74-9450-6f348ae16543.jpg?aki_policy=profile_x_medium","thumbnail_url":"https://a0.muscache.com/im/pictures/user/4b15b39a-9cdc-4f74-9450-6f348ae16543.jpg?aki_policy=profile_small","has_profile_pic":true,"created_at":"2013-07-17T23:43:11Z","reviewee_count":668,"recommendation_count":0,"last_name":"","thumbnail_medium_url":"https://a0.muscache.com/im/pictures/user/4b15b39a-9cdc-4f74-9450-6f348ae16543.jpg?aki_policy=profile_medium","picture_large_url":"https://a0.muscache.com/im/pictures/user/4b15b39a-9cdc-4f74-9450-6f348ae16543.jpg?aki_policy=profile_large","response_time":"within an hour","response_rate":"100%","acceptance_rate":"98%","wishlists_count":15,"publicly_visible_wishlists_count":1,"is_superhost":true},"address":"East Allegheny, Pittsburgh, PA 15212, United States","country_code":"US","cancellation_policy":"moderate","property_type":"Apartment","reviews_count":27,"room_type":"Entire home/apt","room_type_category":"entire_home","bathroom_type":"private","picture_urls":["https://a0.muscache.com/im/pictures/b76dca74-839c-4ace-8012-abc1a649e461.jpg?aki_policy=large","https://a0.muscache.com/im/pictures/ffc925b4-e80f-4668-9254-df55115b441f.jpg?aki_policy=large","https://a0.muscache.com/im/pictures/fc12d34d-9559-4a46-bedd-1fa5dd8b2832.jpg?aki_policy=large","https://a0.muscache.com/im/pictures/aaffb434-fb6a-48e0-9d2e-005bc86b8e44.jpg?aki_policy=large","https://a0.muscache.com/im/pictures/7931eee0-8496-4a6d-a3b1-e741b7b155d2.jpg?aki_policy=large","https://a0.muscache.com/im/pictures/ef573429-6325-472e-b6c4-77658dc3a6b5.jpg?aki_policy=large","https://a0.muscache.com/im/pictures/b87521cd-c77c-4c59-8567-54215b74c1ba.jpg?aki_policy=large","https://a0.muscache.com/im/pictures/3b6e94bd-5dcf-407b-950f-741c066f8574.jpg?aki_policy=large","https://a0.muscache.com/im/pictures/d218805e-165b-47e5-a2c4-5d9be9406495.jpg?aki_policy=large","https://a0.muscache.com/im/pictures/73c94bd8-8740-42b1-ab99-6551ed5389b1.jpg?aki_policy=large","https://a0.muscache.com/im/pictures/3010441a-4df4-4489-a6e0-9532929998eb.jpg?aki_policy=large","https://a0.muscache.com/im/pictures/3182f1ba-dadb-41d9-8d12-060290dcc23c.jpg?aki_policy=large","https://a0.muscache.com/im/pictures/b472a43f-61e2-4f08-9f8a-142fd5dec2c6.jpg?aki_policy=large","https://a0.muscache.com/im/pictures/9887736e-ba56-4ccb-9f74-dee25b33f02f.jpg?aki_policy=large","https://a0.muscache.com/im/pictures/812b795a-b650-4f9e-b106-19908f805370.jpg?aki_policy=large","https://a0.muscache.com/im/pictures/00522f53-d975-4350-a38d-8f15ff59b7f6.jpg?aki_policy=large","https://a0.muscache.com/im/pictures/5499379e-4bf7-4c93-a2ee-2b25697f20eb.jpg?aki_policy=large","https://a0.muscache.com/im/pictures/ea5bc2e1-7cde-46c2-8153-89188e5a44c0.jpg?aki_policy=large","https://a0.muscache.com/im/pictures/3fbec0ab-394f-4afd-83c1-3e60601dd512.jpg?aki_policy=large","https://a0.muscache.com/im/pictures/9f52466e-91de-42fe-9989-2d7c617db207.jpg?aki_policy=large","https://a0.muscache.com/im/pictures/e0bd5b47-6ae3-4e01-b1c7-3173f7dce1a8.jpg?aki_policy=large","https://a0.muscache.com/im/pictures/bbb723b3-f4df-45ab-8bee-023216aa6799.jpg?aki_policy=large","https://a0.muscache.com/im/pictures/4a06e010-6f9d-4199-b274-a218422027be.jpg?aki_policy=large","https://a0.muscache.com/im/pictures/06062c85-6517-45f1-87e9-55c48e7c838e.jpg?aki_policy=large","https://a0.muscache.com/im/pictures/46d05d41-9d39-42a6-a0dd-6b5ee30fe7f2.jpg?aki_policy=large","https://a0.muscache.com/im/pictures/af78dd44-237e-48bd-8205-3d00744e20bf.jpg?aki_policy=large","https://a0.muscache.com/im/pictures/8d6a89a6-fb76-42d5-b204-8c10a6d80dc2.jpg?aki_policy=large","https://a0.muscache.com/im/pictures/8888d4d1-ccc5-4261-abbf-95aafe7cee45.jpg?aki_policy=large","https://a0.muscache.com/im/pictures/056a0681-df41-461e-a6d7-d4860c6f4091.jpg?aki_policy=large","https://a0.muscache.com/im/pictures/26ac81ed-09ba-4736-87a5-09cc9d3910d9.jpg?aki_policy=large","https://a0.muscache.com/im/pictures/14c59825-cb11-464a-92d9-00b90f086d7f.jpg?aki_policy=large","https://a0.muscache.com/im/pictures/9ad6cd45-5f85-4f9e-83c6-f7d60699f660.jpg?aki_policy=large","https://a0.muscache.com/im/pictures/22ee63f8-d283-42dd-b516-70f6c0465bb1.jpg?aki_policy=large","https://a0.muscache.com/im/pictures/ce5f8864-05d3-4d9b-8874-c5a5d192bb02.jpg?aki_policy=large","https://a0.muscache.com/im/pictures/a68e2e40-1d97-46ca-83f3-dc3c2dc48865.jpg?aki_policy=large","https://a0.muscache.com/im/pictures/2e36d080-4730-4f6b-8138-d781e2cbbc65.jpg?aki_policy=large","https://a0.muscache.com/im/pictures/9fbd78fc-ab82-46ba-8150-4a742828baa8.jpg?aki_policy=large"],"thumbnail_urls":["https://a0.muscache.com/im/pictures/b76dca74-839c-4ace-8012-abc1a649e461.jpg?aki_policy=small","https://a0.muscache.com/im/pictures/ffc925b4-e80f-4668-9254-df55115b441f.jpg?aki_policy=small","https://a0.muscache.com/im/pictures/fc12d34d-9559-4a46-bedd-1fa5dd8b2832.jpg?aki_policy=small","https://a0.muscache.com/im/pictures/aaffb434-fb6a-48e0-9d2e-005bc86b8e44.jpg?aki_policy=small","https://a0.muscache.com/im/pictures/7931eee0-8496-4a6d-a3b1-e741b7b155d2.jpg?aki_policy=small","https://a0.muscache.com/im/pictures/ef573429-6325-472e-b6c4-77658dc3a6b5.jpg?aki_policy=small","https://a0.muscache.com/im/pictures/b87521cd-c77c-4c59-8567-54215b74c1ba.jpg?aki_policy=small","https://a0.muscache.com/im/pictures/3b6e94bd-5dcf-407b-950f-741c066f8574.jpg?aki_policy=small","https://a0.muscache.com/im/pictures/d218805e-165b-47e5-a2c4-5d9be9406495.jpg?aki_policy=small","https://a0.muscache.com/im/pictures/73c94bd8-8740-42b1-ab99-6551ed5389b1.jpg?aki_policy=small","https://a0.muscache.com/im/pictures/3010441a-4df4-4489-a6e0-9532929998eb.jpg?aki_policy=small","https://a0.muscache.com/im/pictures/3182f1ba-dadb-41d9-8d12-060290dcc23c.jpg?aki_policy=small","https://a0.muscache.com/im/pictures/b472a43f-61e2-4f08-9f8a-142fd5dec2c6.jpg?aki_policy=small","https://a0.muscache.com/im/pictures/9887736e-ba56-4ccb-9f74-dee25b33f02f.jpg?aki_policy=small","https://a0.muscache.com/im/pictures/812b795a-b650-4f9e-b106-19908f805370.jpg?aki_policy=small","https://a0.muscache.com/im/pictures/00522f53-d975-4350-a38d-8f15ff59b7f6.jpg?aki_policy=small","https://a0.muscache.com/im/pictures/5499379e-4bf7-4c93-a2ee-2b25697f20eb.jpg?aki_policy=small","https://a0.muscache.com/im/pictures/ea5bc2e1-7cde-46c2-8153-89188e5a44c0.jpg?aki_policy=small","https://a0.muscache.com/im/pictures/3fbec0ab-394f-4afd-83c1-3e60601dd512.jpg?aki_policy=small","https://a0.muscache.com/im/pictures/9f52466e-91de-42fe-9989-2d7c617db207.jpg?aki_policy=small","https://a0.muscache.com/im/pictures/e0bd5b47-6ae3-4e01-b1c7-3173f7dce1a8.jpg?aki_policy=small","https://a0.muscache.com/im/pictures/bbb723b3-f4df-45ab-8bee-023216aa6799.jpg?aki_policy=small","https://a0.muscache.com/im/pictures/4a06e010-6f9d-4199-b274-a218422027be.jpg?aki_policy=small","https://a0.muscache.com/im/pictures/06062c85-6517-45f1-87e9-55c48e7c838e.jpg?aki_policy=small","https://a0.muscache.com/im/pictures/46d05d41-9d39-42a6-a0dd-6b5ee30fe7f2.jpg?aki_policy=small","https://a0.muscache.com/im/pictures/af78dd44-237e-48bd-8205-3d00744e20bf.jpg?aki_policy=small","https://a0.muscache.com/im/pictures/8d6a89a6-fb76-42d5-b204-8c10a6d80dc2.jpg?aki_policy=small","https://a0.muscache.com/im/pictures/8888d4d1-ccc5-4261-abbf-95aafe7cee45.jpg?aki_policy=small","https://a0.muscache.com/im/pictures/056a0681-df41-461e-a6d7-d4860c6f4091.jpg?aki_policy=small","https://a0.muscache.com/im/pictures/26ac81ed-09ba-4736-87a5-09cc9d3910d9.jpg?aki_policy=small","https://a0.muscache.com/im/pictures/14c59825-cb11-464a-92d9-00b90f086d7f.jpg?aki_policy=small","https://a0.muscache.com/im/pictures/9ad6cd45-5f85-4f9e-83c6-f7d60699f660.jpg?aki_policy=small","https://a0.muscache.com/im/pictures/22ee63f8-d283-42dd-b516-70f6c0465bb1.jpg?aki_policy=small","https://a0.muscache.com/im/pictures/ce5f8864-05d3-4d9b-8874-c5a5d192bb02.jpg?aki_policy=small","https://a0.muscache.com/im/pictures/a68e2e40-1d97-46ca-83f3-dc3c2dc48865.jpg?aki_policy=small","https://a0.muscache.com/im/pictures/2e36d080-4730-4f6b-8138-d781e2cbbc65.jpg?aki_policy=small","https://a0.muscache.com/im/pictures/9fbd78fc-ab82-46ba-8150-4a742828baa8.jpg?aki_policy=small"],"xl_picture_urls":["https://a0.muscache.com/im/pictures/b76dca74-839c-4ace-8012-abc1a649e461.jpg?aki_policy=x_large","https://a0.muscache.com/im/pictures/ffc925b4-e80f-4668-9254-df55115b441f.jpg?aki_policy=x_large","https://a0.muscache.com/im/pictures/fc12d34d-9559-4a46-bedd-1fa5dd8b2832.jpg?aki_policy=x_large","https://a0.muscache.com/im/pictures/aaffb434-fb6a-48e0-9d2e-005bc86b8e44.jpg?aki_policy=x_large","https://a0.muscache.com/im/pictures/7931eee0-8496-4a6d-a3b1-e741b7b155d2.jpg?aki_policy=x_large","https://a0.muscache.com/im/pictures/ef573429-6325-472e-b6c4-77658dc3a6b5.jpg?aki_policy=x_large","https://a0.muscache.com/im/pictures/b87521cd-c77c-4c59-8567-54215b74c1ba.jpg?aki_policy=x_large","https://a0.muscache.com/im/pictures/3b6e94bd-5dcf-407b-950f-741c066f8574.jpg?aki_policy=x_large","https://a0.muscache.com/im/pictures/d218805e-165b-47e5-a2c4-5d9be9406495.jpg?aki_policy=x_large","https://a0.muscache.com/im/pictures/73c94bd8-8740-42b1-ab99-6551ed5389b1.jpg?aki_policy=x_large","https://a0.muscache.com/im/pictures/3010441a-4df4-4489-a6e0-9532929998eb.jpg?aki_policy=x_large","https://a0.muscache.com/im/pictures/3182f1ba-dadb-41d9-8d12-060290dcc23c.jpg?aki_policy=x_large","https://a0.muscache.com/im/pictures/b472a43f-61e2-4f08-9f8a-142fd5dec2c6.jpg?aki_policy=x_large","https://a0.muscache.com/im/pictures/9887736e-ba56-4ccb-9f74-dee25b33f02f.jpg?aki_policy=x_large","https://a0.muscache.com/im/pictures/812b795a-b650-4f9e-b106-19908f805370.jpg?aki_policy=x_large","https://a0.muscache.com/im/pictures/00522f53-d975-4350-a38d-8f15ff59b7f6.jpg?aki_policy=x_large","https://a0.muscache.com/im/pictures/5499379e-4bf7-4c93-a2ee-2b25697f20eb.jpg?aki_policy=x_large","https://a0.muscache.com/im/pictures/ea5bc2e1-7cde-46c2-8153-89188e5a44c0.jpg?aki_policy=x_large","https://a0.muscache.com/im/pictures/3fbec0ab-394f-4afd-83c1-3e60601dd512.jpg?aki_policy=x_large","https://a0.muscache.com/im/pictures/9f52466e-91de-42fe-9989-2d7c617db207.jpg?aki_policy=x_large","https://a0.muscache.com/im/pictures/e0bd5b47-6ae3-4e01-b1c7-3173f7dce1a8.jpg?aki_policy=x_large","https://a0.muscache.com/im/pictures/bbb723b3-f4df-45ab-8bee-023216aa6799.jpg?aki_policy=x_large","https://a0.muscache.com/im/pictures/4a06e010-6f9d-4199-b274-a218422027be.jpg?aki_policy=x_large","https://a0.muscache.com/im/pictures/06062c85-6517-45f1-87e9-55c48e7c838e.jpg?aki_policy=x_large","https://a0.muscache.com/im/pictures/46d05d41-9d39-42a6-a0dd-6b5ee30fe7f2.jpg?aki_policy=x_large","https://a0.muscache.com/im/pictures/af78dd44-237e-48bd-8205-3d00744e20bf.jpg?aki_policy=x_large","https://a0.muscache.com/im/pictures/8d6a89a6-fb76-42d5-b204-8c10a6d80dc2.jpg?aki_policy=x_large","https://a0.muscache.com/im/pictures/8888d4d1-ccc5-4261-abbf-95aafe7cee45.jpg?aki_policy=x_large","https://a0.muscache.com/im/pictures/056a0681-df41-461e-a6d7-d4860c6f4091.jpg?aki_policy=x_large","https://a0.muscache.com/im/pictures/26ac81ed-09ba-4736-87a5-09cc9d3910d9.jpg?aki_policy=x_large","https://a0.muscache.com/im/pictures/14c59825-cb11-464a-92d9-00b90f086d7f.jpg?aki_policy=x_large","https://a0.muscache.com/im/pictures/9ad6cd45-5f85-4f9e-83c6-f7d60699f660.jpg?aki_policy=x_large","https://a0.muscache.com/im/pictures/22ee63f8-d283-42dd-b516-70f6c0465bb1.jpg?aki_policy=x_large","https://a0.muscache.com/im/pictures/ce5f8864-05d3-4d9b-8874-c5a5d192bb02.jpg?aki_policy=x_large","https://a0.muscache.com/im/pictures/a68e2e40-1d97-46ca-83f3-dc3c2dc48865.jpg?aki_policy=x_large","https://a0.muscache.com/im/pictures/2e36d080-4730-4f6b-8138-d781e2cbbc65.jpg?aki_policy=x_large","https://a0.muscache.com/im/pictures/9fbd78fc-ab82-46ba-8150-4a742828baa8.jpg?aki_policy=x_large"],"picture_count":37,"currency_symbol_left":"$","currency_symbol_right":null,"picture_captions":["Welcome to your apartment!  With high ceilings, two comfy couches, a breakfast nook, and 55' UHDTV, you have everything you\'ll need for a great stay.\n\nThe first bedroom features a king bed, high ceilings, and cool exposed brick features.","Welcome to your apartment!  With high ceilings, two comfy couches, a breakfast nook, and 55' UHDTV, you have everything you\'ll need for a great stay.","The kitchen is stocked with what you\'ll need to cook - pots & pans, knives, plates, utensils, silverware, glassware, baking trays, and more.","The 32' smart TV has complimentary Netflix","The bathroom with a soaker tub is the perfect end to your day. ","Sink into the couches and enjoy complimentary Netflix.","You might be stuck inside working, but at least you\'ll have a window to watch the world go by.","How do you like your coffee?  I provide drip and french press.  A whole bean grinder is provided along with Prestogeorge whole bean medium roast coffee.","Keyless entry is standard - no need to meet for a key - your code will be provided 48 hours prior to your check-in.","Always freshly-laundered, unscented linens & towels are provided.  We use exclusively non-scratchy, plush towels!","If there\'s a small group of you, there\'s plenty of seating.  Feel free to socially distance on separate couches!","If there\'s a small group of you, there\'s plenty of seating.  Feel free to socially distance on separate couches!","The open concept floor plan allows you to watch TV from the kitchen, or call to your friend to grab you a frosty beverage.","An exposed brick hallway leads to the living room, kitchen, and back bedroom","Whip up a cocktail - shakers, jiggers, and bar spoons will help you make it happen.","Fancy a game of checkers?","Soak up some vitamin D with your fresh ground coffee!","Seating for 4 - 2 at the breakfast bar and 2 and the nook by the window.","Whole bean coffee, high-end tea, sugar, and creamer is provided to get you started in the morning.","Plenty of counter space to work in.","The kitchen has a clean, modern, uncluttered look.","Through the kitchen you\'ll find the master bedroom & bathroom.","The second bedroom features a semi-firm queen mattress, desk, and office chair to get your work done.  Or work from bed!","Power ports are featured by the bed in each room, so you don\'t have to play the 'where do I plug my phone in?' game.","","Google Home is in each bedroom - ask about the weather, the traffic, whatever!","Google Home is in each bedroom - ask about the weather, the traffic, whatever!","Need to get ready with friends?  The huge mirror will help with that.","Toiletries are included, along with cotton swabs, makeup remover towels, and more.","Your place is on the second floor of a 3 story building - ultra wide staircase makes carrying any luggage up a breeze.","Work from home warrior?  Settle in to your office desk & chair.","","","","","",""],"bed_type":"Real Bed","bed_type_category":"real_bed","require_guest_profile_picture":false,"require_guest_phone_verification":false,"force_mobile_legal_modal":false,"allowed_currencies":["AED","ARS","AUD","AWG","BAM","BBD","BGN","BHD","BND","BRL","BTN","BZD","CAD","CHF","CLP","CNY","COP","CRC","CZK","DKK","EUR","FJD","GBP","GTQ","GYD","HKD","HNL","HRK","HUF","IDR","ILS","JMD","JOD","JPY","KRW","LAK","LKR","MAD","MOP","MXN","MYR","NOK","NZD","OMR","PEN","PGK","PHP","PLN","RON","RUB","SAR","SEK","SGD","THB","TRY","TTD","TWD","UAH","USD","UYU","VND","ZAR"],"cancel_policy":4,"check_in_time":16,"check_in_time_ends_at":-1,"check_out_time":12,"guests_included":4,"license":null,"max_nights":60,"square_feet":null,"locale":"en","has_viewed_terms":null,"has_viewed_cleaning":null,"has_agreed_to_legal_terms":true,"has_viewed_ib_perf_dashboard_panel":null,"localized_city":"Pittsburgh","language":"en","public_address":"Pittsburgh, PA, United States","map_image_url":"https://maps.googleapis.com/maps/api/staticmap?maptype=roadmap&markers=40.45566%2C-80.00106&size=480x320&zoom=15&client=gme-airbnbinc&channel=monorail-prod&signature=vLExAFRCTykxD_rnLPcQFwXyp6Y%3D","has_license":false,"instant_book_welcome_message":"Thanks for booking my place!  Please let me know why you\'re in town, so I can help to make sure your stay is perfect. ","experiences_offered":"none","max_nights_input_value":60,"min_nights_input_value":3,"requires_license":false,"property_type_id":1,"house_rules":"1. Smoking (or vaping) is NOT allowed in the house. Smoking is allowed outside only; PLEASE no butts on the ground. A minimum $1,000 cleaning fee will apply if smoking is reported inside.\n2. People other than those in the Guest party may not stay overnight in the property. Any other person in the property is the sole responsibility of Guest. The Host has not represented this unit as an event facility, therefore, additional guests are not permitted without prior notice. At no time may any of these visitors remain overnight or assume any guest privileges whatsoever.\n3. The Host is not responsible for any accidents, injuries or illness that occurs while on the premises or its facilities. The Host is not responsible for the loss of personal belongings or valuables of the guest. By accepting this reservation, it is agreed that all guests are expressly assuming the risk of any harm arising from their use of the premises or others whom they invite to use the premise.\n4. Host allows children with the following stipulations: - Guests are responsible for any and all necessary \'baby proofing\'. - Children must be supervised by an adult at all times.\n5. There is no daily housekeeping service. While the house has been thoroughly cleaned prior to your arrival and linens and bath towels are included, daily maid service is not included in the rate.\n6. Pets are not permitted; any pet being 'snuck' into the building will result in a minimum $300 cleaning fee.\n7. The stairwell is not a 'congregating' area - Guests agree to use stairwell only for entering / exiting the building, and to be respectful of neighboring floors by keeping voices / sounds to a low level. \n8. Cleaning fees are paid to assure Guest\'s arrival to a clean property, not to clean up post-checkout 'messes.'  While we don\'t ask or expect Guests to deep clean prior to leaving, we do expect reasonable cleanliness standards followed - garbage thrown away, spills cleaned up, etc. Cleaning required due to messiness will result in additional costs, charge at a rate of $30/hour. \n","cleaning_fee_native":74,"extras_price_native":74,"security_deposit_native":0,"security_price_native":0,"security_deposit_formatted":"$0","description":"You\'ll walk in and say wow! With a gorgeous open floor plan, exposed brick, soaring ceilings, a spacious bathroom and two comfy bedrooms (king & queen beds), you\'ll feel at home! Work from home at your desk, whip up a cocktail or meal in the full kitchen, lounge on the couches and watch Netflix (included) or browse the web (200mbps).\nIt\'s a short walk to Allegheny General and a few minutes to the bars & restaurants and stadiums on the North Shore - this is a great location!\n\nCLEANING PROCEDURES: \nWith Covid-19, it\'s important to stay in a professionally cleaned space. Our world class professional cleaners follow rigorous  procedures. All hard surfaces including counters, switches, handles, and remote controls are disinfected. Fabrics are disinfected, and all linens are washed in hot water with non-chlorine bleach.\n\nAll HostWise properties feature premium amenities, including whole bean coffee, soft towels, thick mattresses, comfy couches, smart TVs, high end toiletries, Netflix, and more!  As the highest rated Superhost in Pittsburgh, you will not be disappointed in your choice! \n\nPROPERTY FEATURES:\n\n-- 2 BR / 1BA ~1,000 sq ft apartment\n\n-- Sleeps 5:  Four in two bedrooms, one on couch\n     -- Bedroom 1 -  1 King - medium firm, 10' memory foam \n     -- Bedroom 2 - 1 Queen -  individual coil inner spring mattress with memory foam, medium plush\n     -- 1 full size couch in main room\n\n-- Parking: One visitor parking pass available for street parking.  The North Side is permit only, but I can provide you a visitor pass for the duration of your stay.\n\n-- Bathroom: One full bath with soaker tub\n     -- Includes shower, soaker tub, blow dryer, linens, premium toiletries, makeup remover, cotton swabs, etc.\n\n-- Technology:  Smart home hub enabled - with your voice, play music, ask about the weather!  55' Roku Smart TV + 32' TVs in the bedrooms.  Note that the property does not have cable, but it does have an over the air antenna. Most apps are free to use and / or you are able to use your cable provider\'s login info for 'paid' apps (ESPN, HBO, Showtime, etc.)\n\n--Internet:  High speed 200mbps up/down Verizon FiOS fiber optic. Work from home with high speed!\n\n-- Kitchen:  fully stocked with stainless pots & pans, knives, utensils, toaster, microwave, drip coffee maker, French press, etc.\n\n-- Coffee & Tea:  Drip coffee maker, French press, premium locally roasted Prestogeorge medium roast whole bean coffee, and premium tea selection is provided\n\n-- Living Room: 55' Flat screen 4K smart UHDTV and two brand new couches. \n\n-- Dining: Seating for 2 along counter, and 2 more at the breakfast nook.\n\n-- Laundry:  washer/dryer available in apartment\n\n-- Pets:  Not permitted\n\n-- No Smoking indoors - smoking 50\' from entrance outdoors\n\n-- Air Conditioning / heating - central air / heating\n\n-- Whole 2nd floor of a three story building\n\nAll parts of the space are accessible.\n\nMessage us through the Airbnb app and we\'ll get back to you right away, with 24/7/365 coverage! In an emergency, there\'s a phone number in the house manual that you can call if we are unavailable through the app.\n\nThe North Side is an old neighborhood full of Pittsburgh history, and now it\'s one of the fastest growing areas in the \'Burgh.  With the revitalization of the North Shore, the whole area is quickly developing into a lively hub of activity. Friendly old locals, new hipsters, starving artists, young professionals,  and more are now calling the diverse North Side home. The location is excellent, with lots of activities in a short walk - you\'re less than a mile walk from PNC Park and Heinz Field, with all of the bars & restaurants you could want in between.  There are parks, museums, the National Aviary, breweries, and more, all with easy access to downtown.\n\nYou can easily walk to most places, including downtown.  If you\'re venturing to other areas of Pittsburgh, Uber / Lyft is super easy and affordable.  Parking for one vehicle is on street (guest permit required) or in local garages - the closest is 3 blocks away at AGH James Street Garage.\n\nYour place is the entire second floor of a three story building.","description_locale":"en","summary":"You\'ll walk in and say wow! With a gorgeous open floor plan, exposed brick, soaring ceilings, a spacious bathroom and two comfy bedrooms (king & queen beds), you\'ll feel at home! Work from home at your desk, whip up a cocktail or meal in the full kitchen, lounge on the couches and watch Netflix (included) or browse the web (200mbps).\nIt\'s a short walk to Allegheny General and a few minutes to the bars & restaurants and stadiums on the North Shore - this is a great location!","space":"CLEANING PROCEDURES: \nWith Covid-19, it\'s important to stay in a professionally cleaned space. Our world class professional cleaners follow rigorous  procedures. All hard surfaces including counters, switches, handles, and remote controls are disinfected. Fabrics are disinfected, and all linens are washed in hot water with non-chlorine bleach.\n\nAll HostWise properties feature premium amenities, including whole bean coffee, soft towels, thick mattresses, comfy couches, smart TVs, high end toiletries, Netflix, and more!  As the highest rated Superhost in Pittsburgh, you will not be disappointed in your choice! \n\nPROPERTY FEATURES:\n\n-- 2 BR / 1BA ~1,000 sq ft apartment\n\n-- Sleeps 5:  Four in two bedrooms, one on couch\n     -- Bedroom 1 -  1 King - medium firm, 10' memory foam \n     -- Bedroom 2 - 1 Queen -  individual coil inner spring mattress with memory foam, medium plush\n     -- 1 full size couch in main room\n\n-- Parking: One visitor parking pass available for street parking.  The North Side is permit only, but I can provide you a visitor pass for the duration of your stay.\n\n-- Bathroom: One full bath with soaker tub\n     -- Includes shower, soaker tub, blow dryer, linens, premium toiletries, makeup remover, cotton swabs, etc.\n\n-- Technology:  Smart home hub enabled - with your voice, play music, ask about the weather!  55' Roku Smart TV + 32' TVs in the bedrooms.  Note that the property does not have cable, but it does have an over the air antenna. Most apps are free to use and / or you are able to use your cable provider\'s login info for 'paid' apps (ESPN, HBO, Showtime, etc.)\n\n--Internet:  High speed 200mbps up/down Verizon FiOS fiber optic. Work from home with high speed!\n\n-- Kitchen:  fully stocked with stainless pots & pans, knives, utensils, toaster, microwave, drip coffee maker, French press, etc.\n\n-- Coffee & Tea:  Drip coffee maker, French press, premium locally roasted Prestogeorge medium roast whole bean coffee, and premium tea selection is provided\n\n-- Living Room: 55' Flat screen 4K smart UHDTV and two brand new couches. \n\n-- Dining: Seating for 2 along counter, and 2 more at the breakfast nook.\n\n-- Laundry:  washer/dryer available in apartment\n\n-- Pets:  Not permitted\n\n-- No Smoking indoors - smoking 50\' from entrance outdoors\n\n-- Air Conditioning / heating - central air / heating\n\n-- Whole 2nd floor of a three story building","access":"All parts of the space are accessible.","interaction":"Message us through the Airbnb app and we\'ll get back to you right away, with 24/7/365 coverage! In an emergency, there\'s a phone number in the house manual that you can call if we are unavailable through the app.","neighborhood_overview":"The North Side is an old neighborhood full of Pittsburgh history, and now it\'s one of the fastest growing areas in the \'Burgh.  With the revitalization of the North Shore, the whole area is quickly developing into a lively hub of activity. Friendly old locals, new hipsters, starving artists, young professionals,  and more are now calling the diverse North Side home. The location is excellent, with lots of activities in a short walk - you\'re less than a mile walk from PNC Park and Heinz Field, with all of the bars & restaurants you could want in between.  There are parks, museums, the National Aviary, breweries, and more, all with easy access to downtown.","notes":"Your place is the entire second floor of a three story building.","transit":"You can easily walk to most places, including downtown.  If you\'re venturing to other areas of Pittsburgh, Uber / Lyft is super easy and affordable.  Parking for one vehicle is on street (guest permit required) or in local garages - the closest is 3 blocks away at AGH James Street Garage.","amenities":["TV","Wifi","Air conditioning","Kitchen","Free street parking","Heating","Washer","Dryer","Smoke alarm","Carbon monoxide alarm","Fire extinguisher","Essentials","Shampoo","Hangers","Hair dryer","Iron","Laptop-friendly workspace","Self check-in","Smart lock","Private living room","Bathtub","Hot water","Body soap","Bed linens","Extra pillows and blankets","Microwave","Coffee maker","Refrigerator","Dishwasher","Dishes and silverware","Cooking basics","Oven","Stove","Long term stays allowed","Hot water kettle","Dining table","Toaster","Freezer","Shower gel","Baking sheet","Conditioner","Laundromat nearby","Cleaning products","Clothing storage","Wine glasses"],"amenities_ids":[1,4,5,8,23,30,33,34,35,36,39,40,41,44,45,46,47,51,52,56,61,77,79,85,86,89,90,91,92,93,94,95,96,104,137,236,251,308,611,625,657,663,665,671,672],"is_location_exact":true,"in_building":false,"in_landlord_partnership":false,"in_toto_area":false,"recent_review":{"review":{"id":738285543,"reviewer_id":369402198,"reviewee_id":7556262,"created_at":"2021-03-12T18:00:40Z","reviewer":{"user":{"id":369402198,"first_name":"Bryan","picture_url":"https://a0.muscache.com/defaults/user_pic-225x225.png?v=3","thumbnail_url":"https://a0.muscache.com/defaults/user_pic-50x50.png?v=3","has_profile_pic":false}},"comments":"Amazing place to stay in Downtown Pittsburgh. Would definitely recommend if you get a friend or two staying together.","listing_id":44250006}},"calendar_updated_at":"never","cancel_policy_short_str":"Moderate","photos":[{"xl_picture":"https://a0.muscache.com/im/pictures/b76dca74-839c-4ace-8012-abc1a649e461.jpg?aki_policy=x_large","picture":"https://a0.muscache.com/ac/pictures/b76dca74-839c-4ace-8012-abc1a649e461.jpg?interpolation=lanczos-none&size=large_cover&output-format=jpg&output-quality=70","thumbnail":"https://a0.muscache.com/im/pictures/b76dca74-839c-4ace-8012-abc1a649e461.jpg?aki_policy=small","caption":"Welcome to your apartment!  With high ceilings, two comfy couches, a breakfast nook, and 55' UHDTV, you have everything you\'ll need for a great stay.\n\nThe first bedroom features a king bed, high ceilings, and cool exposed brick features.","id":1038598636,"sort_order":1},{"xl_picture":"https://a0.muscache.com/im/pictures/ffc925b4-e80f-4668-9254-df55115b441f.jpg?aki_policy=x_large","picture":"https://a0.muscache.com/ac/pictures/ffc925b4-e80f-4668-9254-df55115b441f.jpg?interpolation=lanczos-none&size=large_cover&output-format=jpg&output-quality=70","thumbnail":"https://a0.muscache.com/im/pictures/ffc925b4-e80f-4668-9254-df55115b441f.jpg?aki_policy=small","caption":"Welcome to your apartment!  With high ceilings, two comfy couches, a breakfast nook, and 55' UHDTV, you have everything you\'ll need for a great stay.","id":1038594664,"sort_order":2},{"xl_picture":"https://a0.muscache.com/im/pictures/fc12d34d-9559-4a46-bedd-1fa5dd8b2832.jpg?aki_policy=x_large","picture":"https://a0.muscache.com/ac/pictures/fc12d34d-9559-4a46-bedd-1fa5dd8b2832.jpg?interpolation=lanczos-none&size=large_cover&output-format=jpg&output-quality=70","thumbnail":"https://a0.muscache.com/im/pictures/fc12d34d-9559-4a46-bedd-1fa5dd8b2832.jpg?aki_policy=small","caption":"The kitchen is stocked with what you\'ll need to cook - pots & pans, knives, plates, utensils, silverware, glassware, baking trays, and more.","id":1038596427,"sort_order":3},{"xl_picture":"https://a0.muscache.com/im/pictures/aaffb434-fb6a-48e0-9d2e-005bc86b8e44.jpg?aki_policy=x_large","picture":"https://a0.muscache.com/ac/pictures/aaffb434-fb6a-48e0-9d2e-005bc86b8e44.jpg?interpolation=lanczos-none&size=large_cover&output-format=jpg&output-quality=70","thumbnail":"https://a0.muscache.com/im/pictures/aaffb434-fb6a-48e0-9d2e-005bc86b8e44.jpg?aki_policy=small","caption":"The 32' smart TV has complimentary Netflix","id":1038598023,"sort_order":4},{"xl_picture":"https://a0.muscache.com/im/pictures/7931eee0-8496-4a6d-a3b1-e741b7b155d2.jpg?aki_policy=x_large","picture":"https://a0.muscache.com/ac/pictures/7931eee0-8496-4a6d-a3b1-e741b7b155d2.jpg?interpolation=lanczos-none&size=large_cover&output-format=jpg&output-quality=70","thumbnail":"https://a0.muscache.com/im/pictures/7931eee0-8496-4a6d-a3b1-e741b7b155d2.jpg?aki_policy=small","caption":"The bathroom with a soaker tub is the perfect end to your day. ","id":1038600248,"sort_order":5},{"xl_picture":"https://a0.muscache.com/im/pictures/ef573429-6325-472e-b6c4-77658dc3a6b5.jpg?aki_policy=x_large","picture":"https://a0.muscache.com/ac/pictures/ef573429-6325-472e-b6c4-77658dc3a6b5.jpg?interpolation=lanczos-none&size=large_cover&output-format=jpg&output-quality=70","thumbnail":"https://a0.muscache.com/im/pictures/ef573429-6325-472e-b6c4-77658dc3a6b5.jpg?aki_policy=small","caption":"Sink into the couches and enjoy complimentary Netflix.","id":1038595025,"sort_order":6},{"xl_picture":"https://a0.muscache.com/im/pictures/b87521cd-c77c-4c59-8567-54215b74c1ba.jpg?aki_policy=x_large","picture":"https://a0.muscache.com/ac/pictures/b87521cd-c77c-4c59-8567-54215b74c1ba.jpg?interpolation=lanczos-none&size=large_cover&output-format=jpg&output-quality=70","thumbnail":"https://a0.muscache.com/im/pictures/b87521cd-c77c-4c59-8567-54215b74c1ba.jpg?aki_policy=small","caption":"You might be stuck inside working, but at least you\'ll have a window to watch the world go by.","id":1038599948,"sort_order":7},{"xl_picture":"https://a0.muscache.com/im/pictures/3b6e94bd-5dcf-407b-950f-741c066f8574.jpg?aki_policy=x_large","picture":"https://a0.muscache.com/ac/pictures/3b6e94bd-5dcf-407b-950f-741c066f8574.jpg?interpolation=lanczos-none&size=large_cover&output-format=jpg&output-quality=70","thumbnail":"https://a0.muscache.com/im/pictures/3b6e94bd-5dcf-407b-950f-741c066f8574.jpg?aki_policy=small","caption":"How do you like your coffee?  I provide drip and french press.  A whole bean grinder is provided along with Prestogeorge whole bean medium roast coffee.","id":1038597678,"sort_order":8},{"xl_picture":"https://a0.muscache.com/im/pictures/d218805e-165b-47e5-a2c4-5d9be9406495.jpg?aki_policy=x_large","picture":"https://a0.muscache.com/ac/pictures/d218805e-165b-47e5-a2c4-5d9be9406495.jpg?interpolation=lanczos-none&size=large_cover&output-format=jpg&output-quality=70","thumbnail":"https://a0.muscache.com/im/pictures/d218805e-165b-47e5-a2c4-5d9be9406495.jpg?aki_policy=small","caption":"Keyless entry is standard - no need to meet for a key - your code will be provided 48 hours prior to your check-in.","id":1038600732,"sort_order":9},{"xl_picture":"https://a0.muscache.com/im/pictures/73c94bd8-8740-42b1-ab99-6551ed5389b1.jpg?aki_policy=x_large","picture":"https://a0.muscache.com/ac/pictures/73c94bd8-8740-42b1-ab99-6551ed5389b1.jpg?interpolation=lanczos-none&size=large_cover&output-format=jpg&output-quality=70","thumbnail":"https://a0.muscache.com/im/pictures/73c94bd8-8740-42b1-ab99-6551ed5389b1.jpg?aki_policy=small","caption":"Always freshly-laundered, unscented linens & towels are provided.  We use exclusively non-scratchy, plush towels!","id":1038599787,"sort_order":10},{"xl_picture":"https://a0.muscache.com/im/pictures/3010441a-4df4-4489-a6e0-9532929998eb.jpg?aki_policy=x_large","picture":"https://a0.muscache.com/ac/pictures/3010441a-4df4-4489-a6e0-9532929998eb.jpg?interpolation=lanczos-none&size=large_cover&output-format=jpg&output-quality=70","thumbnail":"https://a0.muscache.com/im/pictures/3010441a-4df4-4489-a6e0-9532929998eb.jpg?aki_policy=small","caption":"If there\'s a small group of you, there\'s plenty of seating.  Feel free to socially distance on separate couches!","id":1038595293,"sort_order":11},{"xl_picture":"https://a0.muscache.com/im/pictures/3182f1ba-dadb-41d9-8d12-060290dcc23c.jpg?aki_policy=x_large","picture":"https://a0.muscache.com/ac/pictures/3182f1ba-dadb-41d9-8d12-060290dcc23c.jpg?interpolation=lanczos-none&size=large_cover&output-format=jpg&output-quality=70","thumbnail":"https://a0.muscache.com/im/pictures/3182f1ba-dadb-41d9-8d12-060290dcc23c.jpg?aki_policy=small","caption":"If there\'s a small group of you, there\'s plenty of seating.  Feel free to socially distance on separate couches!","id":1038595800,"sort_order":12},{"xl_picture":"https://a0.muscache.com/im/pictures/b472a43f-61e2-4f08-9f8a-142fd5dec2c6.jpg?aki_policy=x_large","picture":"https://a0.muscache.com/ac/pictures/b472a43f-61e2-4f08-9f8a-142fd5dec2c6.jpg?interpolation=lanczos-none&size=large_cover&output-format=jpg&output-quality=70","thumbnail":"https://a0.muscache.com/im/pictures/b472a43f-61e2-4f08-9f8a-142fd5dec2c6.jpg?aki_policy=small","caption":"The open concept floor plan allows you to watch TV from the kitchen, or call to your friend to grab you a frosty beverage.","id":1038595525,"sort_order":13},{"xl_picture":"https://a0.muscache.com/im/pictures/9887736e-ba56-4ccb-9f74-dee25b33f02f.jpg?aki_policy=x_large","picture":"https://a0.muscache.com/ac/pictures/9887736e-ba56-4ccb-9f74-dee25b33f02f.jpg?interpolation=lanczos-none&size=large_cover&output-format=jpg&output-quality=70","thumbnail":"https://a0.muscache.com/im/pictures/9887736e-ba56-4ccb-9f74-dee25b33f02f.jpg?aki_policy=small","caption":"An exposed brick hallway leads to the living room, kitchen, and back bedroom","id":1038594461,"sort_order":14},{"xl_picture":"https://a0.muscache.com/im/pictures/812b795a-b650-4f9e-b106-19908f805370.jpg?aki_policy=x_large","picture":"https://a0.muscache.com/ac/pictures/812b795a-b650-4f9e-b106-19908f805370.jpg?interpolation=lanczos-none&size=large_cover&output-format=jpg&output-quality=70","thumbnail":"https://a0.muscache.com/im/pictures/812b795a-b650-4f9e-b106-19908f805370.jpg?aki_policy=small","caption":"Whip up a cocktail - shakers, jiggers, and bar spoons will help you make it happen.","id":1038596268,"sort_order":15},{"xl_picture":"https://a0.muscache.com/im/pictures/00522f53-d975-4350-a38d-8f15ff59b7f6.jpg?aki_policy=x_large","picture":"https://a0.muscache.com/ac/pictures/00522f53-d975-4350-a38d-8f15ff59b7f6.jpg?interpolation=lanczos-none&size=large_cover&output-format=jpg&output-quality=70","thumbnail":"https://a0.muscache.com/im/pictures/00522f53-d975-4350-a38d-8f15ff59b7f6.jpg?aki_policy=small","caption":"Fancy a game of checkers?","id":1038596019,"sort_order":16},{"xl_picture":"https://a0.muscache.com/im/pictures/5499379e-4bf7-4c93-a2ee-2b25697f20eb.jpg?aki_policy=x_large","picture":"https://a0.muscache.com/ac/pictures/5499379e-4bf7-4c93-a2ee-2b25697f20eb.jpg?interpolation=lanczos-none&size=large_cover&output-format=jpg&output-quality=70","thumbnail":"https://a0.muscache.com/im/pictures/5499379e-4bf7-4c93-a2ee-2b25697f20eb.jpg?aki_policy=small","caption":"Soak up some vitamin D with your fresh ground coffee!","id":1038597058,"sort_order":17},{"xl_picture":"https://a0.muscache.com/im/pictures/ea5bc2e1-7cde-46c2-8153-89188e5a44c0.jpg?aki_policy=x_large","picture":"https://a0.muscache.com/ac/pictures/ea5bc2e1-7cde-46c2-8153-89188e5a44c0.jpg?interpolation=lanczos-none&size=large_cover&output-format=jpg&output-quality=70","thumbnail":"https://a0.muscache.com/im/pictures/ea5bc2e1-7cde-46c2-8153-89188e5a44c0.jpg?aki_policy=small","caption":"Seating for 4 - 2 at the breakfast bar and 2 and the nook by the window.","id":1038596600,"sort_order":18},{"xl_picture":"https://a0.muscache.com/im/pictures/3fbec0ab-394f-4afd-83c1-3e60601dd512.jpg?aki_policy=x_large","picture":"https://a0.muscache.com/ac/pictures/3fbec0ab-394f-4afd-83c1-3e60601dd512.jpg?interpolation=lanczos-none&size=large_cover&output-format=jpg&output-quality=70","thumbnail":"https://a0.muscache.com/im/pictures/3fbec0ab-394f-4afd-83c1-3e60601dd512.jpg?aki_policy=small","caption":"Whole bean coffee, high-end tea, sugar, and creamer is provided to get you started in the morning.","id":1038597504,"sort_order":19},{"xl_picture":"https://a0.muscache.com/im/pictures/9f52466e-91de-42fe-9989-2d7c617db207.jpg?aki_policy=x_large","picture":"https://a0.muscache.com/ac/pictures/9f52466e-91de-42fe-9989-2d7c617db207.jpg?interpolation=lanczos-none&size=large_cover&output-format=jpg&output-quality=70","thumbnail":"https://a0.muscache.com/im/pictures/9f52466e-91de-42fe-9989-2d7c617db207.jpg?aki_policy=small","caption":"Plenty of counter space to work in.","id":1038596786,"sort_order":20},{"xl_picture":"https://a0.muscache.com/im/pictures/e0bd5b47-6ae3-4e01-b1c7-3173f7dce1a8.jpg?aki_policy=x_large","picture":"https://a0.muscache.com/ac/pictures/e0bd5b47-6ae3-4e01-b1c7-3173f7dce1a8.jpg?interpolation=lanczos-none&size=large_cover&output-format=jpg&output-quality=70","thumbnail":"https://a0.muscache.com/im/pictures/e0bd5b47-6ae3-4e01-b1c7-3173f7dce1a8.jpg?aki_policy=small","caption":"The kitchen has a clean, modern, uncluttered look.","id":1038597190,"sort_order":21},{"xl_picture":"https://a0.muscache.com/im/pictures/bbb723b3-f4df-45ab-8bee-023216aa6799.jpg?aki_policy=x_large","picture":"https://a0.muscache.com/ac/pictures/bbb723b3-f4df-45ab-8bee-023216aa6799.jpg?interpolation=lanczos-none&size=large_cover&output-format=jpg&output-quality=70","thumbnail":"https://a0.muscache.com/im/pictures/bbb723b3-f4df-45ab-8bee-023216aa6799.jpg?aki_policy=small","caption":"Through the kitchen you\'ll find the master bedroom & bathroom.","id":1038596920,"sort_order":22},{"xl_picture":"https://a0.muscache.com/im/pictures/4a06e010-6f9d-4199-b274-a218422027be.jpg?aki_policy=x_large","picture":"https://a0.muscache.com/ac/pictures/4a06e010-6f9d-4199-b274-a218422027be.jpg?interpolation=lanczos-none&size=large_cover&output-format=jpg&output-quality=70","thumbnail":"https://a0.muscache.com/im/pictures/4a06e010-6f9d-4199-b274-a218422027be.jpg?aki_policy=small","caption":"The second bedroom features a semi-firm queen mattress, desk, and office chair to get your work done.  Or work from bed!","id":1038597877,"sort_order":23},{"xl_picture":"https://a0.muscache.com/im/pictures/06062c85-6517-45f1-87e9-55c48e7c838e.jpg?aki_policy=x_large","picture":"https://a0.muscache.com/ac/pictures/06062c85-6517-45f1-87e9-55c48e7c838e.jpg?interpolation=lanczos-none&size=large_cover&output-format=jpg&output-quality=70","thumbnail":"https://a0.muscache.com/im/pictures/06062c85-6517-45f1-87e9-55c48e7c838e.jpg?aki_policy=small","caption":"Power ports are featured by the bed in each room, so you don\'t have to play the 'where do I plug my phone in?' game.","id":1038598397,"sort_order":24},{"xl_picture":"https://a0.muscache.com/im/pictures/46d05d41-9d39-42a6-a0dd-6b5ee30fe7f2.jpg?aki_policy=x_large","picture":"https://a0.muscache.com/ac/pictures/46d05d41-9d39-42a6-a0dd-6b5ee30fe7f2.jpg?interpolation=lanczos-none&size=large_cover&output-format=jpg&output-quality=70","thumbnail":"https://a0.muscache.com/im/pictures/46d05d41-9d39-42a6-a0dd-6b5ee30fe7f2.jpg?aki_policy=small","caption":"","id":1038598963,"sort_order":25},{"xl_picture":"https://a0.muscache.com/im/pictures/af78dd44-237e-48bd-8205-3d00744e20bf.jpg?aki_policy=x_large","picture":"https://a0.muscache.com/ac/pictures/af78dd44-237e-48bd-8205-3d00744e20bf.jpg?interpolation=lanczos-none&size=large_cover&output-format=jpg&output-quality=70","thumbnail":"https://a0.muscache.com/im/pictures/af78dd44-237e-48bd-8205-3d00744e20bf.jpg?aki_policy=small","caption":"Google Home is in each bedroom - ask about the weather, the traffic, whatever!","id":1038599596,"sort_order":26},{"xl_picture":"https://a0.muscache.com/im/pictures/8d6a89a6-fb76-42d5-b204-8c10a6d80dc2.jpg?aki_policy=x_large","picture":"https://a0.muscache.com/ac/pictures/8d6a89a6-fb76-42d5-b204-8c10a6d80dc2.jpg?interpolation=lanczos-none&size=large_cover&output-format=jpg&output-quality=70","thumbnail":"https://a0.muscache.com/im/pictures/8d6a89a6-fb76-42d5-b204-8c10a6d80dc2.jpg?aki_policy=small","caption":"Google Home is in each bedroom - ask about the weather, the traffic, whatever!","id":1038600099,"sort_order":27},{"xl_picture":"https://a0.muscache.com/im/pictures/8888d4d1-ccc5-4261-abbf-95aafe7cee45.jpg?aki_policy=x_large","picture":"https://a0.muscache.com/ac/pictures/8888d4d1-ccc5-4261-abbf-95aafe7cee45.jpg?interpolation=lanczos-none&size=large_cover&output-format=jpg&output-quality=70","thumbnail":"https://a0.muscache.com/im/pictures/8888d4d1-ccc5-4261-abbf-95aafe7cee45.jpg?aki_policy=small","caption":"Need to get ready with friends?  The huge mirror will help with that.","id":1038600375,"sort_order":28},{"xl_picture":"https://a0.muscache.com/im/pictures/056a0681-df41-461e-a6d7-d4860c6f4091.jpg?aki_policy=x_large","picture":"https://a0.muscache.com/ac/pictures/056a0681-df41-461e-a6d7-d4860c6f4091.jpg?interpolation=lanczos-none&size=large_cover&output-format=jpg&output-quality=70","thumbnail":"https://a0.muscache.com/im/pictures/056a0681-df41-461e-a6d7-d4860c6f4091.jpg?aki_policy=small","caption":"Toiletries are included, along with cotton swabs, makeup remover towels, and more.","id":1038600512,"sort_order":29},{"xl_picture":"https://a0.muscache.com/im/pictures/26ac81ed-09ba-4736-87a5-09cc9d3910d9.jpg?aki_policy=x_large","picture":"https://a0.muscache.com/ac/pictures/26ac81ed-09ba-4736-87a5-09cc9d3910d9.jpg?interpolation=lanczos-none&size=large_cover&output-format=jpg&output-quality=70","thumbnail":"https://a0.muscache.com/im/pictures/26ac81ed-09ba-4736-87a5-09cc9d3910d9.jpg?aki_policy=small","caption":"Your place is on the second floor of a 3 story building - ultra wide staircase makes carrying any luggage up a breeze.","id":1038600630,"sort_order":30},{"xl_picture":"https://a0.muscache.com/im/pictures/14c59825-cb11-464a-92d9-00b90f086d7f.jpg?aki_policy=x_large","picture":"https://a0.muscache.com/ac/pictures/14c59825-cb11-464a-92d9-00b90f086d7f.jpg?interpolation=lanczos-none&size=large_cover&output-format=jpg&output-quality=70","thumbnail":"https://a0.muscache.com/im/pictures/14c59825-cb11-464a-92d9-00b90f086d7f.jpg?aki_policy=small","caption":"Work from home warrior?  Settle in to your office desk & chair.","id":1038598244,"sort_order":31},{"xl_picture":"https://a0.muscache.com/im/pictures/9ad6cd45-5f85-4f9e-83c6-f7d60699f660.jpg?aki_policy=x_large","picture":"https://a0.muscache.com/ac/pictures/9ad6cd45-5f85-4f9e-83c6-f7d60699f660.jpg?interpolation=lanczos-none&size=large_cover&output-format=jpg&output-quality=70","thumbnail":"https://a0.muscache.com/im/pictures/9ad6cd45-5f85-4f9e-83c6-f7d60699f660.jpg?aki_policy=small","caption":"","id":1038597365,"sort_order":32},{"xl_picture":"https://a0.muscache.com/im/pictures/22ee63f8-d283-42dd-b516-70f6c0465bb1.jpg?aki_policy=x_large","picture":"https://a0.muscache.com/ac/pictures/22ee63f8-d283-42dd-b516-70f6c0465bb1.jpg?interpolation=lanczos-none&size=large_cover&output-format=jpg&output-quality=70","thumbnail":"https://a0.muscache.com/im/pictures/22ee63f8-d283-42dd-b516-70f6c0465bb1.jpg?aki_policy=small","caption":"","id":1038624254,"sort_order":33},{"xl_picture":"https://a0.muscache.com/im/pictures/ce5f8864-05d3-4d9b-8874-c5a5d192bb02.jpg?aki_policy=x_large","picture":"https://a0.muscache.com/ac/pictures/ce5f8864-05d3-4d9b-8874-c5a5d192bb02.jpg?interpolation=lanczos-none&size=large_cover&output-format=jpg&output-quality=70","thumbnail":"https://a0.muscache.com/im/pictures/ce5f8864-05d3-4d9b-8874-c5a5d192bb02.jpg?aki_policy=small","caption":"","id":1038624485,"sort_order":34},{"xl_picture":"https://a0.muscache.com/im/pictures/a68e2e40-1d97-46ca-83f3-dc3c2dc48865.jpg?aki_policy=x_large","picture":"https://a0.muscache.com/ac/pictures/a68e2e40-1d97-46ca-83f3-dc3c2dc48865.jpg?interpolation=lanczos-none&size=large_cover&output-format=jpg&output-quality=70","thumbnail":"https://a0.muscache.com/im/pictures/a68e2e40-1d97-46ca-83f3-dc3c2dc48865.jpg?aki_policy=small","caption":"","id":1038624654,"sort_order":35},{"xl_picture":"https://a0.muscache.com/im/pictures/2e36d080-4730-4f6b-8138-d781e2cbbc65.jpg?aki_policy=x_large","picture":"https://a0.muscache.com/ac/pictures/2e36d080-4730-4f6b-8138-d781e2cbbc65.jpg?interpolation=lanczos-none&size=large_cover&output-format=jpg&output-quality=70","thumbnail":"https://a0.muscache.com/im/pictures/2e36d080-4730-4f6b-8138-d781e2cbbc65.jpg?aki_policy=small","caption":"","id":1038624814,"sort_order":36},{"xl_picture":"https://a0.muscache.com/im/pictures/9fbd78fc-ab82-46ba-8150-4a742828baa8.jpg?aki_policy=x_large","picture":"https://a0.muscache.com/ac/pictures/9fbd78fc-ab82-46ba-8150-4a742828baa8.jpg?interpolation=lanczos-none&size=large_cover&output-format=jpg&output-quality=70","thumbnail":"https://a0.muscache.com/im/pictures/9fbd78fc-ab82-46ba-8150-4a742828baa8.jpg?aki_policy=small","caption":"","id":1038594280,"sort_order":37}],"star_rating":5.0,"jurisdiction_names":"Pennsylvania State, Allegheny County, PA","jurisdiction_rollout_names":"Pennsylvania State, Allegheny County, PA","price_for_extra_person_native":25,"time_zone_name":"America/New_York","weekly_price_factor":0.9299999999999999,"monthly_price_factor":0.85,"guest_controls":{"allows_children_as_host":false,"allows_infants_as_host":false,"allows_pets_as_host":false,"allows_smoking_as_host":false,"allows_events_as_host":false,"children_not_allowed_details":"There are tip / trip / fall hazards, and sharp corners.","id":44250006,"structured_house_rules":["Not suitable for children and infants","No smoking","No pets","No parties or events","Check-in is anytime after 4:00 PM"]},"check_in_time_start":"16","check_in_time_end":"FLEXIBLE","formatted_check_out_time":"12","localized_check_in_time_window":"After 4:00 PM","localized_check_out_time":"12:00 PM"}}';
//
//        $data = json_decode($test);
//
//    }

//    public function testFetchOneListing() {
//        $reColorado = new ReColorado();
//
//        $reColorado->fetchOneListing(2);
//    }
//
    public function testFetchBlocks() {
        $scraper = new Scraper();

        $test= $scraper->getCurl('https://transx.com.listcrawler.eu/brief/escorts/usa/colorado/denver/1/');

        $links = $scraper->getLinksWithClass($test, 'listtitle');

        $phoneNumbers = [];
        foreach ($links as $link) {
            $response= $scraper->getCurl($link);
//            phone: '9153029830',
            $phoneNumbers[] = ['first_name' => $link,
                'mobile_phone' => $scraper->getInBetween($response, "phone: '", "',")
            ];
            //<a href="tel:9153029830" data-transition="slide" class="normal" data-analytic="click"> 915-302-9830</a>

            sleep(rand(3,12));
        }
        $test = 1;
    }


    //showplace_prod
    public function testAllSTuff() {

        $dupes = DB::connection('showplace_prod')->select("SELECT listings_id, shopify_product_id, DATE_FORMAT(created_at, '%m-%d-%y %h:%i') as hour_minute_date, count(*) as exist_count
                                                FROM shop_my_home_user_actions
                                                GROUP BY DATE_FORMAT(created_at, '%m-%d-%y %h:%i'), listings_id, shopify_product_id having exist_count > 1;");

        $debug = 1;

        foreach ($dupes as $dupe) {
            $first = true;


            try {
                $lineDupes = DB::connection('showplace_prod')->select("SELECT * FROM shop_my_home_user_actions where listings_id = ".$dupe->listings_id." and shopify_product_id = ".$dupe->shopify_product_id."  
            and DATE_FORMAT(created_at, '%m-%d-%y %h:%i') = '".$dupe->hour_minute_date."';" );

                foreach ($lineDupes as $lineDupe) {
                    if (!$first) {
                        DB::connection('showplace_prod')->delete('delete from shop_my_home_user_actions where id = '.$lineDupe->id);
                    }
                    $first = false;
                }
            }
            catch (\Exception $e) {

            }

        }

    }


    //showplace_prod
    public function testDeleteTwo() {

        $campaignProducts = DB::connection('showplace_prod')->select("SELECT * from vw_campaign_products;");

        $debug = 1;

        foreach ($campaignProducts as $campaignProduct) {

            try {
                DB::connection('showplace_prod')->delete('delete from shop_my_home_user_actions where product_id = '.$campaignProduct->product_id.' and shop_my_home_user_actions.listings_id not in (
                                                                    select listing_id from campaign_applications where status = 1 and campaign_id = '.$campaignProduct->campaign_id.'
                                                                    );');

            }
            catch (\Exception $e) {
                $e->getMessage();
            }

        }

    }

}
