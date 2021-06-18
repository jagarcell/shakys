<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PendingOrders;

class PendingOrdersController extends Controller
{
    /**
     * 
     * @return view pendingorders
     * 
     * */    
    function ShowPendingOrdersPanel(Request $request)
    {
        return (new PendingOrders())->ShowPendingOrdersPanel($request);
    }

    public function GetPricesForSupplier(Request $request)
    {
        # code...
        return (new PendingOrders())->GetPricesForSupplier($request);
    }
}
