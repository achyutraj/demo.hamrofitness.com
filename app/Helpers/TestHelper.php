<?php

namespace App\Helpers;

use App\Models\Device;
use App\Models\GymClientAttendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TestHelper{

    public function handle()
    {
        Log::info('Starting attendance log data collection');

        $devices = Device::get();
        $totalDevices = $devices->count();
        $successfulDevices = 0;
        $failedDevices = 0;

        Log::info("Found {$totalDevices} active devices to process");

        // Check for duplicate serial numbers
        $serialNumbers = $devices->pluck('serial_num')->toArray();
        $duplicateSerials = array_diff_assoc($serialNumbers, array_unique($serialNumbers));
        if (!empty($duplicateSerials)) {
            Log::warning("Found duplicate serial numbers: " . json_encode($duplicateSerials));
            Log::warning("This will cause data conflicts in ADMS API calls");
        }

        foreach ($devices as $device) {
            try {
                Log::info("Processing device: {$device->code} (ID: {$device->id}, Serial: {$device->serial_num})");

                // $date_range_start = Carbon::today();
                // $date_range_end = Carbon::today()->addDay();

                $date_range_start = '2025-08-19';
                $date_range_end = '2025-08-20';

                // Get attendance data from ADMS with retry mechanism
                $clients = $this->getAttendanceDataWithRetry($date_range_start, $date_range_end, $device->serial_num);

                if ($clients === null) {
                    Log::error("Failed to retrieve attendance data for device: {$device->code} (Serial: {$device->serial_num})");
                    $failedDevices++;
                    continue;
                }

                // Check if clients data is valid
                if (!is_array($clients) || empty($clients)) {
                    Log::info("No attendance data found for device: {$device->code} (Serial: {$device->serial_num})");
                    $successfulDevices++;
                    continue;
                }

                $logs = $this->formattingAttendanceAPIData($clients);

                if (empty($logs)) {
                    Log::info("No formatted logs for device: {$device->code} (Serial: {$device->serial_num})");
                    $successfulDevices++;
                    continue;
                }

                $attendanceData = [];
                $clientId = $this->getDeviceClientData($device->id, $device->detail_id);

                if (empty($clientId)) {
                    Log::warning("No clients found for device: {$device->code} (ID: {$device->id})");
                    $successfulDevices++;
                    continue;
                }

                $logCount = count($logs);
                Log::info(message: "Processing {$logCount} attendance logs for device: {$device->code}");

                // Debug: Log client IDs for this device
                Log::info("Client IDs for device {$device->code}: " . json_encode($clientId));

                $validRecords = 0;
                $invalidRecords = 0;
                $clientNotFound = 0;
                $dateParseErrors = 0;

                foreach ($logs as $value) {
                    // Validate required fields
                    if (!isset($value['userId']) || !isset($value['check_in'])) {
                        Log::warning("Invalid log entry found for device {$device->code}: " . json_encode($value));
                        $invalidRecords++;
                        continue;
                    }

                    $exitUser = in_array($value['userId'], $clientId);
                    if (!$exitUser) {
                        $clientNotFound++;
                        continue;
                    }

                    if ($exitUser) {
                        try {
                            $checkIn = null;
                            $checkOut = null;

                            // Parse check-in time
                            if ($value['check_in'] !== null) {
                                $checkIn = $this->parseDateTime($value['check_in']);
                                if ($checkIn === false) {
                                    Log::warning("Invalid check-in time format for device {$device->code}, userId {$value['userId']}: {$value['check_in']}");
                                    $dateParseErrors++;
                                    continue;
                                }
                                $checkIn = $checkIn->format('Y-m-d H:i:s');
                            }

                            // Parse check-out time
                            if ($value['check_out'] !== null) {
                                $checkOut = Carbon::createFromFormat('Y/m/d H:i:s A', $value['check_out']);
                                if ($checkOut === false) {
                                    Log::warning("Invalid check-out time format for device {$device->code}, userId {$value['userId']}: {$value['check_out']}");
                                    $dateParseErrors++;
                                    $checkOut = null;
                                } else {
                                    $checkOut = $checkOut->format('Y-m-d H:i:s');
                                }
                            }

                            $attendanceData[] = [
                                'client_id' => $value['userId'],
                                'check_in' => $checkIn,
                                'check_out' => $checkOut,
                                'status' => 'arrived',
                                'vendor' => 'ZKTECH',
                                'detail_id' => $device->detail_id,
                                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            ];
                            $validRecords++;
                        } catch (\Exception $e) {
                            Log::error("Error processing attendance entry for device {$device->code}, userId {$value['userId']}: " . $e->getMessage());
                            continue;
                        }
                    }
                }

                // Log processing statistics
                Log::info("Processing statistics for device {$device->code}: Valid: {$validRecords}, Invalid: {$invalidRecords}, Client Not Found: {$clientNotFound}, Date Parse Errors: {$dateParseErrors}");

                if (!empty($attendanceData)) {
                    // Remove duplicates before storing
                    $attendanceData = $this->removeDuplicateRecords($attendanceData, $device->detail_id);

                    if (!empty($attendanceData)) {
                        $this->storeDataToDB($attendanceData);
                        Log::info("Successfully stored " . count($attendanceData) . " attendance records for device: {$device->code}");
                    } else {
                        Log::info("All attendance records were duplicates for device: {$device->code}");
                    }
                } else {
                    Log::info("No valid attendance data to store for device: {$device->code}");
                }

                $successfulDevices++;

            } catch (\Exception $e) {
                Log::error("Error processing device {$device->code} (ID: {$device->id}): " . $e->getMessage());
                Log::error("Stack trace: " . $e->getTraceAsString());
                $failedDevices++;
            }
        }

        Log::info("Attendance log processing completed. Total devices: {$totalDevices}, Successful: {$successfulDevices}, Failed: {$failedDevices}");

        return 0;
    }

    /**
     * Get attendance data with retry mechanism
     */
    private function getAttendanceDataWithRetry($start_date, $end_date, $serial_num, $maxRetries = 3)
    {
        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            try {
                $clients = getADMSAttendanceLog($start_date, $end_date, $serial_num);

                if ($clients !== null) {
                    return $clients;
                }

                Log::warning("Attempt {$attempt} failed for device {$serial_num}, retrying...");

                if ($attempt < $maxRetries) {
                    sleep(2); // Wait 2 seconds before retry
                }

            } catch (\Exception $e) {
                Log::error("Error in attempt {$attempt} for device {$serial_num}: " . $e->getMessage());

                if ($attempt < $maxRetries) {
                    sleep(2);
                }
            }
        }

        return null;
    }

    /**
     * Get device client data with error handling
     */
    private function getDeviceClientData($deviceId, $businessId)
    {
        try {
            $clientData = getDeviceClientData($deviceId, $businessId);
            return is_array($clientData) ? $clientData : $clientData->toArray();
        } catch (\Exception $e) {
            Log::error("Error getting device client data for device ID {$deviceId}: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Remove duplicate attendance records
     */
    private function removeDuplicateRecords($attendanceData, $detailId)
    {
        $uniqueRecords = [];
        $duplicateCount = 0;

        foreach ($attendanceData as $record) {
            $checkDate = Carbon::parse($record['check_in'])->format('Y-m-d');

            // Check if record already exists for this client on this date
            $existingRecord = GymClientAttendance::where('client_id', $record['client_id'])
                ->where('detail_id', $detailId)
                ->whereDate('check_in', $checkDate)
                ->first();

            if (!$existingRecord) {
                $uniqueRecords[] = $record;
            } else {
                $duplicateCount++;
            }
        }

        if ($duplicateCount > 0) {
            Log::info("Removed {$duplicateCount} duplicate attendance records");
        }

        return $uniqueRecords;
    }

    public function formattingAttendanceAPIData($jsonData)
    {
        try {
            if (!is_array($jsonData) || empty($jsonData)) {
                Log::warning("Invalid or empty JSON data received");
                return [];
            }

            // Convert array elements to collections
            $transformedData = collect($jsonData)->groupBy('UserPin')->map(function ($group) {
                // Validate required fields
                if (!$group->first() || !isset($group->first()['UserPin']) || !isset($group->first()['CheckTime'])) {
                    Log::warning("Invalid group data: " . json_encode($group->first()));
                    return null;
                }

                // Access array elements directly
                return [
                    "userId" => $group->first()['UserPin'],
                    "check_in" => $group->first()['CheckTime'],
                    "check_out" => $group->count() > 1 ? $group->last()['CheckTime'] : null,
                ];
            })->filter()->values()->toArray();

            return $transformedData;

        } catch (\Exception $e) {
            Log::error("Error formatting attendance API data: " . $e->getMessage());
            return [];
        }
    }

    public function storeDataToDB($data_array)
    {
        try {
            if (empty($data_array)) {
                Log::warning("No data to store in database");
                return false;
            }

            $chunks = array_chunk($data_array, 100);
            $totalStored = 0;

            foreach ($chunks as $data) {
                try {
                    GymClientAttendance::insert($data);
                    $totalStored += count($data);
                } catch (\Exception $e) {
                    Log::error("Error inserting attendance chunk: " . $e->getMessage());
                    Log::error("Data: " . json_encode($data));
                    throw $e;
                }
            }

            Log::info("Successfully stored {$totalStored} attendance records in database");
            return true;

        } catch (\Exception $e) {
            Log::error("Error storing data to database: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Parse date time with multiple format support
     */
    private function parseDateTime($dateTimeString)
    {
        $formats = [
            'Y/m/d H:i:s A',  // 2025/08/29 10:30:00 AM
            'Y/m/d H:i:s',    // 2025/08/29 10:30:00
            'Y-m-d H:i:s A',  // 2025-08-29 10:30:00 AM
            'Y-m-d H:i:s',    // 2025-08-29 10:30:00
            'd/m/Y H:i:s A',  // 29/08/2025 10:30:00 AM
            'd/m/Y H:i:s',    // 29/08/2025 10:30:00
            'm/d/Y H:i:s A',  // 08/29/2025 10:30:00 AM
            'm/d/Y H:i:s',    // 08/29/2025 10:30:00
        ];

        foreach ($formats as $format) {
            $parsed = Carbon::createFromFormat($format, $dateTimeString);
            if ($parsed !== false) {
                return $parsed;
            }
        }

        Log::warning("Could not parse date time: {$dateTimeString}");
        return false;
    }
}


