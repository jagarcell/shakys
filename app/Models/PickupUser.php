<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use App\Errors\ErrorInfo;
use App\Models\Orders;
use App\Models\Suppliers;
use App\Models\OrderLines;
use App\Models\Products;
use App\Models\MeasureUnits;
use App\Models\SuppProdPivots;
use App\Models\ProductUnitsPivots;
use App\Models\Users;
use App\Mail\UnavailablesEmail;

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

                    $Order->lines = [];

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
                            $SuppliersProductsPivots = (new SuppProdPivots())
                            ->where('product_id', $OrderLine->product_id)
                            ->where('supplier_id', $Order->supplier_id)->get();

                            if(count($SuppliersProductsPivots) > 0){
                                $SuppliersProductsPivot = $SuppliersProductsPivots[0];
                                if(strlen($SuppliersProductsPivot->supplier_code) == 0){
                                    $OrderLine->product_code = $InternalCode;    
                                }
                                else{
                                    $OrderLine->product_code = $SuppliersProductsPivot->supplier_code;
                                }
                                if(strlen($SuppliersProductsPivot->supplier_description) == 0){
                                    $OrderLine->product_description = $InternalDescription;
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

                    $Order->lines = [];                    
                }
                return view('pickupdashboard', ['orders' => $Orders]);
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
                
                // SET ORDER AS COMPLETED
                DB::beginTransaction();
                DB::table('orders')->where('id', $OrderId)->update(['completed' => true]);

                foreach($OrderLines as $key => $OrderLine){
                    // SET AVAILABLE QUANTITY
                    DB::table('order_lines')->where('id', $OrderLine['id'])
                        ->update(['available_qty' => $OrderLine['available_qty']]);

                    $LinesSet = (new OrderLines())->where('id', $OrderLine['id']);

                    $Lines = $LinesSet->get();
                    
                    if(count($Lines) > 0){
                        $ProductId = $Lines[0]->product_id;
                        // SET LOCATION STOP
                        DB::table('supp_prod_pivots')
                            ->where('supplier_id', $SupplierId)
                            ->where('product_id', $ProductId)->update(['location_stop' => $OrderLine['location_stop']]);
                        if($Lines[0]->available_qty < $Lines[0]->qty){
                            $LinesSet->update(['not_found' => 1]);
                        }    
                    }
                }

                DB::commit();

                $sendResult = $this->sendUnavailablesEmail($Order->id);
                return ['status' => 'ok', 'unavailables' => $sendResult['Unavailables'], 'mailsent' => $sendResult['MailSent'], 'mailfail' => $sendResult['MailFail'], 'element_tag' => $ElementTag];
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

    public function sendUnavailablesEmail($orderId)
    {
        # code...
        
        $Unavailables = DB::select(
            "SELECT orders.*, suppliers.name 
            AS supplier_name, users.name 
            AS pickup_guy_name 
            FROM orders 
            INNER JOIN suppliers 
            ON orders.supplier_id = suppliers.id 
            INNER JOIN users 
            ON orders.pickup_guy_id = users.id 
            WHERE orders.id = :orderId", ['orderId' => $orderId]);

        $UnavailableLines = DB::select(
            "SELECT order_lines.*, products.internal_description 
            FROM order_lines 
            INNER JOIN products on products.id = order_lines.product_id 
            WHERE order_lines.order_id = :unavailableId
            AND (order_lines.available_qty < order_lines.qty)",
            ['unavailableId', $Unavailables[0]->id]
        );

        $Unavailables[0]->lines = $UnavailableLines;
        $Unavailables[0]->homePage = env('APP_URL');
        $Unavailables[0]->user_name = Auth::user()->name;

        $MailSent = [];
        $MailFail = [];

        if(count($UnavailableLines) > 0){

            $UnavailablesEmail = new UnavailablesEmail($Unavailables);

            $Subject = "Products not found!";
            $AdminUsers = (new Users())->where('user_type', 'admin')->get();
            foreach($AdminUsers as $key => $AdminUser){
                try {
                    //code...
                    if($AdminUser->email !== null && strlen($AdminUser->email) > 0){
                        Mail::to($AdminUser->email)->send(($UnavailablesEmail)->subject($Subject));
                        array_push($MailSent, $AdminUser->email);
                    }
                } catch (\Throwable $th) {
                    //throw $th;
                    $Message = (new ErrorInfo())->GetErrorInfo($th);
                    array_push($MailFail, [$AdminUser->email => $Message]);
                }
            }
        }
        else{
            $Unavailables = [];
        }
        return ['Unavailables' => $Unavailables, 'MailSent' => $MailSent, 'MailFail' => $MailFail];
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
