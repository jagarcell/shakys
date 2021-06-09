<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductLocations extends Model
{
    use HasFactory;

    /**
     * 
     * List all the registered locations
     * 
     * @return view productslocations
     * 
     */
    public function ProductsLocations($request)
    {
        # code...
        try {
            //code...
            $ProductsLocations = $this->where('id', '>', -1)->get();
            return view('productslocations', ['locations' => $ProductsLocations]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    /**
     * 
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
    public function CreateInStoreLocation($request)
    {
        # code...
        $Name = $request['name'];
        $ImagePath = $request['image_path'];
        $ElementTag = $request['element_tag'];

        try {
            //code...
            $this->name = $Name;
            $ImagePath = $ImagePath == null ?
                config('app')['nophoto'] :
                config('app')['instore_images_path'] . $ImagePath;

            if(!\File::exists($ImagePath)){
                $ImagePath = config('app')['nophoto'];
            }

            $this->image_path = $ImagePath;
    
            $this->save();
            $Location = ['id' => $this->id, 'name' => $this->name, 'image_path' => $this->image_path];
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
    public function DeleteInStoreLocation($request)
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
     * @return element_tag always
     * @return message if 'error'
     * 
     *          
     */
    public function GetInStoreLocation($request)
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
            $ImagePath = config('app')['instore_images_path'];
            if(str_contains($Location->image_path, $ImagePath)){
                $Len = strlen($ImagePath);
                $Location->image_name = substr($Location->image_path, $Len);
                $Location->image_size = filesize($Location->image_path);
            }
            else{
                $Location->image_name = $Location->image_path;
            }

            return ['status' => 'ok', 'location' => $Location, 'element_tag' => $ElementTag];
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
    public function UpdateInStoreLocation($request)
    {
        # code...
        $Id = $request['id'];
        $Name = $request['name'];
        $ImagePath = $request['image_path'];
        $ElementTag = $request['element_tag'];

        try {
            //code...
            $Locations = $this->where('id', $Id)->get();
            if(count($Locations) == 0){
                return ['status' => 'notfound', 'element_tag' => $ElementTag];
            }
            $Config = config('app');
            $ImagePath = $ImagePath == null ? $Config['nophoto'] : $Config['instore_images_path'] . $ImagePath;
            if(!\File::exists($ImagePath)){
                $ImagePath = config('app')['nophoto'];
            }

            $this->where('id', $Id)->update(['name' => $Name, 'image_path' => $ImagePath]);

            $Location = ['id' => $Id, 'name' => $Name, 'image_path' => $ImagePath];
            return ['status' => 'ok', 'location' => $Location, 'element_tag' => $ElementTag];
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
