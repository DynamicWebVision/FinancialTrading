<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ReColorado;

class ReColoradoController extends Controller
{
        public function findNewListings() {
        $reColorado = new ReColorado();

        $reColorado->initialSearch();
    }

    public function checkListingData() {
        $listings = ReColoradoListing::all();

        $reColorado = new ReColorado();

        foreach ($listings as $listing) {
            $reColorado->fetchOneListing($listing->id);
            $reColorado->listingId = $listing->id;
            $reColorado->checkPriceHistory();
            sleep(rand(2, 5));
        }

    }
}
