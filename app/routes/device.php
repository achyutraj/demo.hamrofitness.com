<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'gym-admin', 'middleware' => ['merchant.auth'], 'namespace' => 'Device', 'as' => 'device.'], function () {

    Route::get('device-management', 'DepartmentAndShiftController@index')->name('branchDepartment.index');

    Route::post('device/shift-store', 'DepartmentAndShiftController@shiftStore')->name('shifts.store');
    Route::post('device/shift-edit/{id}', 'DepartmentAndShiftController@shiftUpdate')->name('shifts.update');
    Route::delete('device/shift/{id}/delete', 'DepartmentAndShiftController@shiftDelete')->name('shifts.delete');

    Route::post('device/department-store', 'DepartmentAndShiftController@departmentStore')->name('departments.store');
    Route::post('device/department-edit/{id}', 'DepartmentAndShiftController@departmentUpdate')->name('departments.update');
    Route::delete('device/department/{id}/delete', 'DepartmentAndShiftController@departmentDelete')->name('departments.delete');

    Route::get('check-device-status', 'DeviceController@checkDeviceStatus')->name('info.checkDeviceStatus');

    Route::post('device-information-store', 'DeviceController@store')->name('info.store');
    Route::get('device-information/{id}', 'DeviceController@show')->name('info.show');
    Route::post('device-information-edit/{id}', 'DeviceController@update')->name('info.update');
    Route::delete('device-information/{id}/delete', 'DeviceController@destroy')->name('info.delete');
    Route::delete('device-information/clear-attendance/{id}/delete', 'DeviceController@clearDeviceAttendanceLogs')->name('info.clearDeviceAttendance');

    //client biometric
    Route::get('client-biometrics/ajax_create', 'DeviceGymClientController@ajax_create')->name('biometrics.ajax_create');
    Route::delete('client-biometrics/{clientId}/{deviceId}/delete', 'DeviceGymClientController@clientRemoveFromDevice')->name('biometrics.clientRemoveFromDevice');
    Route::delete('denied-client-biometrics/{clientId}/{deviceId}/delete', 'DeviceGymClientController@clientRemoveFromDeviceOnly')->name('biometrics.clientRemoveFromDeviceOnly');

    Route::get('client-biometrics/sync/{clientId?}', 'DeviceGymClientController@create')->name('biometrics.syncUser');
    Route::get('client-biometrics/add-card', 'DeviceGymClientController@addCardForm')->name('biometrics.addCardForm');
    Route::post('client-biometrics/add-card/store', 'DeviceGymClientController@createOrUpdateUserInfo')->name('biometrics.addUserInfo');

    Route::get('client-biometrics/renew/{clientId?}', 'DeviceGymClientController@renewUser')->name('biometrics.renewUser');
    Route::post('client-biometrics/renew/store', 'DeviceGymClientController@renewUserStore')->name('biometrics.renewUserStore');
    Route::resource('client-biometrics', 'DeviceGymClientController')
        ->only(['index','store','create'])->names('biometrics');


    //employee biometric
    Route::get('employee-biometrics/ajax_create', 'DeviceEmployeeController@ajax_create')->name('employee_biometrics.ajax_create');

    Route::resource('employee-biometrics', 'DeviceEmployeeController')
    ->only(['index','store','create'])->names('employee_biometrics');

    //ADMS API
    // Route::get('check-device-status/{device_code}', 'ADMSController@checkDeviceStatus')->name('checkDeviceStatus');
    // Route::get('get-attendance-data/{from}/{to}/{serial_num}', 'ADMSController@GetAllAttendanceData')->name('GetAllAttendanceData');

    //ADMS Real-time Sync Routes
    Route::get('adms/logs', ['uses' => 'ADMSController@index', 'as' => 'adms.logs']);
    Route::post('adms/sync-realtime', ['uses' => 'ADMSController@syncRealtimeData', 'as' => 'adms.sync-realtime']);
    Route::post('adms/process-daily-sync', ['uses' => 'ADMSController@processDailySync', 'as' => 'adms.process-daily-sync']);
    Route::get('adms/sync-stats', ['uses' => 'ADMSController@getSyncStats', 'as' => 'adms.sync-stats']);
    Route::get('adms/log-details', ['uses' => 'ADMSController@getLogDetails', 'as' => 'adms.log-details']);
    Route::get('adms/test-modal', ['uses' => 'ADMSController@testModal', 'as' => 'adms.test-modal']);

});
