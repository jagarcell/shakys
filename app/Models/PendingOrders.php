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
use App\Models\ProductUnitsPivots;
use App\Models\MeasureUnits;

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
        $Products = (new Products())->ProductsToCount($Today);
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
                return view('debug', ['message' => 'ERROR COUNTED PRODUCTS']);
                break;
            default:
                # code...
                break;
        }


        $Result = $this->DiscardedProducts($request);
        switch ($Result['status']) {
            case 'ok':
                # code...
                $DiscardedProducts = $Result['discardedproducts'];
                foreach($DiscardedProducts as $Key => $DiscardedProduct){
                    $DiscardedProduct->due_date = 
                        substr($DiscardedProduct->next_count_date, 5, 2) .'-' . 
                        substr($DiscardedProduct->next_count_date, 8, 2) . '-' . 
                        substr($DiscardedProduct->next_count_date, 0, 4);
                    $Suppliers = (new Suppliers())->where('id', $DiscardedProduct->default_supplier_id)->get();
                    $DiscardedProduct->last_pickup_id = count($Suppliers) > 0 ? $Suppliers[0]->last_pickup_id : -1;
                    $DiscardedProduct->pickup = count($Suppliers) > 0 ? $Suppliers[0]->pickup : -1;
                }
                break;
            case 'error':
                $Product = new \stdClass;
                $Product->id = -1;
                $Product->internal_description = $Result['message'];
                $Product->image_path = config('app')['nophoto'];
                $DiscardedProducts = [];
                array_push($DiscardedProducts, $Product);
                return view('debug', ['message' => 'ERROR COUNTED PRODUCTS']);
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
                return view('debug', ['message' => 'PICKUP USERS']);
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
                return view('debug', ['message' => $Message . "ORDERS"]);
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
                return view('debug', ['message' => 'ERROR SUBMITTED ORDERS']);

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
                return view('debug', ['message' => $Message . "ERROR ALL THE PRODUCTS"]);
                break;

            default:
            # code...
                break;
        }

        $Result = $this->NotFoundProducts($request);
        switch ($Result['status']) {
            case 'ok':
                # code...
                $notFoundProducts = $Result['notfoundproducts'];
                foreach($notFoundProducts as $Key => $notFoundProduct){
                    $notFoundProduct->due_date = 
                        substr($notFoundProduct->next_count_date, 5, 2) .'-' . 
                        substr($notFoundProduct->next_count_date, 8, 2) . '-' . 
                        substr($notFoundProduct->next_count_date, 0, 4);
                    $suppliersForNotFound = (new Suppliers())->where('id', $notFoundProduct->default_supplier_id)->get();
//                    $notFoundProduct->last_pickup_id = count($suppliersForNotFound) > 0 ? $suppliersForNotFound[0]->last_pickup_id : -1;
//                    $notFoundProduct->pickup = count($suppliersForNotFound) > 0 ? $suppliersForNotFound[0]->pickup : -1;
                }
                break;
            case 'error':
                $Product = new \stdClass;
                $Product->id = -1;
                $Product->internal_description = $Result['message'];
                $Product->image_path = config('app')['nophoto'];
                $notFoundProducts = [];
                array_push($notFoundProducts, $Product);
                return view('debug', ['message' => $Result['message']]);
                break;
            default:
                # code...
                break;
        }

        return view('pendingorders', 
            [
                'tabid' => $TabId,
                'products' => $Products, 
                'discardedproducts' =>$DiscardedProducts,
                'countedproducts' => $CountedProducts,
                'allproducts' => $AllProducts,
                'orders' => $Orders,
                'submittedorders' => $SubmittedOrders,
                'suppliers' => $Suppliers,
                'pickupusers' => $PickupUsers,
                'notfoundproducts' => $notFoundProducts,
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
            $CountedProducts = DB::table('products')
                ->join('product_units_pivots', 'products.id', '=', 'product_units_pivots.product_id')
                ->join('measure_units', function($join){
                    $join->on('product_units_pivots.measure_unit_id', '=', 'measure_units.id')
                    ->where('products.counted', '=', 1)
                    ->where('product_units_pivots.qty_to_order', '>', 0);
                })->select(
                    'products.*', 'product_units_pivots.qty_to_order', 
                    'product_units_pivots.id as product_units_pivot_id',
                    'measure_units.id as measure_unit_id')->get();

            foreach($CountedProducts as $Key => $CountedProduct){
                $SuppliersProductsPivots = (new SuppliersProductsPivots())
                    ->where('supplier_id', $CountedProduct->default_supplier_id)
                    ->where('product_units_pivot_id', $CountedProduct->product_units_pivot_id)->get();

                if(count($SuppliersProductsPivots) > 0){
                    $SuppliersProductsPivot = $SuppliersProductsPivots[0];
                    $CountedProduct->supplier_price = $SuppliersProductsPivot->supplier_price;
                }
                else{
                    $CountedProduct->supplier_price = 0;
                }
                $ProductId = $CountedProduct->id;

                $ProductUnits = DB::table('product_units_pivots')
                ->join('measure_units', function($join) use ($ProductId){
                    $join->on('measure_units.id', '=', 'product_units_pivots.measure_unit_id')
                    ->where('product_units_pivots.product_id', '=', $ProductId);
                })->select('measure_units.*')->get();

                $CountedProduct->measure_units = $ProductUnits;
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
     * This function shows the products that have been discarded to count
     * and that should be considered to be ordered to the supplier or rescheduled
     * 
     * @return Object products
     */
    public function DiscardedProducts($request)
    {
        # code...
        try {
            //code...
            $Today = new \DateTime();
            $DiscardedProducts = (new Products())
            ->where('discarded', '=', 1)
            ->where('next_count_date', '>', $Today)
            ->get();
            foreach($DiscardedProducts as $Key => $DiscardedProduct){
                $SuppliersProductsPivots = (new SuppliersProductsPivots())
                    ->where('supplier_id', $DiscardedProduct->default_supplier_id)
                    ->where('product_units_pivot_id', $DiscardedProduct->product_units_pivot_id)->get();

                if(count($SuppliersProductsPivots) > 0){
                    $SuppliersProductsPivot = $SuppliersProductsPivots[0];
                    $DiscardedProduct->supplier_price = $SuppliersProductsPivot->supplier_price;
                }
                else{
                    $DiscardedProduct->supplier_price = 0;
                }
                $ProductId = $DiscardedProduct->id;

                $ProductUnits = DB::table('product_units_pivots')
                ->join('measure_units', function($join) use ($ProductId){
                    $join->on('measure_units.id', '=', 'product_units_pivots.measure_unit_id')
                    ->where('product_units_pivots.product_id', '=', $ProductId);
                })->select('measure_units.*')->get();

                $DiscardedProduct->measure_units = $ProductUnits;
            }
            return ['status' => 'ok', 'discardedproducts' => $DiscardedProducts];
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
                            $ProductUnitsPivots = (new ProductUnitsPivots())
                                ->where('product_id', $orderLine->product_id)
                                ->where('measure_unit_id', $orderLine->measure_unit_id)->get();

                            $orderLine->supplier_code = "";
                            $orderLine->supplier_description = "";
                            $orderLine->supplier_price = 0;

                            if(count($ProductUnitsPivots) > 0){
                                $ProductUnitsPivot = $ProductUnitsPivots[0];

                                $SuppliersProductsPivots = (new SuppliersProductsPivots())
                                ->where('supplier_id', $Supplier->id)
                                ->where('product_units_pivot_id', $ProductUnitsPivot->id)->get();
                                if(count($SuppliersProductsPivots) > 0){
                                    $SuppliersProductsPivot = $SuppliersProductsPivots[0];
                                    $orderLine->supplier_price = $SuppliersProductsPivot->supplier_price;
                                }

                                $SuppliersProductsPivots = (new SuppProdPivots())
                                ->where('supplier_id', $Supplier->id)
                                ->where('product_id', $Product->id)->get();
                                if(count($SuppliersProductsPivots) > 0){
                                    $SuppliersProductsPivot = $SuppliersProductsPivots[0];
                                    $orderLine->supplier_code = $SuppliersProductsPivot->supplier_code;
                                    $orderLine->supplier_description = $SuppliersProductsPivot->supplier_description;
                                }

                            }    
                        }

                        $MeasureUnits = (new MeasureUnits())->where('id', $orderLine->measure_unit_id)->get();
                        if(count($MeasureUnits) > 0){
                            $MeasureUnit = $MeasureUnits[0];
                            $orderLine->unit_description = $MeasureUnit->unit_description;
                        }
                        else{
                            $orderLine->unit_description = "";
                        }
                        
                    }
                    else{
                        $orderLine->internal_code = "";
                        $orderLine->internal_description = "";
                        $orderLine->supplier_code = "";
                        $orderLine->supplier_description = "";
                        $orderLine->unit_description = "";
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
                        $orderLine->supplier_price = 0;

                        $Suppliers = (new Suppliers())->where('id', $Order->supplier_id)->get();
                        if(count($Suppliers) > 0){
                            $Supplier = $Suppliers[0];
                            $ProductUnitsPivots = (new ProductUnitsPivots())
                                ->where('product_id', $orderLine->product_id)
                                ->where('measure_unit_id', $orderLine->measure_unit_id)->get();
                            if(count($ProductUnitsPivots) > 0){
                                $ProductUnitsPivot = $ProductUnitsPivots[0];                                
                                
                                $SuppliersProductsPivots = (new SuppliersProductsPivots())
                                ->where('supplier_id', $Supplier->id)
                                ->where('product_units_pivot_id', $Product->id)->get();

                                if(count($SuppliersProductsPivots) > 0){
                                    $SuppliersProductsPivot = $SuppliersProductsPivots[0];
                                    $orderLine->supplier_price = $SuppliersProductsPivot->supplier_price;
                                }    
                                
                                $SuppliersProductsPivots = (new SuppProdPivots())
                                ->where('supplier_id', $Supplier->id)
                                ->where('product_id', $Product->id)->get();

                                if(count($SuppliersProductsPivots) > 0){
                                    $SuppliersProductsPivot = $SuppliersProductsPivots[0];
                                    $orderLine->supplier_code = $SuppliersProductsPivot->supplier_code;
                                    $orderLine->supplier_description = $SuppliersProductsPivot->supplier_description;
                                }    
                            }
                        }

                        $MeasureUnits = (new MeasureUnits())->where('id', $orderLine->measure_unit_id)->get();
                        if(count($MeasureUnits) > 0){
                            $MeasureUnit = $MeasureUnits[0];
                            $orderLine->unit_description = $MeasureUnit->unit_description;
                        }
                        else{
                            $orderLine->unit_description = "";
                        }
                    }
                    else{
                        $orderLine->internal_code = "";
                        $orderLine->internal_description = "";
                        $orderLine->supplier_code = "";
                        $orderLine->supplier_description = "";
                        $orderLine->supplier_price = 0;
                        $orderLine->unit_description = "";
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
        try {
            if(!isset($request['search_text'])){
                return ['status' => 'ok', 'products' => []];

            }
            $SearchText = isset($request['search_text']) ? $request['search_text'] : "";
            if(strlen($SearchText) == 0){
                $Products = DB::table('products')->where('id', '>', -1)->select('products.*')->get();
            }
            else{
                $Keywords = explode(" ", $SearchText);

                $query = " where ((internal_description like '%";
                $first = true;
                foreach ($Keywords as $key => $Keyword) {
                    # code...
                    if($first){
                        $first = false;
                        $query = $query . $Keyword . "%')";
                        $query = $query . "or (internal_code like '%" . $Keyword . "%')";
                    }
                    else{
                        $query = $query . "or (internal_description like '%" . $Keyword . "%')";
                        $query = $query . "or (internal_code like '%" . $Keyword . "%')";

                    }
                }
        
                $query = $query . ")";
                $basequery = "select * from products";
                $Products = DB::select($basequery . $query);
            }
            foreach($Products as $Key => $Product){
                $ProductId = $Product->id;
                $ProductUnits = DB::table('product_units_pivots')
                    ->join('measure_units', function($join) use ($ProductId){
                        $join->on('measure_units.id', '=', 'product_units_pivots.measure_unit_id')
                            ->where('product_units_pivots.product_id', '=', $ProductId);
                })->select('measure_units.*')->get();

                $Product->measure_units = $ProductUnits;
                $Product->pickup = "";
                $Product->last_pickup_id = -1;
                $Product->supplier_price = 0;

                $Suppliers = (new Suppliers())->where('id', $Product->default_supplier_id)->get();
                if(count($Suppliers) > 0){
                    $Supplier = $Suppliers[0];
                    $Product->pickup = $Supplier->pickup;
                    $Product->last_pickup_id = $Supplier->last_pickup_id;

                    $ProductUnitsPivots = (new ProductUnitsPivots())
                        ->where('measure_unit_id', $Product->default_measure_unit_id)
                        ->where('product_id', $Product->id)->get();

                    if(count($ProductUnitsPivots) > 0){
                        $ProductUnitsPivot = $ProductUnitsPivots[0];
                        $SuppliersProductsPivots = (new SuppliersProductsPivots())
                            ->where('supplier_id', $Product->default_supplier_id)
                            ->where('product_units_pivot_id', $ProductUnitsPivot->id)->get();
                        if(count($SuppliersProductsPivots) > 0){
                            $SuppliersProductsPivot = $SuppliersProductsPivots[0];
                            $Product->supplier_price = $SuppliersProductsPivot->supplier_price;
                        }
    
                    }    
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
     * This function shows the products that weren't found at the supplier
     * and that should be considered to be ordered from another supplier
     * 
     * @return Object products
     * 
     */
    public function NotFoundProducts($request)
    {
        # code...
        try {

            $notFoundProducts = DB::select(
                "SELECT order_lines.*,
                        orders.completed, orders.supplier_id, 
                        orders.pickup, orders.pickup_guy_id as last_pickup_id, products.*,
                        product_units_pivots.id as product_units_pivot_id
                FROM order_lines
                INNER JOIN orders
                ON order_lines.order_id = orders.id
                INNER JOIN products
                ON order_lines.product_id = products.id
                INNER JOIN product_units_pivots 
                ON order_lines.product_id = product_units_pivots.product_id
                AND order_lines.measure_unit_id = product_units_pivots.measure_unit_id
                WHERE orders.completed=:completed
                AND order_lines.not_found=:not_found
                ORDER BY products.id, order_lines.measure_unit_id
                ", ['completed' => 1, 'not_found' => 1]
            );
    
            // SET THE INITIAL CONDIITON FOR PRODUCT-MEASURE UNIT
            $productId = -1;
            $measureUnitId = -1;
            // INITIALIZE THE RESULT ARRAY
            $notFoundProductsArray = [];
            foreach($notFoundProducts as $Key => $notFoundProduct){
                // IF THERE IS A CHANGE ON ANY OF THE PRODUCTS
                // CONDITION THEN WE CREATE A NEW RESULTING ROW
                if($notFoundProduct->product_id != $productId || $notFoundProduct->measure_unit_id != $measureUnitId){
                    // SET THE NEW CONDITION FOR PRODUCT-MEASURE UNIT
                    $productId = $notFoundProduct->product_id;
                    $measureUnitId = $notFoundProduct->measure_unit_id;

                    // SET THE INITIAL NOT FOUND VALUE FOR THIS PRODUCT
                    $notFoundProduct->qty_to_order = $notFoundProduct->qty - $notFoundProduct->available_qty;

                    // SET THE SUPPLIERS LINKED TO THIS PRODUCT
                    $SuppliersProductsPivots = (new SuppliersProductsPivots())
                        ->where('supplier_id', $notFoundProduct->default_supplier_id)
                        ->where('product_units_pivot_id', $notFoundProduct->product_units_pivot_id)->get();

                    if(count($SuppliersProductsPivots) > 0){
                        $SuppliersProductsPivot = $SuppliersProductsPivots[0];
                        $notFoundProduct->supplier_price = $SuppliersProductsPivot->supplier_price;
                    }
                    else{
                        $notFoundProduct->supplier_price = 0;
                    }

                    // SET THE MEASURE UNITS LINKED TO THIS PRODUCT
                    $ProductUnits = DB::table('product_units_pivots')
                    ->join('measure_units', function($join) use ($productId){
                        $join->on('measure_units.id', '=', 'product_units_pivots.measure_unit_id')
                        ->where('product_units_pivots.product_id', '=', $productId);
                    })->select('measure_units.*')->get();

                    $notFoundProduct->measure_units = $ProductUnits;

                    // RECORD THIS NEW RESULT RAW
                    array_push($notFoundProductsArray, $notFoundProduct);
                }
                else{
                    // IF THIS PRODUCT-MEASURE UNIT COMBINATION WERE ALREADY
                    // IN RECORD WE ADD NOT FOUND BALANCE TO THE EXISTING ONE
                    $notFoundProductsArray[count($notFoundProductsArray) - 1]->qty_to_order 
                        += $notFoundProduct->qty - $notFoundProduct->available_qty;

                    $notFoundProductsArray[count($notFoundProductsArray) - 1]->qty 
                        += $notFoundProduct->qty;
    
                    $notFoundProductsArray[count($notFoundProductsArray) - 1]->available_ 
                        += $notFoundProduct->available_qty;

                }
            }
            return ['status' => 'ok', 'notfoundproducts' => $notFoundProductsArray];
        } catch (\Throwable $th) {
            //throw $th;
            $Message = $this->ErrorInfo($th);
            return ['status' => 'error', 'message' => $Message];
        }
    }


    /**
     * 
     * @param Request ['supplier_id' array 'product_ids']
     * 
     * @return String status 'ok' 'error'
     * 
     */
    public function GetPricesForSupplier($request)
    {
        try {
            $ProductIds = $request['product_ids'];
            $SupplierId = $request['supplier_id'];
            $ElementTag = $request['element_tag'];

            $ProductsPrices = array();

            foreach($ProductIds as $key => $ProductId){
                $ProductUnitsPivots = (new ProductUnitsPivots())
                    ->where('product_id', $ProductId)->get();
                foreach($ProductUnitsPivots as $Key => $ProductUnitsPivot){
                    $SuppliersProductsPivots = (new SuppliersProductsPivots())
                        ->where('supplier_id', $SupplierId)
                        ->where('product_units_pivot_id', $ProductUnitsPivot->id)->get();
                    if(count($SuppliersProductsPivots) > 0){
                        $SuppliersProductsPivot = $SuppliersProductsPivots[0];
                        $ProductsPrices[$SuppliersProductsPivot->id] = $SuppliersProductsPivot->supplier_price;
                    }
                }
            }

            return ['status' => 'ok', 'productsprices' => $ProductsPrices, 'element_tag' => $ElementTag];
        } catch (\Throwable $th) {
            //throw $th;
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
