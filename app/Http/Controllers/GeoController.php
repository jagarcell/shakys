<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GeoController extends Controller
{
    //
    public function getGeoLocation()
    {
        # code...
        return view('geolocation');
    }
}
