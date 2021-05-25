<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Suppliers;

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
        try {
            //code...
            $Products = $this->where('id', '>', -1)->get();
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
            }
            return View('products', ['products' => $Products]);
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
        $MeasureUnit = $request['measure_unit'];
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
            $this->internal_code = $Code;
            $this->internal_description = $Description;
            $this->days_to_count = $DaysToCount;
            $this->measure_unit = $MeasureUnit;
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
            $Product = [
                'id' => $this->id, 
                'internal_code' => $this->internal_code,
                'internal_description' => $this->internal_description,
                'days_to_count' => $this->days_to_count,
                'measure_unit' => $this->measure_unit,
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
     * @param Request ['id']
     * @param Object element_tag
     * 
     * @return String status ['ok' 'error' 'notfound']
     * @return String message if status is error
     * @return Object element_tag
     * 
     */
    public function GetProduct($request)
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
        $MeasureUnit = $request['measure_unit'];
        $DefaultSupplierId = $request['default_supplier_id'];
        $ImagePath = $request['image_path'];
        $ElementTag = $request['element_tag'];
        $Config = config('app');

        try {
            //code...
            $Products = $this->where('id', $Id)->get();
            if(count($Products) == 0){
                return ['status' => 'notfound', 'element_tag' => $ElementTag];
            }
            $Products = $this->where('internal_code', $InternalCode)->get();
            if(count($Products) > 0){
                if($Products[0]->id != $Id){
                    return ['status' => 'exist', 'element_tag' => $ElementTag];
                }
            }
            $ProductsUploadeImgPath = $Config['products_images_path'];
            $ImagePath = 
                str_contains($ImagePath, $ProductsUploadeImgPath) ?
                $ImagePath : $ProductsUploadeImgPath . $ImagePath;
            if(!\File::exists($ImagePath)){
                $ImagePath = config('app')['nophoto'];
            }
                    
            $this->where('id', $Id)->update(
                [
                    'internal_code' => $InternalCode,
                    'internal_description' => $InternalDescription,
                    'days_to_count' => $DaysToCount,
                    'measure_unit' => $MeasureUnit,
                    'default_supplier_id' => $DefaultSupplierId,
                    'image_path' => $ImagePath
                ]
            );

            $Suppliers = (new Suppliers())->where('id', $DefaultSupplierId)->get();
            $DefaultSupplierName = "";
            if(count($Suppliers) > 0){
                $DefaultSupplierName = $Suppliers[0]->name;
            }

            $Product =
                [
                    'id' => $Id,
                    'internal_code' => $InternalCode,
                    'internal_description' => $InternalDescription,
                    'days_to_count' => $DaysToCount,
                    'measure_unit' => $MeasureUnit,
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
     * @param Request ['id']
     * @param Double ['qty_to_order']
     * @param Object element_tag
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
            $ElementTag = $request['element_tag'];
            $Products = $this->where('id', $Id)->get();
            if(count($Products) == 0){
                return ['status' => 'notfound', 'element_tag' => $ElementTag];
            }
            $this->where('id', $Id)->update(['counted' => true, 'qty_to_order' => $QtyToOrder]);
            $Product = $Products[0];
            $Product->counted = true;
            return ['status' => 'ok', 'product' => $Product, 'element_tag' => $ElementTag];
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
            $Message = $th->errorInfo[count($th->errorInfo) - 1];
        }
        return $Message;
    }
}
