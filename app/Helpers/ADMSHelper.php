<?php

namespace App\Helpers;

use App\Models\Device;
use App\Models\GymClientAttendance;
use App\Models\GymClient;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ADMSHelper{

    protected static $baseUrl;

    protected static function getBaseUrl()
    {
        if (is_null(self::$baseUrl)) {
            self::$baseUrl = env('ADMS_URL');
        }
        return self::$baseUrl;
    }

    public static function checkDeviceStatus($device_code)
    {
        try {
            $url = self::getBaseUrl() . "api/AdmsEx/GetDeviceByBranch?Code=" . $device_code; // Use static property
            $response = Http::timeout(15) // Increased timeout to 15 seconds
                ->retry(2, 1000) // Retry 2 times with 1 second delay
                ->withoutVerifying()
                ->acceptJson()
                ->get($url);

            $data = $response->json();
            $status = $response->status();
            if ($status == 500) {
                \Log::info('ADMS not working - DeviceCode: ' . $device_code);
                return false; // Return false when ADMS is not working
            } else {
                return response()->json([
                    'status' => $status,
                    'data' => $data['Data'],
                ], 200);
            }
        } catch (\Exception $e) {
            \Log::error('Request to check device status failed - DeviceCode: ' . $device_code . ' Error: ' . $e->getMessage());
            return false; // Return false if timeout or any other error occurs
        }
    }

    public static function updateUserInfo($serial_num, $device_code, $user_data)
    {
        $name = $user_data['name'];
        $userId = $user_data['userId'];
        $card = $user_data['card'];
        $category = $user_data['category']; // category as client status: 0 -> active, 6 -> block

        $url = self::getBaseUrl() .'api/AdmsEx/UpdateUser';
        $jsonBody = [
            'DeviceSn' => $serial_num,
            'UserPin' => $userId,
            'Name' => $name,
            'Card' => $card,
            'Category' => $category,
            'Privilege' => 0,
            'BranchCode' => $device_code,
        ];

        // Send the HTTP POST request with timeout (15 seconds)
        try {
            $response = Http::timeout(15) // Increased timeout to 15 seconds
                ->retry(2, 1000) // Retry 2 times with 1 second delay
                ->acceptJson()
                ->post($url, $jsonBody);

            $status = $response->getStatusCode();

            if ($status == 200) {
                Log::info('User info updated successfully - UserID: ' . $userId . ', DeviceCode: ' . $device_code);
                return true;
            } else {
                Log::info('Failed to update user info - UserID: ' . $userId . ', DeviceCode: ' . $device_code);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Store/Edit User Request timed out or failed - UserID: ' . $userId . ', DeviceCode: ' . $device_code . ' Error: ' . $e->getMessage());
            return null; // Return null if timeout or any error occurs
        }
    }

    public static function deleteUserFromDevice($user_pin, $device_code)
    {
        $url = self::getBaseUrl() .'api/AdmsEx/DeleteUserDev?userPin=' . $user_pin . '&branchCode=' . $device_code;

        try {
            $response = Http::timeout(15) // Increased timeout to 15 seconds
                ->retry(2, 1000) // Retry 2 times with 1 second delay
                ->acceptJson()
                ->get($url);

            $status = $response->getStatusCode();

            if ($status == 200) {
                Log::info('User deleted from device - UserPin: ' . $user_pin . ', DeviceCode: ' . $device_code);
                return true;
            } else {
                Log::info('Failed to delete user from device - UserPin: ' . $user_pin . ', DeviceCode: ' . $device_code);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Delete User Request timed out or failed - UserPin: ' . $user_pin . ', DeviceCode: ' . $device_code . ' Error: ' . $e->getMessage());
            return null; // Return null if timeout or any error occurs
        }
    }

    public static function GetAllAttendanceData($from, $to, $serial_num)
    {
        $from = Carbon::parse($from)->format('Y/m/d');
        $to = Carbon::parse($to)->format('Y/m/d');
        $url = self::getBaseUrl() .'api/AdmsEx/GetAttendanceLog?fromDate=' . $from . '&toDate=' . $to . '&DeviceSn=' . $serial_num;
        try {
            $response = Http::timeout(30) // Increased timeout to 30 seconds
                ->retry(2, 1000) // Retry 2 times with 1 second delay
                ->acceptJson()
                ->get($url);

            $status = $response->getStatusCode();
            $data = $response->json();

            if ($status == 200) {
                Log::info('Attendance data retrieved - SerialNum: ' . $serial_num . ', From: ' . $from . ', To: ' . $to);
                return $data;
            } else {
                Log::error('Failed to retrieve attendance data - SerialNum: ' . $serial_num . ', From: ' . $from . ', To: ' . $to . ', Status: ' . $status);
                return null; // Return null instead of data when status is not 200
            }
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Connection timeout for device - SerialNum: ' . $serial_num . ', From: ' . $from . ', To: ' . $to . ' Error: ' . $e->getMessage());
            return null;
        } catch (\Illuminate\Http\Client\RequestException $e) {
            Log::error('Request failed for device - SerialNum: ' . $serial_num . ', From: ' . $from . ', To: ' . $to . ' Error: ' . $e->getMessage());
            return null;
        } catch (\Exception $e) {
            Log::error('Attendance Request timed out or failed - SerialNum: ' . $serial_num . ', From: ' . $from . ', To: ' . $to . ' Error: ' . $e->getMessage());
            return null; // Return null if timeout or any error occurs
        }
    }

    public static function sendUserDevice($user_pin, $device_code)
    {
        $url = self::getBaseUrl() .'api/AdmsEx/sendUserDev?userPin=' . $user_pin . '&branchCode=' . $device_code;

        try {
            $response = Http::timeout(15) // Increased timeout to 15 seconds
                ->retry(2, 1000) // Retry 2 times with 1 second delay
                ->acceptJson()
                ->get($url);

            $status = $response->getStatusCode();

            if ($status == 200) {
                Log::info('Renew User to device - UserPin: ' . $user_pin . ', DeviceCode: ' . $device_code);
                return true;
            } else {
                Log::info('Failed to renew user to device - UserPin: ' . $user_pin . ', DeviceCode: ' . $device_code);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Renew User Request timed out or failed - UserPin: ' . $user_pin . ', DeviceCode: ' . $device_code . ' Error: ' . $e->getMessage());
            return null; // Return null if timeout or any error occurs
        }
    }


    public static function storeAttendance($date_range_start,$date_range_end){

        $devices = Device::where('device_status',1)->get();
        foreach($devices as $device){
            $clients = self::GetAllAttendanceData($date_range_start,$date_range_end,$device->serial_num);
            $logs = self::formattingAttendanceAPIData($clients['Data']);
            $attendanceData = [];
            $clientId = getDeviceClientData($device->id,$device->detail_id)->toArray();

            if(count($logs) > 0){
                $logCount = count($logs);
                \Log::info('Attendance log count of ' . $device->name . ': ' . $logCount);
                foreach($logs as $value){
                    $exitUser = in_array($value['userId'],$clientId);
                    if($exitUser){
                        $attendanceData[] = [
                            'client_id' => $value['userId'],
                            'check_in' =>  $value['check_in'] != null ? Carbon::createFromFormat('Y/m/d H:i:s A',$value['check_in'])->format('Y-m-d H:i:s') : null,
                            'check_out' => $value['check_out'] != null ? Carbon::createFromFormat('Y/m/d H:i:s A',$value['check_out'])->format('Y-m-d H:i:s') : null,
                            'status' => 'arrived',
                            'vendor' => 'ZKTECH',
                            'detail_id' => $device->detail_id,
                            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        ];
                    }
                }
                self::storeDataToDB($attendanceData);
            }
            return $device->detail_id.'----success and total is '.count($logs);
        }
       return 'ok';

    }

    private static function formattingAttendanceAPIData($jsonData) {
        // Sort the data by 'CheckTime' in ascending order
        usort($jsonData, function ($a, $b) {
            return strtotime($a['CheckTime']) - strtotime($b['CheckTime']);
        });

        // Group data by UserPin and date
        $groupedData = collect($jsonData)->groupBy(function ($item) {
            return $item['UserPin'] . '_' . Carbon::parse($item['CheckTime'])->format('Y-m-d');
        });

        // Transform the grouped data
        $transformedData = $groupedData->map(function ($group) {
            $first = $group->first();
            $last = $group->last();

            return [
                "userId" => $first['UserPin'],
                "check_in" => $first['CheckTime'],
                "check_out" => $group->count() > 1 ? $last['CheckTime'] : null,
            ];
        })->values()->toArray();

        // Return the transformed data array
        return $transformedData;
    }


    private static function storeDataToDB($data_array){
        $chunks = array_chunk($data_array,100);
        foreach ($chunks as $data){
            GymClientAttendance::insert($data);
        }
        return true;
    }

    /**
     * Process ADMS logs and convert to gym_client_attendance records
     */
    public static function processAdmsLogsToAttendance($businessId, $date = null)
    {
        if (!$date) {
            $date = Carbon::today()->format('Y-m-d');
        }

        $logs = \App\Models\AdmsLog::where('detail_id', $businessId)
            ->whereDate('date', $date)
            ->where('status', 'success')
            ->get();

        $processedCount = 0;

        foreach ($logs as $log) {
            if ($log->adms_response && isset($log->adms_response['Data'])) {
                $attendanceData = self::formatAttendanceDataFromLog($log->adms_response['Data'], $log->device_code, $log->serial_num);

                if (!empty($attendanceData)) {
                    self::storeAttendanceData($attendanceData);
                    $processedCount += count($attendanceData);
                }
            }
        }

        return $processedCount;
    }

    /**
     * Format ADMS response data for gym_client_attendance from logs
     */
    private static function formatAttendanceDataFromLog($admsData, $deviceCode, $serialNum)
    {
        // Get device details
        $device = Device::where('device_code', $deviceCode)->first();
        if (!$device) {
            return [];
        }

        // Get client IDs for this device
        $clientIds = getDeviceClientData($device->id, $device->detail_id)->toArray();

        // Format the data similar to existing logic
        $formattedData = [];

        foreach ($admsData as $record) {
            $userId = $record['UserPin'] ?? null;

            if ($userId && in_array($userId, $clientIds)) {
                $checkIn = isset($record['CheckTime']) ?
                    Carbon::createFromFormat('Y/m/d H:i:s A', $record['CheckTime'])->format('Y-m-d H:i:s') :
                    null;

                $formattedData[] = [
                    'client_id' => $userId,
                    'check_in' => $checkIn,
                    'check_out' => null, // Will be updated if there's a second record
                    'status' => 'arrived',
                    'vendor' => 'ZKTECH',
                    'detail_id' => $device->detail_id,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ];
            }
        }

        return $formattedData;
    }

    /**
     * Store attendance data in chunks
     */
    private static function storeAttendanceData($attendanceData)
    {
        $chunks = array_chunk($attendanceData, 100);
        foreach ($chunks as $chunk) {
            GymClientAttendance::insert($chunk);
        }
    }

    /**
     * Get real-time attendance data for today
     */
    public static function getRealtimeAttendanceData($businessId)
    {
        $devices = Device::where('device_status', 1)
            ->where('business_id', $businessId)
            ->get();

        $results = [];
        $today = Carbon::today();

        foreach ($devices as $device) {
            $attendanceData = self::GetAllAttendanceData(
                $today->format('Y-m-d'),
                $today->format('Y-m-d'),
                $device->serial_num
            );

            if ($attendanceData) {
                $results[] = [
                    'device' => $device,
                    'data' => $attendanceData,
                    'record_count' => count($attendanceData['Data'] ?? [])
                ];
            }
        }

        return $results;
    }

    /**
     * Check if daily sync limit is reached
     */
    public static function isDailySyncLimitReached($businessId, $limit = 3)
    {
        $today = Carbon::today();
        $count = \App\Models\AdmsLog::where('detail_id', $businessId)
            ->whereDate('created_at', $today->format('Y-m-d'))
            ->count();

        return $count >= $limit;
    }

    /**
     * Get today's sync count
     */
    public static function getTodaySyncCount($businessId)
    {
        $today = Carbon::today();
        return \App\Models\AdmsLog::where('detail_id', $businessId)
            ->whereDate('created_at', $today->format('Y-m-d'))
            ->count();
    }

    /**
     * Filter ADMS JSON data based on existing UserIds in the branch
     */
    public static function filterAdmsDataByBranchUsers($admsData, $businessId, $deviceCode = null)
    {
        if (!$admsData || !isset($admsData['Data'])) {
            return [
                'filtered_data' => [],
                'user_not_found' => [],
                'total_records' => 0,
                'filtered_records' => 0,
                'not_found_records' => 0
            ];
        }

        // Get all devices for this business
        $devices = Device::where('device_status', 1)
            ->where('detail_id', $businessId);

        if ($deviceCode) {
            $devices->where('code', $deviceCode);
        }

        $devices = $devices->get();

        // Get all client IDs for all devices in this business
        $allClientIds = [];
        foreach ($devices as $device) {
            $clientIds = getDeviceClientData($device->id, $device->detail_id)->toArray();
            $allClientIds = array_merge($allClientIds, $clientIds);
        }

        // Remove duplicates
        $allClientIds = array_unique($allClientIds);

        // Fetch user names for all client IDs
        $userNames = [];
        if (!empty($allClientIds)) {
            $clients = GymClient::whereIn('id', $allClientIds)
                ->select('id', 'first_name', 'middle_name', 'last_name')
                ->get();

            foreach ($clients as $client) {
                $fullName = trim($client->first_name . ' ' . $client->middle_name . ' ' . $client->last_name);
                $userNames[$client->id] = [
                    'full_name' => $fullName,
                ];
            }
        }

        $filteredData = [];
        $userNotFound = [];
        $totalRecords = count($admsData['Data']);

        // Group records by UserId and date for checkout calculation
        $groupedRecords = [];
        $notFoundRecords = [];

        foreach ($admsData['Data'] as $record) {
            $userId = $record['UserPin'] ?? null;

            if ($userId) {
                if (in_array($userId, $allClientIds)) {
                    // User exists in branch - group by userId and date
                    $checkTime = $record['CheckTime'] ?? null;
                    if ($checkTime) {
                        $date = Carbon::createFromFormat('Y/m/d H:i:s A', $checkTime)->format('Y-m-d');
                        $key = $userId . '_' . $date;

                        if (!isset($groupedRecords[$key])) {
                            $groupedRecords[$key] = [];
                        }
                        $groupedRecords[$key][] = $record;
                    }
                } else {
                    // User not found in branch
                    $notFoundRecords[] = $record;
                }
            }
        }

        // Process grouped records to calculate checkout times
        foreach ($groupedRecords as $key => $records) {
            // Sort records by check time
            usort($records, function ($a, $b) {
                return strtotime($a['CheckTime']) - strtotime($b['CheckTime']);
            });

            $firstRecord = $records[0];
            $lastRecord = count($records) > 1 ? end($records) : null;

            $userId = $firstRecord['UserPin'];
            $userInfo = $userNames[$userId] ?? null;

            $processedRecord = [
                'UserPin' => $userId,
                'UserName' => $userInfo ? $userInfo['full_name'] : 'Unknown User',
                'CheckIn' => $firstRecord['CheckTime'],
                'CheckOut' => $lastRecord ? $lastRecord['CheckTime'] : null,
                'TotalEntries' => count($records),
            ];

            $filteredData[] = $processedRecord;
        }

        // Process not found records - group by UserId and date
        $notFoundGrouped = [];
        foreach ($notFoundRecords as $record) {
            $checkTime = $record['CheckTime'] ?? null;
            if ($checkTime) {
                $date = Carbon::createFromFormat('Y/m/d H:i:s A', $checkTime)->format('Y-m-d');
                $key = $record['UserPin'] . '_' . $date;

                if (!isset($notFoundGrouped[$key])) {
                    $notFoundGrouped[$key] = [];
                }
                $notFoundGrouped[$key][] = $record;
            }
        }

        // Process grouped not found records to calculate checkout times
        foreach ($notFoundGrouped as $key => $records) {
            // Sort records by check time
            usort($records, function ($a, $b) {
                return strtotime($a['CheckTime']) - strtotime($b['CheckTime']);
            });

            $firstRecord = $records[0];
            $lastRecord = count($records) > 1 ? end($records) : null;

            $processedRecord = [
                'UserPin' => $firstRecord['UserPin'],
                'UserName' => 'Unknown User',
                'CheckIn' => $firstRecord['CheckTime'],
                'CheckOut' => $lastRecord ? $lastRecord['CheckTime'] : null,
                'TotalEntries' => count($records),
            ];

            $userNotFound[] = $processedRecord;
        }

        return [
            'filtered_data' => $filteredData,
            'user_not_found' => $userNotFound,
            'total_records' => $totalRecords,
            'filtered_records' => count($filteredData),
            'not_found_records' => count($userNotFound)
        ];
    }

    /**
     * Get filtered ADMS data with caching
     */
    public static function getFilteredAdmsData($logId, $admsData, $businessId, $deviceCode = null, $forceRefresh = false)
    {
        // Filter the data
        $filteredData = self::filterAdmsDataByBranchUsers($admsData, $businessId, $deviceCode);

        return $filteredData;
    }
    
    /**
     * Get branches with missing attendance logs in a date range
     *
     * @param string $fromDate Y-m-d
     * @param string $toDate Y-m-d
     * @return array
     */
    public static function getBranchesWithMissingAttendance($fromDate, $toDate)
    {
        $from = Carbon::parse($fromDate);
        $to   = Carbon::parse($toDate);

        // 1. Build date range
        $dateRange = [];
        for ($date = $from->copy(); $date->lte($to); $date->addDay()) {
            $dateRange[] = $date->format('Y-m-d');
        }

        // 2. Get all active branches that have devices
        $branches = Device::where('device_status', 1)
            ->select('detail_id')
            ->distinct()
            ->pluck('detail_id');

        $result = [];

        foreach ($branches as $branchId) {

            // 3. Get attendance dates already synced for this branch
            $syncedDates = GymClientAttendance::where('detail_id', $branchId)
                ->whereBetween('check_in', [
                    $from->format('Y-m-d 00:00:00'),
                    $to->format('Y-m-d 23:59:59')
                ])
                ->selectRaw('DATE(check_in) as attendance_date')
                ->distinct()
                ->pluck('attendance_date')
                ->toArray();

            // 4. Find missing dates
            $missingDates = array_values(array_diff($dateRange, $syncedDates));

            if (!empty($missingDates)) {
                $result[] = [
                    'branch_id' => $branchId,
                    'missing_dates' => $missingDates,
                    'missing_count' => count($missingDates),
                ];
            }
        }

        return [
            'from_date' => $from->format('Y-m-d'),
            'to_date' => $to->format('Y-m-d'),
            'total_branches_checked' => count($branches),
            'branches_with_missing_attendance' => count($result),
            'data' => $result
        ];
    }
}
