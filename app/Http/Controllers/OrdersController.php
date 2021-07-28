<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orders;

class OrdersController extends Controller
{
    //
    public function AddToOrder(Request $request)
    {
        # code...
        return (new Orders())->AddToOrder($request);
    }

    public function SubmitOrder(Request $request)
    {
        # code...
        return (new Orders())->SubmitOrder($request);
    }

    public function EmailOrder(Request $request)
    {
        # code...
        return (new Orders())->EmailOrder($request);
    }

    public function OrderPreview(Request $request)
    {
        # code...
        return (new Orders())->OrderPreview($request);
    }
    
    public function ExportToPdf(Request $request)
    {
        # code...
        return (new Orders())->ExportToPdf($request);
    }

    public function ReceiveOrder(Request $request)
    {
        return (new Orders())->ReceiveOrder($request);
    }
}
