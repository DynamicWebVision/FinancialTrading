<?php namespace App\Http\Controllers\Equity;


use \DB;
use \Log;
use Request;
use App\Http\Controllers\Controller;
use App\Services\StockSearch;

class StockSearchController extends Controller {
    public function index() {
        $data = Request::all();

        $stockSearch = new StockSearch();
        $stockSearch->criteria = $data['searchCriteria'];
        $stockSearch->setQuery();
        DB::enableQueryLog();
        $totalCount = $stockSearch->getResultTotalCount();
        $test = DB::getQueryLog();

        $stockSearch->currentPage = 1;
        $results = $stockSearch->getCurrentResult();
        return ['totalCount'=>$totalCount, 'results'=>$results];
    }
}