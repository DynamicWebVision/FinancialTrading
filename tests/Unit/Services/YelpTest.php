<?php

namespace Tests\Unit\Services;

use App\Services\Scraper;
use Tests\TestCase;

use \App\Model\Exchange;
use \App\Services\BackTest;
use \App\Services\Yelp;
use \App\Services\ReColorado;

use App\Model\Yelp\States;
use App\Model\Yelp\Cities;

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

    public function testReColorado() {
        $reColorado = new ReColorado();

        $reColorado->initialSearch();
    }

//    public function testFetchOneListing() {
//        $reColorado = new ReColorado();
//
//        $reColorado->fetchOneListing(2);
//    }
//
//    public function testFetchBlocks() {
//        $reColorado = new ReColorado();
//        $reColorado->listingId = 1;
//
//        $scraper = new Scraper();
//
//        $reColorado->listingResponse = $scraper->getCurl('https://www.recolorado.com/listing/281607912-151082055/921-kalamath-street-denver-co-80204/');
//
//        $reColorado->checkPriceHistory();
//    }

}
