<?php

namespace App\Http\Controllers\API\Device;

use App\Http\Controllers\Controller;
use App\Http\Resources\DeviceResource;
use App\Models\Device;
use App\Models\GymClient;
use App\Models\GymClientAttendance;
use App\Models\Shift;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DeviceController extends DeviceBaseController
{
    public function getDeviceInfoBySN(Request $request)
    {
        if(!$this->data['companyCheck']){
            return $this->sendError(null,'Key Does not Match');
        }
        $device = Device::where('detail_id',$this->data['company']->id)
                ->where('serial_num',$request->get('sn'))->first();
        if($device == null){
            return $this->sendError($device,'Device Info Not Found');
        }
        $data = new DeviceResource($device);
        return $this->sendResponse($data,'Device Info');
    }

    public function getCompanyDeviceList(){
        if(!$this->data['companyCheck']){
            return $this->sendError(null,'Key Does not Match');
        }
        $device = Device::where('detail_id',$this->data['company']->id)->get();
        if($device == null){
            return $this->sendError($device,'Device Does not  exists');
        }
        $data = DeviceResource::collection($device);
        return $this->sendResponse( $data,'Company Device List');
    }

    public function getActiveClientList(Request $request){
        if(!$this->data['companyCheck']){
            return $this->sendError(null,'Key Does not Match');
        }
        $shift = Shift::where('detail_id',$this->data['company']->id)
        ->where('slug',$request->shift)
        ->first();
        if($shift == null){
            return $this->sendError($shift,'Shift does not exists');
        }
        $device = Device::where('detail_id',$this->data['company']->id)
                ->where('serial_num',$request->get('sn'))->first();
        if($device == null){
            return $this->sendError($device,'Device Does not  exists');
        }
        $clientWithDeviceAndShifts = GymClient::join('business_customers', 'business_customers.customer_id', '=', 'gym_clients.id')
                ->where('business_customers.detail_id',$this->data['company']->id)
                ->where('gym_clients.is_client',  'yes')
                ->whereHas('devices',function($q) use ($device){
                    $q->where('device_id',$device->id);
                })
                ->when($request->start_time && $request->end_time,function($q) use ($shift){
                    $q->whereHas('shifts',function($query) use ($shift){
                        $query->where('shift_id',$shift->id);
                    });
                })
                ->when($request->shift,function($q) use ($shift){
                    $q->whereHas('shifts',function($query) use ($shift){
                        $query->where('shift_id',$shift->id);
                    });
                })
                ->get();
        return response()->json([
            'message' => 'Client List',
            'shift' => $request->shift,
            'start_time' => $shift->from_time,
            'end_time' => $shift->to_time,
            'data' => [
                'clients' => $clientWithDeviceAndShifts->pluck('uuid'),
            ]
        ]);
    }

    public function storeClientAttendance(Request $request){
        if(!$this->data['companyCheck']){
            return $this->sendError(null,'Key Does not Match');
        }
        $attendanceData = [];
        $jsonData = $request->getContent();
        $logs = $this->formattingJsonToArray($jsonData);
        foreach($logs as $userId=> $value){
            // $attendanceData[$userId] = [$startTime,$endTime];
            $clientId = GymClient::findByUid($userId);
            $attendanceData[] = [
                'client_id' => $clientId->id,
                'check_in' =>  Carbon::createFromTimestamp($value[0])->format('Y-m-d H:i:s'),
                'check_out' => Carbon::createFromTimestamp(end($value))->format('Y-m-d H:i:s'),
                'status' => 'arrived',
                'vendor' => 'RFID'
            ];
        }
        $this->storeDataToDB($attendanceData);
        return response()->json([
            'message' => 'Client Attendance Store',
            'data' => $attendanceData
        ]);
    }

    public function formattingJsonToArray($jsonData){
        
        $lines = explode("\n", $jsonData);
        $redundantData = [];
        
        foreach ($lines as $line) {
            $line = trim($line);

             // Check if the line contains valid JSON-like data
            if (!empty($line) && preg_match('/"([^"]+)"\s*:\s*(\d+)/', $line, $matches)) {
                $key = $matches[1];
                $value = (int)$matches[2];
                if (!isset($redundantData[$key])) {
                    $redundantData[$key] = [];
                }

                $redundantData[$key][] = $value;
            }
        }
        return $redundantData;
    }

    public function storeDataToDB($data_array){
        $chunks = array_chunk($data_array,100);
        foreach ($chunks as $data){
            GymClientAttendance::insert($data);
        }
        return true;
    }

}
