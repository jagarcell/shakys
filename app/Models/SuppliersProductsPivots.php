<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Errors\ErrorInfo;
use App\Models\Products;
use App\Models\Suppliers;
use App\Models\MeasureUnits;
use App\Models\ProductUnitsPivots;
use App\Models\SuppProdPivots;

class SuppliersProductsPivots extends Model
{
    use HasFactory;

    /**
     * 
     * @param Request [
     *                  'product_id',
     *                  'supplier_id',
     *                  'supplier_code',
     *                  'supplier_description',
     *                  'element_tag',
     *                ]
     * 
     * @return String status ['ok' 'error']
     * 
     */
    public function CreatePivot($request)
    {
        # code...
        $ProductId = $request['product_id'];
        $SupplierId = $request['supplier_id'];
        $SupplierCode = $request['supplier_code'];
        $SupplierDescription = $request['supplier_description'];
        $ElementTag = $request['element_tag'];

        try {
            //code...
            if($SupplierId == -1){
                return ['status' => 'nodata', 'element_tag' => $ElementTag];
            }
            $SupplierProductPivots = (new SuppProdPivots())->where('product_id', $ProductId)->where('supplier_id', $SupplierId)->get();
            if(count($SupplierProductPivots) > 0){
                $Id = $SupplierProductPivots[0]->id;
                // Update action
                (new SuppProdPivots())->where('id', $Id)->update(['supplier_code' => $SupplierCode, 'supplier_description' => $SupplierDescription]);
            }
            else{
                // Create action
                $SuppProdPivots = (new SuppProdPivots());
                $SuppProdPivots->product_id = $ProductId;
                $SuppProdPivots->supplier_id = $SupplierId;
                $SuppProdPivots->supplier_code = $SupplierCode;
                $SuppProdPivots->supplier_description = $SupplierDescription;
                $SuppProdPivots->save();
                $Id = $SuppProdPivots->id;
            }

            $Products = (new Products())->where('id', $ProductId)->get();
            if(count($Products) > 0){
                $Product = $Products[0];
                
                $Suppliers = (new Suppliers())->where('id', $Product->default_supplier_id)->get();
                if(count($Suppliers) > 0){
                    $Supplier = $Suppliers[0];
                    $Product->default_supplier_name = $Supplier->name;
                }
                else{
                    $Product->default_supplier_name = "";
                }

                $MeasureUnits = (new MeasureUnits())->where('id', $Product->default_measure_unit_id)->get();
                if(count($MeasureUnits) > 0){
                    $MeasureUnit = $MeasureUnits[0];
                    $Product->measure_unit = $MeasureUnit->unit_description;
                }
                else{
                    $Product->measure_unit = "";
                }
            }
            else{
                $Product = new \stdClass;
                $Product->id = -1;
                $Product->internal_code = "";
                $Product->internal_description = "";
                $Product->days_to_count = 0;
                $Product->measure_unit = "";
                $Product->default_supplier_id = -1;
                $Product->image_path = "";
                $Product->counted = false;
                $Product->qty_to_order = 0;
                $Product->default_supplier_name = "";
            }

            $SupplierProductPivot = [
                'id' => $Id, 
                'supplier_id' => $SupplierId, 
                'product_id' => $ProductId,
                'supplier_code' => $SupplierCode,
                'supplier_description' => $SupplierDescription,
            ];
            
            return ['status' => 'ok', 'supplierproductpivot' => $SupplierProductPivot, 'product' => $Product, 'element_tag' => $ElementTag];
        } catch (\Throwable $th) {
            //throw $th;
            $Message = (new ErrorInfo())->GetErrorInfo($th);
            return ['status' => 'error', 'message' => $Message, 'element_tag' => $ElementTag];
        }
    }

    /**
     * 
     * @param Request [
     *                  'product_id'
     *                  'supplier_id'
     *                  'element_tag'
     *                ]
     * 
     * @return Object SupplierProductPivot
     */
    public function GetPivot($request)
    {
        # code...
        $ProductId = $request['product_id'];
        $SupplierId = $request['supplier_id'];
        $ElementTag = $request['element_tag'];

        try {
            //code...
            $SuppliersProductsPivots = (new SuppProdPivots())
            ->where('product_id', $ProductId)
            ->where('supplier_id', $SupplierId)->get();
            if(count($SuppliersProductsPivots) > 0){
                $SuppliersProductsPivot = $SuppliersProductsPivots[0];
                return ['status' => 'ok', 'suppliersproductspivot' => $SuppliersProductsPivot, 'element_tag' => $ElementTag];
            }
            else{
                return ['status' => 'notfound', 'element_tag' => $ElementTag];
            }
        } catch (\Throwable $th) {
            //throw $th;
            return ['status' => 'error', 'element_tag' => $ElementTag];
        }
    }
}
