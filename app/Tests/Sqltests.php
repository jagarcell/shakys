<?php

namespace App\Tests;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Sqltests extends Model
{
    use HasFactory;
    public function Test($request)
    {
        # code...
        $SearchText = isset($request['search_text']) ? $request['search_text'] : "";
        $Keywords = explode(" ", $SearchText);

        $query = " where ((internal_description like '%";
        $first = true;
        foreach ($Keywords as $key => $Keyword) {
            # code...
            if($first){
                $first = false;
                $query = $query . $Keyword . "%')";
            }
            else{
                $query = $query . "or (internal_description like '%" . $Keyword . "%')";
            }
            $query = $query . "or (internal_code like '%" . $Keyword . "%')";
            $query = $query . "or (days_to_count like '%" . $Keyword . "%')";
            $query = $query . "or (supplier_code like '%" . $Keyword . "%')";
            $query = $query . "or (supplier_description like '%" . $Keyword . "%')";
         }

        $query = $query . ")";
        $basequery = "select products.*, supp_prod_pivots.supplier_code,supp_prod_pivots.supplier_description from products inner join supp_prod_pivots on products.id = supp_prod_pivots.product_id";
        
//        $Products = DB::select($basequery . $query);
        $query = "SELECT products.*
                    (SELECT
                        supp_prod_pivots.*
                    FROM supp_prod_pivots
                    WHERE supp_prod_pivots.product_id = id) AS supp
                    FROM products
                ";
        $Products = DB::select($query);
        return ['products' => $Products]; 
    }
}
