<?php

namespace App\Http\Controllers;

use App\Models\SupplierProductLocations;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierProductLocationsController extends Controller
{
    //
    public function SuppliersProductLocation(Request $request)
    {
        # code...
        return (new SupplierProductLocations())->SuppliersProductLocation($request);
    }

    /**
     * 
     * @param file
     * 
     * @return fileName
     * 
     */
    public function SupplierImgUpload(Request $request)
    {
        # code...
        if(!empty($_FILES)){
            $fileToUpload = $request->file('file');

            $user = Auth::user();
            if($user != null){
                $counterFile = "counters/suppliers_prod_location_counter_" . $user->id;
                $contents = "0";
                if(Storage::exists($counterFile)){
                    $contents = Storage::get($counterFile);
                    $contents++;
                }
                Storage::put($counterFile, $contents);
    
                $fileName = $_FILES['file']['name'];
                $fileNameChunks = explode('.', $fileName);
                $fileExt = $fileNameChunks[count($fileNameChunks) - 1];
                $fileName = $contents . "_" . $user->id . "." . $fileExt;
                $Config = config('app');
                $path = $fileToUpload->storeAs($Config['suppliers_prod_location_images_path'], $fileName, 'images');
                return ['filename' => $fileName];
            }
        }
    }

    /**
     * 
     * Call this action to create an Supplier Product Location
     * 
     */
    public function CreateSupplierLocation(Request $request)
    {
        # code...
        return (new SupplierProductLocations())->CreateSupplierLocation($request);
    }
    
    /**
     * 
     * Call this action to delete an Supplier Product Location
     * 
     */
    public function DeleteSupplierLocation(Request $request)
    {
        # code...
        return (new SupplierProductLocations())->DeleteSupplierLocation($request);
    }

    public function GetSupplierLocation(Request $request)
    {
        # code...
        return (new SupplierProductLocations())->GetSupplierLocation($request);
    }

    public function UpdateSupplierLocation(Request $request)
    {
        # code...
        return (new SupplierProductLocations())->UpdateSupplierLocation($request);
    }

}
