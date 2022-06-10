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

    public function getClientIp()
    {
        $ip = Arr::get($_SERVER, 'HTTP_CLIENT_IP', Arr::get($_SERVER, 'HTTP_X_FORWARDED_FOR', Arr::get($_SERVER, 'HTTP_X_FORWARDED', Arr::get($_SERVER, 'HTTP_FORWARDED_FOR', Arr::get($_SERVER, 'HTTP_FORWARDED', Arr::get($_SERVER, 'REMOTE_ADDR', '127.0.0.1'))))));

        $ip = explode(',', $ip);
        $ip = trim(head($ip));
        return $ip;
    }
}
