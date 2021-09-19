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
use App\Models\SuppProdPivots;
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
                    $OrderId = $Order->id;

                    $OrderLines = DB::table('order_lines')
                        ->join('orders', function($join) use($OrderId){
                            $join->on('orders.id', '=', 'order_lines.order_id')
                            ->where('orders.id', '=', $OrderId);
                        })->select('order_lines.*', 'orders.supplier_id');
 
                    $OrderLines = DB::table('supp_prod_pivots')
                        ->joinSub($OrderLines, 'order_lines', function($join){
                            $join->on('supp_prod_pivots.product_id', '=', 'order_lines.product_id');
                        })->where('supp_prod_pivots.supplier_id', '=', $Order->supplier_id)
                        ->orderBy('checked')
                        ->orderBy('location_stop')
                        ->select(
                            'order_lines.*',
                            'supp_prod_pivots.supplier_code',
                            'supp_prod_pivots.supplier_description',
                            'supp_prod_pivots.location_stop'
                        )->get();
                    
                    foreach($OrderLines as $Key => $OrderLine){
                        $ProductUnitsPivots = (new ProductUnitsPivots())
                            ->where('product_id', $OrderLine->product_id)
                            ->where('measure_unit_id', $OrderLine->measure_unit_id)->get();

                        $InternalCode = "";
                        $InternalDescription = "";

                        $Products = (new Products())->where('id', $OrderLine->product_id)->get();
                        if(count($Products) > 0){
                            $Product = $Products[0];
                            $InternalCode = $Product->internal_code;
                            $InternalDescription = $Product->internal_description;
                        }

                        if(count($ProductUnitsPivots) > 0){
                            $ProductUnitsPivot = $ProductUnitsPivots[0];
                            $SuppliersProductsPivots = (new SuppProdPivots())
                            ->where('product_id', $OrderLine->product_id)
                            ->where('supplier_id', $Order->supplier_id)->get();

                            if(count($SuppliersProductsPivots) > 0){
                                $SuppliersProductsPivot = $SuppliersProductsPivots[0];
                                if(strlen($SuppliersProductsPivot->supplier_code) == 0){
                                    $OrderLine->product_code = $Product->internal_code;    
                                }
                                else{
                                    $OrderLine->product_code = $SuppliersProductsPivot->supplier_code;
                                }
                                if(strlen($SuppliersProductsPivot->supplier_description) == 0){
                                    $OrderLine->product_description = $Product->internal_description;
                                }
                                else{
                                    $OrderLine->product_description = $SuppliersProductsPivot->supplier_description;
                                }
                                $OrderLine->location_stop = $SuppliersProductsPivot->location_stop;
                            }
                            else{
                                if(count($Products) > 0){
                                    $Product = $Products[0];
                                    $OrderLine->product_code = $Product->internal_code;
                                    $OrderLine->product_description = $Product->internal_description;
                                    $OrderLine->location_stop = 0;
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
            return view('debug', ['message' => $Message[0]]);
        }
    }

    
    public function ShowDashboard1($request)
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
                    
                    $OrderLines = (new OrderLines())->where('order_id', $Order->id)->orderBy('checked')->get();
                    
                    foreach($OrderLines as $Key => $OrderLine){
                        $ProductUnitsPivots = (new ProductUnitsPivots())
                            ->where('product_id', $OrderLine->product_id)
                            ->where('measure_unit_id', $OrderLine->measure_unit_id)->get();

                        if(count($ProductUnitsPivots) > 0){
                            $ProductUnitsPivot = $ProductUnitsPivots[0];
                            $SuppliersProductsPivots = (new SuppProdPivots())
                            ->where('product_id', $OrderLine->product_id)
                            ->where('supplier_id', $Order->supplier_id)->get();

                            if(count($SuppliersProductsPivots) > 0){
                                $SuppliersProductsPivot = $SuppliersProductsPivots[0];
                                $OrderLine->product_code = $SuppliersProductsPivot->supplier_code;
                                $OrderLine->product_description = $SuppliersProductsPivot->supplier_description;
                                $OrderLine->location_stop = $SuppliersProductsPivot->location_stop;
                            }
                            else{
                                $Products = (new Products())->where('id', $OrderLine->product_id)->get();
                                if(count($Products) > 0){
                                    $Product = $Products[0];
                                    $OrderLine->product_code = "";
                                    $OrderLine->product_description = $Product->internal_description;
                                    $OrderLine->location_stop = 0;
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
            $Orders = (new Orders())->where('id', $OrderId)->get();

            if(count($Orders) > 0){
                $Order = $Orders[0];
                $SupplierId = $Order->supplier_id;

                /* THIS TRANSACTION WILL SET THE completed status ON THE ORDER */
                /* THE QUANTITY THAT WAS AVAILABLE */
                /* AND THE LOCATION STOP SELECTED BY THE PICKUP GUY */
                
                
                // ORDER COMPLETED
                DB::beginTransaction();
                DB::table('orders')->where('id', $OrderId)->update(['completed' => true]);

                foreach($OrderLines as $key => $OrderLine){
                    // AVAILABLE QUANTITY
                    DB::table('order_lines')->where('id', $OrderLine['id'])
                        ->update(['available_qty' => $OrderLine['available_qty']]);

                    $Lines = (new OrderLines())->where('id', $OrderLine['id'])->get();
                    
                    if(count($Lines) > 0){
                        $ProductId = $Lines[0]->product_id;
                        // LOCATION STOP
                        DB::table('supp_prod_pivots')
                            ->where('supplier_id', $SupplierId)
                            ->where('product_id', $ProductId)->update(['location_stop' => $OrderLine['location_stop']]);
                    }
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

    public function CheckOrderLine($request)
    {
        # code...
        try {
            //code...
            $Id = $request["id"];
            $Checked = $request["checked"];
            (new OrderLines())->where('id', $Id)->update(['checked' => $Checked]);
            return ['status' => 'ok'];
        } catch (\Throwable $th) {
            //throw $th;
            $Message = (new ErrorInfo())->GetErrorInfo($th);
            return ['status' => 'error', 'message' => $Message];
        }
    }
}
