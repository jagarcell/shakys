<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Errors\ErrorInfo;
use App\Models\Orders;
use App\Models\Suppliers;
use App\Models\OrderLines;
use App\Models\Products;
use App\Models\MeasureUnits;
use App\Models\SuppliersProductsPivots;
use App\Models\ProductUnitsPivots;

class PickupUser extends Model
{
    use HasFactory;
    //
    public function ShowDashboard($request)
    {
        # code...
        try {
            //code...
            $PickupUser = Auth::User();
            if($PickupUser !== null){
                $Orders = (new Orders())
                    ->where('pickup', 'pickup')
                    ->where('pickup_guy_id', $PickupUser->id)
                    ->where('submitted', true)
                    ->where('received', false)
                    ->where('completed', false)->get();
                foreach($Orders as $Key => $Order){
                    $Suppliers = (new Suppliers())->where('id', $Order->supplier_id)->get();
                    if(count($Suppliers) > 0){
                        $Supplier = $Suppliers[0];
                        $Order->supplier_name = $Supplier->name;
                        $Order->supplier_address = $Supplier->address;
                    }
                    else{
                        return view('debug', ['message' => 'Somenthing went Wrong Searching The Supplier']);
                    }
                    
                    $OrderLines = (new OrderLines())->where('order_id', $Order->id)->get();
                    
                    foreach($OrderLines as $Key => $OrderLine){
                        $ProductUnitsPivots = (new ProductUnitsPivots())
                            ->where('product_id', $OrderLine->product_id)
                            ->where('product_units_pivot_id', $OrderLine->product_units_pivots_id)->get();

                        if(count($ProductUnitsPivots) > 0){
                            $ProductUnitsPivot = $ProductUnitsPivots[0];
                            $SuppliersProductsPivots = (new SuppliersProductsPivots())
                            ->where('product_units_pivot_id', $ProductUnitsPivot->id)
                            ->where('supplier_id', $Order->supplier_id)->get();

                            if(count($SuppliersProductsPivots) > 0){
                                $SuppliersProductsPivot = $SuppliersProductsPivots[0];
                                $OrderLine->product_code = $SuppliersProductsPivot->supplier_code;
                                $OrderLine->product_description = $SuppliersProductsPivot->supplier_description;
                            }
                            else{
                                $Products = (new Products())->where('id', $OrderLine->product_id)->get();
                                if(count($Products) > 0){
                                    $Product = $Products[0];
                                    $OrderLine->product_code = "";
                                    $OrderLine->product_description = $Product->internal_description;
                                }
                                else{
                                    $OrderLine->product_code = "ERROR";
                                    $OrderLine->product_description = "THIS PRODUCT WAS NOT FOUND";
                                }
                            }

                        }

                        $MeasureUnits = (new MeasureUnits())->where('id', $OrderLine->measure_unit_id)->get();
                        if(count($MeasureUnits) > 0){
                            $MeasureUnit = $MeasureUnits[0];
                            $OrderLine->unit_description = $MeasureUnit->unit_description;
                        }
                        else{
                            $OrderLine->unit_description = "";
                        }
                    }
                    $Order->lines = $OrderLines;
                }
                return view('/pickupdashboard', ['orders' => $Orders]);
            }
            else{
                return redirect('/login');
            }
        } catch (\Throwable $th) {
            //throw $th;
            $Message = (new ErrorInfo())->GetErrorInfo($th);
            return view('debug', ['message' => $Message]);
        }
    }

    /**
     * 
     * @param Request ['id' 'order_lines' 'element_tag']
     * 
     * @return String status 'ok' 'notfound' 'error' '419'
     * 
     */
    public function CompleteOrder($request)
    {
        # code...
        $OrderId = $request['id'];
        $OrderLines = $request['order_lines'];
        $ElementTag = $request['element_tag'];

        try {
            //code...
            $Orders = DB::table('orders')->where('id', $OrderId)->get();
            if(count($Orders) > 0){
                DB::beginTransaction();
                DB::table('orders')->where('id', $OrderId)->update(['completed' => true]);
                foreach($OrderLines as $key => $OrderLine){
                    DB::table('order_lines')->where('id', $OrderLine['id'])->update(['available_qty' => $OrderLine['available_qty']]);
                }
                DB::commit();
                return ['status' => 'ok', 'element_tag' => $ElementTag];
            }
            else{
                return ['status' => 'notfound', 'element_tag' => $ElementTag];
            }
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            $Message = (new ErrorInfo())->GetErrorInfo($th);
            return ['status' => 'error', 'message' => $Message];
        }
    }    
}
