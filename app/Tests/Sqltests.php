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

        $notFoundProducts = 
        DB::table('order_lines')->join('orders', function($join){
            $join->on('order_lines.order_id', '=', 'orders.id')
                    ->where('orders.completed', 1)
                    ->where('order_lines.available_qty', '<', 'order_lines.qty');
        })->get();

        $notFoundProducts =
        DB::table('order_lines')->join('orders', function($join){
            $join->on('order_lines.order_id', '=', 'orders.id')->
            where('orders.completed', '=', 1);
        })->get();

        return ['NOT FOUND' => $notFoundProducts];
    }
}
