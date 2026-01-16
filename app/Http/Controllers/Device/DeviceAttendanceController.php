<?php

namespace App\Http\Controllers\Device;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GymAdmin\GymAdminBaseController;
use App\Classes\Reply;
use App\Models\Device;
use App\Models\GymClient;
use App\Models\GymClientAttendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use DataTables;

class DeviceAttendanceController extends GymAdminBaseController
{
    public function index()
    {
        if (!$this->data['user']->can("view_attendance_report")) {
            return App::abort(401);
        }
        
        $this->data['title'] = "Bio Attendance Report";
        $this->data['devices'] = Device::where('device_status',1)->where('detail_id',$this->data['user']->detail_id)->get();
        $this->data['customers'] = GymClient::join('business_customers',
            'business_customers.customer_id', '=', 'gym_clients.id')
            ->where('gym_clients.is_client',  'yes')
            ->where('business_customers.detail_id', '=', $this->data['user']->detail_id)
            ->select('gym_clients.id', 'gym_clients.first_name', 'gym_clients.middle_name', 'gym_clients.last_name')
            ->get();
        return View::make('gym-admin.reports.attendance.bio', $this->data);
    }
    public function getADMSAttendanceLog($start_date,$end_date,$serial_num){
        $adms = new ADMSController;
        $data = $adms->GetAllAttendanceData($start_date,$end_date,$serial_num);
        $adms_data = $data->getData();
        return $adms_data->data;
    }

    public function store()
    {
        $type = request()->get('type');
        $device = Device::findOrFail(request()->get('device'));
        $validator = Validator::make(request()->all(), GymClientAttendance::reportRules());
        if ($validator->fails()) {
            return Reply::formErrors($validator);
        } else {
            $date_range = explode('-', request()->get('date_range'));
            $date_range_start = Carbon::createFromFormat('M d, Y', trim($date_range[0]));
            $date_range_end = Carbon::createFromFormat('M d, Y', trim($date_range[1]));
            switch ($type) {
                case 'client':
                    $clients = $this->getADMSAttendanceLog($date_range_start,$date_range_end,$device->serial_num);
                    break;
                default:
                    // $result_explode = explode('|', $type);
                    // if ($result_explode[0] === 'customer') {
                    //     $allData = $this->getADMSAttendanceLog($date_range_start,$date_range_end,$device->serial_num);
                    //     $clients = collect([$allData->Data])->filter(function ($client) use ($result_explode) {
                    //         return isset($client['userPin']) && $client['userPin'] == $result_explode[1];
                    //     })->all();
                    // }
                break;
            }
            $data = [
                'total' => $clients->TotalCount,
                'start_date' => $date_range_start->format('Y-m-d'),
                'end_date' => $date_range_end->format('Y-m-d'),
                'deviceId' => $device->id,
                'type' => $type,
                'report' => 'Client Attendance'
            ];
            return Reply::successWithData('Reports Fetched', $data);
        }
    }

    public function ajax_create($deviceId,$id,$start_date, $end_date)
    {
        $sDate = new Carbon($start_date);
        $eDate = new Carbon($end_date);
        $device = Device::findOrFail($deviceId);
        switch ($id) {
            case 'client':
                $clients = $this->getADMSAttendanceLog($sDate,$eDate,$device->serial_num);
                break;
            default:
                $result_explode = explode('|', $id);
                if ($result_explode[0] === 'customer') {
                    $clients = $this->getADMSAttendanceLog($sDate,$eDate,$device->serial_num);
                }
            break;
        }
        $clients = $clients->Data;

        return Datatables::of($clients)
            ->editColumn('user_pin', function ($row) {
                return $row->UserPin;
            })
            ->editColumn('user_name', function ($row) {
                return $row->UserName;
            })
            ->editColumn('verify_mode', function ($row) {
                return $row->VerifyMode == 1 ? 'FingerPrint' : 'Badge Number';
            })
            ->editColumn('serial_num', function ($row) {
                return $row->DeviceSN;
            })
            ->editColumn('check_in', function ($row) {
                return date('M d , Y  H:i:s a', strtotime($row->CheckTime));
            })
            ->editColumn('check_in_out', function ($row) {
                return $row->CheckInOut == 1 ? 'Yes' : 'No';
            })
            ->rawColumns(['user_pin','user_name','verify_mode','check_in','serial_num','check_in_out'])
            ->make();
    }
}
