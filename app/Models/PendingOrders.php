<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Products;
use App\Models\Suppliers;

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
        return view('pendingorders', ['products' => $Products]);
    }
}
