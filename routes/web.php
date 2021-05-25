<?php

use Illuminate\Support\Facades\Route;
use App\Models\Users;

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

/**
 * Home page (admin dashboard or user dashboar)
 */
Route::get('/', function () {
    return view('admindashboard');
})->Middleware('checkusersstate');

Route::get('/userdashboard', 'UserDashboardController@ShowUserDashboard');

/**
 * Unauthorized Action for the user
 */
Route::get('/unauth', function(){
    return view('admindashboard', ['unauthorized_user' => 'Unautorized']);
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

/**
 * Error page
 */
Route::get('/error/{message}', function($message){
    return view('error', ['message' => $message]);
});

require __DIR__.'/auth.php';

/*********************************
 *           USERS               *
 ********************************/

Route::get('/users', 'UsersController@ListUsers')->middleware('checkifcanregister');

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

 Route::get('/getsuppliers', 'SuppliersController@GetSuppliers');

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

 /***************************************
  *                                     *
  *             PRODUCTS                *
  *                                     *
  **************************************/

  Route::get('/listproducts', 'ProductsController@ListProducts');

  Route::post('/productimgupload', 'ProductsController@ProductImgUpload');

  Route::post('/createproduct', 'ProductsController@CreateProduct');

  Route::post('/deleteproduct', 'ProductsController@DeleteProduct');

  Route::get('/getproduct','ProductsController@GetProduct');

  Route::post('/updateproduct', 'ProductsController@UpdateProduct');
  
  Route::post('/markascounted', 'ProductsController@MarkAsCounted');

/****************************************
 *                                      *
 *             PICKUP USER              *
 *                                      *
 ***************************************/

 Route::get('/pickupdashboard', 'PickupUserController@ShowDashboard');

 /***************************************
  *                                     * 
  *          PENDING ORDERS             *
  *                                     *
  **************************************/

  Route::get('/showpendingorderspanel','PendingOrdersController@ShowPendingOrdersPanel');
  