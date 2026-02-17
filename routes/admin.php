<?php

use Illuminate\Support\Facades\Route;

//region routes for merchant panel
Route::group(['namespace' => 'Merchant', 'as' => 'merchant.'], function () {

    Route::get("/", "MerchantsController@index");
    Route::get("/dashboard", "MerchantsController@dashboard");
});
//endregion

//region gym merchant admin routes
Route::group(['prefix' => 'gym-admin', 'middleware' => ['merchant.auth'], 'namespace' => 'GymAdmin', 'as' => 'gym-admin.'], function () {

    //activity log
    Route::get('activity-logs', ['as' => 'activity_log', 'uses' => 'AdminGymDashboardController@activityLog']);

    // Mark read notifications
    Route::post('markRead', ['as' => 'dashboard.markRead', 'uses' => 'AdminGymDashboardController@markRead']);
    Route::resource('dashboard', 'AdminGymDashboardController', ['only' => ['index', 'markRead']]);

    // Gym Admin Logout Controller
    Route::resource('logout', 'GymAdminLogoutController', ['only' => ['index']]);

    // Gym Admin Base Controller
    Route::get('client/profile-download/{ID}', ['uses' => 'AdminGymClientsController@downloadProfile', 'as' => 'client.downloadProfile']);
    Route::get('clients/get-data/{id}', ['uses' => 'AdminGymClientsController@getData', 'as' => 'client.getData']);

    Route::get('client/index2', ['uses' => 'AdminGymClientsController@index2', 'as' => 'client.index2']);

    Route::get('client/index', ['uses' => 'AdminGymClientsController@index', 'as' => 'client.index']);
    Route::get('client/create', ['uses' => 'AdminGymClientsController@create', 'as' => 'client.create']);
    Route::get('client/show/{ID}', ['uses' => 'AdminGymClientsController@show', 'as' => 'client.show']);
    Route::post('client/store', ['uses' => 'AdminGymClientsController@store', 'as' => 'client.store']);
    Route::post('client/update', ['uses' => 'AdminGymClientsController@update', 'as' => 'client.update']);
    Route::get('client/ajax_create', ['uses' => 'AdminGymClientsController@ajax_create', 'as' => 'client.ajax_create']);
    Route::get('client/remove/modal/{ID}', ['uses' => 'AdminGymClientsController@removeClient', 'as' => 'remove.modal']);
    Route::get('client/calendar/{ID}', ['uses' => 'AdminGymClientsController@calender', 'as' => 'client.calender']);

    Route::get('client/ajax/membership-subscription/{ID}', ['uses' => 'AdminGymClientsController@ajax_membership_subscriptions', 'as' => 'client.ajax-subscriptions']);
    Route::get('client/ajax/locker-reservations/{ID}', ['uses' => 'AdminGymClientsController@ajax_locker_reservations', 'as' => 'client.ajax-reservations']);
    Route::get('client/ajax/payments/{ID}', ['uses' => 'AdminGymClientsController@ajax_create_payments', 'as' => 'client.ajax-payments']);
    Route::get('client/ajax/due/{ID}', ['uses' => 'AdminGymClientsController@ajax_create_due', 'as' => 'client.ajax-due']);
    Route::get('client/ajax/product-payments/{ID}', ['uses' => 'AdminGymClientsController@ajax_create_product_payments', 'as' => 'client.ajax-product-payments']);
    Route::get('client/ajax/product-due/{ID}', ['uses' => 'AdminGymClientsController@ajax_create_product_due', 'as' => 'client.ajax-product-due']);
    Route::get('client/ajax/locker-payments/{ID}', ['uses' => 'AdminGymClientsController@ajax_locker_payments', 'as' => 'client.ajax-locker-payments']);
    Route::get('client/ajax/locker-due/{ID}', ['uses' => 'AdminGymClientsController@ajax_locker_due', 'as' => 'client.ajax-locker-dues']);

    Route::post('client/save-webcam-image/{id}', ['as' => 'client.save-webcam-image', 'uses' => 'AdminGymClientsController@saveWebCamImage']);
    Route::get('client/remove/{ID}', ['uses' => 'AdminGymClientsController@destroy', 'as' => 'client.destroy']);

    Route::post('gymclient/uploadimage', ['as' => 'gymclient.uploadimage', 'uses' => 'AdminGymClientsController@uploadImage']);
    Route::get('register-enquiry/{ID}', ['uses' => 'AdminGymClientsController@registerEnquiry', 'as' => 'client.register-enquiry']);


    // Gym Enquiry Controller
    Route::get('enquiry/download-qrcode', ['uses' => 'GymEnquiryController@downloadQrCode', 'as' => 'enquiry.downloadQrCode']);
    Route::get('enquiry/ajax/create', ['uses' => 'GymEnquiryController@ajaxCreate', 'as' => 'enquiry.create.ajax']);
    Route::get('enquiry/remove/modal/{ID}', ['uses' => 'GymEnquiryController@removeEnquiry', 'as' => 'enquiry.modal']);
    Route::get('enquiry/follow-modal/{ID}', ['uses' => 'GymEnquiryController@followModal', 'as' => 'enquiry.follow-modal']);
    Route::get('enquiry/view-follow-modal/{ID}', ['uses' => 'GymEnquiryController@viewFollowModal', 'as' => 'enquiry.view-follow-modal']);
    Route::post('enquiry/saveFollowUp', ['uses' => 'GymEnquiryController@saveFollowUp', 'as' => 'enquiry.saveFollowUp']);
    Route::post('enquiry/update', ['uses' => 'GymEnquiryController@update', 'as' => 'enquiry.update']);
    Route::resource('enquiry', 'GymEnquiryController')->except(['update', 'show']);

    // Gym Setting
    Route::get('setting/notification', ['as' => 'setting.notification', 'uses' => 'GymAdminSettingController@notificationPage']);
    Route::post('setting/store-notification-credentials', ['as' => 'setting.storeNotification', 'uses' => 'GymAdminSettingController@storeNotification']);

    Route::get('setting/sms', ['as' => 'setting.sms', 'uses' => 'GymAdminSettingController@smsPage']);
    Route::post('setting/store-sms-credentials', ['as' => 'setting.storeSmsCredentials', 'uses' => 'GymAdminSettingController@storeSmsCredentials']);
    Route::get('setting/mail', ['as' => 'setting.mail', 'uses' => 'GymAdminSettingController@mailPage']);
    Route::post('setting/store-mail-credentials', ['as' => 'setting.storeMailCredentials', 'uses' => 'GymAdminSettingController@storeMailCredentials']);
    Route::get('setting/file-upload', ['as' => 'setting.fileUpload', 'uses' => 'GymAdminSettingController@fileUploadPage']);
    Route::post('setting/store-file-upload-credentials', ['as' => 'setting.storeFileUploadCredentials', 'uses' => 'GymAdminSettingController@storeFileUploadCredentials']);

    Route::get('setting/payment-gateways', ['as' => 'setting.payment-gateways', 'uses' => 'GymAdminSettingController@paymentsPage']);
    Route::post('setting/store-payment-gateways', ['as' => 'setting.storePayments', 'uses' => 'GymAdminSettingController@storePayments']);

    Route::get('setting/others', ['as' => 'setting.others', 'uses' => 'GymAdminSettingController@othersPage']);
    Route::post('setting/store-others-setting-credentials', ['as' => 'setting.storeOtherSettingCredentials', 'uses' => 'GymAdminSettingController@storeOtherSettingCredentials']);
    Route::get('setting/footer', ['as' => 'setting.footer', 'uses' => 'GymAdminSettingController@footerPage']);
    Route::post('setting/store-footer-setting-credentials', ['as' => 'setting.storeFooterSettingCredentials', 'uses' => 'GymAdminSettingController@storeFooterSettingCredentials']);
    Route::post('gymsetting/image', ['as' => 'gymsetting.image', 'uses' => 'GymAdminSettingController@image']);
    Route::post('setting/front-image', ['as' => 'setting.frontImage', 'uses' => 'GymAdminSettingController@storeFrontImage']);
    Route::post('setting/customer-image', ['as' => 'setting.customerImage', 'uses' => 'GymAdminSettingController@storeCustomerImage']);
    Route::get('setting/activities', ['as' => 'setting.activityPage', 'uses' => 'GymAdminSettingController@activityPage']);

    Route::get('setting/mobile-apps/update', ['as' => 'setting.apps', 'uses' => 'GymAdminSettingController@appPage']);

    //setting
    Route::resource('setting', 'GymAdminSettingController');

    //activity
    Route::post('activities/store', ['as' => 'activity.create', 'uses' => 'LevelActivityController@create']);
    Route::post('activities/{id}/update', ['as' => 'activity.update', 'uses' => 'LevelActivityController@update']);
    Route::delete('activities/{id}/delete', ['as' => 'activity.destroy', 'uses' => 'LevelActivityController@delete']);

    //classesTrainers
    Route::resource('classesTrainers', 'ClassesController');

    Route::post('trainers/store', 'TrainerController@store')->name('trainers.store');
    Route::post('trainers/{id}/edit', 'TrainerController@update')->name('trainers.update');
    Route::delete('trainers/{id}/delete', 'TrainerController@delete')->name('trainers.destroy');

    Route::post('createClasses', 'ClassesController@store')->name('classes.store');
    Route::post('editClasses/{id}', 'ClassesController@update')->name('classes.update');
    Route::delete('deleteClasses/{id}', 'ClassesController@delete')->name('classes.destroy');

    Route::get('banks-and-branches', 'BankController@index')->name('banksBranches.index');
    Route::post('banks/store', 'BankController@store')->name('banks.create');
    Route::post('banks/{id}/edit', 'BankController@update')->name('banks.update');
    Route::delete('banks/{id}/delete', 'BankController@delete')->name('banks.delete');

    Route::post('bank-branches/store', 'BankBranchController@store')->name('bankBranch.create');
    Route::post('bank-branches/{id}/edit', 'BankBranchController@update')->name('bankBranch.update');
    Route::delete('bank-branches/{id}/delete', 'BankBranchController@delete')->name('bankBranch.delete');

    Route::get('BanksAccounts', 'BankAccountController@index')->name('banksAccount.index');
    Route::post('bank-accounts/store', 'BankAccountController@store')->name('banksAccount.create');
    Route::post('bank-accounts/{id}/edit', 'BankAccountController@update')->name('banksAccount.update');
    Route::delete('bank-accounts/{id}/delete', 'BankAccountController@delete')->name('banksAccount.delete');

    Route::get('getBankBranches/{bank_id}', 'BankAccountController@get_bank_branches')->name('getBankBranches');

    //Bank Ledger
    Route::get('bank-ledger', 'BankLedgerController@index')->name('bankLedger.index');
    Route::post('deposit-withdraw', 'BankLedgerController@store')->name('bankLedger.create');
    Route::post('edit-transaction/{id}', 'BankLedgerController@update')->name('bankLedger.update');
    Route::delete('delete-transaction/{id}', 'BankLedgerController@delete')->name('bankLedger.delete');

    // Gym backup
    Route::get('backup/getbackup/{id}', ['uses' => 'GymAdminbackupController@getbackup', 'as' => 'backup.getbackup']);
    Route::resource('backup', 'GymAdminbackupController');

    // Software update
    Route::resource('upcoming', 'SoftwareUpdateController')->except('show', 'destroy');

    // Gym membership resource
    Route::resource('membership-plans', 'GymAdminMembershipController')->except('show');
    Route::get('membership-plans/{id}/history', 'GymAdminMembershipController@history')->name('membership-plans.history');

    // Suppliers resource
    Route::post('suppliers/update', ['uses' => 'GymSupplierController@update', 'as' => 'suppliers.update']);
    Route::resource('suppliers', 'GymSupplierController', ['except' => ['show', 'update']]);

    // Gym Profile Controller
    Route::resource('profile', 'GymAdminProfileController');
    Route::post('gym/admin/save-webcam-image/{id}', ['as' => 'profile.save-webcam-image', 'uses' => 'GymAdminProfileController@saveWebCamImage']);
    Route::post('gym/uploadimage', ['as' => 'gym.uploadimage', 'uses' => 'GymAdminProfileController@uploadImage']);

    // Gym Membership Payments
    Route::get('membership-payments/ajax-create', ['uses' => 'GymMembershipPaymentsController@ajax_create', 'as' => 'membership-payment.ajax-create']);

    Route::get('membership-payments/ajax-create-deleted', ['uses' => 'GymMembershipPaymentsController@ajax_create_deleted', 'as' => 'membership-payment.ajax-create-deleted']);
    Route::get('membership-payments/view-receipt/{id}', ['uses' => 'GymMembershipPaymentsController@viewReceipt', 'as' => 'membership-payment.view-receipt']);
    Route::get('membership-payments/email-receipt/{id}', ['uses' => 'GymMembershipPaymentsController@emailReceipt', 'as' => 'membership-payment.email-receipt']);
    Route::get('clientPurchases/{id}', ['as' => 'gympurchase.clientPurchases', 'uses' => 'GymMembershipPaymentsController@clientPurchases']);
    Route::get('clientPayment/{id}', ['as' => 'gympurchase.clientPayment', 'uses' => 'GymMembershipPaymentsController@clientPayment']);
    Route::get('clientEditPayment/{id}', ['as' => 'gympurchase.clientEditPayment', 'uses' => 'GymMembershipPaymentsController@clientEditPayment']);
    Route::get('add-payment-modal/{id}', ['as' => 'membership-payments.add-payment-modal', 'uses' => 'GymMembershipPaymentsController@addPaymentModal']);
    Route::post('ajax-payment-store/{id}', ['as' => 'membership-payment.ajax-payment-store', 'uses' => 'GymMembershipPaymentsController@ajaxPaymentStore']);
    Route::get('membership-payment/user-create/{clientId}/{membershipId}', ['as' => 'membership-payment.user-create', 'uses' => 'GymMembershipPaymentsController@userPayCreate']);

    Route::resource('membership-payment', 'GymMembershipPaymentsController');

    // Invoice
    Route::get('create-invoice', ['uses' => 'GymInvoiceController@createInvoice', 'as' => 'gym-invoice.create-invoice']);
    Route::post('save-invoice', ['uses' => 'GymInvoiceController@saveInvoice', 'as' => 'gym-invoice.save-invoice']);
    Route::get('generate-invoice/{id}', ['uses' => 'GymInvoiceController@generateInvoice', 'as' => 'gym-invoice.generate-invoice']);
    Route::get('create-payment-invoice/{id}', ['uses' => 'GymInvoiceController@createPaymentInvoice', 'as' => 'gym-invoice.create-payment-invoice']);
    Route::get('generate-payment-invoice/{id}', ['uses' => 'GymInvoiceController@generatePaymentInvoice', 'as' => 'gym-invoice.generate-payment-invoice']);
    Route::get('download-invoice/{id}', ['uses' => 'GymInvoiceController@downloadInvoice', 'as' => 'gym-invoice.download-invoice']);
    Route::post('email-invoice', ['uses' => 'GymInvoiceController@emailInvoice', 'as' => 'gym-invoice.email-invoice']);
    Route::post('update-gstin', ['uses' => 'GymInvoiceController@updateGstNumber', 'as' => 'gym-invoice.update-gstin']);
    Route::get('gym-invoice/create/{type?}', ['uses' => 'GymInvoiceController@create', 'as' => 'gym-invoice.create']);
    Route::resource('gym-invoice', 'GymInvoiceController')->only(['index', 'destroy']);

    //membership payment invoice
    Route::get('gym-membership-invoice', ['uses' => 'GymInvoiceController@membershipIndex', 'as' => 'gym-invoice.membershipIndex']);

    //product payment invoice
    Route::get('gym-product-invoice', ['uses' => 'GymInvoiceController@productIndex', 'as' => 'gym-invoice.productIndex']);
    Route::get('create-product-payment-invoice/{id}', ['uses' => 'GymInvoiceController@createProductPaymentInvoice', 'as' => 'gym-invoice.create-product-payment-invoice']);
    Route::post('save-product-invoice', ['uses' => 'GymInvoiceController@saveProductInvoice', 'as' => 'gym-invoice.save-product-invoice']);

    //locker payment invoice
    Route::get('gym-locker-invoice', ['uses' => 'GymInvoiceController@lockerIndex', 'as' => 'gym-invoice.lockerIndex']);
    Route::get('create-locker-payment-invoice/{id}', ['uses' => 'GymInvoiceController@createLockerPaymentInvoice', 'as' => 'gym-invoice.create-locker-payment-invoice']);

    Route::get('target/ajax-create', ['uses' => 'GymTargetManageController@ajax_create', 'as' => 'target.ajax-create']);
    Route::resource('target', 'GymTargetManageController');

    Route::get('target-report/ajax-create/{ID}/{type}', ['uses' => 'GymTargetReportController@ajax_create', 'as' => 'target-report.ajax-create']);
    Route::resource('target-report', 'GymTargetReportController', ['only' => ['index', 'store']]);
    Route::get('target-report/download/{id}/{type}', 'GymTargetReportController@downloadTargetData')->name('downloadTargetData');
    Route::get('target-report/download/excel/{id}/{type}', 'GymTargetReportController@downloadExcelTargetData')->name('downloadExcelTargetData');

    // GymClientReportController
    Route::get('client-report/ajax-create/{ID}/{SD}/{ED}', ['uses' => 'GymClientReportController@ajax_create', 'as' => 'client-report.ajax-create']);
    Route::resource('client-report', 'GymClientReportController', ['only' => ['index', 'store', 'show']]);
    Route::get('client-report/download/{id}/{sd}/{ed}', 'GymClientReportController@downloadClientReport');
    Route::get('client-report/download/excel/{id}/{sd}/{ed}', 'GymClientReportController@downloadExcelClientReport');

    // GymBookingReportController
    Route::get('booking-report/ajax-create/{ID}/{SD}/{ED}/{membership}', ['uses' => 'GymBookingReportsController@ajax_create', 'as' => 'booking-report.ajax-create']);
    Route::resource('booking-report', 'GymBookingReportsController', ['only' => ['index', 'store']]);
    Route::get('booking-report/download/{id}/{sd}/{ed}/{membership}', 'GymBookingReportsController@downloadBookingReport');
    Route::get('booking-report/download/excel/{id}/{sd}/{ed}/{membership}', 'GymBookingReportsController@downloadExcelBookingReport');

    // LockerReservationReportsController
    Route::get('reservation-report/ajax-create/{SD}/{ED}', ['uses' => 'LockerReservationReportsController@ajax_create', 'as' => 'reservation-report.ajax-create']);
    Route::resource('reservation-report', 'LockerReservationReportsController', ['only' => ['index', 'store']]);
    Route::get('reservation-report/download/{sd}/{ed}', 'LockerReservationReportsController@downloadReservationReport');
    Route::get('reservation-report/download/excel/{sd}/{ed}', 'LockerReservationReportsController@downloadExcelReservationReport');

    // GymFinanceReportController
    Route::get('finance-report/ajax-create/{ID}/{SD}/{ED}/{paymentType}', ['uses' => 'GymFinanceReportController@ajax_create', 'as' => 'finance-report.ajax-create']);
    Route::resource('finance-report', 'GymFinanceReportController', ['only' => ['index', 'store']]);
    Route::get('finance-report/download/{id}/{sd}/{ed}/{paymentType}', 'GymFinanceReportController@downloadFinanceReport');
    Route::get('finance-report/download/excel/{id}/{sd}/{ed}/{paymentType}', 'GymFinanceReportController@downloadExcelFinanceReport');
    // GymAttendanceReportController
    Route::get('attendance-report/ajax-create/{ID}/{SD}/{ED}', ['uses' => 'GymAttendanceReportController@ajax_create', 'as' => 'attendance-report.ajax-create']);
    Route::get('attendance-report/ajax-create/attendance/{ID}/{SD}/{ED}/{ST}/{ET}', ['uses' => 'GymAttendanceReportController@ajax_create_attendance', 'as' => 'attendance-report.ajax-create-attendance']);
    Route::resource('attendance-report', 'GymAttendanceReportController', ['only' => ['index', 'store']]);
    Route::get('attendance-report/download/{id}/{sd}/{ed}', 'GymAttendanceReportController@downloadAttendanceReport');
    Route::get('attendance-report/download/excel/{id}/{sd}/{ed}', 'GymAttendanceReportController@downloadExcelAttendanceReport');

    // GymClientPurchaseController
    Route::get('client-dues', ['uses' => 'GymClientPurchaseController@clientDues', 'as' => 'client-purchase.client-dues']);
    Route::get('client-purchase/ajax-dues', ['uses' => 'GymClientPurchaseController@ajaxDues', 'as' => 'client-purchase.ajax-dues']);
    Route::get('client-purchase/show-model/{id}', ['uses' => 'GymClientPurchaseController@showModel', 'as' => 'client-purchase.show-model']);
    Route::post('client-purchase/sendReminder', ['uses' => 'GymClientPurchaseController@sendReminder', 'as' => 'client-purchase.sendReminder']);
    Route::get('reminder-history', ['uses' => 'GymClientPurchaseController@reminderHistory', 'as' => 'client-purchase.reminder-history']);
    Route::get('client-purchase/ajax-reminder-history', ['uses' => 'GymClientPurchaseController@ajaxReminderHistory', 'as' => 'client-purchase.ajax-reminder-history']);
    Route::post('client-purchase/sendRenewReminder', ['uses' => 'GymClientPurchaseController@sendRenewReminder', 'as' => 'client-purchase.sendRenewReminder']);

    Route::get('client-purchase/ajax-create/{type?}', ['uses' => 'GymClientPurchaseController@ajax_create', 'as' => 'client-purchase.ajax-create']);
    Route::get('client-purchase/ajax-create-deleted', ['uses' => 'GymClientPurchaseController@ajax_create_deleted', 'as' => 'client-purchase.ajax-create-deleted']);

    //renew subscription
    Route::get('renew-subscription', ['uses' => 'GymClientPurchaseController@renewList', 'as' => 'client-purchase.renew']);
    Route::get('renew-subscription-modal/{id}', ['uses' => 'GymClientPurchaseController@renewSubscriptionModal', 'as' => 'client-purchase.renew-subscription-modal']);
    Route::post('renew-subscription-store/{id}', ['uses' => 'GymClientPurchaseController@renewSubscriptionStore', 'as' => 'client-purchase.renew-subscription-store']);
    Route::get('client-purchase/user-create/{id}/{redeem_mem?}', ['uses' => 'GymClientPurchaseController@userCreate', 'as' => 'client-purchase.user-create']);
    Route::get('show-subscription-reminder-modal/{id}', ['uses' => 'GymClientPurchaseController@subscriptionReminderModal', 'as' => 'client-purchase.show-subscription-reminder-modal']);
    //pending subscription
    Route::get('client-purchase/pending-subscription', ['uses' => 'GymClientPurchaseController@pendingSubscription', 'as' => 'client-purchase.pending-subscription']);
    Route::get('client-purchase/ajax-pending-subscription', ['uses' => 'GymClientPurchaseController@ajaxPendingSubscription', 'as' => 'client-purchase.ajax-pending-subscription']);
    //deleted subscription
    Route::get('client-purchase/deleted-subscription', ['uses' => 'GymClientPurchaseController@deletedSubscription', 'as' => 'client-purchase.deleted-subscription']);
    Route::get('client-purchase/ajax-deleted-subscription', ['uses' => 'GymClientPurchaseController@ajaxDeletedSubscription', 'as' => 'client-purchase.ajax-deleted-subscription']);
    Route::get('client-purchase/restore/{id}', ['uses' => 'GymClientPurchaseController@restore', 'as' => 'client-purchase.restore']);
    Route::delete('client-purchase/delete/{id}', ['uses' => 'GymClientPurchaseController@delete', 'as' => 'client-purchase.permanent_delete']);
    //extend subscription
    Route::get('extend-subscription', ['uses' => 'GymClientPurchaseController@extendList', 'as' => 'client-purchase.extend']);
    Route::get('client-purchase/ajax-extend-subscription', ['uses' => 'GymClientPurchaseController@ajaxExtendSubscription', 'as' => 'client-purchase.ajax-extend-subscription']);
    Route::get('extend-subscription-modal/{id}', ['uses' => 'GymClientPurchaseController@extendSubscriptionModal', 'as' => 'client-purchase.extend-subscription-modal']);
    Route::post('extend-subscription-store/{id}', ['uses' => 'GymClientPurchaseController@extendSubscriptionStore', 'as' => 'client-purchase.extend-subscription-store']);
    //extend subscription
    Route::get('expire-subscription', ['uses' => 'GymClientPurchaseController@expireList', 'as' => 'client-purchase.expire']);

    Route::get('active-subscription', ['uses' => 'GymClientPurchaseController@activeList', 'as' => 'client-purchase.active']);
    Route::get('inactive-subscription', ['uses' => 'GymClientPurchaseController@inactiveList', 'as' => 'client-purchase.inactive']);

    Route::resource('client-purchase', "GymClientPurchaseController");
    //Freeze subscription
    Route::get('freeze-subscription', ['uses' => 'GymMembershipFreezeController@index', 'as' => 'client-purchase.freezeIndex']);
    Route::get('ajax-freeze-subscription', ['uses' => 'GymMembershipFreezeController@ajax_create', 'as' => 'client-purchase.ajax-freeze-subscription']);
    Route::get('freeze-subscription-modal/{id}', ['uses' => 'GymMembershipFreezeController@showModal', 'as' => 'client-purchase.freeze-subscription-modal']);
    Route::post('freeze-subscription-store/{id}', ['uses' => 'GymMembershipFreezeController@store', 'as' => 'client-purchase.freeze-subscription-store']);
    Route::post('freeze-subscription-update/{id}', ['uses' => 'GymMembershipFreezeController@update', 'as' => 'client-purchase.freeze-subscription-update']);


    //Locker Category
    Route::resource('locker-category', "LockerCategoryController")->except('show');
    //Locker Module
    Route::get('lockers/remove/modal/{uuid}', ['uses' => 'LockerController@removeLocker', 'as' => 'lockers.modal']);
    Route::get('lockers/ajax-create', ['uses' => 'LockerController@ajax_create', 'as' => 'lockers.ajax-create']);
    Route::resource('lockers', "LockerController")->except('show');

    //Locker Payments
    Route::get('reservation/remainingPayment/{id}', ['uses' => 'LockerPaymentController@remainingPayment', 'as' => 'reservation-payments.remainingPayment']);
    Route::get('reservation/purchase/{id}', ['uses' => 'LockerPaymentController@clientPurchases', 'as' => 'reservation-payments.clientPurchases']);
    Route::get('reservation/user/{id}', ['uses' => 'LockerPaymentController@clientPayment', 'as' => 'reservation-payments.clientPayment',]);
    Route::get('reservation-payments/ajax-create', ['uses' => 'LockerPaymentController@ajax_create', 'as' => 'reservation-payments.ajax-create']);
    Route::get('reservation-payments/ajax-create-deleted', ['uses' => 'LockerPaymentController@ajax_create_deleted', 'as' => 'reservation-payments.ajax-create-deleted']);
    Route::get('reservation-payment/{reservationId}', ['uses' => 'LockerPaymentController@userPayCreate', 'as' => 'reservation-payments.user-create',]);
    Route::get('reservation-payments/modal/{id}', ['uses' => 'LockerPaymentController@addPaymentModal', 'as' => 'reservation-payments.add-payment-model']);
    Route::post('reservation-payments-store/{id}', ['uses' => 'LockerPaymentController@ajaxPaymentStore', 'as' => 'reservation-payments.ajax-payment-store']);
    Route::resource('reservation-payments', 'LockerPaymentController')->except('show');

    //Locker Due
    Route::get('locker-dues', ['uses' => 'LockerReservationController@dueIndex', 'as' => 'reservations.dues']);
    Route::get('locker-dues/ajax_create', ['uses' => 'LockerReservationController@due_ajax_create', 'as' => 'reservations.dues-ajax-create']);
    Route::get('locker-dues/get-locker-category/{id}', ['uses' => 'LockerReservationController@getLockerCategory', 'as' => 'reservations.getLockerCategory']);

    //Locker Reservations
    //pending
    Route::get('pending-reservations', ['uses' => 'LockerReservationController@pendingReservation', 'as' => 'reservations.pending']);
    Route::get('pending-reservations/ajax-data', ['uses' => 'LockerReservationController@ajaxPendingReservation', 'as' => 'reservations.ajax-pending-data']);
    //deleted
    Route::get('deleted-reservations', ['uses' => 'LockerReservationController@deletedReservation', 'as' => 'reservations.deleted']);
    Route::get('deleted-reservations/ajax-data', ['uses' => 'LockerReservationController@ajaxDeletedReservation', 'as' => 'reservations.ajax-deleted-data']);
    Route::get('deleted-reservations/restore/{id}', ['uses' => 'LockerReservationController@restore', 'as' => 'reservations.restore']);
    Route::delete('locker-reservations/delete/{id}', ['uses' => 'LockerReservationController@delete', 'as' => 'reservations.permanent_delete']);
    Route::get('locker-reservations/user-create/{id}', ['uses' => 'LockerReservationController@userCreate', 'as' => 'reservations.user-create']);
    //locker reminder
    Route::get('show-locker-reminder-modal/{id}', ['uses' => 'LockerReservationController@lockerReminderModal', 'as' => 'reservations.show-locker-reminder-modal']);
    Route::post('locker-reservations/send-reminder', ['uses' => 'LockerReservationController@sendReminder', 'as' => 'reservations.sendReminder']);

    //renew reservation
    Route::get('renew-reservation-modal/{id}', ['uses' => 'LockerReservationController@renewReservationModal', 'as' => 'reservations.renew-reservation-modal']);
    Route::post('renew-reservation-store/{id}', ['uses' => 'LockerReservationController@renewReservationStore', 'as' => 'reservations.renew-reservation-store']);

    Route::get('locker-reservations/ajax-create', ['uses' => 'LockerReservationController@ajax_create', 'as' => 'reservations.ajax-create']);
    Route::post('locker-reservations/amount', ['uses' => 'LockerReservationController@getAmount', 'as' => 'reservations.get-amount']);
    Route::resource('locker-reservations', "LockerReservationController")->except('show')->names('reservations');

    // Bank Report
    Route::get('bank-report/ajax-create/{ID}/{SD}/{ED}', ['uses' => 'BankReportController@ajax_create', 'as' => 'bank-report.ajax-create']);
    Route::resource('bank-report', 'BankReportController', ['only' => ['index', 'store']]);
    Route::get('bank-report/download/{sd}/{ed}', 'BankReportController@downloadBankReport');
    Route::get('bank-report/download/excel/{sd}/{ed}', 'BankReportController@downloadExcelBankReport');

    // GymEnquiryReportController
    Route::get('enquiry-report/ajax-create/{ID}/{SD}/{ED}/{SOURCE}', ['uses' => 'GymEnquiryReportController@ajax_create', 'as' => 'enquiry-report.ajax-create']);
    Route::resource('enquiry-report', 'GymEnquiryReportController', ['only' => ['index', 'store']]);
    Route::get('enquiry-report/download/{sd}/{ed}/{source}', 'GymEnquiryReportController@downloadEnquiryReport');
    Route::get('enquiry-report/download/excel/{sd}/{ed}/{source}', 'GymEnquiryReportController@downloadExcelEnquiryReport');

    // BranchRenew ReportController
    Route::get('branch-renew-report/ajax-create/{SD}/{ED}', ['uses' => 'BranchRenewReportController@ajax_create', 'as' => 'branchRenew-report.ajax-create']);
    Route::resource('branch-renew-report', 'BranchRenewReportController', ['only' => ['index', 'store']]);
    Route::get('branch-renew-report/download/{sd}/{ed}', 'BranchRenewReportController@downloadBranchReport');
    Route::get('branch-renew-report/download/excel/{sd}/{ed}', 'BranchRenewReportController@downloadExcelBranchReport');


    // Balance Report
    Route::get('balance-report/ajax-create/{ID}/{SD}/{ED}', ['uses' => 'GymBalanceReportController@ajax_create', 'as' => 'balance-report.ajax-create']);
    Route::get('balance-report/ajax-create-mem/{ID}/{SD}/{ED}', ['uses' => 'GymBalanceReportController@ajax_create_mem', 'as' => 'balance-report.ajax-create-mem']);
    Route::get('balance-report/ajax-create-locker/{ID}/{SD}/{ED}', ['uses' => 'GymBalanceReportController@ajax_create_locker', 'as' => 'balance-report.ajax-create-locker']);
    Route::get('balance-report/ajax-create-product/{ID}/{SD}/{ED}', ['uses' => 'GymBalanceReportController@ajax_create_product', 'as' => 'balance-report.ajax-create-product']);
    Route::get('balance-report/ajax-create-income/{ID}/{SD}/{ED}', ['uses' => 'GymBalanceReportController@ajax_create_income', 'as' => 'balance-report.ajax-create-income']);
    // Route::get('balance-report/ajax-create-payroll/{ID}/{SD}/{ED}', ['uses' => 'GymBalanceReportController@ajax_create_payroll', 'as' => 'balance-report.ajax-create-payroll']);
    Route::resource('balance-report', 'GymBalanceReportController', ['only' => ['index', 'store']]);

    // Profit-loss Report
    Route::get('profit-loss-report/{date?}', ['uses' => 'ProfitLossReportController@index', 'as' => 'profit-loss-report.index']);

    //locker balance
    Route::get('locker-balance-report/download/{sd}/{ed}', 'GymBalanceReportController@downloadLockerBalanceReport');
    Route::get('locker-balance-report/download/excel/{sd}/{ed}', 'GymBalanceReportController@downloadExcelLockerBalanceReport');

    //membership balance
    Route::get('mem-balance-report/download/{sd}/{ed}', 'GymBalanceReportController@downloadMembershipBalanceReport');
    Route::get('mem-balance-report/download/excel/{sd}/{ed}', 'GymBalanceReportController@downloadExcelMembershipBalanceReport');
    //product balance
    Route::get('product-balance-report/download/{sd}/{ed}', 'GymBalanceReportController@downloadProductBalanceReport');
    Route::get('product-balance-report/download/excel/{sd}/{ed}', 'GymBalanceReportController@downloadExcelProductBalanceReport');
    //expense balance
    Route::get('expense-balance-report/download/{sd}/{ed}', 'GymBalanceReportController@downloadExpenseBalanceReport');
    Route::get('expense-balance-report/download/excel/{sd}/{ed}', 'GymBalanceReportController@downloadExcelExpenseBalanceReport');

    //income balance
    Route::get('income-balance-report/download/{sd}/{ed}', 'GymBalanceReportController@downloadIncomeBalanceReport');
    Route::get('income-balance-report/download/excel/{sd}/{ed}', 'GymBalanceReportController@downloadExcelIncomeBalanceReport');

    //payroll balance
    // Route::get('payroll-balance-report/download/{sd}/{ed}', 'GymBalanceReportController@downloadPayrollBalanceReport');
    // Route::get('payroll-balance-report/download/excel/{sd}/{ed}', 'GymBalanceReportController@downloadExcelPayrollBalanceReport');

    // ProductSellReportController
    Route::get('product-sale-report/ajax-create/{ID}/{SD}/{ED}', ['uses' => 'ProductSellReportController@ajax_create', 'as' => 'product-sale-report.ajax-create']);
    Route::resource('product-sale-report', 'ProductSellReportController', ['only' => ['index', 'store']]);
    Route::get('product-sale-report/download/{sd}/{ed}', 'ProductSellReportController@downloadProductSaleReport');
    Route::get('product-sale-report/download/excel/{sd}/{ed}', 'ProductSellReportController@downloadExcelProductSaleReport');

    // Attendance resource
    Route::get('attendance/ajax/create', ['uses' => 'GymAdminAttendanceController@ajax_create', 'as' => 'attendance.ajax_create']);
    Route::get('attendance/ajax/create/{Id}', ['uses' => 'GymAdminAttendanceController@checkin', 'as' => 'attendance.checkin']);
    Route::get('attendance/ajax/create/check-out/{Id}', ['uses' => 'GymAdminAttendanceController@checkout', 'as' => 'attendance.checkout']);
    Route::post('attendance/markAttendance', ['uses' => 'GymAdminAttendanceController@markAttendance', 'as' => 'attendance.markAttendance']);
    Route::post('attendance/markCheckout', ['uses' => 'GymAdminAttendanceController@markCheckout', 'as' => 'attendance.markCheckout']);
    Route::resource('attendance', 'GymAdminAttendanceController');

    Route::post('import', 'GymAdminAttendanceController@import')->name('import');

    Route::get('my-gym/remove/image/{ID}', ['uses' => 'MyGymController@destroyImage', 'as' => 'my-admin.remove.image']);
    Route::get('my-gym/set-main/image/{ID}', ['uses' => 'MyGymController@setMain', 'as' => 'my-admin.set-main.image']);
    Route::resource('my-gym', 'MyGymController');

    //Mobile App
    Route::post('mobile-app/update', ['as' => 'mobile-app.update', 'uses' => 'MobileAppController@update']);
    Route::post('mobile-app/image-store', ['as' => 'mobile-app.imagesStore', 'uses' => 'MobileAppController@imagesStore']);

    Route::post('mobile-app/uploadimage', ['as' => 'mobile-app.uploadimage', 'uses' => 'MobileAppController@uploadImage']);
    Route::get('mobile-app/ajax/create', ['uses' => 'MobileAppController@ajaxCreate', 'as' => 'mobile-app.create.ajax']);
    Route::resource('mobile-app', 'MobileAppController')->except(['update']);

    // Merchant Promotional Db
    Route::get('promotion-db/ajax-create', ['uses' => 'GymPromotionalDbController@ajax_create', 'as' => 'promotion-db.ajax-create']);
    Route::resource('promotion-db', 'GymPromotionalDbController');

    // Account setup
    Route::get('account-setup/profile', ['uses' => 'GymAccountSetupController@profile', 'as' => 'account-setup.profile']);
    Route::post('account-setup/profile', ['uses' => 'GymAccountSetupController@profileStore', 'as' => 'account-setup.profileStore']);

    Route::get('account-setup/membership', ['uses' => 'GymAccountSetupController@membership', 'as' => 'account-setup.membership']);
    Route::post('account-setup/membership', ['uses' => 'GymAccountSetupController@membershipStore', 'as' => 'account-setup.membershipStore']);

    Route::get('account-setup/client', ['uses' => 'GymAccountSetupController@client', 'as' => 'account-setup.client']);
    Route::post('account-setup/client', ['uses' => 'GymAccountSetupController@clientStore', 'as' => 'account-setup.clientStore']);

    Route::get('account-setup/subscription', ['uses' => 'GymAccountSetupController@subscription', 'as' => 'account-setup.subscription']);
    Route::post('account-setup/subscription', ['uses' => 'GymAccountSetupController@subscriptionStore', 'as' => 'account-setup.subscriptionStore']);

    Route::get('account-setup/payment', ['uses' => 'GymAccountSetupController@payment', 'as' => 'account-setup.payment']);
    Route::post('account-setup/payment', ['uses' => 'GymAccountSetupController@paymentStore', 'as' => 'account-setup.paymentStore']);
    Route::get('account-setup/complete', ['uses' => 'GymAccountSetupController@complete', 'as' => 'account-setup.complete']);

    // Accept terms & conditions
    Route::post('gym-admin/accept-terms', ['uses' => 'AdminGymDashboardController@acceptTerms', 'as' => 'accept-terms']);

    // Manage Users
    Route::get('users/ajax-create', ['uses' => 'GymAdminManageUsersController@ajaxCreate', 'as' => 'users.ajax-create']);
    Route::get('users/assign-role-modal/{id}', ['uses' => 'GymAdminManageUsersController@assignRoleModal', 'as' => 'users.assign-role-modal']);
    Route::post('users/assign-role-store/{id}', ['uses' => 'GymAdminManageUsersController@assignRoleStore', 'as' => 'users.assign-role-store']);
    Route::resource('users', 'GymAdminManageUsersController');

    Route::get('employee/create', 'EmployManagementController@createEmloyee')->name('users.createEmployee');
    Route::get('employee', 'EmployManagementController@showEmployee')->name('users.showEmployee');
    Route::post('employee/assignPermissionStoreEmploy/{id}', 'EmployManagementController@employStore')->name('assignPermissionEmploy');
    Route::post('employee/store', 'EmployManagementController@storeEmployee')->name('users.storeEmployee');
    Route::get('employee/{id}/edit', 'EmployManagementController@editEmployee')->name('users.editEmployee');
    Route::post('employee/{id}/update', 'EmployManagementController@updateEmployee')->name('users.updateEmployee');

    Route::delete('employee/{id}/delete', 'EmployManagementController@deleteEmployee')->name('users.deleteEmployee');
    Route::post('employee/{id}/resync', 'EmployManagementController@resyncEmployee')->name('users.resyncEmployee');

    // Employ Leave
    Route::get('employ-leave', 'EmployLeaveController@index')->name('employ.showLeave');
    Route::post('employ-leave/create', 'EmployLeaveController@store')->name('employ.createLeave');
    Route::post('employ-leave/{id}/edit', 'EmployLeaveController@update')->name('employ.editLeave');
    Route::delete('employ-leave/{id}/delete', 'EmployLeaveController@destroy')->name('employ.deleteLeave');

    //Employ Leave Type
    Route::get('leaveType', 'EmployManagementController@leaveType')->name('employ.leaveType');
    Route::post('leaveType/create', 'EmployManagementController@createLeaveType')->name('employ.create.leaveType');
    Route::post('leaveType/edit/{id}', 'EmployManagementController@editLeaveType')->name('employ.edit.leaveType');
    Route::delete('leaveType/delete/{id}', 'EmployManagementController@deleteLeaveType')->name('employ.delete.leaveType');

    //Employ Task Management Routes
    Route::get('employTask', ['as' => 'employTask.loadmoretask', 'uses' => 'EmployTaskManagementController@loadMoreTask']);
    Route::resource('employTaskList', 'EmployTaskManagementController', ['except' => 'create', 'show']);

    // Employ Attendance Management
    Route::get('employ/attendance/ajax/create', ['uses' => 'EmployAttendanceManagementController@ajax_create', 'as' => 'employ.attendance.ajax_create']);
    Route::get('employ/attendance/ajax/create/{Id}', ['uses' => 'EmployAttendanceManagementController@checkin', 'as' => 'employ.attendance.checkin']);
    Route::get('employ/attendance/ajax/create/check-out/{Id}', ['uses' => 'EmployAttendanceManagementController@checkout', 'as' => 'employ.attendance.checkout']);
    Route::post('employ/attendance/markAttendance', ['uses' => 'EmployAttendanceManagementController@markAttendance', 'as' => 'employ.attendance.markAttendance']);
    Route::post('employ/attendance/markCheckout', ['uses' => 'EmployAttendanceManagementController@markCheckout', 'as' => 'employ.attendance.markCheckout']);
    Route::resource('employAttendance', 'EmployAttendanceManagementController');

    // Employ Payroll Management
    Route::resource('employPayroll', 'EmployPayrollController');
    Route::post('employPayroll/addAllowanceDeduction/{id}', 'EmployPayrollController@add')->name('employPayroll.add');

    // Asset Management
    Route::resource('asset-management', 'AssetManagementController');
    Route::post('asset-management/edit/{id}', 'AssetManagementController@edit')->name('assetManagement.edit');
    Route::delete('asset-management/delete/{id}', 'AssetManagementController@delete')->name('assetManagement.delete');
    // Employ Asset
    Route::post('asset-management/employ', 'AssetManagementController@assignUser')->name('assetManagement.assignUser');
    Route::post('asset-management/employ/edit/{id}', 'AssetManagementController@editAssetUsage')->name('assetManagement.editAssetUsage');
    Route::delete('asset-management/employ/delete/{id}', 'AssetManagementController@deleteAssetUsage')->name('assetManagement.deleteAssetUsage');
    // Asset Services
    Route::post('asset-management/services', 'AssetManagementController@assetServicesStore')->name('assetManagement.assetServicesStore');
    Route::post('asset-management/services/edit/{id}', 'AssetManagementController@assetServicesUpdate')->name('assetManagement.assetServicesUpdate');
    Route::delete('asset-management/services/delete/{id}', 'AssetManagementController@assetServicesDelete')->name('assetManagement.assetServicesDelete');

    // Product Management
    //product sales
    Route::get('product-sell', 'ProductController@saleIndex')->name('sales.index');
    Route::get('product-sell/create', 'ProductController@saleCreate')->name('sales.create');
    Route::get('product-sell/edit/{id}', 'ProductController@saleEdit')->name('sales.edit');
    Route::post('product-sell/update/{id}', 'ProductController@saleUpdate')->name('sales.update');
    Route::delete('product-sell/destroy/{id}', 'ProductController@saleDelete')->name('sales.destroy');
    Route::get('product-sell/download/{id}', 'ProductController@saleDownload')->name('sales.download');
    Route::post('product-sell/store', 'ProductController@saleStore')->name('sales.store');

    //product quantity
    Route::get('products/{id}/quantity', 'ProductController@quantity')->name('products.quantity');
    Route::post('products/{id}/quantity-update', 'ProductController@updateQuantity')->name('products.update-quantity');

    //product
    Route::resource('products', 'ProductController');

    //due product
    Route::get('product-dues', ['uses' => 'ProductController@productDues', 'as' => 'products.product-dues']);
    Route::get('product-dues/ajax-create', ['uses' => 'ProductController@ajax_create', 'as' => 'product-dues.ajax-create']);

    Route::resource('product-payments', 'ProductPaymentController');
    Route::get('product-payment/ajax-create', ['uses' => 'ProductPaymentController@ajax_create', 'as' => 'product-payments.ajax-create']);
    Route::get('product-payment/{purchaseId}', ['uses' => 'ProductPaymentController@userPayCreate', 'as' => 'product-payments.user-create']);
    Route::get('add-product-payment-modal/{id}', ['as' => 'product-payments.add-payment-modal', 'uses' => 'ProductPaymentController@addPaymentModal']);
    Route::post('ajax-product-payment-store/{id}', ['as' => 'product-payments.ajax-payment-store', 'uses' => 'ProductPaymentController@ajaxPaymentStore']);
    Route::get('remainingProductPayment/{id}', ['as' => 'product-payments.remainingPayment', 'uses' => 'ProductPaymentController@remainingPayment']);
    Route::get('clientProductPurchases/{id}', ['as' => 'product-payments.clientProductPurchases', 'uses' => 'ProductPaymentController@clientProductPurchases']);
    Route::get('productEditPayment/{id}', ['as' => 'product-payments.productEditPayment', 'uses' => 'ProductPaymentController@productEditPayment']);
    Route::get('deleted-product-payments/ajax-create', ['as' => 'product-payments.ajax_create_deleted', 'uses' => 'ProductPaymentController@ajax_create_deleted',]);

    // Manage Users
    Route::get('roles/ajax-create', ['uses' => 'GymAdminManageRolesController@ajaxCreate', 'as' => 'gymmerchantroles.ajax-create']);
    Route::get('roles/assign-permission/{id}', ['uses' => 'GymAdminManageRolesController@assignPermission', 'as' => 'gymmerchantroles.assign-permission']);
    Route::post('roles/assign-permission-store/{id}', ['uses' => 'GymAdminManageRolesController@assignPermissionStore', 'as' => 'gymmerchantroles.assign-permission-store']);
    Route::post('gym-merchant-roles/update/{id}', 'GymAdminManageRolesController@update')->name('gymmerchantroles.updateData');
    Route::resource('gymmerchantroles', 'GymAdminManageRolesController');

    // Gym software Email promotion
    Route::get('email-promotion/preview-template/{id}', ['uses' => 'GymEmailPromotionController@previewTemplate', 'as' => 'email-promotion.preview-template']);
    Route::get('email-promotion/view-campaign/{id}', ['uses' => 'GymEmailPromotionController@viewCampaign', 'as' => 'email-promotion.view-campaign']);
    Route::get('email-promotion/edit-campaign/{id}', ['uses' => 'GymEmailPromotionController@editCampaign', 'as' => 'email-promotion.edit-campaign']);
    Route::get('email-promotion/ajax-promotions', ['uses' => 'GymEmailPromotionController@ajaxCreate', 'as' => 'email-promotion.ajax-promotions']);
    Route::post('email-promotion/send-promotion', ['uses' => 'GymEmailPromotionController@sendPromotion', 'as' => 'email-promotion.sendPromotion']);
    Route::resource('email-promotion', 'GymEmailPromotionController');

    //Sms Template
    Route::get('templates/ajax-create', ['uses' => 'TemplateController@ajax_create', 'as' => 'templates.ajax-create']);
    Route::resource('templates', 'TemplateController')->except(['show', 'destroy']);

    // Generate I-card
    Route::get('client-list/{filter}', ['uses' => 'GymIdentityCardController@clientList', 'as' => 'i-card.clientList']);
    Route::post('email-qr-code', ['uses' => 'GymIdentityCardController@emailQrCode', 'as' => 'i-card.email-qr-code']);
    Route::get('qr-check-in/{url}', ['uses' => 'GymQrCheckinController@qrCodeCheckIn', 'as' => 'gym-qr-check-in']);
    Route::resource('i-card', 'GymIdentityCardController');

    // Generate Barcode
    Route::get('bar-client-list/{filter}', ['uses' => 'BarCodeGeneratorController@clientList', 'as' => 'barcode.clientList']);
    Route::post('email-barcode', ['uses' => 'BarCodeGeneratorController@emailQrCode', 'as' => 'barcode.email-barcode']);
    Route::get('barcode-check-in/{url}', ['uses' => 'BarCodeGeneratorController@barCodeCodeCheckIn', 'as' => 'gym-barcode-check-in']);
    Route::resource('barcode', 'BarCodeGeneratorController');

    Route::get('tutorials/remove/modal/{uuid}', ['uses' => 'GymAdminTutorialController@remove', 'as' => 'tutorials.modal']);
    Route::get('tutorials/ajax-create', ['uses' => 'GymAdminTutorialController@ajax_create', 'as' => 'tutorials.ajax-create']);
    Route::resource('tutorials', 'GymAdminTutorialController');

    Route::get('notifications/remove/modal/{uuid}', ['uses' => 'PushNotificationController@remove', 'as' => 'notifications.modal']);
    Route::get('notifications/ajax-create', ['uses' => 'PushNotificationController@ajax_create', 'as' => 'notifications.ajax-create']);
    Route::resource('notifications', 'PushNotificationController');

    //GymExpense Management Controller
    Route::post('expense-category-store', ['uses' => 'GymAdminExpenseManagementController@addExpenseCategory', 'as' => 'addExpenseCategory']);
    Route::put('expense-category-update/{id}', ['uses' => 'GymAdminExpenseManagementController@updateExpenseCategory', 'as' => 'updateExpenseCategory']);

    Route::get('remove-expense-modal/{ID}', ['uses' => 'GymAdminExpenseManagementController@removeExpense', 'as' => 'remove-expense-modal']);
    Route::get('download-expense/{id}', ['uses' => 'GymAdminExpenseManagementController@downloadExpense', 'as' => 'expense.download']);
    Route::get('get-expense', ['uses' => 'GymAdminExpenseManagementController@getExpense', 'as' => 'expense.get-expense']);
    Route::resource('expense', 'GymAdminExpenseManagementController');

    // GymIncomeController
    Route::post('income-category-store', ['uses' => 'GymIncomeController@addIncomeCategory', 'as' => 'addIncomeCategory']);
    Route::put('income-category-update/{id}', ['uses' => 'GymIncomeController@updateIncomeCategory', 'as' => 'updateIncomeCategory']);

    Route::get('remove-income-modal/{ID}', ['uses' => 'GymIncomeController@remove', 'as' => 'remove-income-modal']);
    Route::get('download-income/{id}', ['uses' => 'GymIncomeController@download', 'as' => 'incomes.download']);
    Route::get('get-income', ['uses' => 'GymIncomeController@ajax_create', 'as' => 'incomes.ajax_create']);
    Route::resource('incomes', 'GymIncomeController');


    //Task Management Routes
    Route::get('task/load-moretask', ['as' => 'task.loadmoretask', 'uses' => 'GymAdminTaskManagementController@loadMoreTask']);
    Route::resource('task', 'GymAdminTaskManagementController', ['except' => 'create', 'show']);

    //Diet Plans
    Route::resource('/diet-plans', 'ManageDietPlansController');
    Route::post('/create-default-diet-plan', 'ManageDietPlansController@createDefaultDietPlan')->name('createDefaultDietPlan');
    Route::post('/update-default-diet-plan/{id}', 'ManageDietPlansController@updateDefaultDietPlan')->name('updateDefaultDietPlan');
    Route::post('/update-client-diet-plan/{id}', 'ManageDietPlansController@updateClientDietPlan')->name('updateClientDietPlan');
    Route::get('/download/{id}', 'ManageDietPlansController@exportDietPlan')->name('downloadDietPlan');
    Route::get('/downloadClientDietPlan/{id}', 'ManageDietPlansController@exportClientDietPlan')->name('downloadClientDietPlan');
    Route::delete('/delete-diet-plan/{id}', 'ManageDietPlansController@deleteDietPlan')->name('deleteDietPlan');
    //End Diet Plans Routes

    Route::resource('/training-plans', 'ManageTrainingController');
    Route::post('/create-default-training-plan', 'ManageTrainingController@createDefaultTrainingPlan')->name('createDefaultTrainingPlan');
    Route::post('/update-default-training-plan/{id}', 'ManageTrainingController@updateDefaultTrainingPlan')->name('updateDefaultTrainingPlan');
    Route::post('/update-client-training-plan/{id}', 'ManageTrainingController@updateClientTrainingPlan')->name('updateClientTrainingPlan');
    Route::get('/downloadTrainingPlan/{id}', 'ManageTrainingController@downloadClientTrainingPlan')->name('downloadClientTrainingPlan');
    Route::get('/download', 'ManageTrainingController@downloadDefaultTrainingPlan')->name('downloadDefaultTrainingPlan');
    Route::delete('/delete-training-plan/{id}', 'ManageTrainingController@deleteTrainingPlan')->name('deleteTrainingPlan');
    Route::delete('/delete-default-training-plan', 'ManageTrainingController@deleteDefaultTrainingPlan')->name('deleteDefaultTrainingPlan');

    //class schedule
    Route::resource('class-schedule', 'ClassScheduleController');

    //region Super Admin Routes
    Route::get('superadmin/get-data', ['as' => 'superadmin.getData', 'uses' => 'GymSuperAdminController@getData']);
    Route::get('superadmin/get-branch-sms-data', ['as' => 'superadmin.getBranchDataWithSMSCredit', 'uses' => 'GymSuperAdminController@getBranchDataWithSMSCredit']);
    Route::get('superadmin/branches-with-sms', ['as' => 'superadmin.branchWithSMSCreditList', 'uses' => 'GymSuperAdminController@branchWithSMSCreditList']);

    Route::get('superadmin/dashboard', ['as' => 'superadmin.dashboard', 'uses' => 'GymSuperAdminController@showDashboard']);
    Route::get('superadmin/branch/{id?}', ['as' => 'superadmin.branch', 'uses' => 'GymSuperAdminController@branchPage']);
    Route::post('superadmin/store-branch', ['as' => 'superadmin.storeBranchPage', 'uses' => 'GymSuperAdminController@storeBranchPage']);
    Route::get('superadmin/manager/{id?}', ['as' => 'superadmin.manager', 'uses' => 'GymSuperAdminController@managerPage']);
    Route::post('superadmin/store-manager', ['as' => 'superadmin.storeManagerPage', 'uses' => 'GymSuperAdminController@storeManagerPage']);
    Route::get('superadmin/role/{id?}', ['as' => 'superadmin.role', 'uses' => 'GymSuperAdminController@rolePage']);
    Route::post('superadmin/store-role', ['as' => 'superadmin.storeRolePage', 'uses' => 'GymSuperAdminController@storeRolePage']);
    Route::get('superadmin/permission/{id?}', ['as' => 'superadmin.permission', 'uses' => 'GymSuperAdminController@permissionPage']);
    Route::post('superadmin/store-permission', ['as' => 'superadmin.storePermissionPage', 'uses' => 'GymSuperAdminController@storePermissionPage']);
    Route::get('superadmin/complete', ['as' => 'superadmin.complete', 'uses' => 'GymSuperAdminController@completePage']);
    Route::post('superadmin/set-business-id/{id?}', ['as' => 'superadmin.setBusinessId', 'uses' => 'GymSuperAdminController@setBusinessId']);
    Route::post('superadmin/update-role-and-permission', ['as' => 'superadmin.updateRolesAndPermission', 'uses' => 'GymSuperAdminController@updateRolesAndPermission']);
    Route::get('superadmin/get-earning-chart-data', ['as' => 'superadmin.getEarningChartData', 'uses' => 'GymSuperAdminController@getEarningChartData']);
    Route::get('superadmin/get-client-chart-data', ['as' => 'superadmin.getClientChartData', 'uses' => 'GymSuperAdminController@getClientChartData']);

    Route::get('superadmin/{branch}/key-generate', ['as' => 'superadmin.branchKeyGenerate', 'uses' => 'GymSuperAdminController@branchKeyGenerate']);

    Route::get('superadmin/manage-branches', ['as' => 'superadmin.manage-branches', 'uses' => 'GymSuperAdminController@manageBranches']);
    //Branch Renew
    Route::get('superadmin/renew-model/{ID}', ['uses' => 'GymSuperAdminController@renewBranchModel', 'as' => 'superadmin.renewBranchModel']);
    Route::get('superadmin/renew-histroy/{ID}', ['uses' => 'GymSuperAdminController@renewHistory', 'as' => 'superadmin.renewHistory']);
    Route::post('superadmin/saveBranchRenew', ['uses' => 'GymSuperAdminController@saveBranchRenew', 'as' => 'superadmin.saveBranchRenew']);

    Route::resource('superadmin', 'GymSuperAdminController')->except('show');

    //endregion

    //region message routes
    Route::resource('message', 'GymAdminMessageController', ['except' => ['edit', 'destroy', 'show', 'update']]);
    Route::get('message/{userType}/{id}', 'GymAdminMessageController@show')->name('message.show');
    Route::put('message/{id}/{userType}', 'GymAdminMessageController@update')->name('message.update');
    Route::get('messages/{userType}', 'GymAdminMessageController@messageByUser')->name('messages.by-user');

    Route::resource('sms', 'GymAdminSMSController', ['except' => ['edit', 'destroy', 'show']]);
    Route::post('sms/resend-sms', 'GymAdminSMSController@resendSms')->name('sms.resend');
    Route::get('sms/customers', 'GymAdminSMSController@showCustomer')->name('sms.show-customer-smses');
    Route::delete('sms/customers/destroy/{id}', 'GymAdminSMSController@destroyCustomerSms')->name('sms.destroy-customer-sms');
    Route::get('sms/ajax_get_customer_smses', 'GymAdminSMSController@ajax_get_customer_smses')->name('sms.ajax-get-customer-smses');
    Route::post('sms/ajax-customer-sms', 'GymAdminSMSController@ajaxCustomerSms')->name('sms.ajax-customer-sms');
    Route::delete('sms/mass_remove_customer_sms', 'GymAdminSMSController@destoryMassCustomerSms')->name('customer_sms.massremove');


    Route::get('sms/employees', 'GymAdminSMSController@showEmployee')->name('sms.show-employee-smses');
    Route::delete('sms/employees/destroy/{id}', 'GymAdminSMSController@destroyEmployeeSms')->name('sms.destroy-employee-sms');
    Route::get('sms/ajax_get_employee_smses', 'GymAdminSMSController@ajax_get_employee_smses')->name('sms.ajax-get-employee-smses');
    Route::post('sms/ajax-employee-sms', 'GymAdminSMSController@ajaxEmployeeSms')->name('sms.ajax-employee-sms');
    Route::delete('sms/mass_remove_employee_sms', 'GymAdminSMSController@destoryMassEmployeeSms')->name('employee_sms.massremove');

    Route::get('sms/admins', 'GymAdminSMSController@showAdmin')->name('sms.show-admin-sms');
    Route::delete('sms/admins/destroy/{id}', 'GymAdminSMSController@destroyAdminSms')->name('sms.destroy-admin-sms');
    Route::get('sms/ajax_get_admin_smses', 'GymAdminSMSController@ajax_get_admin_smses')->name('sms.ajax-get-admin-smses');
    Route::post('sms/ajax-admin-sms', 'GymAdminSMSController@ajaxAdminSms')->name('sms.ajax-admin-sms');
    Route::delete('sms/mass_remove_admin_sms', 'GymAdminSMSController@destoryMassAdminSms')->name('admin_sms.massremove');

    Route::resource('emails', 'GymAdminEmailController', ['except' => ['edit', 'destroy', 'show']]);
    Route::get('emails/customers', 'GymAdminEmailController@showCustomer')->name('emails.show-customer-emails');
    Route::delete('emails/customers/destroy/{id}', 'GymAdminEmailController@destroyCustomerEmail')->name('emails.destroy-customer-email');
    Route::get('emails/ajax_get_customer_emails', 'GymAdminEmailController@ajax_get_customer_emails')->name('emails.ajax-get-customer-emails');
    Route::delete('emails/mass_remove_customer_emails', 'GymAdminEmailController@destoryMassCustomerEmail')->name('customer_email.massremove');

    Route::get('emails/employees', 'GymAdminEmailController@showEmployee')->name('emails.show-employee-emails');
    Route::delete('emails/employees/destroy/{id}', 'GymAdminEmailController@destroyEmployeeEmail')->name('emails.destroy-employee-email');
    Route::get('emails/ajax_get_employee_emails', 'GymAdminEmailController@ajax_get_employee_emails')->name('emails.ajax-get-employee-emails');
    Route::delete('emails/mass_remove_employee_emails', 'GymAdminEmailController@destoryMassEmployeeEmail')->name('employee_email.massremove');

    //endregion

    // Gym Feedback Controller
    Route::get('feedback/', ['uses' => 'GymFeedbackController@index', 'as' => 'feedback.index']);
    Route::post('feedback/load/comments', ['uses' => 'GymFeedbackController@ajaxLoadComments', 'as' => 'feedback.load.comments']);
    Route::post('feedback/more/comments', ['uses' => 'GymFeedbackController@loadMoreComments', 'as' => 'feedback.more.comments']);
    Route::post('feedback/reply', ['uses' => 'GymFeedbackController@postReply', 'as' => 'feedback.reply.store']);

    Route::get('feedback/remove/modal/{ID}', ['uses' => 'GymFeedbackController@removeReply', 'as' => 'remove.reply']);
    Route::get('feedback/remove/reply/{ID}', ['uses' => 'GymFeedbackController@destroy', 'as' => 'feedback.destroy']);
    Route::post('feedback/edit/reply', ['uses' => 'GymFeedbackController@edit', 'as' => 'feedback.edit']);

    //Fitness Calculation
    Route::get('fitness-measurement', ['uses' => 'BodyMeasurementController@fitnessCalculation', 'as' => 'fitnessCalculation']);
    Route::post('fitness-measurement/report', ['uses' => 'BodyMeasurementController@calculation', 'as' => 'calculation']);

    //Body Measurement
    Route::get('get-client-entry-date/{id}', ['uses' => 'BodyMeasurementController@getClientDate', 'as' => 'measurements.getClientDate']);
    Route::post('client-progress', ['uses' => 'BodyMeasurementController@clientProgressReport', 'as' => 'measurements.clientProgressReport']);
    Route::get('progress-tracker', ['uses' => 'BodyMeasurementController@progressIndex', 'as' => 'measurements.progressIndex']);

    Route::get('body-measurement/remove/modal/{uuid}', ['uses' => 'BodyMeasurementController@removeData', 'as' => 'measurements.modal']);
    Route::get('body-measurement/ajax-create', ['uses' => 'BodyMeasurementController@ajax_create', 'as' => 'measurements.ajax-create']);
    Route::resource('body-measurement', 'BodyMeasurementController')->names('measurements');

    //Expense Report
    Route::get('expenses-report', ['uses' => 'GymExpenseReportController@index', 'as' => 'reports.expense']);
    Route::post('expense-report/store', ['uses' => 'GymExpenseReportController@store', 'as' => 'reports.expense.store']);
    Route::get('expense-report/ajax-create/{SD}/{ED}/{CATEGORY}', ['uses' => 'GymExpenseReportController@ajax_create_expense', 'as' => 'reports.expense.ajax_create_expense']);
    Route::get('expense-report/download/{sd}/{ed}/{CATEGORY}', 'GymExpenseReportController@downloadReport');
    Route::get('expense-report/download/excel/{sd}/{ed}/{CATEGORY}', 'GymExpenseReportController@downloadExcelReport');

    //Extra Collection Report
    Route::get('collection-report', ['uses' => 'GymExtraIncomeReportController@index', 'as' => 'reports.income']);
    Route::post('collection-report/store', ['uses' => 'GymExtraIncomeReportController@store', 'as' => 'reports.income.store']);
    Route::get('collection-report/ajax-create/{SD}/{ED}/{CATEGORY}/{PAYMENT_SOURCE}', ['uses' => 'GymExtraIncomeReportController@ajax_create_income', 'as' => 'reports.income.ajax_create_income']);
    Route::get('collection-report/download/{sd}/{ed}/{CATEGORY}/{PAYMENT_SOURCE}', 'GymExtraIncomeReportController@downloadReport');
    Route::get('collection-report/download/excel/{sd}/{ed}/{CATEGORY}/{PAYMENT_SOURCE}', 'GymExtraIncomeReportController@downloadExcelReport');

    //FAQs
    Route::resource('faqs', 'FAQController')->except('show');

    //Redeem Points
    Route::post('redeems/update', ['uses' => 'RedeemPointController@update', 'as' => 'redeems.update']);
    Route::resource('redeems', 'RedeemPointController', ['except' => ['show', 'update']]);

});
