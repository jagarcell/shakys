<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Models\Suppliers;
use App\Models\SuppliersProductsPivots;
use App\Models\ProductUnitsPivots;
use App\Models\MeasureUnits;

class Products extends Model
{
    use HasFactory;

    /**
     * 
     * @return View ['products']
     * @return Redirect ['message']
     *                  '
     */
    public function ListProducts($request)
    {
        # code...
        if(!isset($request[['search_text']])){
            return View('products', ['products' => [], 'measureunits' => []]);
        }
        $SearchText = isset($request['search_text']) ? $request['search_text'] : "";
        try {
            //code...
            if(strlen($SearchText) == 0){
                $Products = $this->where('id', '>', -1)->get();
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
                        $query = $query . "or (days_to_count like '%" . $Keyword . "%')";
                    }
                    else{
                        $query = $query . "or (internal_description like '%" . $Keyword . "%')";
                        $query = $query . "or (internal_code like '%" . $Keyword . "%')";
                        $query = $query . "or (days_to_count like '%" . $Keyword . "%')";
                    }
                }
        
                $query = $query . ")";
                $basequery = "select * from products";
                $Products = DB::select($basequery . $query);
            }
            for($i = 0; $i < count($Products); $i++){
                $DefaultSupplierName = "";
                $Product = $Products[$i];
                if($Product->default_supplier_id != -1){
                    $Supplier = (new Suppliers())->GetSupplierById($Product->default_supplier_id);
                    if(!is_null($Supplier)){
                        $DefaultSupplierName = $Supplier->name;
                    }
                }
                $Product->default_supplier_name = $DefaultSupplierName;

                $DefaultMeasureUnits = (new MeasureUnits())->where('id', $Product->default_measure_unit_id)->get();
                $DefaultMeasureUnitName = "";
                if(count($DefaultMeasureUnits) > 0){
                    $DefaultMeasureUnit = $DefaultMeasureUnits[0];
                    $DefaultMeasureUnitName = $DefaultMeasureUnit->unit_description;
                }
                $Product->default_measure_unit = $DefaultMeasureUnitName;
                $ProductId = $Product->id;

                $ProductMeasureUnits = DB::table('product_units_pivots')->join('measure_units', function($join) use ($ProductId){
                    $join->on('product_units_pivots.measure_unit_id', '=', 'measure_units.id')
                    ->where('product_units_pivots.product_id', '=', $ProductId);
                })->select('measure_units.*')->get();

                $Product->measure_units = $ProductMeasureUnits;
                
                if($Product->plan_type == -1){
                    $Product->plan_type = "NONE";
                }
            }

            $MeasureUnits = (new MeasureUnits())->where('id', '>', -1)->get();
            return View('products', ['products' => $Products, 'measureunits' => $MeasureUnits]);
        } catch (\Throwable $th) {
            //throw $th;
            $Message = $this->ErrorInfo($th);
            return Redirect("/error/$Message");
        }
    }

    /**
     * 
     * @param Request $request[
     *                   'internal_code'
     *                   'internal_description'
     *                   'days_to_count'
     *                   'measure_unit'
     *                   'default_supplier_id'
     *                   'default_supplier_name'
     *                   'supplier_code'
     *                   'supplier_product_description'
     *                   'supplier_product_location'
     *                   'image_to_upload'
     *                   'element_tag']
     * 
     * @return String status 'ok' 'error'
     * @return JSON supplier
     * @return Mixed element_tag
     */
    public function CreateProduct($request)
    {
        $Code = $request['internal_code'];
        $Description = $request['internal_description'];
        $DaysToCount = $request['days_to_count'];
        $DefaultMeasureUnitId = $request['default_measure_unit_id'];
        $DefaultSupplierId = $request['default_supplier_id'];
        $ImageToUpload = $request['image_to_upload'];
        $ElementTag = $request['element_tag'];
        $Config = config('app');

        # code...
        try {
            $Products = $this->where('internal_code', $Code)->get();
            if(count($Products) > 0){
                return ['status' => 'exist', 'element_tag' => $ElementTag];
            }
            //code...
            $this->internal_code = $Code == null ? "" : $Code;
            $this->internal_description = $Description;
            $this->days_to_count = $DaysToCount;
            $this->default_measure_unit_id = $DefaultMeasureUnitId;
            $this->default_supplier_id = $DefaultSupplierId;
            $ImagePath = $ImageToUpload == null ? $Config['nophoto']: $Config['products_images_path'] . $ImageToUpload;
            if(!\File::exists($ImagePath)){
                $ImagePath = $Config['nophoto'];
            }
            $this->image_path = $ImagePath; 
            $NextCountDate = new \DateTime();
            $NextCountDate = date_modify($NextCountDate, "+" . $DaysToCount . " day");
            $this->next_count_date = $NextCountDate;

            $this->counted = false;
            $this->save();
            
            $DefaultSupplierName = ' ';
            $Supplier = (new Suppliers())->GetSupplierById($DefaultSupplierId);
            if($Supplier !== null){
                $DefaultSupplierName = $Supplier->name;
            }

            $UnitDescription = "";
            $MeasureUnits = (new MeasureUnits())->where('id', $DefaultMeasureUnitId)->get();
            if(count($MeasureUnits) > 0){
                $MeasureUnit = $MeasureUnits[0];
                $UnitDescription = $MeasureUnit->unit_description;
            }

            $Product = [
                'id' => $this->id, 
                'internal_code' => $this->internal_code,
                'internal_description' => $this->internal_description,
                'days_to_count' => $this->days_to_count,
                'measure_unit' => $UnitDescription,
                'default_supplier_id' => $this->default_supplier_id,
                'image_path' => $this->image_path,
                'default_supplier_name' => $DefaultSupplierName
            ];
            return ['status' => 'ok', 'product' => $Product, 'element_tag' => $ElementTag];
        } catch (\Throwable $th) {
            //throw $th;
            $Message = $this->ErrorInfo($th);
            return ['status' => 'error', 'message' => $Message, 'element_tag' => $ElementTag];
        }
    }

    /**
     * 
     * @param Request request[id]
     * 
     * @return Array status['ok' 'error' 'notfound']
     * @return String message [if 'error']
     * @return String element_tag [always]
     * 
     */
    public function DeleteProduct($request)
    {
        # code...
        $Id = $request['id'];
        $ElementTag = $request['element_tag'];
        try {
            //code...
            $Products = $this->where('id', $Id)->get();
            if(count($Products) == 0){
                return ['status' => 'notfound', 'element_tag' => $ElementTag];
            }
            $this->where('id', $Id)->delete();
            return ['status' => 'ok', 'element_tag' => $ElementTag];
        } catch (\Throwable $th) {
            //throw $th;
            $Message = $this->ErrorInfo($th);
            return ['status' => 'error', 'message' => $Message, 'element_tag' => $ElementTag];
        }
    }

    /**
     * 
     * @param Request [
     *                  'id' 
     *                  'suppliers' if any value is set include suppliers
     *                ]
     * @param Object element_tag
     * 
     * @return String status ['ok' 'error' 'notfound']
     * @return String message if status is error
     * @return Object product
     * @return Object element_tag
     * 
     */
    public function GetProduct($request)
    {
        # code...
        $Id = $request['id'];
        if(isset($request['supplier_id'])){
            $SupplierId = $request['supplier_id'];
        }
        $ElementTag = $request['element_tag'];

        try {
            //code...
            $Products = $this->where('id', $Id)->get();
            if(count($Products) == 0){
                return ['status' => 'notfound', 'element_tag' => $ElementTag];
            }
            $Product = $Products[0];

            $ImagePath = config('app')['products_images_path'];
            if(str_contains($Product->image_path, $ImagePath)){
                $Len = strlen($ImagePath);
                $Product->image_name = substr($Product->image_path, $Len);
                $Product->image_size = filesize($Product->image_path);
            }
            else{
                $Product->image_name = $Product->image_path;
            }

            $DefaultSupplierName = "";
            $Supplier = (new Suppliers())->GetSupplierById($Product->default_supplier_id);
            if(!is_null($Supplier)){
                $DefaultSupplierName = $Supplier->name;
            }

            $Product->default_supplier_name = $DefaultSupplierName;
            if(isset($request['suppliers'])){
                $Suppliers = (new Suppliers())->where('id', '>', -1)->get();
                $Product->suppliers = $Suppliers;
            }

            $DefaultMeasureUnits = (new MeasureUnits())->where('id', $Product->default_measure_unit_id)->get();
            if(count($DefaultMeasureUnits) > 0){
                $DefaultMeasureUnit = $DefaultMeasureUnits[0];
                $Product->measure_unit = $DefaultMeasureUnit->unit_description;
            }
            else{
                $Product->measure_unit = "";
            }

            $ProductId = $Product->id;

            $MeasureUnits = DB::table('product_units_pivots')->join('measure_units', function($join) use ($ProductId){
                $join->on('product_units_pivots.measure_unit_id', '=', 'measure_units.id')
                ->where('product_units_pivots.product_id', '=', $ProductId);
            })->select('measure_units.*')->get();

            $Product->measure_units = $MeasureUnits;

            return ['status' => 'ok', 'product' => $Product, 'element_tag' => $ElementTag];
        } catch (\Throwable $th) {
            //throw $th;
            $Message = $this->ErrorInfo($th);
            return ['status' => 'error', 'message' => $Message, 'element_tag' => $ElementTag];
        }
    }

    /**
     * 
     * @param Request [
     *                  'id' 
     *                  'internal_code' 
     *                  'internal_description'
     *                  'days_to_count',
     *                  'measure_unit',
     *                  'default_supplier_id'
     *                  'image_path',
     *                  'counted']
     * 
     * @param Object element_tag
     * 
     * @return String status ['ok', 'error', 'notfound']
     * @return String message if status is error
     * @return Object element_tag
     * 
     */
    public function UpdateProduct($request)
    {
        # code...
        $Id = $request['id'];
        $InternalCode = $request['internal_code'];
        $InternalDescription = $request['internal_description'];
        $DaysToCount = $request['days_to_count'];
        $DefaultMeasureUnitId = $request['default_measure_unit_id'];
        $DefaultSupplierId = $request['default_supplier_id'];
        $ImagePath = $request['image_path'];
        $ElementTag = $request['element_tag'];
        $Config = config('app');

        try {
            // Check if the product exists
            $Products = $this->where('id', $Id)->get();
            if(count($Products) == 0){
                return ['status' => 'notfound', 'element_tag' => $ElementTag];
            }
            $Product = $Products[0];

            // Check if the product's code is in use
            $Products = $this->where('internal_code', $InternalCode)->get();
            if(count($Products) > 0){
                if($Products[0]->id != $Id){
                    return ['status' => 'exist', 'element_tag' => $ElementTag];
                }
            }

            // Check if the product's image is uploaded
            $ProductsUploadeImgPath = $Config['products_images_path'];
            $ImagePath = 
                str_contains($ImagePath, $ProductsUploadeImgPath) ?
                $ImagePath : $ProductsUploadeImgPath . $ImagePath;
            if(!\File::exists($ImagePath)){
                $ImagePath = config('app')['nophoto'];
            }

            if($Product->days_to_count != $DaysToCount){
                $NextCountDate = date_modify(new \DateTime(), "+$DaysToCount day");
            }
            else{
                $NextCountDate = new \DateTime($Product->next_count_date);
            }
            $this->where('id', $Id)->update(
                [
                    'internal_code' => $InternalCode,
                    'internal_description' => $InternalDescription,
                    'days_to_count' => $DaysToCount,
                    'default_measure_unit_id' => $DefaultMeasureUnitId,
                    'default_supplier_id' => $DefaultSupplierId,
                    'image_path' => $ImagePath,
                    'next_count_date' => $NextCountDate,
                ]
            );

            $Suppliers = (new Suppliers())->where('id', $DefaultSupplierId)->get();
            $DefaultSupplierName = "";
            if(count($Suppliers) > 0){
                $DefaultSupplierName = $Suppliers[0]->name;
            }

            $UnitDescription = "";
            $MeasureUnits = (new MeasureUnits())->where('id', $DefaultMeasureUnitId)->get();
            if(count($MeasureUnits) > 0){
                $MeasureUnit = $MeasureUnits[0];
                $UnitDescription = $MeasureUnit->unit_description;
            }

            $Product =
                [
                    'id' => $Id,
                    'internal_code' => $InternalCode,
                    'internal_description' => $InternalDescription,
                    'days_to_count' => $DaysToCount,
                    'measure_unit' => $UnitDescription,
                    'default_supplier_id' => $DefaultSupplierId,
                    'image_path' => $ImagePath,
                    'default_supplier_name' => $DefaultSupplierName,
                ];
            return ['status' => 'ok', 'product' => $Product, 'element_tag' => $ElementTag];
        } catch (\Throwable $th) {
            //throw $th;
            $Message = $this->ErrorInfo($th);
            return ['status' => 'error', 'message' => $Message, 'element_tag' => $ElementTag];
        }
    }

    /**
     * 
     * @param Request id qty_to_order measure_unit_id element_tag   
     * @param Object 
     * 
     * @return [status => 'ok']
     *         [status => 'notfound']
     *         [status => '419']
     *         [status => 'error'] 
     * @return Object product
     * @return Object message (if error)
     * @return Object element_tag
     *  
     */
    public function MarkAsCounted($request)
    {
        # code...
        try {
            //code...
            $Id = $request['id'];
            $QtyToOrder = $request['qty_to_order'];
            $MeasureUnitId = $request['measure_unit_id'];
            $ElementTag = $request['element_tag'];

            $Products = $this->where('id', $Id)->get();
            if(count($Products) == 0){
                return ['status' => 'notfound', 'element_tag' => $ElementTag];
            }

            $Product = $Products[0];
            $Product->counted = true;

            $UpdatedProductUnitsPivots = (new ProductUnitsPivots())
                ->where('product_id', $Product->id)
                ->where('measure_unit_id', $MeasureUnitId)->update(['qty_to_order' => $QtyToOrder]);

            // If there is a link to the measure unit 
            if($UpdatedProductUnitsPivots > 0){
                // The qty to order is asigned to that measure unit
                $this->where('id', $Id)->update(['counted' => true, 'qty_to_order' => 0]);
            }
            else{
                // Othewise it is asigned to the product itself
                $this->where('id', $Id)->update(['counted' => true, 'qty_to_order' => $QtyToOrder]);
            }
            return ['status' => 'ok', 'product' => $Product, 'element_tag' => $ElementTag];
        } catch (\Throwable $th) {
            //throw $th;
            $Message = $this->ErrorInfo($th);
            return ['status' => 'error', 'message' => $Message, 'element_tag' => $ElementTag];
        }
    }

    /**
     * 
     * @param Request ['product_id' 'measure_unit_id' 'supplier_id']
     * @param String 'element_tag' 
     * 
     * @return string status 'ok' 'error' 'notfound'
     * 
     */
    public function GetSupplierPrice($request)
    {
        try {
            $ProductId = $request['product_id'];
            $MeasureUnitId = $request['measure_unit_id'];
            $SupplierId = $request['supplier_id'];
            $ElementTag = $request['element_tag'];

            $ProductUnitsPivots = (new ProductUnitsPivots())
                ->where('product_id', $ProductId)
                ->where('measure_unit_id', $MeasureUnitId)->get();
            if(count($ProductUnitsPivots) == 0){
                return ['status' => 'notfound', 'supplier_id' => $SupplierId, 'product_id' => $ProductId, 'element_tag' => $ElementTag];
            }

            $ProductUnitsPivot = $ProductUnitsPivots[0];

            $SuppliersProductsPivots = (new SuppliersProductsPivots())
                ->where('supplier_id', $SupplierId)
                ->where('product_units_pivot_id', $ProductUnitsPivot->id)->get();
            
            if(count($SuppliersProductsPivots) == 0){
                return ['status' => 'notfound', 'supplier_id' => $SupplierId, 'product_id' => $ProductId, 'element_tag' => $ElementTag];
            }

            $SuppliersProductsPivot = $SuppliersProductsPivots[0];
            return ['status' => 'ok', 'supplier_price' => $SuppliersProductsPivot->supplier_price, 'element_tag' => $ElementTag];

        } catch (\Throwable $th) {
            //throw $th;
            $Message = $this-ErrorInfo($th);
            return ['status' => 'error', 'message' => $Message, 'element_tag' => $ElementTag];
        }
    }

    /**
     * 
     * @param Request product_id, element_tag
     * 
     * @return String status 'ok' 'error' 'notfound'
     * @return Mixed measureunits
     * @return Mixed product
     * 
     */

     public function GetProductUnits($request)
     {
         try {
             $ProductId = $request['product_id'];
             $ElementTag = $request['element_tag'];

             $MeasureUnits = (new MeasureUnits())->where('id', '>', -1)->get();

             if($ProductId == -1){
                 return ['status' => 'ok', 'element_tag' => $ElementTag, 'measureunits' => $MeasureUnits, 'productunits' => []];
             }

             $Products = $this->where('id', $ProductId)->get();
             if(count($Products) == 0){
                 return ['status' => 'notfound', 'element_tag' => $ElementTag];
             }

             $Product = $Products[0];

             $ProductUnits = DB::table('product_units_pivots')
                                ->join('measure_units', function($join) use ($ProductId){
                                    $join->on('measure_units.id', '=', 'product_units_pivots.measure_unit_id')
                                    ->where('product_units_pivots.product_id', '=', $ProductId);
                                })->select('measure_units.*')->get();

             foreach($ProductUnits as $Key => $ProductUnit){
                if($ProductUnit->id == $Product->default_measure_unit_id){
                    $ProductUnit->default_unit = true;
                }
             }

            return ['status' => 'ok', 'measureunits' => $MeasureUnits, 'productunits' => $ProductUnits, 'product' => $Product, 'element_tag' => $ElementTag];
         } catch (\Throwable $th) {
             $Message = $this->ErrorInfo($th);
             return['status' => 'error', 'message' => $Message, 'element_tag' => $ElementTag];
         }
     }

     /**
      * 
      * @param Request [product_id  array measure_units[id, checked] element_tag]
      *
      * @return String status 'ok' 'error' 'notfound'
      * @return String message (in case of error)
      * @return Object element_tag (always)
      *
      */

      public function SetProductUnits($request)
      {
        try {
            $ProductId = $request['product_id'];
            $MeasureUnits = $request['measure_units'];
            $ElementTag = $request['element_tag'];

            foreach($MeasureUnits as $Key => $MeasureUnit){
                $ProductUnitsPivots = (new ProductUnitsPivots())
                    ->where('measure_unit_id', $MeasureUnit['id'])
                    ->where('product_id', $ProductId)->get();

                if($MeasureUnit['checked'] !== null){
                    if(count($ProductUnitsPivots) == 0){
                        $ProductUnitsPivot = (new ProductUnitsPivots());
                        $ProductUnitsPivot->measure_unit_id = $MeasureUnit['id'];
                        $ProductUnitsPivot->product_id = $ProductId;
                        $ProductUnitsPivot->save();

                    }
                }
                else{
                    if(count($ProductUnitsPivots) > 0){
                        $ProductUnitsPivot = $ProductUnitsPivots[0];
                        (new ProductUnitsPivots())->where('id', $ProductUnitsPivot->id)->delete();
                    }
                }                
            }
            
            return ['status' => 'ok', 'element_tag' => $ElementTag];
        } catch (\Throwable $th) {
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
            $Message = $th->errorInfo[count($th->errorInfo) - 1];
        }
        return $Message;
    }
}
