<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suppliers extends Model
{
    use HasFactory;

    public function ListSuppliers($request)
    {
        # code...
        try {
            //code...
            $Suppliers = $this->where('id', '>', -1)->orderBy('id', 'desc')->get();
            return view('suppliers', ['suppliers' => $Suppliers]);
        } catch (\Throwable $th) {
            //throw $th;
            return view('suppliers');
        }
    }

    /**
     * 
     * @param name
     * @param email
     * @param address
     * @param phone
     * @param pickup
     * @param image_path
     * @param element_tag
     * 
     * @return status
     * @return id
     * 
     */

    public function AddSupplier($request)
    {
        # code...
        $Name = $request['name'];
        $Email = $request['email'];
        $Address = $request['address'];
        $Phone = $request['phone'];
        $Pickup = $request['pickup'];
        $ImagePath = $request['image_path'];
        $ElementTag = $request['element_tag'];
        try {
            //code...
            $Suppliers = $this->where('email', $Email)->get();

            if(count($Suppliers) > 0){
                return ['status' => 'emailtaken', 'element_tag' => $ElementTag];
            }
    
            $this->name = $Name;
            $this->email = $Email;
            $this->address = $Address;
            $this->phone = $Phone;
            $this->pickup = $Pickup;
            $this->image_path = 
                $ImagePath == null ?
                config('app')['suppliers_images_path'] . config('app')['noimg'] :
                    config('app')['suppliers_images_path'] .  $ImagePath;
    
            $this->save();

            $Supplier = [
                'id' => $this->id, 
                'name' => $this->name, 
                'email' => $this->email, 
                'address' => $this->address,
                'phone' => $this->phone,
                'pickup' => $this->pickup,
                'image_path' => $this->image_path
            ];
            return ['status' => 'ok', 'supplier' => $Supplier, 'element_tag' => $ElementTag];
        } catch (\Throwable $th) {
            //throw $th;
            return ['status' => 'error', 'message' => $th, 'element_tag' => $ElementTag];
        }
    }

    /**
     * 
     * @param id
     * 
     * @return status
     * @return Supplier
     */
    public function GetSupplier($request)
    {
        # code...
        $Id = $request['id'];
        $ElementTag = $request['element_tag'];

        try {
            //code...
            $Suppliers = $this->where('id', $Id)->get();
            if(count($Suppliers) == 0){
                return ['status' => 'notfound', 'element_tag' => $ElementTag];
            }
            $Supplier = $Suppliers[0];
            $ImagePath = config('app')['suppliers_images_path'];
            if(str_contains($Supplier->image_path, $ImagePath)){
                $Len = strlen($ImagePath);
                $Supplier->image_name = substr($Supplier->image_path, $Len);
                $Supplier->image_size = filesize($Supplier->image_path);
            }
            else{
                $Supplier->image_name = $Supplier->image_path;
            }
            return ['status' => 'ok', 'supplier' => $Supplier, 'element_tag' => $ElementTag];
        } catch (\Throwable $th) {
            //throw $th;
            return ['status' => 'error', 'message' => $th, 'element_tag' => $ElementTag];
        }
    }

    /**
     * 
     * @param id
     * @param name
     * @param email
     * @param address
     * @param phone
     * @param pickup
     * @param image_path
     * 
     * @return status
     **          'ok'
     **          'emailtaken'
     **          'notfound'
     * @return supplier
     */

     public function UpdateSupplier($request)
    {
        # code...
        $Id = $request['id'];
        $Name = $request['name'];
        $Email = $request['email'];
        $Address = $request['address'];
        $Phone = $request['phone'];
        $Pickup = $request['pickup'];
        $ImagePath = config('app')['suppliers_images_path'] . $request['image_path'];
        $ElementTag = $request['element_tag'];

        try {
            //code...
            $Suppliers = $this->where('id', $Id)->get();
            if(count($Suppliers) == 0){
                return['status' => 'notfound', 'id' => $Id, 'element_tag' => $ElementTag];
            }
            $Suppliers = $this->where('email', $Email)->get();
            if(count($Suppliers) >= 0 && $Suppliers[0]->id != $Id){
                return['status' => 'emailtaken', 'id' => $Id, 'element_tag' => $ElementTag];
            }
            $this->where('id', $Id)->update(
                [
                    'name' => $Name, 
                    'email' =>$Email, 
                    'address' => $Address,
                    'phone' => $Phone,
                    'pickup' => $Pickup,
                    'image_path' => $ImagePath,
                ]
            );
            $Suppliers = $this->where('id', $Id)->get();
            if(count($Suppliers) == 0){
                return ['status' => 'notfound', 'id' => $Id, 'element_tag' => $ElementTag];
            }
            $Supplier = $Suppliers[0];
            return ['status' => 'ok', 'supplier' => $Supplier, 'element_tag' => $ElementTag];
        } catch (\Throwable $th) {
            //throw $th;
            return ['status' => 'error', 'message' => $th, 'element_tag' => $ElementTag];
        }
    }
}
