<?php namespace App\Http\Controllers\Equity;


use \DB;
use \Log;
use Request;
use App\Http\Controllers\Controller;
use App\Model\Stocks\StocksSector;

class StockSectorController extends Controller {
    public function index() {
        $sectors = StocksSector::orderBy('name')->get()->toArray();
        $sectors[] = [
            'id' =>-1,
            'name'=>'------'
        ];
        return $sectors;
    }
}