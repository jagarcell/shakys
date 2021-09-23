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
        $Unavailables = DB::select("select orders.*, suppliers.name as supplier_name, users.name as pickup_guy_name from orders inner join suppliers on orders.supplier_id = suppliers.id inner join users on orders.pickup_guy_id = users.id where orders.id = 3");

        $UnavailableLines = DB::select(
            "SELECT order_lines.*, products.internal_description 
            FROM order_lines 
            INNER JOIN products on products.id = order_lines.product_id 
            WHERE order_lines.order_id = " . $Unavailables[0]->id . " 
            AND (order_lines.qty > order_lines.available_qty)"
        );

        $Unavailables[0]->lines = $UnavailableLines;

        return $Unavailables;
    }
}
