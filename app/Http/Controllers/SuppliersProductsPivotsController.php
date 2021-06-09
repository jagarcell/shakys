<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\SuppliersProductsPivots;

class SuppliersProductsPivotsController extends Controller
{

    public function CreatePivot(Request $request)
    {
        # code...
        return (new SuppliersProductsPivots())->CreatePivot($request);
    }

    public function GetPivot(Request $request)
    {
        # code...
        return (new SuppliersProductsPivots())->GetPivot($request);
    }
}
