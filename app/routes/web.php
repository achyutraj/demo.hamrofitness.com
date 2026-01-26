<?php

use Illuminate\Support\Facades\Artisan;
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

Route::get('/cache-clear', function() {
    Artisan::call('cache:clear');
    return Artisan::output();
});

Route::get('/view-clear', function() {
    Artisan::call('view:clear');
    return Artisan::output();
});

Route::get('/all-clear', function() {
    Artisan::call('optimize:clear');
    return Artisan::output();
});

Route::get('/migrate', function() {
    Artisan::call('migrate');
    return 'migrate';
});

Route::get('/test',function(){
    $data = \App\Helpers\ADMSHelper::storeAttendance('2025-01-12','2025-01-13');
    dd($data);
});

Route::get('enquiry-form/{branch_name}', ['middleware'=>'web','uses' => 'EnquiryController@showEnquiryForm', 'as' => 'enquiry.create']);
Route::post('enquiry-form/store', ['middleware'=>'web','uses' => 'EnquiryController@store', 'as' => 'enquiry.store']);

Route::group(['middleware'=>'web','namespace' => 'Merchant', 'as' => 'merchant.'], function () {

    // Login resource controller
    Route::post('reset-password', ['as' => 'login.send-reset-link', 'uses' => 'MerchantsController@sendResetPasswordLink']);
    Route::get('reset/{token}', ['as' => 'login.reset-password', 'uses' => 'MerchantsController@resetPassword']);
    Route::post('update-password', ['as' => 'login.update-password', 'uses' => 'MerchantsController@updatePassword']);
    Route::resource('login', 'MerchantsController', ['only' => ['index', 'store']]);

    Route::get('lock-screen', ['uses' => 'LockScreenController@get', 'as' => 'lockscreen']);
    Route::get('keep-alive', ['uses' => 'LockScreenController@keepAlive', 'as' => 'keep-alive']);
    Route::post('lock-login', ['uses' => 'LockScreenController@post', 'as' => 'lockLogin']);
    Route::get('logout', ['uses' => 'LockScreenController@logout', 'as' => 'logout']);

});
