<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class GeoController extends Controller
{
    //
    public function getGeoLocation()
    {
        # code...
        return view('geolocation');
    }

    public function getClientIp(Request $request)
    {
        
        return $request->ip();
    }
}
