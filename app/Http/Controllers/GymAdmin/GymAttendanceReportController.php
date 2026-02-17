<?php

namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use App\Exports\Reports\AttendanceReportExport;
use App\Models\Employ;
use App\Models\GymClient;
use App\Models\GymClientAttendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use PDF;
use Excel;
use DataTables;

class GymAttendanceReportController extends GymAdminBaseController
{

    public function __construct()
    {
        parent::__construct();

        $this->data['reportMenu'] = 'active';
        $this->data['attendancereportMenu'] = 'active';
    }

    public function index()
    {
        if (!$this->data['user']->can("view_attendance_report")) {
            return App::abort(401);
        }
        $this->data['title'] = "Attendance Report";
        $this->data['customers'] = GymClient::join('business_customers',
            'business_customers.customer_id', '=', 'gym_clients.id')
            ->where('gym_clients.is_client','yes')
            ->where('business_customers.detail_id', '=', $this->data['user']->detail_id)
            ->select('gym_clients.id', 'gym_clients.first_name', 'gym_clients.middle_name', 'gym_clients.last_name')
            ->get();
        if($this->data['common_details']->has_device == 1){
            $this->data['employees'] = GymClient::join('business_customers',
                'business_customers.customer_id', '=', 'gym_clients.id')
                ->where('gym_clients.is_client','no')
                ->where('business_customers.detail_id', '=', $this->data['user']->detail_id)
                ->select('gym_clients.id', 'gym_clients.first_name', 'gym_clients.middle_name', 'gym_clients.last_name')
                ->get();
        }else{
            $this->data['employees'] = Employ::where('detail_id', '=', $this->data['user']->detail_id)
            ->select('id', 'first_name', 'middle_name', 'last_name')
            ->get();
        }

        return View::make('gym-admin.reports.attendance.index', $this->data);
    }

    public function store()
    {
        $data = [];
        $type = request()->get('type');
        $validator = Validator::make(request()->all(), GymClientAttendance::reportRules());
        if ($validator->fails()) {
            return Reply::formErrors($validator);
        } else {
            $date_range = explode('-', request()->get('date_range'));
            $date_range_start = Carbon::createFromFormat('M d, Y', trim($date_range[0]));
            $date_range_end = Carbon::createFromFormat('M d, Y', trim($date_range[1]));
            $detail_id = $this->data['user']->detail_id;
            switch ($type) {
                case 'employ':
                    if($this->data['common_details']->has_device == 1){
                        $employees = GymClient::leftJoin('gym_client_attendances', 'gym_client_attendances.client_id', 'gym_clients.id')
                        ->leftJoin('business_customers', 'business_customers.customer_id', 'gym_clients.id')
                        ->where('gym_clients.is_client','no')
                        ->where('business_customers.detail_id', $detail_id)
                        ->whereDate('gym_client_attendances.check_in', '>=', $date_range_start->format('Y-m-d'))
                        ->whereDate('gym_client_attendances.check_in', '<=', $date_range_end->format('Y-m-d'))
                        ->count();
                    }else{
                        $employees = Employ::leftJoin('employ_attendances', 'employ_attendances.client_id', 'employes.id')
                        ->whereDate('employ_attendances.check_in', '>=', $date_range_start->format('Y-m-d'))
                        ->whereDate('employ_attendances.check_in', '<=', $date_range_end->format('Y-m-d'))
                        ->where('employes.detail_id', $detail_id)
                        ->count();
                    }

                    $data = [
                        'total' => $employees,
                        'start_date' => $date_range_start->format('Y-m-d'),
                        'end_date' => $date_range_end->format('Y-m-d'),
                        'type' => $type,
                        'report' => 'Employ Attendance'
                    ];
                    break;
                case 'client':
                    $clients = GymClient::leftJoin('gym_client_attendances', 'gym_client_attendances.client_id', 'gym_clients.id')
                        ->leftJoin('business_customers', 'business_customers.customer_id', 'gym_clients.id')
                        ->where('gym_clients.is_client','yes')
                        ->where('business_customers.detail_id', $detail_id)
                        ->whereDate('gym_client_attendances.check_in', '>=', $date_range_start->format('Y-m-d'))
                        ->whereDate('gym_client_attendances.check_in', '<=', $date_range_end->format('Y-m-d'))
                        ->count();
                    $data = [
                        'total' => $clients,
                        'start_date' => $date_range_start->format('Y-m-d'),
                        'end_date' => $date_range_end->format('Y-m-d'),
                        'type' => $type,
                        'report' => 'Client Attendance'
                    ];
                    break;
                default:
                    $result_explode = explode('|', $type);
                    if ($result_explode[0] === 'customer') {
                        $client = GymClient::leftJoin('gym_client_attendances', 'gym_client_attendances.client_id', 'gym_clients.id')
                            ->leftJoin('business_customers', 'business_customers.customer_id', 'gym_clients.id')
                            ->where('gym_clients.is_client','yes')
                            ->where('business_customers.detail_id', $detail_id)
                            ->whereDate('gym_client_attendances.check_in', '>=', $date_range_start->format('Y-m-d'))
                            ->whereDate('gym_client_attendances.check_in', '<=', $date_range_end->format('Y-m-d'))
                            ->where('gym_client_attendances.client_id',$result_explode[1])->count();
                        $data = [
                            'total' => $client,
                            'start_date' => $date_range_start->format('Y-m-d'),
                            'end_date' => $date_range_end->format('Y-m-d'),
                            'type' => $type,
                            'report' => 'Client Attendance'
                        ];
                    }elseif ($result_explode[0] === 'employee'){
                        if($this->data['common_details']->has_device == 1){
                            $employee = GymClient::leftJoin('gym_client_attendances', 'gym_client_attendances.client_id', 'gym_clients.id')
                            ->leftJoin('business_customers', 'business_customers.customer_id', 'gym_clients.id')
                            ->where('gym_clients.is_client','no')
                            ->where('business_customers.detail_id', $detail_id)
                            ->whereDate('gym_client_attendances.check_in', '>=', $date_range_start->format('Y-m-d'))
                            ->whereDate('gym_client_attendances.check_in', '<=', $date_range_end->format('Y-m-d'))
                            ->where('gym_client_attendances.client_id',$result_explode[1])->count();
                        }else{
                            $employee = Employ::leftJoin('employ_attendances', 'employ_attendances.client_id', 'employes.id')
                            ->whereDate('employ_attendances.check_in', '>=', $date_range_start->format('Y-m-d'))
                            ->whereDate('employ_attendances.check_in', '<=', $date_range_end->format('Y-m-d'))
                            ->where('employes.detail_id', $detail_id)
                            ->where('employ_attendances.client_id',$result_explode[1])->count();

                            $employees = Employ::leftJoin('employ_attendances', 'employ_attendances.client_id', 'employes.id')
                            ->whereDate('employ_attendances.check_in', '>=', $date_range_start->format('Y-m-d'))
                            ->whereDate('employ_attendances.check_in', '<=', $date_range_end->format('Y-m-d'))
                            ->where('employes.detail_id', $detail_id)
                            ->count();
                        }

                        $data = [
                            'total' => $employee,
                            'start_date' => $date_range_start->format('Y-m-d'),
                            'end_date' => $date_range_end->format('Y-m-d'),
                            'type' => $type,
                            'report' => 'Employ Attendance'
                        ];
                    }
                break;
            }
            return Reply::successWithData('Reports Fetched', $data);
        }
    }

    public function ajax_create($id, $start_date, $end_date)
    {
        // $start_date = new Carbon($start_date);
        // $end_date = new Carbon($end_date);
        $start_date = $start_date && strtotime($start_date)
            ? Carbon::parse($start_date)
            : Carbon::now();

        $end_date = $end_date && strtotime($end_date)
            ? Carbon::parse($end_date)
            : Carbon::now();
        $detail_id = $this->data['user']->detail_id;
        switch ($id) {
            case 'employ':
                if($this->data['common_details']->has_device == 1){
                    $data = GymClient::select('first_name', 'middle_name', 'last_name', 'email', 'mobile', 'gender', 'gym_client_attendances.check_in','gym_client_attendances.check_out')
                    ->leftJoin('gym_client_attendances', 'gym_client_attendances.client_id', 'gym_clients.id')
                    ->leftJoin('business_customers', 'business_customers.customer_id', 'gym_clients.id')
                    ->where('gym_clients.is_client','no')
                    ->where('business_customers.detail_id', $detail_id)
                    ->whereDate('gym_client_attendances.check_in', '>=', $start_date->format('Y-m-d'))
                    ->whereDate('gym_client_attendances.check_in', '<=', $end_date->format('Y-m-d'));
                }else{
                    $data = Employ::select('first_name', 'middle_name', 'last_name', 'email', 'mobile', 'gender', 'employ_attendances.check_in','employ_attendances.check_out', 'branch_id', 'employ_attendances.client_id')
                    ->leftJoin('employ_attendances', 'employ_attendances.client_id', 'employes.id')
                    ->whereDate('employ_attendances.check_in', '>=', $start_date->format('Y-m-d'))
                    ->whereDate('employ_attendances.check_in', '<=', $end_date->format('Y-m-d'))
                    ->where('detail_id', $detail_id);
                }

                break;
            case 'client':
                $data = GymClient::select('first_name', 'middle_name', 'last_name', 'email', 'mobile', 'gender', 'gym_client_attendances.check_in','gym_client_attendances.check_out')
                    ->leftJoin('gym_client_attendances', 'gym_client_attendances.client_id', 'gym_clients.id')
                    ->leftJoin('business_customers', 'business_customers.customer_id', 'gym_clients.id')
                    ->where('gym_clients.is_client','yes')
                    ->where('business_customers.detail_id', $detail_id)
                    ->whereDate('gym_client_attendances.check_in', '>=', $start_date->format('Y-m-d'))
                    ->whereDate('gym_client_attendances.check_in', '<=', $end_date->format('Y-m-d'));
                break;
            default:
                $result_explode = explode('|', $id);
                if ($result_explode[0] === 'customer') {
                    $data = GymClient::select('first_name', 'middle_name', 'last_name', 'email', 'mobile', 'gender', 'gym_client_attendances.check_in','gym_client_attendances.check_out')
                        ->leftJoin('gym_client_attendances', 'gym_client_attendances.client_id', 'gym_clients.id')
                        ->leftJoin('business_customers', 'business_customers.customer_id', 'gym_clients.id')
                        ->where('gym_clients.is_client','yes')
                        ->where('business_customers.detail_id', $detail_id)
                        ->whereDate('gym_client_attendances.check_in', '>=', $start_date->format('Y-m-d'))
                        ->whereDate('gym_client_attendances.check_in', '<=', $end_date->format('Y-m-d'))
                        ->where('gym_client_attendances.client_id',$result_explode[1]);

                }elseif ($result_explode[0] === 'employee'){
                    if($this->data['common_details']->has_device == 1){
                        $data = GymClient::select('first_name', 'middle_name', 'last_name', 'email', 'mobile', 'gender', 'gym_client_attendances.check_in','gym_client_attendances.check_out')
                        ->leftJoin('gym_client_attendances', 'gym_client_attendances.client_id', 'gym_clients.id')
                        ->leftJoin('business_customers', 'business_customers.customer_id', 'gym_clients.id')
                        ->where('gym_clients.is_client','no')
                        ->where('business_customers.detail_id', $detail_id)
                        ->whereDate('gym_client_attendances.check_in', '>=', $start_date->format('Y-m-d'))
                        ->whereDate('gym_client_attendances.check_in', '<=', $end_date->format('Y-m-d'))
                        ->where('gym_client_attendances.client_id',$result_explode[1]);
                    }else{
                        $data =  Employ::select('first_name', 'middle_name', 'last_name', 'email', 'mobile', 'gender', 'employ_attendances.check_in','employ_attendances.check_out', 'branch_id', 'employ_attendances.client_id')
                        ->leftJoin('employ_attendances', 'employ_attendances.client_id', 'employes.id')
                        ->whereDate('employ_attendances.check_in', '>=', $start_date->format('Y-m-d'))
                        ->whereDate('employ_attendances.check_in', '<=', $end_date->format('Y-m-d'))
                        ->where('detail_id', $detail_id)
                        ->where('employ_attendances.client_id',$result_explode[1]);
                    }

                }
        }

        return Datatables::of($data)
            ->editColumn('first_name', function ($row) {
                return $row->first_name . ' ' . $row->middle_name . ' ' . $row->last_name;
            })
            ->editColumn('email', function ($row) {
                return '<i class="fa fa-envelope"></i> ' . $row->email;
            })->editColumn('mobile', function ($row) {
                return '<i class="fa fa-mobile"></i> ' . $row->mobile;
            })
            ->editColumn('gender', function ($row) {
                if ($row->gender == 'female') {
                    return '<i class="fa fa-female"></i> Female';
                } else {
                    return '<i class="fa fa-male"></i> Male';
                }
            })
            ->editColumn('check_in', function ($row) {
                return '<i class="fa fa-clock"></i> ' . date('M d , Y  H:i:s a', strtotime($row->check_in));
            })
            ->editColumn('check_out', function ($row) {
                if($row->check_out != null){
                    return '<i class="fa fa-clock"></i> ' . date('M d , Y  H:i:s a', strtotime($row->check_out));
                }else{
                    return '---';
                }
            })
            ->rawColumns(['email','mobile','gender','check_in','check_out'])
            ->make();
    }

    public function downloadAttendanceReport($id, $sd, $ed)
    {
        $start_date = new Carbon($sd);
        $end_date = new Carbon($ed);
        $detail_id = $this->data['user']->detail_id;
        switch ($id) {
            case 'employ':
                if($this->data['common_details']->has_device == 1){
                    $data = GymClient::select('first_name', 'middle_name', 'last_name', 'email', 'mobile', 'gender', 'gym_client_attendances.check_in','gym_client_attendances.check_out')
                    ->leftJoin('gym_client_attendances', 'gym_client_attendances.client_id', 'gym_clients.id')
                    ->leftJoin('business_customers', 'business_customers.customer_id', 'gym_clients.id')
                    ->where('gym_clients.is_client','no')
                    ->where('business_customers.detail_id', $detail_id)
                    ->whereDate('gym_client_attendances.check_in', '>=', $start_date->format('Y-m-d'))
                    ->whereDate('gym_client_attendances.check_in', '<=', $end_date->format('Y-m-d'))->get();
                }else{
                    $data = Employ::select('first_name', 'middle_name', 'last_name', 'email', 'mobile', 'gender', 'employ_attendances.check_in','employ_attendances.check_out', 'branch_id', 'employ_attendances.client_id')
                    ->leftJoin('employ_attendances', 'employ_attendances.client_id', 'employes.id')
                    ->whereDate('employ_attendances.check_in', '>=', $start_date->format('Y-m-d'))
                    ->whereDate('employ_attendances.check_in', '<=', $end_date->format('Y-m-d'))
                    ->where('detail_id', $detail_id)->get();
                }

                break;
            case 'client':
                $data = GymClient::select('first_name', 'middle_name', 'last_name', 'email', 'mobile', 'gender', 'gym_client_attendances.check_in','gym_client_attendances.check_out')
                    ->leftJoin('gym_client_attendances', 'gym_client_attendances.client_id', 'gym_clients.id')
                    ->leftJoin('business_customers', 'business_customers.customer_id', 'gym_clients.id')
                    ->where('gym_clients.is_client','yes')
                    ->where('business_customers.detail_id', $detail_id)
                    ->whereDate('gym_client_attendances.check_in', '>=', $start_date->format('Y-m-d'))
                    ->whereDate('gym_client_attendances.check_in', '<=', $end_date->format('Y-m-d'))->get();
                break;
            default:
                $result_explode = explode('|', $id);
                if ($result_explode[0] === 'customer') {
                    $data = GymClient::select('first_name', 'middle_name', 'last_name', 'email', 'mobile', 'gender', 'gym_client_attendances.check_in','gym_client_attendances.check_out')
                        ->leftJoin('gym_client_attendances', 'gym_client_attendances.client_id', 'gym_clients.id')
                        ->leftJoin('business_customers', 'business_customers.customer_id', 'gym_clients.id')
                        ->where('gym_clients.is_client','yes')
                        ->where('business_customers.detail_id', $detail_id)
                        ->whereDate('gym_client_attendances.check_in', '>=', $start_date->format('Y-m-d'))
                        ->whereDate('gym_client_attendances.check_in', '<=', $end_date->format('Y-m-d'))
                        ->where('gym_client_attendances.client_id',$result_explode[1])->get();

                }elseif ($result_explode[0] === 'employee'){
                    if($this->data['common_details']->has_device == 1){
                        $data = GymClient::select('first_name', 'middle_name', 'last_name', 'email', 'mobile', 'gender', 'gym_client_attendances.check_in','gym_client_attendances.check_out')
                        ->leftJoin('gym_client_attendances', 'gym_client_attendances.client_id', 'gym_clients.id')
                        ->leftJoin('business_customers', 'business_customers.customer_id', 'gym_clients.id')
                        ->where('gym_clients.is_client','no')
                        ->where('business_customers.detail_id', $detail_id)
                        ->whereDate('gym_client_attendances.check_in', '>=', $start_date->format('Y-m-d'))
                        ->whereDate('gym_client_attendances.check_in', '<=', $end_date->format('Y-m-d'))
                        ->where('gym_client_attendances.client_id',$result_explode[1])->get();
                    }else{
                        $data =  Employ::select('first_name', 'middle_name', 'last_name', 'email', 'mobile', 'gender', 'employ_attendances.check_in','employ_attendances.check_out', 'branch_id', 'employ_attendances.client_id')
                        ->leftJoin('employ_attendances', 'employ_attendances.client_id', 'employes.id')
                        ->whereDate('employ_attendances.check_in', '>=', $start_date->format('Y-m-d'))
                        ->whereDate('employ_attendances.check_in', '<=', $end_date->format('Y-m-d'))
                        ->where('detail_id', $detail_id)
                        ->where('employ_attendances.client_id',$result_explode[1])->get();
                    }

                }
            break;
        }
        $getType = explode('|', $id);
        $fileName = $getType[0].'-attendanceReport.pdf';
        $pdf = PDF::loadView('pdf.attendanceReport', compact(['data', 'id', 'sd', 'ed']));
        return $pdf->download($fileName);
    }

    public function downloadExcelAttendanceReport($id,$sd,$ed){
        $sDate = new Carbon($sd);
        $eDate = new Carbon($ed);
        $user = $this->data['user']->detail_id;
        $getType = explode('|', $id);
        $fileName = $getType[0].'-attendanceReport.xls';
        return Excel::download(new AttendanceReportExport($id,$sDate,$eDate,$user),$fileName);

    }
}
