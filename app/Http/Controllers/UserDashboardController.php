<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Products;
use App\Models\ProductLocations;

class UserDashboardController extends Controller
{
    //
    public function ShowUserDashboard(Request $request)
    {
        # code...
        $Date = new \DateTime();
        $Products = (new Products())->where('counted', false)->where('next_count_date', '<=', $Date)->get();

        $Locations = (new ProductLocations)->where('id', '>', -1)->get();

        $LocationsCount = count($Locations);
        if(round($LocationsCount / 2) * 2 != $LocationsCount){
            $Locations[$LocationsCount - 1]->odd = true;
        }
        return view('userdashboard', ['locations' => $Locations, 'products' => $Products]);
    }

    public function ProductsToCount(Request $request)
    {
        # code...
        if(isset($request['locationid'])){
            $LocationId = $request['locationid'];
        }
    }
}
