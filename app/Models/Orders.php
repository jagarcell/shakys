<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use App\Models\OrderLines;
use App\Models\Products;
use App\Models\Suppliers;
use App\Models\Users;
use App\Mail\OrderEmail;
use App\Models\SuppliersProductsPivots;

class Orders extends Model
{
    use HasFactory;

    /**
     * 
     * Create a purchase order
     * 
     * @param Request [supplier_id, product_id, qty, pickup, pickup_guy_id]
     * 
     * @return String status [ok, error, 419]
     * @return String message (if error)
     * 
     */
    public function AddToOrder($request)
    {
        try {
            $SupplierId = $request['supplier_id'];
            $ProductId = $request['product_id'];
            $Qty = $request['qty'];
            $Pickup = $request['pickup'];
            $PickupGuyId = $request['pickup_guy_id'];
            $ElementTag = $request['element_tag'];

            $Suppliers = (new Suppliers())->where('id', $SupplierId)->get();
            // Check if the supplier exists
            if(count($Suppliers) == 0){
                return ['status' => 'notfound', 'message' => 'Supplier Not Found', 'element_tag' => $ElementTag];
            }
            $Supplier = $Suppliers[0];

            $Products = (new Products())->where('id', $ProductId)->get();
            // Check if the product exists
            if(count($Products) == 0){
                return ['status' => 'notfound', 'message' => 'Product Not Found', 'element_tag' => $ElementTag];
            }
            $Product = $Products[0];

            // Check if the supplier is for pickup or delivery    
            if($Pickup == 'pickup'){
                $PickupUsers = (new Users())->where('user_type', 'pickup')->where('id', $PickupGuyId)->get();
                // Check if a valid pickup user was specified
                if(count($PickupUsers) == 0){
                    return ['status' => 'notfound', 'message' => 'Invalid pickup user specified', 'element_tag' => $ElementTag];
                }
                $PickupUser = $PickupUsers[0];
                $PickupGuyId = $PickupUser->id;
            }
            else{
                $PickupGuyId = -1;
            }

            // Search for a matching order in progress
            $Orders = $this->where('supplier_id', $SupplierId)
                            ->where('pickup', $Pickup)
                            ->where('pickup_guy_id', $PickupGuyId)
                            ->where('submitted', false)->get();

            // Check if there is an ongoing order for this supplier,
            // it means, an order that hasn't been submitted
            if(count($Orders) > 0){
                // Ongoing order
                $Order = $Orders[0];
                $Products = (new OrderLines())->where('order_id', $Order->id)->where('product_id', $ProductId)->get();
                // Check if the product is already in the ongoing order
                if(count($Products) > 0){
                    // Lets add quantity to this products
                    $Product = $Products[0];
                    $Qty += $Product->qty;
                    (new OrderLines())->where('id', $Product->id)->where('order_id', $Order->id)->update(['qty' => $Qty]);
                }
                else{
                    // We add the new product to the order lines
                    $OrderLines = (new OrderLines());
                    $OrderLines->order_id = $Order->id;
                    $OrderLines->product_id = $ProductId;
                    $OrderLines->qty = $Qty;
                    $OrderLines->save();
                }
            }
            else{
                // Let's create a new order
                $this->supplier_id = $SupplierId;
                $this->date = new \DateTime();
                $this->pickup = $Pickup;
                $this->pickup_guy_id = $PickupGuyId;
                $this->save();

                $OrderLines = (new OrderLines());
                $OrderLines->order_id = $this->id;
                $OrderLines->product_id = $ProductId;
                $OrderLines->qty = $Qty;
                $OrderLines->save();
            }

            (new Products())->where('id', $ProductId)->update(['default_supplier_id' => $Supplier->id, 'qty_to_order' => 0]);

            (new Suppliers())->where('id', $SupplierId)->update(['pickup' => $Pickup, 'last_pickup_id' => $PickupGuyId]);

            return ['status' => 'ok', 'element_tag' => $ElementTag];
        } catch (\Throwable $th) {
            //throw $th;
            $Message = $this->ErrorInfo($th);
            return ['status' => 'error', 'message' => $Message, 'element_tag' => $ElementTag];
        }
    }

    /**
     * 
     * Update an order (Lines and Header)
     * 
     * @param Request [
     *                  order_id, supplier_id, order_type, pickup_user_id, 
     *                  order_lines => {line_id, qty}
     *                ]
     * 
     * @return String ['status' => 'ok', 'error']
     * 
     *  
     */

     public function SubmitOrder($request)
     {
         # code...
        try {
            $OrderId = $request['order_id'];
            $SupplierId = $request['supplier_id'];
            $OrderType = $request['order_type'];
            $PickupUserId = $request['pickup_user_id'];
            $OrderLines = $request['order_lines'];
            $ElementTag = $request['element_tag'];

            if($PickupUserId === null){
                $PickupUserId = -1;
            }    
            DB::beginTransaction();
            foreach($OrderLines as $Key => $OrderLine){
                DB::table('order_lines')->where('id', $OrderLine['id'])->update(['qty' => $OrderLine['qty']]);
            }
            DB::table('orders')->where('id', $OrderId)
                ->update(
                    [
                        'supplier_id' => $SupplierId, 
                        'pickup' => $OrderType,
                        'pickup_guy_id' => $PickupUserId,
                        'submitted' => true,
                    ]);

            DB::commit();
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            $Message = $this->ErrorInfo($th);
            return ['status' => 'error', 'message' => $Message, 'element_tag' => $ElementTag];
        }
        try {
            //code...

        } catch (\Throwable $th) {
            //throw $th;
        }
        return ['status' => 'ok', 'element_tag' => $ElementTag];
     }

     /**
      * 
      * @param Request $request ['id']
      *
      * @return String ['status' => 'ok' 'error' 'not found']
      * @return String ['message' => 'error message']
      * @return Mixed  ['element_tag' => 'element_tag']
      */

      
     public function EmailOrder($request)
     {
        try {
            // Set the parameters
            $OrderId = $request['order_id'];
            $ElementTag = $request['element_tag'];

            // Find the order
            $Orders = $this->where('id', $OrderId)->get();

            if(count($Orders) > 0){
                $Order = $Orders[0];
                $OrderLines = (new OrderLines())->where('order_id', $Order->id)->get();
                foreach($OrderLines as $Key => $OrderLine){
                    $Products = (new Products())->where('id', $OrderLine->product_id)->get();
                    $OrderLine->product_code = "";
                    $OrderLine->product_description = "";
                    if(count($Products) > 0){
                        $Product = $Products[0];
                        $SuppliersProductsPivots = (new SuppliersProductsPivots())
                            ->where('supplier_id', $Order->supplier_id)
                            ->where('product_id', $Product->id)->get();
                        if(count($SuppliersProductsPivots) > 0){
                            $SuppliersProductsPivot = $SuppliersProductsPivots[0];
                            $OrderLine->product_code = $SuppliersProductsPivot->supplier_code;
                            $OrderLine->product_description = $SuppliersProductsPivot->supplier_description;
                        }
                        else{
                            $OrderLine->product_code = $Product->internal_code;
                            $OrderLine->product_description = $Product->internal_description;
                        }
                    }
                }

                $Order->lines = $OrderLines;

                $Order->supplier_name = "";
                $Order->email = "";
 
                $Suppliers =  (new Suppliers())->where('id', $Order->supplier_id)->get();
                if(count($Suppliers) > 0){
                    $Order->supplier_name = $Suppliers[0]->name;
                    $Order->email = $Suppliers[0]->email;
                    $Order->supplier_address = $Suppliers[0]->address;
                }
                
                $Order->pickup_user_name = "";
                $PickupUsers = (new Users())->where('id', $Order->pickup_guy_id)->get();
                if($Order->pickup == 'pickup'){
                    if(count($PickupUsers) > 0){
                        $PickupUser = $PickupUsers[0];
                        $Order->pickup_user_name = $PickupUser->name;
                        $Order->email = $PickupUser->email;
                    }
                    $Subject = "Order for pickup";
                    $Order->instructions1 = "Please pickup this order from:";
                    $Order->instructions2 = $Order->supplier_name;
                    $Order->instructions3 = $Order->supplier_address;
                }
                else{
                    $Subject = "Shaky's Purchase Order";
                    $Order->instructions1 = "Please deliver this order to:";
                    $Order->instructions2 = "Shaky's Juice Bar & Cafe";
                    $Order->instructions3 = "6235 Kennedy Blvd, Local #1";
                    $Order->instructions4 = "North Bergen, NJ, 074407";
                    $Order->instructions5 = "Phone: +1 201-520-9351";
                }
            }
            else{
                return ['status' => 'notfound', 'element_tag' => $ElementTag];
            }
        } catch (\Throwable $th) {
            //throw $th;
            $Message = $this->ErrorInfo($th);
            return ['status' => 'error', 'message' => $Message, 'element_tag' => $ElementTag, 'th' => $th];
        }
        try {
            //code...
            if(strlen($Order->email) > 0){
//                Mail::to($Order->email)->send((new OrderEmail($Order))->subject($Subject));
            }
            else{
                return ['status' => 'noemail', 'element_tag' => $ElementTag];
            }
        } catch (\Exception $th) {
            //throw $th;
            $Message = $this->ErrorInfo($th);
            return ['status' => 'error', 'message' => $Message, 'element_tag' => $ElementTag, 'th' => $th];
        }
        return ['status' => 'ok', 'order' => $Order, 'element_tag' => $ElementTag];
     }

     /**
      * 
      * @param Request ['id' 'order_lines:{id, avilable_qy}' 'element_tag']
      *
      * @return String status ['ok' 'error' 'notfound' '419']
      * 
      */
     public function ReceiveOrder($request)
     {
         # code...
         $Id = $request['id'];
         $OrderLines = $request['order_lines'];
         $ElementTag = $request['element_tag'];

         try {
             //code...
             $Orders = $this->where('id', $Id)->get();
             if(count($Orders) == 0){
                return ['status' => 'notfound', 'element_tag' => $ElementTag];
             }
             DB::beginTransaction();
             foreach($OrderLines as $Key => $OrderLine){
                 DB::table('order_lines')->where('id', $OrderLine['id'])->update(['available_qty' => $OrderLine['available_qty']]);
             }
             DB::table('orders')->where('id', $Orders[0]->id)->update(['received' => true]);
             DB::commit();
             return ['status' => 'ok', 'order_line' => $OrderLines, 'element_tag' => $ElementTag];
         } catch (\Throwable $th) {
             //throw $th;
             DB::rollback();
             $Message = $this->ErrorInfo($th);
             return ['status' => 'error', 'message' => $Message, 'element_tag' => $ElementTag];
         }
     }

     /**
     * 
     * @param $th
     * 
     * @return $Message
     * 
     * 
     */
    public function ErrorInfo($th)
    {
        # code...
        if(!property_exists($th, 'errorInfo') || count($th->errorInfo) == 0){
            $Message = ["Undefined Server Error"];
        }
        else{
            $Message = $th->errorInfo[2];
        }
        return $Message;
    }
}
