<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web']], function () {
    //region customer routes
    Route::post('customer/reset-password', ['as' => 'customer.send-reset-link', 'uses' => 'CustomerController@sendResetPasswordLink']);
    Route::get('customer/reset/{token}', ['as' => 'customer.reset-password', 'uses' => 'CustomerController@resetPassword']);
    Route::post('customer/update-password', ['as' => 'customer.update-password', 'uses' => 'CustomerController@updatePassword']);
    Route::post('customer/register-store', ['as' => 'customer.register-store', 'uses' => 'CustomerController@registerStore']);
    Route::get('customer/register', ['as' => 'customer.register', 'uses' => 'CustomerController@register']);
    Route::get('customer/{provider}/{id?}', ['as' => 'customer.social-login', 'uses' => 'CustomerController@redirectToProvider']);
    Route::get('customer/{provider}/callback', ['uses' => 'CustomerController@handleProviderCallback']);
    Route::resource('customer', 'CustomerController', ['only' => ['index', 'store']]);

    Route::group(['prefix' => 'customer-app', 'as' => 'customer-app.', 'middleware' => ['customer.auth', 'revalidate']], function () {
        Route::get('logout', ['as' => 'logout', 'uses' => 'CustomerController@customerLogout']);

        //region dashboard routes
        Route::post('markRead', ['as' => 'dashboard.markRead', 'uses' => 'CustomerDashboardController@markRead']);
        Route::resource('dashboard', 'CustomerDashboardController', ['only' => ['index']]);
        //endregion

        //region profile routes
        Route::post('profile/upload-webcam-image/{id?}', ['as' => 'profile.upload-webcam-image', 'uses' => 'CustomerProfileController@uploadWebcamImage']);
        Route::post('profile/upload-image', ['as' => 'profile.upload-image', 'uses' => 'CustomerProfileController@uploadImage']);
        Route::resource('profile', 'CustomerProfileController', ['only' => ['index', 'store']]);
        //endregion

        //region subscriptions routes
        Route::get('manage-subscription/get-membership', ['as' => 'manage-subscription.get-membership', 'uses' => 'CustomerManageSubscriptionController@getMembership']);
        Route::get('manage-subscription/get-membership-amount', ['as' => 'manage-subscription.get-membership-amount', 'uses' => 'CustomerManageSubscriptionController@getMembershipAmount']);
        Route::get('manage-subscription/get-data', ['as' => 'manage-subscription.get-data', 'uses' => 'CustomerManageSubscriptionController@getData']);
        Route::resource('manage-subscription', 'CustomerManageSubscriptionController', ['except' => ['edit', 'update']]);
        //endregion


        //region membership due routes
        Route::get('membership/due-payments', ['as' => 'payments.due-payments', 'uses' => 'CustomerPaymentController@duePayments']);
        Route::get('membership/get-due-payment-data', ['as' => 'payments.get-due-payment-data', 'uses' => 'CustomerPaymentController@getDuePaymentData']);
        Route::get('membership/get-payment-data', ['as' => 'payments.get-payment-data', 'uses' => 'CustomerPaymentController@getPaymentData']);
        Route::get('membership/download-invoice/{id}', ['as' => 'payments.download-invoice', 'uses' => 'CustomerPaymentController@downloadInvoice']);

        //membership payment routes
        Route::get('payments/remain/{id}', ['as' => 'payments.remainingPayment', 'uses' => 'CustomerPaymentController@remainingPayment']);
        Route::get('payments/create/{id?}/', ['as' => 'payments.create','uses' => 'CustomerPaymentController@create']);
        Route::post('payments/pay', ['as' => 'payments.pay','uses' => 'CustomerPaymentController@pay']);
        Route::post('payments/store', ['as' => 'payments.store','uses' => 'CustomerPaymentController@store']);
        Route::get('payments/', ['as' => 'payments.index','uses' => 'CustomerPaymentController@index']);

        Route::any('payments/{purchaseid}/success', 'CustomerPaymentController@successfulPayment')->name('payments.success');
        Route::any('payments/{purchaseid}/cancel', 'CustomerPaymentController@cancelledPayment')->name('payments.cancel');
        //endregion

        //product payments routes
        Route::get('productPayments/due-payments', ['as' => 'product-payments.due-payments', 'uses' => 'ProductPaymentController@dueIndex']);
        Route::get('productPayments/get-due-payment-data', ['as' => 'product-payments.get-due-payment-data', 'uses' => 'ProductPaymentController@getDueData']);
        Route::get('productPayments/get-payment-data', ['as' => 'product-payments.get-payment-data', 'uses' => 'ProductPaymentController@getPaymentData']);

        Route::get('productPayments/remain/{id}', ['as' => 'product-payments.remainingPayment', 'uses' => 'ProductPaymentController@remainingPayment']);
        Route::get('productPayments/create/{id?}', ['as' => 'product-payments.create', 'uses' => 'ProductPaymentController@create']);
        Route::post('productPayments/pay', ['as' => 'product-payments.pay','uses' => 'ProductPaymentController@pay']);
        Route::post('productPayments/store', ['as' => 'product-payments.store','uses' => 'ProductPaymentController@store']);
        Route::get('productPayments', ['as' => 'product-payments.index', 'uses' => 'ProductPaymentController@index']);

        Route::any('productPayments/{saleid}/success', 'ProductPaymentController@successfulPayment')->name('product-payments.success');
        Route::any('productPayments/{saleid}/cancel', 'ProductPaymentController@cancelledPayment')->name('product-payments.cancel');
        //endregion

        //region attendance routes
        Route::resource('attendance', 'CustomerAttendanceController', ['only' => ['index']]);
        //endregion

        //region message routes
        Route::resource('message', 'CustomerMessageController', ['except' => ['edit', 'destroy']]);
        //endregion

        /*
       |--------------------------------------------------------------------------
       | Developer module
       |--------------------------------------------------------------------------
       */
        Route::get('developers', 'DeveloperController@settings')->name('developer.settings');
        Route::post('developers/generate', 'DeveloperController@generate')->name('developer.generate');
        Route::get('developers/docs', 'DeveloperController@docs')->name('developer.docs');

        //Feedback
        Route::resource('feedback', 'FeedbackController')->only('index','store','show','update','destroy');

        //Body Measurement
        Route::get('body-measurement', ['uses' => 'BodyMeasurementController@index', 'as' => 'measurements.index']);

        //Locker Management
        Route::get('locker-dues/get-due-payment-data', ['as' => 'locker-payments.get-due-payment-data', 'uses' => 'LockerManagementController@getDueData']);
        Route::get('locker-payments/get-payment-data', ['as' => 'locker-payments.get-payment-data', 'uses' => 'LockerManagementController@getPaymentData']);

        Route::post('locker-payments/pay', ['as' => 'locker-payments.pay','uses' => 'LockerManagementController@pay']);
        Route::post('locker-payments/store', ['as' => 'locker-payments.store','uses' => 'LockerManagementController@store']);
        Route::any('locker-payments/{reservId}/success', 'LockerManagementController@successfulPayment')->name('locker-payments.success');
        Route::any('locker-payments/{reservId}/cancel', 'LockerManagementController@cancelledPayment')->name('locker-payments.cancel');

        Route::get('locker-payments/remain/{id}', ['as' => 'locker-payments.remainingPayment', 'uses' => 'LockerManagementController@remainingPayment']);
        Route::get('locker-payments/create/{id?}', ['as' => 'locker-payments.create', 'uses' => 'LockerManagementController@create']);
        Route::get('locker-payments', ['uses' => 'LockerManagementController@index', 'as' => 'locker-payments.index']);
        Route::get('locker-dues', ['uses' => 'LockerManagementController@dueIndex', 'as' => 'locker-payments.dueIndex']);

        //Locker Reservation
        Route::get('reservations/get-locker-amount', ['as' => 'reservations.get-locker-amount', 'uses' => 'LockerReservationController@getLockerAmount']);
        Route::get('reservations/get-data', ['as' => 'reservations.get-data', 'uses' => 'LockerReservationController@getData']);
        Route::get('locker-dues/get-locker-category/{id}', ['uses' => 'LockerReservationController@getLockerCategory', 'as' => 'reservations.getLockerCategory']);
        Route::resource('reservations', 'LockerReservationController', ['except' => ['edit', 'update']]);
    }
    );
    //endregion
});
