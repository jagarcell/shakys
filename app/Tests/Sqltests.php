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

        $notFoundProducts = DB::select(
            "SELECT order_lines.*,
                    orders.completed, orders.supplier_id, 
                    orders.pickup, orders.id as ORDERID, products.*,
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
        return ['NOT FOUND' => $notFoundProducts];
    }
}
