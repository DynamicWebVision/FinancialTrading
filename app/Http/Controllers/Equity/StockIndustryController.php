<?php namespace App\Http\Controllers\Equity;


use \DB;
use \Log;
use Request;
use App\Http\Controllers\Controller;
use App\Model\Stocks\StocksIndustry;

class StockIndustryController extends Controller {
    public function index() {
        $industries = StocksIndustry::orderBy('name')->get()->toArray();
        $industries[] = [
            'id' =>-1,
            'name'=>'------'
        ];
        return $industries;
    }
}