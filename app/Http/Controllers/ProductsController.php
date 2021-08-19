<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

use App\Models\Products;

class ProductsController extends Controller
{
    //

    public function ListProducts(Request $request)
    {
        # code...
        return (new Products())->ListProducts($request);
    }

    public function ProductImgUpload(Request $request)
    {
        # code...
        $fileToUpload = $request->file('file');
        if(!empty($_FILES)){
            $user = Auth::user();
            if($user != null){
                $Config = config('app');
                $counterFile = $Config['counter'] . $user->id;
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
                $path = $fileToUpload->storeAs($Config['products_images_path'], $fileName, 'images');
                return ['filename' => $fileName];
            }
        }
        return ['filename' => ''];
    }

    public function CreateProduct(Request $request)
    {
        # code...
        return (new Products())->CreateProduct($request);
    }

    public function DeleteProduct(Request $request)
    {
        # code...
        return (new Products())->DeleteProduct($request);
    }

    public function GetProduct(Request $request)
    {
        # code...
        return (new Products())->GetProduct($request);
    }

    public function MarkAsCounted(Request $request)
    {
        # code...
        return (new Products())->MarkAsCounted($request);
    }
    public function UpdateProduct(Request $request)
    {
        # code...
        return (new Products())->UpdateProduct($request);
    }

    public function GetSupplierPrice(Request $request)
    {
        return (new Products())->GetSupplierPrice($request);
    }

    public function GetProductUnits(Request $request)
    {
        # code...
        return (new Products())->GetProductUnits($request);
    }

    public function SetProductUnits(Request $request)
    {
        # code...
        return (new Products())->SetProductUnits($request);
    }

    public function GetCountedProducts(Request $request)
    {
        # code...
        return ((new Products())->GetCountedProducts($request));
    }

    public function GetProductRequests(Request $request)
    {
        # code...
        return (new Products())->GetProductRequests($request);
    }
}
