<?php

namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use App\Helpers\ADMSHelper;
use App\Models\Employ;
use App\Models\EmployLeave;
use App\Models\LeaveType;
use App\Models\MerchantBusiness;
use App\Models\Role;
use App\Models\Merchant;
use App\Models\GymClient;
use App\Models\BusinessCustomer;
use App\Models\Device;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\SyncLog;

class EmployManagementController extends GymAdminBaseController
{
    public function showEmployee()
    {
        if (!$this->data['user']->can("view_employs")) {
            return App::abort(401);
        }
        $this->data['role'] = Role::where('detail_id', $this->data['user']->detail_id)->get();
        $this->data['title'] = "Employee List";
        $this->data['employees'] = Employ::where('detail_id', '=', $this->data['user']->detail_id)->get();

        // Add sync status information for each employee
        foreach ($this->data['employees'] as $employee) {
            $gymClient = GymClient::where('username', $employee->username)
                ->where('is_client', 'no')
                ->first();

            if ($gymClient) {
                // Get all sync logs for this employee
                $syncLogs = SyncLog::where('client_id', $gymClient->id)->get();

                if ($syncLogs->count() > 0) {
                    // Check if all devices are synced successfully
                    $allSynced = $syncLogs->every(function ($log) {
                        return $log->synced === true;
                    });

                    $employee->sync_status = $allSynced ? true : false;
                    $employee->last_sync = $syncLogs->max('sync_on');
                } else {
                    $employee->sync_status = null;
                    $employee->last_sync = null;
                }
            } else {
                $employee->sync_status = null;
                $employee->last_sync = null;
            }
        }

        return view('gym-admin.employ.index', $this->data);
    }

    public function createEmloyee()
    {
        if (!$this->data['user']->can("add_employ")) {
            return App::abort(401);
        }
        $this->data['role'] = Role::where('detail_id', $this->data['user']->detail_id)->get();
        $this->data['title'] = 'Add Employee';
        return view('gym-admin.employ.create', $this->data);
    }

    public function storeEmployee(Request $request)
    {
        // Validate the request
        $validator = Validator::make(request()->all(), Merchant::$addUserRules);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return redirect()->back()->with('errors', $errors)->withInput();
        }

        // Create the Merchant
        $merchant = new Merchant();
        $merchant->first_name = $request->first_name;
        $merchant->middle_name = $request->middle_name;
        $merchant->last_name = $request->last_name;
        $merchant->detail_id = $this->data['merchantBusiness']->detail_id;
        $merchant->username = $request->username;
        $merchant->password = Hash::make($request->password);
        $merchant->email = $request->email;
        $merchant->date_of_birth = $request->date_of_birth;
        $merchant->gender = $request->gender;
        $merchant->position = $request->position;
        $merchant->mobile = $request->mobile;
        $merchant->user_type = 'employee';
        $merchant->save();

        // Create the Employ
        $employees = new Employ();
        // $employees->branch_id = $this->data['merchantBusiness']->detail_id;
        $employees->merchant_id = $merchant->id;
        $employees->first_name = $request->first_name;
        $employees->middle_name = $request->middle_name;
        $employees->last_name = $request->last_name;
        $employees->username = $request->username;
        $employees->password = Hash::make($request->password);
        $employees->email = $request->email;
        $employees->date_of_birth = $request->date_of_birth;
        $employees->gender = $request->gender;
        $employees->position = $request->position;
        $employees->mobile = $request->mobile;
        $employees->detail_id = $this->data['user']->detail_id;
        $employees->save();

        $merchant->assignRole($request->get('role'));

        // Create MerchantBusiness
        $merchants = Merchant::latest('created_at')->first();
        $insert = [
            "merchant_id" => $merchants->id,
            "detail_id" => $this->data['user']->detail_id
        ];
        MerchantBusiness::firstOrCreate($insert);

        // Create GymClient and sync data for biometric
        if ($this->data['common_details']->has_device == true) {
            $dob = $request->get('date_of_birth') ?? null;

            $gymClient = GymClient::create([
                'username' => $request->get('username'),
                'first_name' => $request->get('first_name'),
                'middle_name' => $request->get('middle_name'),
                'last_name' => $request->get('last_name'),
                'email' => $request->get('email'),
                'mobile' => $request->get('mobile'),
                'gender' => strtolower($request->get('gender')),
                'date_of_birth' => $dob,
                'is_client' => 'no',
            ]);

            $businessCustomer = new BusinessCustomer();
            $businessCustomer->detail_id = $this->data['merchantBusiness']->detail_id;
            $businessCustomer->customer_id = $gymClient->id;
            $businessCustomer->save();

            $devices = Device::where('device_status', 1)
                ->where('detail_id', $this->data['user']->detail_id)
                ->get();

            $shifts = Shift::where('detail_id', $this->data['user']->detail_id)->get();

            $gymClient->shifts()->sync($shifts);
            $gymClient->devices()->sync($devices);

            foreach ($devices as $device) {
                $userData = [
                    'userId' => $gymClient->id,
                    'name' => $gymClient->fullName,
                    'card' => null,
                    'category' => 0,
                ];
                $status = ADMSHelper::updateUserInfo($device->serial_num, $device->code, $userData);

                SyncLog::updateOrCreate(
                    [
                        'client_id' => $gymClient->id,
                        'device_id' => $device->id,
                    ],
                    [
                        'synced' => $status,
                        'sync_on' => now(),
                    ]
                );
            }
        }
        return redirect('/gym-admin/employee')->with('message', 'Employ Successfully Added');

    }

    public function editEmployee(Request $request, $id)
    {
        if (!$this->data['user']->can("edit_employ")) {
            return App::abort(401);
        }

        if (!$this->data['user']->can("add_employ")) {
            return App::abort(401);
        }
         $this->data['employ'] = Employ::findOrFail($id);
        $this->data['role'] = Role::where('detail_id', $this->data['user']->detail_id)->get();
        $this->data['title'] = 'Edit Employee';
        return view('gym-admin.employ.edit', $this->data);
    }


    public function updateEmployee(Request $request, $id)
    {
        if (!$this->data['user']->can("edit_employ")) {
            return App::abort(401);
        }

        $employees = Employ::findOrFail($id);
        $mer = Merchant::where('email', $employees->email)->select('id')->first();
        $employees->first_name = $request->first_name;
        $employees->middle_name = $request->middle_name;
        $employees->last_name = $request->last_name;
        $employees->username = $request->username;
        if ($request->password) {
            $employees->password = Hash::make($request->password);
        }
        $employees->email = $request->email;
        $employees->date_of_birth = $request->date_of_birth;
        $employees->gender = $request->gender;
        $employees->position = $request->position;
        $employees->mobile = $request->mobile;
        $employees->detail_id = $this->data['user']->detail_id;
        $employees->role = $request->role;
        $employees->merchant_id = $mer->id;
        $employees->save();

        $merchant = Merchant::findOrFail($mer->id);
        $merchant->first_name = $request->first_name;
        $merchant->middle_name = $request->middle_name;
        $merchant->last_name = $request->last_name;
        $merchant->username = $request->username;
        $merchant->email = $request->email;
        $merchant->date_of_birth = $request->date_of_birth;
        $merchant->gender = $request->gender;
        $merchant->position = $request->position;
        $merchant->mobile = $request->mobile;
        if (!is_null($request->password)) {
            $merchant->password = Hash::make($request->password);
        }
        $merchant->save();
        $merchant->assignRole($request->get('role'));
        return redirect('/gym-admin/employee')->with('message', 'Employ Updated Successfully');
    }

    public function deleteEmployee($id)
    {
        if (!$this->data['user']->can("delete_employ")) {
            return App::abort(401);
        }
        $emp = Employ::find($id);
        $mer = Merchant::where('email', $emp->email)->select('id')->first();
        if ($emp->payroll->count() > 0) {
            return Reply::error("Unable to remove. Employ has payroll.");
        }
        $emp->delete();
        $mer->delete();
        return Reply::success("Employ deleted successfully.");
    }

    //leave Type
    public function leaveType()
    {
        $this->data['title'] = "Leave Type";
        $this->data['leaveType'] = LeaveType::where('branch_id', '=', $this->data['user']->detail_id)->get();
        return view('gym-admin.employ.leaveType', $this->data);
    }

    public function createLeaveType(Request $request)
    {
        $leave = new LeaveType();
        $leave->name = $request->leaveType;
        $leave->branch_id = $this->data['user']->detail_id;
        $leave->days = $request->days;
        $leave->save();
        return redirect()->back()->with('message', 'leave type added successfully');
    }

    public function editLeaveType(Request $request, $id)
    {
        $leave = LeaveType::findOrFail($id);
        $leave->name = $request->leaveType;
        $leave->branch_id = $this->data['user']->detail_id;
        $leave->days = $request->days;
        $leave->save();
        return redirect()->back()->with('message', 'Leave Type Updated successfully');
    }

    public function deleteLeaveType($id)
    {
        $leave = LeaveType::find($id);
        $leave->delete();
        return Reply::success("Leave Type deleted successfully.");
    }

    public function resyncEmployee($id)
    {
        if (!$this->data['user']->can("edit_employ")) {
            return App::abort(401);
        }

        try {
            $employee = Employ::findOrFail($id);

            // Find the corresponding GymClient for this employee
            $gymClient = GymClient::where('username', $employee->username)
                ->where('is_client', 'no')
                ->first();

            if (!$gymClient) {
                $gymClient = GymClient::create([
                    'username' => $employee->username,
                    'first_name' => $employee->first_name,
                    'middle_name' => $employee->middle_name,
                    'last_name' => $employee->last_name,
                    'email' => $employee->email,
                    'mobile' => $employee->mobile,
                    'gender' => $employee->gender,
                    'date_of_birth' => $employee->date_of_birth,
                    'is_client' => 'no',
                ]);

                $businessCustomer = new BusinessCustomer();
                $businessCustomer->detail_id = $this->data['merchantBusiness']->detail_id;
                $businessCustomer->customer_id = $gymClient->id;
                $businessCustomer->save();
            }

            // Check if device sync is enabled
            if (!$this->data['common_details']->has_device) {
                return Reply::error("Biometric devices are not enabled for this business.");
            }

            $devices = Device::where('device_status', 1)
                ->where('detail_id', $this->data['user']->detail_id)
                ->get();

            if ($devices->count() == 0) {
                return Reply::error("No active biometric devices found for this business.");
            }

            $shifts = Shift::where('detail_id', $this->data['user']->detail_id)->get();

            // Sync shifts and devices
            $gymClient->shifts()->sync($shifts);
            $gymClient->devices()->sync($devices);

            $syncSuccess = true;
            $syncErrors = [];
            $syncCount = 0;

            foreach ($devices as $device) {
                $userData = [
                    'userId' => $gymClient->id,
                    'name' => $gymClient->fullName,
                    'card' => null,
                    'category' => 0,
                ];

                $status = ADMSHelper::updateUserInfo($device->serial_num, $device->code, $userData);

                // Update or create sync log
                SyncLog::updateOrCreate(
                    [
                        'client_id' => $gymClient->id,
                        'device_id' => $device->id,
                    ],
                    [
                        'synced' => $status,
                        'sync_on' => now(),
                    ]
                );

                if ($status) {
                    $syncCount++;
                } else {
                    $syncSuccess = false;
                    $syncErrors[] = "Failed to sync with device: {$device->name}";
                }
            }

            if ($syncSuccess) {
                return Reply::success("Employee successfully resynced with all biometric devices ({$syncCount} devices).");
            } else {
                $errorMessage = "Employee resynced with {$syncCount} devices successfully, but failed with: " . implode(', ', $syncErrors);
                return Reply::error($errorMessage);
            }

        } catch (\Exception $e) {
            \Log::error("Employee resync failed for ID {$id}: " . $e->getMessage());
            return Reply::error("Failed to resync employee: " . $e->getMessage());
        }
    }

    public function bulkResyncEmployees()
    {
        if (!$this->data['user']->can("edit_employ")) {
            return App::abort(401);
        }

        try {
            // Check if device sync is enabled
            if (!$this->data['common_details']->has_device) {
                return Reply::error("Biometric devices are not enabled for this business.");
            }

            $employees = Employ::where('detail_id', '=', $this->data['user']->detail_id)->get();
            $successCount = 0;
            $errorCount = 0;
            $errors = [];

            foreach ($employees as $employee) {
                try {
                    $gymClient = GymClient::where('username', $employee->username)
                        ->where('is_client', 'no')
                        ->first();

                    if (!$gymClient) {
                        $errorCount++;
                        $errors[] = "Employee {$employee->fullName}: Not found in biometric system";
                        continue;
                    }

                    // Check if employee needs resync (not synced or failed)
                    $syncLogs = SyncLog::where('client_id', $gymClient->id)->get();
                    $needsResync = $syncLogs->count() == 0 || $syncLogs->contains('synced', false);

                    if (!$needsResync) {
                        continue; // Skip if already synced successfully
                    }

                    $devices = Device::where('device_status', 1)
                        ->where('detail_id', $this->data['user']->detail_id)
                        ->get();

                    if ($devices->count() == 0) {
                        $errorCount++;
                        $errors[] = "Employee {$employee->fullName}: No active devices found";
                        continue;
                    }

                    $shifts = Shift::where('detail_id', $this->data['user']->detail_id)->get();

                    // Sync shifts and devices
                    $gymClient->shifts()->sync($shifts);
                    $gymClient->devices()->sync($devices);

                    $deviceSyncSuccess = true;

                    foreach ($devices as $device) {
                        $userData = [
                            'userId' => $gymClient->id,
                            'name' => $gymClient->fullName,
                            'card' => null,
                            'category' => 0,
                        ];

                        $status = ADMSHelper::updateUserInfo($device->serial_num, $device->code, $userData);

                        // Update or create sync log
                        SyncLog::updateOrCreate(
                            [
                                'client_id' => $gymClient->id,
                                'device_id' => $device->id,
                            ],
                            [
                                'synced' => $status,
                                'sync_on' => now(),
                            ]
                        );

                        if (!$status) {
                            $deviceSyncSuccess = false;
                        }
                    }

                    if ($deviceSyncSuccess) {
                        $successCount++;
                    } else {
                        $errorCount++;
                        $errors[] = "Employee {$employee->fullName}: Some devices failed to sync";
                    }

                } catch (\Exception $e) {
                    $errorCount++;
                    $errors[] = "Employee {$employee->fullName}: " . $e->getMessage();
                }
            }

            $message = "Bulk resync completed. Successfully synced: {$successCount}, Failed: {$errorCount}";

            if ($errorCount > 0) {
                $message .= ". Errors: " . implode('; ', array_slice($errors, 0, 5)); // Show first 5 errors
                if (count($errors) > 5) {
                    $message .= " and " . (count($errors) - 5) . " more errors";
                }
                return Reply::error($message);
            } else {
                return Reply::success($message);
            }

        } catch (\Exception $e) {
            \Log::error("Bulk employee resync failed: " . $e->getMessage());
            return Reply::error("Failed to perform bulk resync: " . $e->getMessage());
        }
    }
}
