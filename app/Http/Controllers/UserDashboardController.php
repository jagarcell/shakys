<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserDashboard;

class UserDashboardController extends Controller
{
    //
    public function ShowUserDashboard(Request $request)
    {
        # code...
        return (new UserDashboard())->ShowUserDashboard($request);
    }

    public function ProductsToCount(Request $request)
    {
        # code
        return (new UserDashboard())->ProductsToCount($request);
    }

    public function SearchFor(Request $request)
    {
        # code...
        return (new UserDashboard())->SearchFor($request);
    }
}
