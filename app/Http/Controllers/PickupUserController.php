<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PickupUser;

class PickupUserController extends Controller
{
    //
    public function ShowDashboard(Request $request)
    {
        # code...
        return (new PickupUser())->ShowDashboard($request);
    }

    public function CompleteOrder(Request $request)
    {
        return (new PickupUser())->CompleteOrder($request);
    }
}
