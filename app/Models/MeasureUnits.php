<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Models\Products;
use App\Models\ProductUnitsPivots;
use App\Errors\ErrorInfo;

class MeasureUnits extends Model
{
    use HasFactory;

    /**
     * 
     * @param Request
     * 
     * @return View
     * 
     */
    public function MeasureUnits($request)
    {
        try {
            $MeasureUnits = $this->where('id', '>', -1)->get();
            return view('measureunits', ['measureunits' => $MeasureUnits]);
        } catch (\Throwable $th) {
            //throw $th;
            $Message = (new ErrorInfo())->GetErrorInfo($th);
            return view('debug', ['message' => $Message]);
        }
    }

    /**
     * 
     * @param Request unit_description element_tag
     * 
     * @return String status ok error 419
     * 
     */
    public function CreateMeasureUnit($request)
    {
        try {
            $UnitDescription = $request['unit_description'];
            $ElementTag = $request['element_tag'];
            
            $this->unit_description = $UnitDescription;
            $this->save();
            $MeasureUnit = new \stdClass();
            $MeasureUnit->id = $this->id;
            $MeasureUnit->unit_description = $this->unit_description;
            return ['status' => 'ok', 'measureunit' => $MeasureUnit, 'element_tag' => $ElementTag];
        } catch (\Throwable $th) {
            $Message = (new ErrorInfo())->GetErrorInfo($th);
            return ['status' => 'error', 'message' => $Message, 'element_tag' => $ElementTag];
        }
    }

    /**
     * 
     * @param Request id, element_tag, verbose 
     * 
     * @return String status ok notfound inuse error 419
     * 
     */

    public function RemoveMeasureUnit($request)
    {
        try {
            $MeasureUnitId = $request['id'];
            $ElementTag = $request['element_tag'];
            $Verbose = isset($request['verbose']) ? $request['verbose'] : false;
            DB::beginTransaction();
            $MeasureUnits = DB::table('measure_units')->where('id', '=', $MeasureUnitId)->get();
            if(count($MeasureUnits) == 0){
                return ['status' => 'notfound', 'element_tag' => $ElementTag];
            }
            $MeasureUnit = $MeasureUnits[0];

            $ProductUnitsPivots = DB::table('product_units_pivots')->where('measure_unit_id', '=', $MeasureUnit->id)->get();
            if($Verbose){
                if(count($ProductUnitsPivots) > 0){
                    return ['status' => 'inuse', 'measureunit' => $MeasureUnit, 'element_tag' => $ElementTag];
                }
            }
            
            DB::table('product_units_pivots')->where('measure_unit_id', '=', $MeasureUnit->id)->delete();
            DB::table('measure_units')->where('id', '=', $MeasureUnitId)->delete();
            DB::commit();
            return ['status' => 'ok', 'element_tag' => $ElementTag];
        } catch (\Throwable $th) {
            DB::rollBack();
            $Message = (new ErrorInfo())->GetErrorInfo($th);
            return ['status' => 'error', 'message' => $Message, 'element_tag' => $ElementTag];
        }
    }

    /**
     * 
     * @param Request 'id' 'elemnt_tag'
     * 
     * @return String status 'ok' 'error' 'notfound'
     * 
     */
    public function GetMeasureUnit($request)
    {
        try {
            $MeasureId = $request['id'];
            $ElementTag = $request['element_tag'];

            $MeasureUnits = $this->where('id', $MeasureId)->get();
            if(count($MeasureUnits) == 0){
                return ['status' => 'notfound', 'element_tag' => $ElementTag];
            }

            $MeasureUnit = $MeasureUnits[0];
            return ['status' => 'ok', 'measureunit' => $MeasureUnit, 'element_tag' => $ElementTag];
        } catch (\Throwable $th) {
            //throw $th;
            $Message = (new ErrorInfo())->GetErrorInfo($th);
            return ['status' => 'error', 'message' => $Message, 'element_tag' => $ElementTag];
        }
    }

    /**
     * 
     * @param Request 'id' 'unit_description' 'element_tag'
     * 
     * @return String status 'ok' 'error' 'notfound' '419'
     * 
     */
    public function UpdateMeasureUnit($request)
    {
        try {
            $Id = $request['id'];
            $UnitDescription = $request['unit_description'];
            $ElementTag = $request['element_tag'];

            $MeasureUnits = $this->where('id', $Id)->get();
            if(count($MeasureUnits) == 0){
                return ['status' => 'notfound', 'element_tag' => $ElementTag]; 
            }

            $RecordsUpdated = $this->where('id', $Id)->update(['unit_description' => $UnitDescription]);
            $MeasureUnit = (['id' => $Id, 'unit_description' => $UnitDescription]);
            return ['status' => 'ok', 'measureunit' => $MeasureUnit, 'element_tag' => $ElementTag];

        } catch (\Throwable $th) {
            //throw $th;
            $Message = (new ErrorInfo())->GetErrorInfo($th);
            return ['status' => 'error', 'message' => $Message, 'element_tag' => $ElementTag];
        }
    }
}
