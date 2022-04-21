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

Route::get('/check', function(){
    return view('measureunits2');
});

Route::get('/unavailables', function(){
    return view('unavailablesemail');
});

/**
 * Home page (admin dashboard or user dashboar)
 */
Route::get('/', function () {
    return view('admindashboard');
})->Middleware('checkusersstate');

/**
 * Unauthorized Action for the user
 */
Route::get('/unauth', function(){
    return view('admindashboard', ['unauthorized_user' => 'Unautorized']);
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->middleware('user.admin')->name('dashboard');

Route::get('/goback', function(){
    return session()->all();
});

/**
 * Error page
 */
Route::get('/error/{message}', function($message){
    return view('error', ['message' => $message]);
});

require __DIR__.'/auth.php';

Route::get('/vuejs', function(){
    return view('vue');
})->middleware('checkusersstate');

Route::get('/sqltest', 'SqltestController@SqlTest');

/*********************************
 *           USERS               *
 ********************************/

Route::get('/users', 'UsersController@ListUsers')->middleware('checkifcanregister');

Route::get('/getusers', 'UsersController@getUsers');

Route::post('/userbyid', 'UsersController@UserById');

Route::post('/saveuser', 'UsersController@SaveUser')->middleware('checkifcanregister');

Route::post('deleteuser', 'UsersController@DeleteUser');

Route::post('changepassword', 'UsersController@ChangePassword');

Route::post('/createuser', 'UsersController@CreateUser');

Route::get('authuser', 'UsersController@AuthUser');

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

  Route::get('/listproducts', 'ProductsController@ListProducts')->middleware('checkusersstate');

  Route::get('/listproducts1', 'ProductsController@ListProducts1')->middleware('checkusersstate');

  Route::post('/productimgupload', 'ProductsController@ProductImgUpload');

  Route::post('/createproduct', 'ProductsController@CreateProduct');

  Route::post('/deleteproduct', 'ProductsController@DeleteProduct');

  Route::get('/getproduct','ProductsController@GetProduct');

  Route::post('/updateproduct', 'ProductsController@UpdateProduct');
  
  Route::post('/markascounted', 'ProductsController@MarkAsCounted');
  
  Route::post('/markasdiscarded', 'ProductsController@MarkAsDiscarded');

  Route::post('reschedulecount', 'ProductsController@RescheduleCount');
  
  Route::get('/getsupplierprice','ProductsController@GetSupplierPrice');

  Route::get('/getproductunits', 'ProductsController@GetProductUnits');

  Route::post('/setproductunits', 'ProductsController@SetProductUnits');

  Route::get('/getcountedproducts', 'ProductsController@GetCountedProducts');

  Route::get('/getproductrequests', 'ProductsController@GetProductRequests');

  Route::get('/resetcounts', 'ProductsController@ResetCounts')->middleware('checkifcanregister');

  Route::get('/getproductsbysearch', 'ProductsController@getProductsBySearch');

/****************************************
 *                                      *
 *             PICKUP USER              *
 *                                      *
 ***************************************/

 Route::get('/pickupdashboard', 'PickupUserController@ShowDashboard')->middleware('user.pickup');

 Route::post('/completeorder', 'PickupUserController@CompleteOrder');

 Route::post('/checkorderline', 'PickupUserController@CheckOrderLine');

 
 /***************************************
  *                                     * 
  *          PENDING ORDERS             *
  *                                     *
  **************************************/

  Route::get('/showpendingorderspanel','PendingOrdersController@ShowPendingOrdersPanel')->middleware('checkusersstate');

  Route::get('/getpricesforsupplier','PendingOrdersController@GetPricesForSupplier');

/****************************************
 *                                      *
 *           USER DASHBOARD             *
 *                                      *
 ***************************************/
  
Route::get('/userdashboard', 'UserDashboardController@ShowUserDashboard')->middleware('user.user');

Route::get('getuserdashboard', 'UserDashboardController@GetUserDashboard');
  
Route::get('/searchfor', 'UserDashboardController@SearchFor');

/****************************************
 *                                      *
 *               ORDERS                 *
 *                                      *
 ***************************************/

 Route::post('/addtoorder', 'OrdersController@AddToOrder');

 Route::post('/submitorder', 'OrdersController@SubmitOrder');

 Route::post('/emailorder', 'OrdersController@EmailOrder');

 Route::get('orderpreview', 'OrdersController@OrderPreview');
 
 Route::get('exporttopdf', 'OrdersController@ExportToPdf');

 Route::post('/receiveorder', 'OrdersController@ReceiveOrder');

 /***************************************
  *                                     *
  *      SUPPLIERS-PRODUCTS PIVOTS      *
  *                                     *
  **************************************/

  Route::post('/createsuppliersproductspivot', 'SuppliersProductsPivotsController@CreatePivot');

  Route::get('/getsuppliersproductspivot', 'SuppliersProductsPivotsController@GetPivot');
 
 /***************************************
  *                                     *
  *           MEASURE UNITS             *
  *                                     *
  **************************************/

  Route::get('/measureunits','MeasureUnitsController@MeasureUnits')->middleware('checkusersstate');

  Route::get('/measureunits1','MeasureUnitsController@MeasureUnits1')->middleware('checkusersstate');
 
  Route::post('/createmeasureunit','MeasureUnitsController@CreateMeasureUnit');

  Route::post('/removemeasureunit', 'MeasureUnitsController@RemoveMeasureUnit');

  Route::get('/getmeasureunit','MeasureUnitsController@GetMeasureUnit');

  Route::post('/updatemeasureunit', 'MeasureUnitsController@UpdateMeasureUnit');

  Route::get('/searchbytext', 'MeasureUnitsController@searchByText');
