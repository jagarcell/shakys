<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Products;
use App\Models\Suppliers;
use App\Models\Users;

class PendingOrders extends Model
{
    use HasFactory;
    public function ShowPendingOrdersPanel($request)
    {
        # code...
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
            default:
                # code...
                break;
        }
        return view('pendingorders', 
            [
                'products' => $Products, 
                'countedproducts' => $CountedProducts,
                'pickupusers' => $PickupUsers,
                'suppliers' => $Suppliers
            ]);
    }

    /**
     * 
     * This function shows the products tha have been counted
     * and should be considered to be ordered to the supplier
     * 
     * @return Object products
     */
    public function CountedProducts($request)
    {
        # code...
        try {
            //code...
            $CountedProducts = (new Products())->where('counted', true)->get();
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
