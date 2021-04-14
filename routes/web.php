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

/*********************************
 *           USERS               *
 ********************************/

Route::get('/users', 'UsersController@ListUsers')->name('users')->middleware('checkifcanregister');

Route::get('/userbyid', 'UsersController@UserById');

Route::post('/saveuser', 'UsersController@SaveUser')->middleware('checkifcanregister');

Route::post('deleteuser', 'UsersController@DeleteUser');

Route::post('changepassword', 'UsersController@ChangePassword');

require __DIR__.'/auth.php';
