<?php

namespace App\Http\Controllers;

use App\Models\ProductLocations;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductLocationsController extends Controller
{
    /**
     * 
     * @return view
     */
    public function ProductsLocations(Request $request)
    {
        # code...
        return (new ProductLocations())->ProductsLocations($request);
    }

    /**
     * 
     * @param file
     * 
     * @return fileName
     * 
     */
    public function InStoreImgUpload(Request $request)
    {
        # code...
        if(!empty($_FILES)){
            $fileToUpload = $request->file('file');

            $user = Auth::user();
            if($user != null){
                $counterFile = "counters/instore_counter_" . $user->id;
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
                $path = $fileToUpload->storeAs($Config['instore_images_path'], $fileName, 'images');
                return ['filename' => $fileName];
            }
        }
    }

    /**
     * 
     * Call this action to create an InStore Product Location
     */
    public function CreateInStoreLocation(Request $request)
    {
        # code...
        return (new ProductLocations())->CreateInStoreLocation($request);
    }
}
