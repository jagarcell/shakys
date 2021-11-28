<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

use App\Models\OrderLines;
use App\Models\Products;
use App\Models\Suppliers;
use App\Models\Users;
use App\Mail\OrderEmail;
use App\Mail\UnavailablesEmail;
use App\Models\SuppliersProductsPivots;
use App\Models\ProductUnitsPivots;
use App\Models\SuppProdPivots;
use App\Errors\ErrorInfo;
use Barryvdh\DomPDF\Facade as PDF;

class Orders extends Model
{
    use HasFactory;

    /**
     * 
     * Create a purchase order
     * 
     * @param Request [supplier_id, product_id, qty, measure_unit_id pickup, pickup_guy_id]
     * 
     * @return String status [ok, error, 419]
     * @return String message (if error)
     * 
     */
    public function AddToOrder($request)
    {
        try {
            $productsToOrder = $request['productsToOrder'];
            $elementTags = [];
            $ProcessedProducts = [];
            $ResOrders = [];

            foreach($productsToOrder as $key => $productToOrder){
                $SupplierId = $productToOrder['supplier_id'];
                $ProductId = $productToOrder['product_id'];
                $Qty = $productToOrder['qty'];
                $OriginalUnitId = $productToOrder['original_unit_id'];
                $MeasureUnitId = $productToOrder['measure_unit_id'];
                $Pickup = $productToOrder['pickup'];
                $PickupGuyId = $productToOrder['pickup_guy_id'];
                $ElementTag = $productToOrder['element_tag'];

                // If some quantity was requested to be ordered ...
                if($Qty > 0){
                    array_push($ProcessedProducts, $productToOrder);
                    // ... then let's continue to put it on an order
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

                    array_push($ResOrders, $Orders);

                    // Check if there is an ongoing order for this supplier,
                    // it means, an order that hasn't been submitted
                    if(count($Orders) > 0){
                        array_push($ResOrders, 'found - ' . $SupplierId);
                        // Ongoing order
                        $Order = $Orders[0];
                        $Products = (new OrderLines())
                            ->where('order_id', $Order->id)
                            ->where('product_id', $ProductId)
                            ->where('measure_unit_id', $MeasureUnitId)->get();
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
                            $OrderLines->measure_unit_id = $MeasureUnitId;
                            $OrderLines->save();
                        }
                    }
                    else{
                        // Let's create a new order
                        array_push($ResOrders, 'not found - ' . $SupplierId);

                        $Order = (new Orders());
                        $Order->supplier_id = $SupplierId;
                        $Order->date = new \DateTime();
                        $Order->pickup = $Pickup;
                        $Order->pickup_guy_id = $PickupGuyId;
                        $Order->save();

                        $OrderLines = (new OrderLines());
                        $OrderLines->order_id = $Order->id;
                        $OrderLines->product_id = $ProductId;
                        $OrderLines->qty = $Qty;
                        $OrderLines->measure_unit_id = $MeasureUnitId;
                        $OrderLines->save();
                    }

                    $SuppProdPivots = (new SuppProdPivots())
                        ->where('product_id', $ProductId)
                        ->where('supplier_id', $SupplierId)->get();
                    
                    if(count($SuppProdPivots) == 0){
                        $SuppProdPivots = (new SuppProdPivots());
                        $SuppProdPivots->supplier_id = $SupplierId;
                        $SuppProdPivots->product_id = $ProductId;
                        $SuppProdPivots->supplier_code = "";
                        $SuppProdPivots->supplier_description = "";
                        $SuppProdPivots->location_stop= -1;
                        $SuppProdPivots->save();
                    }

                    (new Products())->where('id', $ProductId)->update(['default_supplier_id' => $Supplier->id]);

                }

                (new Products())->where('id', $ProductId)->update(['qty_to_order' => 0]);

                (new ProductUnitsPivots())->where('product_id', $ProductId)
                    ->where('measure_unit_id', $OriginalUnitId)->update(['qty_to_order' => 0]);

                (new Suppliers())->where('id', $SupplierId)->update(['pickup' => $Pickup, 'last_pickup_id' => $PickupGuyId]);

                (new OrderLines())->where('product_id', $ProductId)
                    ->where('measure_unit_id', $MeasureUnitId)
                    ->where('not_found', 1)->update(['not_found' => 0]);
                array_push($elementTags, $ElementTag);                
            }

            return ['status' => 'ok', 'element_tags' => $elementTags, 'resorders' => $ResOrders, 'processedproducts' => $ProcessedProducts];
        } catch (\Throwable $th) {
            //throw $th;
            $Message = $this->ErrorInfo($th);
            return ['status' => 'error', 'message' => $Message];
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
        
        $Result = $this->EmailOrder($request);
        switch ($Result['status']) {
            case 'ok':
                # code...
                return ['status' => 'ok', 'order' => $Result['order'], 'element_tag' => $ElementTag];
                break;

            case 'error':
                return ['status' => 'emailnotsent', 'message' => $Result['message'], 'element_tag' => $ElementTag];    
                break;
            case 'noemail':
                return ['status' => 'noemail', 'element_tag' => $ElementTag];
                break;    
            default:
                # code...
                break;
        }
     }

     /**
      * 
      * @param Request $request ['id']
      *
      * @return String ['status' => 'ok' 'error' 'notfound']
      * @return String ['message' => 'error message']
      * @return Mixed  ['element_tag' => 'element_tag']
      *
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
                    
                    $product_code = "No Code Asiggned";
                    $product_description = "No Description Entered";

                    if(count($Products) > 0){
                        $Product = $Products[0];

                        $ProductUnitsPivots = (new ProductUnitsPivots())
                        ->where('product_id', $OrderLine->product_id)
                        ->where('measure_unit_id', $OrderLine->measure_unit_id)->get();

                        if(count($ProductUnitsPivots) > 0){
                            return ['result' => $request['order_id']];
                            $ProductUnitsPivot = $ProductUnitsPivots[0];

                            $SuppliersProductsPivots = (new SuppProdPivots())
                            ->where('supplier_id', $Order->supplier_id)
                            ->where('product_id', $Product->id)->get();

                            $product_code = $Product->internal_code;
                            $product_description = $Product->internal_description;

                            if(count($SuppliersProductsPivots) > 0){
                                $SuppliersProductsPivot = $SuppliersProductsPivots[0];
                                if(($SuppliersProductsPivot->supplier_code !== null && strlen($SuppliersProductsPivot->supplier_code) > 0)
                                    || ($SuppliersProductsPivot->supplier_description !== null && strlen($SuppliersProductsPivot->supplier_description) > 0)){
                                    $product_code = $SuppliersProductsPivot->supplier_code;
                                    $product_description = $SuppliersProductsPivot->supplier_description;
                                }
                            }
                        }
                    }
                    $OrderLine->product_code = $product_code;
                    $OrderLine->product_description = $product_description;
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
                $Order->homePage = env('APP_URL');
                $User = Auth::user();
                $Order->user_name = $User->name;
                Mail::to($Order->email)
                    ->send((new OrderEmail($Order))->subject($Subject));
            }
            else{
                return ['status' => 'noemail', 'element_tag' => $ElementTag];
            }
        } catch (\Exception $th) {
            //throw $th;
            $Message = $this->ErrorInfo($th);
            return ['status' => 'error', 'message' => $Message, 'element_tag' => $ElementTag, 'th' => $th, 'email' => $Order->email, 'subject' => $Subject];
        }
        return ['status' => 'ok', 'order' => $Order, 'element_tag' => $ElementTag];
     }

     /**
      * 
      * @param Request ['id]
      *
      * @return View
      */
     public function OrderPreview($request)
     {
        try {
            // Set the parameters
            $OrderId = $request['id'];
            $PreviousURL = $request['previousURL'];
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

                        $ProductUnitsPivots = (new ProductUnitsPivots())
                        ->where('product_id', $OrderLine->product_id)
                        ->where('measure_unit_id', $OrderLine->measure_unit_id)->get();

                        if(count($ProductUnitsPivots) > 0){
                            $ProductUnitsPivot = $ProductUnitsPivots[0];

                            $SuppliersProductsPivots = (new SuppProdPivots())
                            ->where('supplier_id', $Order->supplier_id)
                            ->where('product_id', $Product->id)->get();

                            $OrderLine->product_code = $Product->internal_code;
                            $OrderLine->product_description = $Product->internal_description;

                            if(count($SuppliersProductsPivots) > 0){
                                $SuppliersProductsPivot = $SuppliersProductsPivots[0];
                                if(strlen($SuppliersProductsPivot->supplier_code) > 0
                                    || strlen($SuppliersProductsPivot->supplier_description) > 0){
                                    $OrderLine->product_code = $SuppliersProductsPivot->supplier_code;
                                    $OrderLine->product_description = $SuppliersProductsPivot->supplier_description;
                                }
                            }
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
                $Order->previousURL = $PreviousURL;
                $order = $Order;
                return View('orderpreview', compact('Order'));                
            }
            else{
                return ['status' => 'notfound', 'element_tag' => $ElementTag];
            }
        } catch (\Throwable $th) {
            //throw $th;
            $Message = $this->ErrorInfo($th);
            return ['status' => 'error', 'message' => $Message, 'element_tag' => $ElementTag, 'th' => $th];
        }
     }

     /**
      * 
      * @param Request ['id']
      *
      * @return View
      *
      */
      public function ExportToPdf($request)
      {
          # code...
          try {
            // Set the parameters
            $OrderId = $request['id'];
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

                        $ProductUnitsPivots = (new ProductUnitsPivots())
                        ->where('product_id', $OrderLine->product_id)
                        ->where('measure_unit_id', $OrderLine->measure_unit_id)->get();

                        if(count($ProductUnitsPivots) > 0){
                            $ProductUnitsPivot = $ProductUnitsPivots[0];

                            $SuppliersProductsPivots = (new SuppProdPivots())
                            ->where('supplier_id', $Order->supplier_id)
                            ->where('product_id', $Product->id)->get();

                            $OrderLine->product_code = $Product->internal_code;
                            $OrderLine->product_description = $Product->internal_description;

                            if(count($SuppliersProductsPivots) > 0){
                                $SuppliersProductsPivot = $SuppliersProductsPivots[0];
                                if(strlen($SuppliersProductsPivot->supplier_code) > 0
                                    || strlen($SuppliersProductsPivot->supplier_description) > 0){
                                    $OrderLine->product_code = $SuppliersProductsPivot->supplier_code;
                                    $OrderLine->product_description = $SuppliersProductsPivot->supplier_description;
                                }
                            }
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
                $order = $Order;
                $pdf = PDF::loadView('orderpdf', compact('order'));
                $DateStamp = (new \DateTime())->format("Y_m_d_h_i_s");
                return $pdf->download("order_" . $DateStamp . ".pdf");
            }
            else{
                return ['status' => 'notfound', 'element_tag' => $ElementTag];
            }
        } catch (\Throwable $th) {
            //throw $th;
            $Message = $this->ErrorInfo($th);
            return ['status' => 'error', 'message' => $Message, 'element_tag' => $ElementTag, 'th' => $th];
        }
}

     /**
      * 
      * @param Request ['id' 'order_lines:{id, avilable_qty, supplier_price}' 'element_tag']
      *
      * @return Array ['status' => 'ok', 'mailfail' => [], 'mailsent' => [], 'element_tag' => string]
      * @return Array ['status' => 'error', 'message' => string, 'element_tag' => string]
      * 
      */
     public function ReceiveOrder($request)
     {
         # code...
         $Id = $request['id'];
         $Lines = $request['order_lines'];
         $ElementTag = $request['element_tag'];

         try {
             //code...
             $Orders = $this->where('id', $Id)->get();
             if(count($Orders) == 0){
                return ['status' => 'notfound', 'element_tag' => $ElementTag];
             }

             $Order = $Orders[0];

             // Transaction to update the order and products records
             DB::beginTransaction();

             // Update each order line
             foreach($Lines as $Key => $Line){
                DB::table('order_lines')->where('id', $Line['id'])
                    ->update(
                        [
                            'available_qty' => $Line['available_qty'],
                        ]
                    );

                // Get the line to fetch the product
                $OrderLines = DB::table('order_lines')->where('id', $Line['id'])->get();
                if(count($OrderLines) > 0){
                    $OrderLine = $OrderLines[0];
                    // Fetch the product
                    $Products = DB::table('products')->where('id', $OrderLine->product_id)->get();
                    if(count($Products) > 0){
                        $Product = $Products[0];
                        // If this line has available quantity ...
                        if($Line['available_qty'] > 0){
                            // ... then reset the counted flag and next count date
                            $DaysToCount = $Product->days_to_count;
                            $NextCountDate = new \DateTime();
                            $NextCountDate = date_modify($NextCountDate, "+" . $DaysToCount . " day");
                            DB::table('products')
                            ->where('id', $Product->id)
                            ->update([
                                'next_count_date' => $NextCountDate, 
                                'counted' => false,
                                'default_supplier_id' => $Order->supplier_id,
                                'default_measure_unit_id' => $OrderLine->measure_unit_id,
                            ]);
                        }
                        else{
                            // ... in case that the available quantity is zero then reset just the counted flag 
                            DB::table('products')
                            ->where('id', $Product->id)
                            ->update(['counted' => false]);
                        }

                        if($Order->pickup != 'pickup' && $OrderLine->available_qty < $OrderLine->qty){
                            (new OrderLines())->where('id', $OrderLine->id)->update(['not_found' => 1]);
                        }

                        // Fecth the product units pivot
                        $ProductUnitsPivots = (new ProductUnitsPivots())
                            ->where('product_id', $OrderLine->product_id)
                            ->where('measure_unit_id', $OrderLine->measure_unit_id)->get();

                        if(count($ProductUnitsPivots) > 0){
                            $ProductUnitsPivot = $ProductUnitsPivots[0];
                            // Fetch the suppliers/product-unit pivot
                            $UpdatedSupplierProductsPivots = DB::table('suppliers_products_pivots')
                                ->where('supplier_id', $Order->supplier_id)
                                ->where('product_units_pivot_id', $ProductUnitsPivot->id)
                                ->update(['supplier_price' => $Line['supplier_price']]);
                    
                            if($UpdatedSupplierProductsPivots == 0){
                                $SuppliersProductsPivot = (new SuppliersProductsPivots());
                                $SuppliersProductsPivot->supplier_id = $Order->supplier_id;
                                $SuppliersProductsPivot->product_units_pivot_id = $ProductUnitsPivot->id;
                                $SuppliersProductsPivot->supplier_code = "";
                                $SuppliersProductsPivot->supplier_description = "";
                                $SuppliersProductsPivot->supplier_price = $Line['supplier_price'];
                                $SuppliersProductsPivot->measure_unit_id = $OrderLine->measure_unit_id;
                                $SuppliersProductsPivot->save();
                            }    
                        }
                    }
                }
             }

             // Update the order's header
             DB::table('orders')->where('id', $Orders[0]->id)->update(['received' => true, 'completed' => true]);
             
             DB::commit();

             DB::select(
                "UPDATE order_lines
                SET order_lines.not_found = 1
                WHERE order_lines.order_id = :orderId
                AND order_lines.available_qty < order_lines.qty",
                ['orderId' => $Id]
            );
            
            $res = $this->emailUnavailables($Id);

             return ['status' => 'ok', 'mailfail' => $res['mailfail'], 'mailsent' => $res['mailsent'], 'element_tag' => $ElementTag];

             // If all went right return an OK status
         } catch (\Throwable $th) {
             // If something went wrong return an error
             DB::rollback();
             $Message = $this->ErrorInfo($th);
             return ['status' => 'error', 'message' => $Message, 'element_tag' => $ElementTag];
         }
     }

     /**
      * @param orderId
      *
      * @return ['Unavailables' => [], 'mailsent' => [], 'mailfail' => []]
      */
     public function emailUnavailables($orderId)
     {
         # code...
         $MailSent = [];
         $MailFail = [];

         $unavailables = DB::select(
             "SELECT suppliers.name as supplier_name
              FROM orders
              INNER JOIN suppliers
              ON orders.supplier_id = suppliers.id
              WHERE orders.id=:orderId
             ", ['orderId' => $orderId]);

        if(count($unavailables) > 0){
            $unavailableLines = DB::select(
                "SELECT order_lines.*, products.internal_description
                FROM order_lines
                INNER JOIN products
                ON order_lines.product_id = products.id
                WHERE order_lines.id = :orderId
                AND order_lines.available_qty < order_lines.qty
                ", ['orderId' => $orderId]);
    
            if(count($unavailableLines) > 0){

                $unavailables[0]->lines = $unavailableLines;
                $unavailables[0]->homePage = env('APP_URL');
                $unavailables[0]->user_name = Auth::user()->name;

                $unavailablesEmail = new UnavailablesEmail($unavailables);
    
                $Subject = "Products not found!";
                $adminUsers = (new Users())->where('user_type', 'admin')->get();

                foreach($adminUsers as $key => $adminUser){
                    try {
                        //code...
                        if($adminUser->email !== null && strlen($adminUser->email) > 0){
                            Mail::to($adminUser->email)->send(($unavailablesEmail)->subject($Subject));
                            array_push($MailSent, $adminUser->email);
                        }
                    } catch (\Throwable $th) {
                        //throw $th;
                        $Message = (new ErrorInfo())->GetErrorInfo($th);
                        array_push($MailFail, [$adminUser->email => $Message]);
                    }
                }
            }
            else{
                $unavailables = [];
            }
        }
        return ['Unavailables' => $unavailables, 'mailsent' => $MailSent, 'mailfail' => $MailFail];
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
