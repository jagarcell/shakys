<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PickupUserController extends Controller
{
    //
    public function ShowDashboard(Request $request)
    {
        # code...
        return view('/pickupdashboard');
    }
}
