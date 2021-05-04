<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/unauth', function(){
    return view('welcome', ['unauthorized_user' => 'Unautorized']);
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

/*********************************
 *           USERS               *
 ********************************/

Route::get('/users', 'UsersController@ListUsers')->name('users')->middleware('checkifcanregister');

Route::post('/userbyid', 'UsersController@UserById');

Route::post('/saveuser', 'UsersController@SaveUser')->middleware('checkifcanregister');

Route::post('deleteuser', 'UsersController@DeleteUser');

Route::post('changepassword', 'UsersController@ChangePassword');

Route::post('/createuser', 'UsersController@CreateUser');

/****************************************
 *                                      *
 *             SUPPLIERS                *
 *                                      * 
 ***************************************/

 Route::get('/suppliers', 'SuppliersController@ListSuppliers')->name('suppliers')->middleware('checkifcanregister');

 Route::post('/addsupplier', 'SuppliersController@AddSupplier');

 Route::post('/supplierimgupload', 'SuppliersController@UploadImage');

 Route::post('/getsupplier', 'SuppliersController@GetSupplier');

 Route::post('/updatesupplier', 'SuppliersController@UpdateSupplier');

 Route::post('/deletesupplier', 'SuppliersController@DeleteSupplier');

 /***************************************
  *                                     *
  *         PRODUCTS LOCATIONS          *
  *                                     *   
  **************************************/

 Route::get('/productslocations', 'ProductLocationsController@ProductsLocations')->middleware('checkifcanregister');

 Route::post('/instoreimgupload', 'ProductLocationsController@InStoreImgUpload');

 Route::post('/createinstorelocation', 'ProductLocationsController@CreateInStoreLocation');

 Route::post('/deleteinstorelocation', 'ProductLocationsController@DeleteInStoreLocation');

 Route::post('/getinstorelocation', 'ProductLocationsController@GetInStoreLocation');

 Route::post('/updateinstorelocation', 'ProductLocationsController@UpdateInStoreLocation');
 
 /***************************************
  *                                     *
  *    SUPPLIER'S PRODUCT LOCATIONS     *
  *                                     *
  **************************************/

  Route::get('/suppliersproductlocation', 'SupplierProductLocationsController@SuppliersProductLocation')->middleware('checkifcanregister');

  Route::post('/supplierlocationimgupload', 'SupplierProductLocationsController@SupplierImgUpload');

  Route::post('/createsupplierlocation', 'SupplierProductLocationsController@CreateSupplierLocation');
 
  Route::post('/deletesupplierlocation', 'SupplierProductLocationsController@DeleteSupplierLocation');
 
  Route::post('/getsupplierlocation', 'SupplierProductLocationsController@GetSupplierLocation');
 
  Route::post('/updatesupplierlocation', 'SupplierProductLocationsController@UpdateSupplierLocation');

  
 