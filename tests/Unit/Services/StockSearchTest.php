<?php

namespace Tests\Unit\Services;

use Tests\TestCase;

use \App\Services\StockSearch;

class StockSearchTest extends TestCase
{
    public function testBasicSearch()
    {
        $stockSearch = new StockSearch();

        $stocks = $stockSearch->index();
    }
}
