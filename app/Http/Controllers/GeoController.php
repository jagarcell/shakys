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
        $url = 'http://ipecho.net/plain';
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);        
        $resp = curl_exec($curl);
        curl_close($curl);
        return $resp;
        $tz = file_get_contents($url);
        $tz = json_decode($tz,true)['timezone'];
        return $tz;
    }
}
