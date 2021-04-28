<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Suppliers;

class SuppliersController extends Controller
{
    //
    public function ListSuppliers(Request $request)
    {
        # code...
        return (new Suppliers())->ListSuppliers($request);
    }

    public function AddSupplier(Request $request)
    {
        # code...
        return (new Suppliers())->AddSupplier($request);
    }

    public function GetSupplier(Request $request)
    {
        # code...
        return (new Suppliers())->GetSupplier($request);
    }

    public function UploadImage(Request $request)
    {
        $fileToUpload = $request->file('file');

        if(!empty($_FILES)){
            $user = Auth::user();
            if($user != null){
                $counterFile = "counters/counter_" . $user->id;
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
                $path = $fileToUpload->storeAs($Config['suppliers_images_path'], $fileName, 'images');
                return ['filename' => $fileName];
            }
        }
    }

    public function UpdateSupplier(Request $request)
    {
        # code...
        return (new Suppliers())->UpdateSupplier($request);
    }

    public function DeleteSupplier(Request $request)
    {
        # code...
        return (new Suppliers())->DeleteSupplier($request);
    }
}
