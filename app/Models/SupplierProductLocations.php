<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Suppliers;

class SupplierProductLocations extends Model
{
    use HasFactory;

    /**
     * 
     * @return view 'supplierproductlocations'
     *          '
     */
    public function SuppliersProductLocation($request)
    {
        # code...
        try {
            //code...
            $SupplierProductLocations = $this->where('id', '>', -1)->get();
            $Suppliers = (new Suppliers())->where('id', '>', -1)->get();
            foreach($SupplierProductLocations as $Key => $SupplierProductLocation){
                $SuppliersNames = (new Suppliers())->where('id', $SupplierProductLocation->supplier_id)->get();
                if(count($SuppliersNames) > 0){
                    $SupplierProductLocation->supplier_name = $SuppliersNames[0]->name;
                }
            }
            return view('suppliersproductlocations', ['locations' => $SupplierProductLocations, 'suppliers' => $Suppliers]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    /**
     * 
     * @param str supplier_id
     * @param str name
     * @param str image_path
     * @param mix element_tag
     * 
     * 
     * @return str status
     *             'ok'
     *             '419'
     *             'error'
     * @return mix location
     * @return mix element_tag
     * @return th message
     * 
     */
    public function CreateSupplierLocation($request)
    {
        # code...
        $SupplierId = $request['supplier_id'];
        $Name = $request['name'];
        $ImagePath = $request['image_path'];
        $ElementTag = $request['element_tag'];

        try {
            //code...
            $this->name = $Name;
            $this->image_path = $ImagePath == null ?
                config('app')['nophoto'] :
                config('app')['suppliers_prod_location_images_path'] . $ImagePath;

            if(!\File::exists($ImagePath)){
                $ImagePath = config('app')['nophoto'];
            }

            $this->supplier_id = $SupplierId;
            $this->save();
            $SupplierName = "";
            $Suppliers = (new Suppliers())->where('id', $SupplierId)->get();
            if(count($Suppliers) > 0){
                $SupplierName = $Suppliers[0]->name;
            }
            $Location = ['id' => $this->id, 'name' => $this->name, 'image_path' => $this->image_path, 'supplier_name' => $SupplierName];
            return['status' => 'ok', 'location' => $Location, 'element_tag' => $ElementTag];
        } catch (\Throwable $th) {
            //throw $th;
            $Message = $this->ErrorInfo($th);
            return ['status' => 'error', 'message' => $Message, 'element_tag' => $ElementTag];
        }
    }

    /**
     * 
     * @param id
     * @param element_tag
     * 
     * @return status
     *          'ok'
     *          '419'
     *          'notfound'
     *          'error'
     * @return element_tag
     * 
     */
    public function DeleteSupplierLocation($request)
    {
        # code...
        $Id = $request['id'];
        $ElementTag = $request['element_tag'];

        try {
            //code...
            $Locations = $this->where('id', $Id)->get();
            if(count($Locations) == 0){
                return ['status' => 'notfound', 'element_tag' => $ElementTag];
            }
            $this->where('id', $Id)->delete();
            return ['status' => 'ok', 'element_tag' => $ElementTag];
        } catch (\Throwable $th) {
            //throw $th;
            $Message = $this->ErrorInfo($th);
            return['status' => 'error', 'message' => $Message, 'element_tag' => $ElementTag];
        }
    }

    /**
     * 
     * @param id
     * @param element_tag
     * 
     * @return status
     *          'ok'
     *          'notfound'
     *          '419'
     *          'error'
     * @return location if 'ok'
     * @return suppliers
     * @return element_tag always
     * @return message if 'error'
     * 
     *          
     */
    public function GetSupplierLocation($request)
    {
        # code...
        $Id = $request['id'];
        $ElementTag = $request['element_tag'];

        try {
            //code...
            $Locations = $this->where('id', $Id)->get();
            if(count($Locations) == 0){
                return ['status' => 'notfound', 'element_tag' => $ElementTag];
            }
            $Location = $Locations[0];
            $ImagePath = config('app')['suppliers_prod_location_images_path'];
            if(str_contains($Location->image_path, $ImagePath)){
                $Len = strlen($ImagePath);
                $Location->image_name = substr($Location->image_path, $Len);
                $Location->image_size = filesize($Location->image_path);
            }
            else{
                $Location->image_name = $Location->image_path;
            }

            $Suppliers = (new Suppliers())->where('id', $Location->supplier_id)->get();
            if(count($Suppliers) > 0){
                $Supplier = $Suppliers[0];
            }
            else{
                $Supplier = ['id' => -1, 'name' => ''];
            }

            $Suppliers = (new Suppliers())->where('id', '>', -1)->get();

            return ['status' => 'ok', 'location' => $Location, 'suppliers' => $Suppliers, 'supplier' => $Supplier, 'element_tag' => $ElementTag];
        } catch (\Throwable $th) {
            //throw $th;
            $Message = $this->ErrorInfo($th);
            return ['status' => 'error', 'message' => $Message, 'element_tag' => $ElementTag];
        }
    }
    
    /**
     * 
     * @param id
     * @param name
     * @param image_path
     * @param element_tag
     *          
     * @return status
     *          'ok'
     *          'notfound'
     *          '419'
     *          'error'
     * @return location     if status is 'ok'
     * @return message      if status is 'error'
     * @return element_tag  always
     * 
     */
    public function UpdateSupplierLocation($request)
    {
        # code...
        $Id = $request['id'];
        $Name = $request['name'];
        $ImagePath = $request['image_path'];
        $SupplierId = $request['supplier_id'];
        $ElementTag = $request['element_tag'];

        try {
            //code...
            $Locations = $this->where('id', $Id)->get();
            if(count($Locations) == 0){
                return ['status' => 'notfound', 'element_tag' => $ElementTag];
            }
            $Config = config('app');
            $ImagePath = $ImagePath == null ? $Config['nophoto'] : $Config['suppliers_prod_location_images_path'] . $ImagePath;
            if(!\File::exists($ImagePath)){
                $ImagePath = config('app')['nophoto'];
            }

            $this->where('id', $Id)->update(['name' => $Name, 'image_path' => $ImagePath, 'supplier_id' => $SupplierId]);

            $Location = ['id' => $Id, 'name' => $Name, 'image_path' => $ImagePath];
            $Suppliers = (new Suppliers())->where('id', $SupplierId)->get();
            if(count($Suppliers) > 0){
                $Supplier = $Suppliers[0];
            }
            else{
                $Supplier = ['id' => -1, 'name' => ''];
            }
            return ['status' => 'ok', 'location' => $Location, 'supplier' => $Supplier, 'element_tag' => $ElementTag];
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
            $Message = $th->errorInfo;
        }
        return $Message;
    }
}
