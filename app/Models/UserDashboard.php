<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


use App\Models\Products;
use App\Models\ProductLocations;

class UserDashboard extends Model
{
    use HasFactory;
    /**
     * 
     * @return View userdashboard [locations, products]
     * 
     */
    public function ShowUserDashboard($request)
    {
        # code...
        $Products = $this->ProductsToCount($request);

        $Locations = (new ProductLocations)->where('id', '>', -1)->get();

        $LocationsCount = count($Locations);
        if(round($LocationsCount / 2) * 2 != $LocationsCount){
            $Locations[$LocationsCount - 1]->odd = true;
        }
        return view('userdashboard', ['locations' => $Locations, 'products' => $Products]);
    }

    /**
     * 
     * Search for products due to count, if locationid is
     * set it shows only the products for that location
     * 
     * @param String locationid
     * 
     * @return Array products
     * 
     */
    public function ProductsToCount($request)
    {
        # code...
        try {
            //code...
            $Date = new \DateTime();
    
            if(isset($request['locationid'])){
                $LocationId = $request['locationid'];
            }
            else{
                $Products = (new Products())->where('counted', false)->where('next_count_date', '<=', $Date)->get();
            }
            return $Products;
        } catch (\Throwable $th) {
            //throw $th;
            $Message = $this->ErrorInfo($th);
            $Product = new \stdClass;
            $Product->id = -1;
            $Product->internal_description = $Message;
            $Product->image_path = config('app')['nophoto'];
            $Products = new \stdClass;
            $Products = [];
            array_push($Products, $Product);
            return $Products;
        }
    }

    /**
     * 
     * @param String searchtext
     * 
     * @return Object products
     * 
     */
    public function SearchFor($request)
    {
        # code...
        try {
            $SearchText = $request['searchtext'];
            if(strlen($SearchText) == 0){
                $Products = (new Products())->where('id', '>', -1)->get();
                return ['status' => 'ok', 'products' => $Products];
            }
            $Keywords = explode(" ", $SearchText);

            $query = " where internal_description like '%";
            $first = true;
            foreach ($Keywords as $key => $Keyword) {
                # code...
                if($first){
                    $first = false;
                    $query = $query . $Keyword . "%'";
                }
                else{
                    $query = $query . "or internal_description like '%" . $Keyword . "%'";
                }
            }

            $basequery = "select * from products";
            $Products = DB::select($basequery . $query);
            return ['status' => 'ok', 'products' =>  $Products];
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
