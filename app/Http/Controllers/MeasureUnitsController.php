<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\MeasureUnits;

class MeasureUnitsController extends Controller
{
    //
    public function CreateMeasureUnit(Request $request)
    {
        return (new MeasureUnits())->CreateMeasureUnit($request);
    }

    public function RemoveMeasureUnit(Request $request)
    {
        return (new MeasureUnits())->RemoveMeasureUnit($request);
    }
}
