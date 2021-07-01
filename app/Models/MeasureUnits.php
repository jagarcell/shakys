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
}
