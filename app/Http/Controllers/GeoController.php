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
        $url = 'http://ip-api.com/json/96.225.102.144';
        $curl = curl_init($url);
        return curl_exec($curl);

        $tz = file_get_contents($url);
        $tz = json_decode($tz,true)['timezone'];
        return $tz;
    }
}
