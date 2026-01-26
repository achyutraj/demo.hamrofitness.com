<?php

use App\Http\Controllers\API\Customer\CustomerAttendanceController;
use App\Http\Controllers\API\Customer\CustomerController;
use App\Http\Controllers\API\Customer\CustomerLoginController;
use App\Http\Controllers\API\Customer\CustomerMessageController;
use App\Http\Controllers\API\Customer\CustomerPaymentController;
use App\Http\Controllers\API\Customer\CustomerLockerPaymentController;
use App\Http\Controllers\API\Customer\CustomerSubscriptionController;
use App\Http\Controllers\API\Customer\CustomerReservationController;
use App\Http\Controllers\API\Customer\CustomerProductPurchaseController;
use App\Http\Controllers\API\Customer\ProductPaymentController;

use App\Http\Controllers\API\Device\DeviceController;
use App\Http\Controllers\API\Merchant\FrontendController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//laravel sanctum api routes

//merchant info route
Route::get('merchant/home', [FrontendController::class, 'home']);

Route::get('merchant-info/{mobileApp}', [FrontendController::class, 'merchantInfo'])->name('merchant.merchantInfo');
Route::get('v1/{gym_slug}', [FrontendController::class, 'getGymInactiveClients'])->name('getGymInactiveClients');

Route::get('/tutorials', [FrontendController::class, 'tutorialLists']);
Route::get('/payment-sources', [FrontendController::class, 'paymentSources']);


//customer route
Route::group(['prefix' => 'customer', 'as' => 'api.'], function () {

    Route::middleware('api')->group(function(){
        Route::post('login', [CustomerLoginController::class, 'login']);
        Route::post('register', [CustomerLoginController::class, 'register']);
    });
   

    Route::group(['middleware' => ['api.customer']], function () {
        //region dashboard routes
        Route::get('dashboard', [CustomerController::class, 'dashboard']);
        Route::get('qr-info', [CustomerController::class, 'getQRInfo']);
        Route::get('diet-plans', [CustomerController::class, 'dietPlanList']);
        Route::get('training-plans', [CustomerController::class, 'trainingPlanList']);
        Route::get('class-schedules', [CustomerController::class, 'classSchedulePlanList']);


        //endregion

        //notification
        Route::post('markRead', [CustomerController::class, 'markRead'])->name('dashboard.markRead');
        Route::get('unread-notification', [CustomerController::class, 'getTotalUnReadNotification'])->name('dashboard.getTotalUnReadNotification');
        Route::get('count-unread-notification', [CustomerController::class, 'getTotalCountUnReadNotification'])->name('dashboard.getTotalCountUnReadNotification');
        //region profile routes

        //profile de-active
        Route::post('profile/deactivate', [CustomerController::class, 'updateClientStatus'])->name('profile.updateClientStatus');
        
        Route::post('profile/upload-webcam-image/{id?}', [CustomerController::class, 'uploadWebcamImage'])->name('profile.upload-webcam-image');
        Route::post('profile/upload-image', [CustomerController::class, 'uploadImage'])->name('profile.upload-image');
        Route::get('profile', [CustomerController::class, 'profileIndex'])->name('profile.index');
        Route::post('profile/store', [CustomerController::class, 'profileStore'])->name('profile.store');
        //endregion

        //membership subscription
        Route::get('membership-plans', [CustomerSubscriptionController::class,'membershipPlanLists']);
        Route::delete('manage-subscription/destroy/{id}', [CustomerSubscriptionController::class,'destroy']);
        Route::get('manage-subscription/show/{id}', [CustomerSubscriptionController::class,'show']);
        Route::post('manage-subscription/store', [CustomerSubscriptionController::class,'store']);
        Route::get('manage-subscription', [CustomerSubscriptionController::class,'index']);
        //endregion
        //region membership due routes
        Route::get('membership/due-payments', [CustomerPaymentController::class, 'duePayments'])->name('payments.due-payments');
        Route::get('membership/download-invoice/{id}', [CustomerPaymentController::class, 'downloadInvoice'])->name('payments.download-invoice');
        //endregion

        //membership payment routes
        Route::post('payments/store', [CustomerPaymentController::class, 'store'])->name('payments.store');
        Route::get('payments', [CustomerPaymentController::class, 'index'])->name('payments.index');
        //endregion

         //locker reservation
         Route::get('locker-lists', [CustomerReservationController::class,'availableLockerLists']);
         Route::delete('manage-reservation/destroy/{id}', [CustomerReservationController::class,'destroy']);
         Route::get('manage-reservation/show/{id}', [CustomerReservationController::class,'show']);
         Route::post('manage-reservation/store', [CustomerReservationController::class,'store']);
         Route::get('manage-reservation', [CustomerReservationController::class,'index']);
         //endregion

         //region locker due routes
        Route::get('locker/due-payments', [CustomerLockerPaymentController::class, 'duePayments']);
        //endregion

        //locker payment routes
        Route::post('locker-payments/store', [CustomerLockerPaymentController::class, 'store']);
        Route::get('locker-payments', [CustomerLockerPaymentController::class, 'index']);
        //endregion

         //product purchases
         Route::get('product-lists', [CustomerProductPurchaseController::class,'productLists']);
         Route::delete('product-purchases/destroy/{id}', [CustomerProductPurchaseController::class,'destroy']);
         Route::get('product-purchases/show/{id}', [CustomerProductPurchaseController::class,'show']);
         Route::post('product-purchases/store', [CustomerProductPurchaseController::class,'store']);
         Route::get('product-purchases', [CustomerProductPurchaseController::class,'index']);
         //endregion


        //region products due routes
        Route::get('productPayments/due-payments', [ProductPaymentController::class, 'dueIndex']);
        //endregion

        //product payments routes
        Route::post('productPayments/store', [ProductPaymentController::class, 'store'])->name('product-payments.store');
        Route::get('productPayments', [ProductPaymentController::class, 'index'])->name('product-payments.index');
        //endregion

        //region attendance routes
        Route::get('attendance', [CustomerAttendanceController::class,'index']);
        //endregion

        //region message routes
        Route::apiResource('message', CustomerMessageController::class)->except(['edit', 'destroy']);
        //endregion
    });
});

 
//device route for RFID devices
Route::group(['prefix' => 'device', 'as' => 'api.', 'middleware' => ['api']], function () {

    Route::get('company-device', [DeviceController::class, 'getCompanyDeviceList']);
    Route::get('device-info', [DeviceController::class, 'getDeviceInfoBySN']);

    Route::get('client-list', [DeviceController::class, 'getActiveClientList']);
    Route::post('client-attendance', [DeviceController::class, 'storeClientAttendance']);
});
