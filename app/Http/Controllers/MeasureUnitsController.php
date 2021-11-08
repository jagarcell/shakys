<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\MeasureUnits;

class MeasureUnitsController extends Controller
{
    public function MeasureUnits2(Request $request)
    {
        # code...
        return (new MeasureUnits())->MeasureUnits($request);
    }
    public function MeasureUnits(Request $request)
    {
        # code...
        return (new MeasureUnits())->MeasureUnits($request);
    }
    //
    public function CreateMeasureUnit(Request $request)
    {
        return (new MeasureUnits())->CreateMeasureUnit($request);
    }

    public function RemoveMeasureUnit(Request $request)
    {
        return (new MeasureUnits())->RemoveMeasureUnit($request);
    }

    public function GetMeasureUnit(Request $request)
    {
        return (new MeasureUnits())->GetMeasureUnit($request);
    }

    public function UpdateMeasureUnit(Request $request)
    {
        return (new MeasureUnits())->UpdateMeasureUnit($request);
    }

    public function searchByText(Request $request)
    {
        # code...
        return (new MeasureUnits())->searchByText($request);
    }
}
