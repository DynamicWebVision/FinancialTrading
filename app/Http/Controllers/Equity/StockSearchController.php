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

        $stockSearch->currentPage = $data['currentPage'];

        $results = $stockSearch->getCurrentResult();
        $test = DB::getQueryLog();
        return ['totalCount'=>$totalCount, 'results'=>$results];
    }
}