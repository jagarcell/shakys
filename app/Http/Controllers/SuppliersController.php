<?php

namespace App\Http\Controllers;

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
            $Config = config('app');
	        $path = $fileToUpload->storeAs($Config['suppliers_images_path'], $_FILES['file']['name'], 'suppliers_images');
        }
    }

    public function UpdateSupplier(Request $request)
    {
        # code...
        return (new Suppliers())->UpdateSupplier($request);
    }
}
