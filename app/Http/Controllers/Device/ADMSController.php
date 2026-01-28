<?php

namespace App\Http\Controllers\Device;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GymAdmin\GymAdminBaseController;
use Illuminate\Http\Request;
use App\Models\AdmsLog;
use App\Models\Device;
use App\Helpers\ADMSHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ADMSController extends GymAdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['manageMenu'] = 'active';
        $this->data['admsLogMenu'] = 'active';
    }

    /**
     * Display ADMS logs for the current business
     */
    public function index()
    {
        if (!$this->data['user']->can("add_biometrics") && $this->data['common_details']->has_device == 1) {
            return App::abort(401);
        }

        // dd($this->syncRealtimeData());
        $this->data['title'] = "ADMS Logs";
        $businessId = $this->data['user']->detail_id;

        $this->data['logs'] = AdmsLog::forBusiness($businessId)
            ->with('device')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $this->data['todaySyncCount'] = AdmsLog::getTodaySyncCount($businessId);
        $this->data['canSync'] = !AdmsLog::hasExceededDailyLimit($businessId);

        return view('devices.adms.logs', $this->data);
    }

    /**
     * Sync real-time data from ADMS
     */
    public function syncRealtimeData()
    {
        // Check daily limit
        if (AdmsLog::hasExceededDailyLimit($this->data['user']->detail_id)) {
            return response()->json([
                'success' => false,
                'message' => 'Daily sync limit exceeded. You can only sync 3 times per day.',
                'todaySyncCount' => AdmsLog::getTodaySyncCount($this->data['user']->detail_id)
            ], 429);
        }

        try {
            // Get active devices for this business
            $devices = Device::where('device_status', 1)
                ->where('detail_id', $this->data['user']->detail_id)
                ->get();

            if ($devices->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active devices found for this business.'
                ], 404);
            }

            $syncResults = [];
            $fromDate = Carbon::today();
            $toDate = Carbon::today()->addDay();

            foreach ($devices as $device) {
                // Fetch real-time data for today
                $attendanceData = ADMSHelper::GetAllAttendanceData(
                    $fromDate->format('Y-m-d'),
                    $toDate->format('Y-m-d'),
                    $device->serial_num
                );

                if ($attendanceData) {
                    // Store in ADMS logs
                    AdmsLog::create([
                        'date' => $fromDate,
                        'adms_response' => $attendanceData,
                        'detail_id' => $this->data['user']->detail_id,
                        'device_code' => $device->code,
                        'serial_num' => $device->serial_num,
                        'status' => 'success'
                    ]);

                    $syncResults[] = [
                        'device' => $device->name,
                        'records' => count($attendanceData['Data'] ?? []),
                        'status' => 'success'
                    ];
                } else {
                    // Log failed attempt
                    AdmsLog::create([
                        'date' => $fromDate,
                        'adms_response' => null,
                        'detail_id' => $this->data['user']->detail_id,
                        'device_code' => $device->code,
                        'serial_num' => $device->serial_num,
                        'status' => 'failed',
                        'error_message' => 'Failed to fetch data from ADMS'
                    ]);

                    $syncResults[] = [
                        'device' => $device->name,
                        'records' => 0,
                        'status' => 'failed'
                    ];
                }
            }

            $todaySyncCount = AdmsLog::getTodaySyncCount($this->data['user']->detail_id);

            return response()->json([
                'success' => true,
                'message' => 'Real-time data synced successfully',
                'results' => $syncResults,
                'todaySyncCount' => $todaySyncCount,
                'canSync' => !AdmsLog::hasExceededDailyLimit($this->data['user']->detail_id)
            ]);

        } catch (\Exception $e) {
            Log::error('ADMS real-time sync failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Sync failed: ' . $e->getMessage(),
                'todaySyncCount' => AdmsLog::getTodaySyncCount($this->data['user']->detail_id)
            ], 500);
        }
    }

    /**
     * Process daily sync - convert ADMS logs to gym_client_attendance
     */
    public function processDailySync(Request $request)
    {
        $businessId = $this->data['user']->detail_id;
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));

        try {
            $processedCount = $this->processAdmsLogsToAttendance($businessId, $date);

            return response()->json([
                'success' => true,
                'message' => "Daily sync completed. Processed {$processedCount} records.",
                'processedCount' => $processedCount
            ]);

        } catch (\Exception $e) {
            Log::error('Daily sync processing failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Daily sync processing failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process ADMS logs and convert to gym_client_attendance records
     */
    private function processAdmsLogsToAttendance($businessId, $date)
    {
        $logs = AdmsLog::forBusiness($businessId)
            ->whereDate('date', $date)
            ->where('status', 'success')
            ->get();

        $processedCount = 0;

        foreach ($logs as $log) {
            if ($log->adms_response && isset($log->adms_response['Data'])) {
                $attendanceData = $this->formatAttendanceData($log->adms_response['Data'], $log->device_code, $log->serial_num);

                if (!empty($attendanceData)) {
                    $this->storeAttendanceData($attendanceData);
                    $processedCount += count($attendanceData);
                }
            }
        }

        return $processedCount;
    }

    /**
     * Format ADMS response data for gym_client_attendance
     */
    private function formatAttendanceData($admsData, $deviceCode, $serialNum)
    {
        // Get device details
        $device = Device::where('code', $deviceCode)->first();
        if (!$device) {
            return [];
        }

        // Get client IDs for this device
        $clientIds = getDeviceClientData($device->id, $device->detail_id)->toArray();

        // Format the data similar to existing ADMSHelper logic
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
    private function storeAttendanceData($attendanceData)
    {
        $chunks = array_chunk($attendanceData, 100);
        foreach ($chunks as $chunk) {
            \App\Models\GymClientAttendance::insert($chunk);
        }
    }

    /**
     * Get sync statistics
     */
    public function getSyncStats(Request $request)
    {
        $businessId = $this->data['user']->detail_id;
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));

        $stats = AdmsLog::forBusiness($businessId)
            ->whereDate('date', $date)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        $todaySyncCount = AdmsLog::getTodaySyncCount($businessId);

        return response()->json([
            'success' => true,
            'stats' => $stats,
            'todaySyncCount' => $todaySyncCount,
            'canSync' => !AdmsLog::hasExceededDailyLimit($businessId)
        ]);
    }

    /**
     * Get detailed log information with filtered data
     */
    public function getLogDetails(Request $request)
    {
        $logId = $request->input('log_id');
        $businessId = $this->data['user']->detail_id;
        $forceRefresh = $request->input('force_refresh', false);

        Log::info('ADMS Log Details Request', [
            'log_id' => $logId,
            'business_id' => $businessId,
            'force_refresh' => $forceRefresh
        ]);

        $log = AdmsLog::forBusiness($businessId)
            ->with('device')
            ->find($logId);

        if (!$log) {
            Log::warning('ADMS Log not found', [
                'log_id' => $logId,
                'business_id' => $businessId
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Log not found'
            ], 404);
        }

        if($log->filter_response == null){
             // Get filtered data
            $filteredData = ADMSHelper::getFilteredAdmsData(
                $logId,
                $log->adms_response,
                $businessId,
                $log->device_code,
                $forceRefresh
            );

            $log->update(['filter_response' => $filteredData ]);
        }else{
            $filteredData = $log->filter_response;
        }

        $response = [
            'success' => true,
            'log' => [
                'id' => $log->id,
                'date' => $log->date ? $log->date->format('Y-m-d') : 'N/A',
                'device_name' => $log->device->name ?? 'N/A',
                'device_code' => $log->device_code,
                'serial_num' => $log->serial_num,
                'status' => $log->status,
                'error_message' => $log->error_message,
                'records_count' => $log->adms_response && isset($log->adms_response['Data']) ? count($log->adms_response['Data']) : 0,
                'sync_time' => $log->created_at->format('Y-m-d H:i:s'),
                'adms_response' => $log->adms_response
            ],
            'filtered_data' => $filteredData
        ];

        Log::info('ADMS Log Details Response', $response);

        return response()->json($response);
    }

}
