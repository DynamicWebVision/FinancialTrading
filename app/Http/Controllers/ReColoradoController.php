<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ReColorado;

class ReColoradoController extends Controller
{
    public function fetchNewListing($listingId) {
        $reColoradoService = new ReColorado();
        $reColoradoService->fetchOneListing($listingId);
    }
}
