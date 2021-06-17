<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Models\Products;
use App\Models\Suppliers;
use App\Models\Users;
use App\Models\Orders;
use App\Models\OrderLines;
use App\Models\SuppliersProductsPivots;

class PendingOrders extends Model
{
    use HasFactory;
    public function ShowPendingOrdersPanel($request)
    {
        # code...
        if(isset($request['tab_id'])){
            $TabId= $request['tab_id'];
        }
        else{
            $TabId = "tab_1";
        }

        $Today = new \DateTime();
        $Products = (new Products())->where('counted', false)->where('next_count_date', '<=', $Today)->get();
        foreach($Products as $Key => $Product){
            $Product->due_date = 
                substr($Product->next_count_date, 5, 2) .'-' . 
                substr($Product->next_count_date, 8, 2) . '-' . 
                substr($Product->next_count_date, 0, 4);
        }

        $Result = $this->CountedProducts($request);
        switch ($Result['status']) {
            case 'ok':
                # code...
                $CountedProducts = $Result['countedproducts'];
                foreach($CountedProducts as $Key => $CountedProduct){
                    $CountedProduct->due_date = 
                        substr($CountedProduct->next_count_date, 5, 2) .'-' . 
                        substr($CountedProduct->next_count_date, 8, 2) . '-' . 
                        substr($CountedProduct->next_count_date, 0, 4);
                    $Suppliers = (new Suppliers())->where('id', $CountedProduct->default_supplier_id)->get();
                    $CountedProduct->last_pickup_id = count($Suppliers) > 0 ? $Suppliers[0]->last_pickup_id : -1;
                    $CountedProduct->pickup = count($Suppliers) > 0 ? $Suppliers[0]->pickup : -1;
                }
                break;
            case 'error':
                $Product = new \stdClass;
                $Product->id = -1;
                $Product->internal_description = $Result['message'];
                $Product->image_path = config('app')['nophoto'];
                $CountedProducts = [];
                array_push($CountedProducts, $Product);
                break;
            default:
                # code...
                break;
        }

        $Result = $this->PickupUsers($request);
        switch ($Result['status']) {
            case 'ok':
                # code...
                $PickupUsers = $Result['pickupusers'];
                break;
            case 'error':
                $PickupUsers = [];
                break;
            default:
                # code...
                break;
        }

        $Result = $this->Suppliers($request);
        switch ($Result['status']) {
            case 'ok':
                # code...
                $Suppliers = $Result['suppliers'];
                break;
            case 'error':
                $Suppliers = [];
                $Message = $Result['message'][0];
                return view('debug', ['message' => $Message . " 1"]);
            default:
                # code...
                break;
        }

        $Result = $this->Orders($request);
        switch ($Result['status']) {
            case 'ok':
                # code...
                $Orders = $Result['orders'];
                break;
            case 'error':
                $Orders = [];
                $Message = $Result['message'][0];
                return view('debug', ['message' => $Message . " 2"]);
                break;
            default:
                # code...
                break;
        }

        $Result = $this->SubmittedOrders($request);
        switch ($Result['status']) {
            case 'ok':
                # code...
                $SubmittedOrders = $Result['orders'];
                break;
            case 'error':
                $SubmittedOrders = [];
                break;
            default:
                # code...
                break;
        }

        $Result = $this->AllTheProducts($request);
        switch ($Result['status']) {
            case 'ok':
                # code...
                $AllProducts = $Result['products'];
                break;
        
            case 'error':
                $Message = $Result['message'][0];
                return view('debug', ['message' => $Message . " 2"]);
                break;

            default:
            # code...
                break;
        }
        return view('pendingorders', 
            [
                'tabid' => $TabId,
                'products' => $Products, 
                'countedproducts' => $CountedProducts,
                'pickupusers' => $PickupUsers,
                'suppliers' => $Suppliers,
                'orders' => $Orders,
                'submittedorders' => $SubmittedOrders,
                'allproducts' => $AllProducts,
            ]);
    }

    /**
     * 
     * This function shows the products that have been counted
     * and that should be considered to be ordered to the supplier
     * 
     * @return Object products
     */
    public function CountedProducts($request)
    {
        # code...
        try {
            //code...
            $CountedProducts = (new Products())->where('counted', true)->where('qty_to_order', '>', 0)->get();
            foreach($CountedProducts as $Key => $CountedProduct){
                $SuppliersProductsPivots = (new SuppliersProductsPivots())
                    ->where('supplier_id', $CountedProduct->default_supplier_id)
                    ->where('product_id', $CountedProduct->id)
                    ->get();
                if(count($SuppliersProductsPivots) > 0){
                    $SuppliersProductsPivot = $SuppliersProductsPivots[0];
                    $CountedProduct->supplier_price = $SuppliersProductsPivot->supplier_price;
                }    
                else{
                    $CountedProduct->supplier_price = 0;
                }
            }
                
            return ['status' => 'ok', 'countedproducts' => $CountedProducts];
        } catch (\Throwable $th) {
            //throw $th;
            $Message = $this->ErrorInfo($th);
            return ['status' => 'error', 'message' => $Message];
        }
    }

    /**
     * 
     * @return Object pickupusers
     * 
     * 
     */
    public function PickupUsers($request)
    {
        # code...
        try {
            //code...
            $PickupUsers = (new Users())->where('user_type', 'pickup')->get();
            return ['status' => 'ok', 'pickupusers' => $PickupUsers];
        } catch (\Throwable $th) {
            //throw $th;
            $Message = $this->ErrorInfo($th);
            return ['status' => 'error', 'message' => $Message];
        }
    }

    /**
     * 
     * @return String status [ok, error]
     * 
     */
    public function Suppliers($request)
    {
        # code...
        try {
            //code...
            $Suppliers = (new Suppliers())->where('id', '>', -1)->get();
            return ['status' => 'ok', 'suppliers' => $Suppliers];
        } catch (\Throwable $th) {
            //throw $th;
            $Message = $this->ErrorInfo($th);
            return ['status' => 'error', 'message' => $Message];
        }
    }

    /**
     * 
     * This function find the orders that hasn't been submitted
     * 
     * @param Request $request[]
     * 
     * @return String ['status' => 'ok''error', 'message' => '']
     * 
     * 
     */
    public function Orders($request)
    {
        # code...
        try {
            //code...
            $Orders = (new Orders())->where('submitted', false)->get();
            foreach($Orders as $Key => $Order){
                $OrderLines = (new OrderLines())->where('order_id', $Order->id)->get();
                $Order->order_lines = $OrderLines;
                foreach($Order->order_lines as $key => $orderLine){
                    $Products = (new Products())->where('id', $orderLine->product_id)->get();
                    if(count($Products) > 0){
                        $Product = $Products[0];
                        
                        $orderLine->internal_code = $Product->internal_code;
                        $orderLine->internal_description = $Product->internal_description;
                        $orderLine->supplier_code = "";
                        $orderLine->supplier_description = "";

                        $Suppliers = (new Suppliers())->where('id', $Order->supplier_id)->get();
                        if(count($Suppliers) > 0){
                            $Supplier = $Suppliers[0];
                            $SuppliersProductsPivots = (new SuppliersProductsPivots())
                                ->where('supplier_id', $Supplier->id)
                                ->where('product_id', $Product->id)->get();
                            if(count($SuppliersProductsPivots) > 0){
                                $SuppliersProductsPivot = $SuppliersProductsPivots[0];
                                $orderLine->supplier_code = $SuppliersProductsPivot->supplier_code;
                                $orderLine->supplier_description = $SuppliersProductsPivot->supplier_description;
                                $orderLine->supplier_price = $SuppliersProductsPivot->supplier_price;
                            }
                            else{
                                $orderLine->supplier_code = "";
                                $orderLine->supplier_description = "";
                                $orderLine->supplier_price = 0;
                            }
                        }
                    }
                    else{
                        $orderLine->internal_code = "";
                        $orderLine->internal_description = "";
                        $orderLine->supplier_code = "";
                        $orderLine->supplier_description = "";
                    }
                }
                $Order->date = (new \DateTime($Order->date))->format("m-d-Y");
            }
            return ['status' => 'ok', 'orders' => $Orders];
        } catch (\Throwable $th) {
            //throw $th;
            $Message = $this->ErrorInfo($th);
            return ['status' => 'error', 'message' => $Message];
        }
    }

    /**
     * 
     * This function find the orders that hasn't been submitted
     * 
     * @param Request $request[]
     * 
     * @return String ['status' => 'ok''error', 'message' => '']
     * 
     * 
     */
    public function SubmittedOrders($request)
    {
        # code...
        try {
            //code...
            $Orders = (new Orders())->where('submitted', true)->where('received', false)->get();
            foreach($Orders as $Key => $Order){
                $OrderLines = (new OrderLines())->where('order_id', $Order->id)->get();
                $Order->order_lines = $OrderLines;
                foreach($Order->order_lines as $key => $orderLine){
                    $Products = (new Products())->where('id', $orderLine->product_id)->get();
                    if(count($Products) > 0){
                        $Product = $Products[0];
                        
                        $orderLine->internal_code = $Product->internal_code;
                        $orderLine->internal_description = $Product->internal_description;
                        $orderLine->supplier_code = "";
                        $orderLine->supplier_description = "";

                        $Suppliers = (new Suppliers())->where('id', $Order->supplier_id)->get();
                        if(count($Suppliers) > 0){
                            $Supplier = $Suppliers[0];
                            $SuppliersProductsPivots = (new SuppliersProductsPivots())
                                ->where('supplier_id', $Supplier->id)
                                ->where('product_id', $Product->id)->get();
                            if(count($SuppliersProductsPivots) > 0){
                                $SuppliersProductsPivot = $SuppliersProductsPivots[0];
                                $orderLine->supplier_code = $SuppliersProductsPivot->supplier_code;
                                $orderLine->supplier_description = $SuppliersProductsPivot->supplier_description;
                            }    
                        }
                    }
                    else{
                        $orderLine->internal_code = "";
                        $orderLine->internal_description = "";
                        $orderLine->supplier_code = "";
                        $orderLine->supplier_description = "";
                    }
                }
                $Order->date = (new \DateTime($Order->date))->format("m-d-Y");

                $Suppliers = (new Suppliers())->where('id', $Order->supplier_id)->get();
                if(count($Suppliers) > 0){
                    $Supplier = $Suppliers[0];
                    $Order->supplier_name = $Supplier->name;
                }
                else{
                    $Order->supplier_name = "";
                }

                $PickupUsers = (new Users())->where('id', $Order->pickup_guy_id)->get();
                if(count($PickupUsers) > 0){
                    $PickupUser = $PickupUsers[0];
                    $Order->pickup_user = $PickupUser->name;
                }
                else{
                    $Order->pickup_user = "";
                }
            }
            return ['status' => 'ok', 'orders' => $Orders];
        } catch (\Throwable $th) {
            //throw $th;
            $Message = $this->ErrorInfo($th);
            return ['status' => 'error', 'message' => $Message];
        }
    }

    /**
     * 
     * @return String status [ok error]
     * 
     */
    public function AllTheProducts($request)
    {
        # code...
        try {
            //code...
            $Products = DB::table('products')
                ->join('suppliers', 'products.default_supplier_id', '=', 'suppliers.id')
                ->select('products.*', 'suppliers.pickup', 'suppliers.last_pickup_id')->get();
            foreach($Products as $Key => $Product){
                $SuppliersProductsPivots = (new SuppliersProductsPivots())
                    ->where('supplier_id', $Product->default_supplier_id)
                    ->where('product_id', $Product->id)->get();

                    if(count($SuppliersProductsPivots) > 0){
                    $SuppliersProductsPivot = $SuppliersProductsPivots[0];
                    $Product->supplier_price = $SuppliersProductsPivot->supplier_price;
                }
                else{
                    $Product->supplier_price = 0;
                }
            }
            return ['status' => 'ok', 'products' => $Products];
        } catch (\Throwable $th) {
            //throw $th;
            $Message = $this->ErrorInfo($th);
            return ['status' => 'error', 'message' => $Message];
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
