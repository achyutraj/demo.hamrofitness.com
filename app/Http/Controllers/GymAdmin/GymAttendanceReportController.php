<?php

namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use App\Exports\Reports\AttendanceReportExport;
use App\Models\Employ;
use App\Models\GymClient;
use App\Models\GymClientAttendance;
use App\Models\GymPurchase;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
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
                case 'regular_active_client':
                    // Report for regular active subscription clients
                    // Calculate total days in the selected date range
                    $totalDaysInRange = $date_range_start->diffInDays($date_range_end) + 1;

                    // Get clients who were active during the date range (subscription overlaps with date range)
                    $activePurchases = GymPurchase::select('client_id', 'start_date', 'expires_on')
                        ->where('detail_id', $detail_id)
                        ->where(function($query) use ($date_range_start, $date_range_end) {
                            // Subscription overlaps with date range
                            $query->where(function($q) use ($date_range_start, $date_range_end) {
                                $q->where('start_date', '<=', $date_range_end->format('Y-m-d'))
                                  ->where('expires_on', '>=', $date_range_start->format('Y-m-d'));
                            });
                        })
                        ->groupBy('client_id')
                        ->get();

                    $regularCount = 0;
                    foreach ($activePurchases as $purchase) {
                        // Count attendance days in the date range
                        $attendedDays = DB::table('gym_client_attendances')
                            ->where('client_id', $purchase->client_id)
                            ->whereDate('check_in', '>=', $date_range_start->format('Y-m-d'))
                            ->whereDate('check_in', '<=', $date_range_end->format('Y-m-d'))
                            ->selectRaw('COUNT(DISTINCT DATE(check_in)) as total')
                            ->value('total') ?? 0;

                        // Calculate attendance rate based on date range
                        $attendanceRate = $totalDaysInRange > 0 ? ($attendedDays / $totalDaysInRange) * 100 : 0;

                        // Regular clients have ≥40% attendance
                        if ($attendanceRate >= 40) {
                            $regularCount++;
                        }
                    }

                    $data = [
                        'total' => $regularCount,
                        'start_date' => $date_range_start->format('Y-m-d'),
                        'end_date' => $date_range_end->format('Y-m-d'),
                        'type' => $type,
                        'report' => 'Regular Active Clients (≥40% Attendance)'
                    ];
                    break;
                case 'irregular_active_client':
                    // Report for irregular active subscription clients (high absenteeism)
                    // Get active clients with their subscription details
                    $activePurchases = GymPurchase::select('client_id', 'start_date', 'expires_on')
                        ->where('expires_on','>=',now())
                        ->where('detail_id', $detail_id)
                        ->groupBy('client_id')
                        ->get();

                    $irregularCount = 0;
                    foreach ($activePurchases as $purchase) {
                        // Calculate total subscription days
                        $totalSubDays = Carbon::parse($purchase->start_date)->diffInDays(Carbon::parse($purchase->expires_on)) + 1;

                        // Get attended days for this client
                        $attendedDays = DB::table('gym_client_attendances')
                            ->where('client_id', $purchase->client_id)
                            ->whereDate('check_in', '>=', $purchase->start_date)
                            ->whereDate('check_in', '<=', $purchase->expires_on)
                            ->selectRaw('COUNT(DISTINCT DATE(check_in)) as total')
                            ->value('total') ?? 0;

                        // Calculate absenteeism rate
                        $attendanceRate = $totalSubDays > 0 ? ($attendedDays / $totalSubDays) * 100 : 0;

                        // Irregular if attendance rate < 40%
                        if ($attendanceRate < 40) {
                            $irregularCount++;
                        }
                    }

                    $data = [
                        'total' => $irregularCount,
                        'start_date' => $date_range_start->format('Y-m-d'),
                        'end_date' => $date_range_end->format('Y-m-d'),
                        'type' => $type,
                        'report' => 'Irregular Active Clients (High Absenteeism)'
                    ];
                    break;
                case 'high_attendance':
                    // Report for clients with highest attendance days
                    $minPresentDays = request()->get('min_present_days', 10);

                    $highAttendanceClients = GymClient::selectRaw('gym_clients.id, COUNT(DISTINCT DATE(gym_client_attendances.check_in)) as present_days')
                        ->leftJoin('gym_client_attendances', 'gym_client_attendances.client_id', 'gym_clients.id')
                        ->leftJoin('business_customers', 'business_customers.customer_id', 'gym_clients.id')
                        ->where('gym_clients.is_client','yes')
                        ->where('business_customers.detail_id', $detail_id)
                        ->whereDate('gym_client_attendances.check_in', '>=', $date_range_start->format('Y-m-d'))
                        ->whereDate('gym_client_attendances.check_in', '<=', $date_range_end->format('Y-m-d'))
                        ->groupBy('gym_clients.id')
                        ->havingRaw('COUNT(DISTINCT DATE(gym_client_attendances.check_in)) >= ?', [$minPresentDays])
                        ->get()
                        ->count();

                    $data = [
                        'total' => $highAttendanceClients,
                        'start_date' => $date_range_start->format('Y-m-d'),
                        'end_date' => $date_range_end->format('Y-m-d'),
                        'type' => $type,
                        'min_present_days' => $minPresentDays,
                        'report' => 'High Attendance Clients'
                    ];
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
                break;
            case 'regular_active_client':
                // Get regular active clients with their attendance (≥40% attendance rate)
                $totalDaysInRange = $start_date->diffInDays($end_date) + 1;

                // Get clients who were active during the date range
                $activePurchases = GymPurchase::select('client_id', 'start_date', 'expires_on')
                    ->where('detail_id', $detail_id)
                    ->where(function($query) use ($start_date, $end_date) {
                        $query->where(function($q) use ($start_date, $end_date) {
                            $q->where('start_date', '<=', $end_date->format('Y-m-d'))
                              ->where('expires_on', '>=', $start_date->format('Y-m-d'));
                        });
                    })
                    ->groupBy('client_id')
                    ->get();

                $regularClients = [];
                foreach ($activePurchases as $purchase) {
                    // Count attendance days
                    $attendedDays = DB::table('gym_client_attendances')
                        ->where('client_id', $purchase->client_id)
                        ->whereDate('check_in', '>=', $start_date->format('Y-m-d'))
                        ->whereDate('check_in', '<=', $end_date->format('Y-m-d'))
                        ->selectRaw('COUNT(DISTINCT DATE(check_in)) as total')
                        ->value('total') ?? 0;

                    // Calculate attendance rate
                    $attendanceRate = $totalDaysInRange > 0 ? ($attendedDays / $totalDaysInRange) * 100 : 0;

                    // Only include clients with ≥40% attendance (regular)
                    if ($attendanceRate >= 40) {
                        $client = GymClient::select('first_name', 'middle_name', 'last_name', 'email', 'mobile', 'gender')
                            ->where('id', $purchase->client_id)
                            ->first();

                        if ($client) {
                            $client->present_days = $attendedDays;
                            $client->total_days_in_range = $totalDaysInRange;
                            $client->last_attendance = GymClientAttendance::where('client_id', $purchase->client_id)
                                ->whereDate('check_in', '>=', $start_date->format('Y-m-d'))
                                ->whereDate('check_in', '<=', $end_date->format('Y-m-d'))
                                ->orderBy('check_in', 'desc')
                                ->value('check_in');
                            $regularClients[] = $client;
                        }
                    }
                }

                // Sort by present days descending
                usort($regularClients, function($a, $b) {
                    return $b->present_days - $a->present_days;
                });

                $data = collect($regularClients);
                break;
            case 'irregular_active_client':
                // Get irregular active clients (high absenteeism)
                $activePurchases = GymPurchase::select('gym_client_purchases.client_id', 'gym_client_purchases.start_date', 'gym_client_purchases.expires_on')
                    ->where('expires_on','>=',now())
                    ->where('detail_id', $detail_id)
                    ->groupBy('client_id')
                    ->get();

                $irregularClients = [];
                foreach ($activePurchases as $purchase) {
                    // Calculate total subscription days
                    $totalSubDays = Carbon::parse($purchase->start_date)->diffInDays(Carbon::parse($purchase->expires_on)) + 1;

                    // Get attended days for this client
                    $attendedDays = DB::table('gym_client_attendances')
                        ->where('client_id', $purchase->client_id)
                        ->whereDate('check_in', '>=', $purchase->start_date)
                        ->whereDate('check_in', '<=', $purchase->expires_on)
                        ->selectRaw('COUNT(DISTINCT DATE(check_in)) as total')
                        ->value('total') ?? 0;

                    // Calculate absent days and rate
                    $absentDays = $totalSubDays - $attendedDays;
                    $attendanceRate = $totalSubDays > 0 ? ($attendedDays / $totalSubDays) * 100 : 0;

                    // Irregular if attendance rate < 40%
                    if ($attendanceRate < 40) {
                        $client = GymClient::select('first_name', 'middle_name', 'last_name', 'email', 'mobile', 'gender')
                            ->where('id', $purchase->client_id)
                            ->first();

                        if ($client) {
                            $client->present_days = $attendedDays;
                            $client->absent_days = $absentDays;
                            $client->total_subscription_days = $totalSubDays;
                            $client->last_attendance = GymClientAttendance::where('client_id', $purchase->client_id)
                                ->whereDate('check_in', '>=', $purchase->start_date)
                                ->orderBy('check_in', 'desc')
                                ->value('check_in');
                            $irregularClients[] = $client;
                        }
                    }
                }

                $data = collect($irregularClients);
                break;
            case 'high_attendance':
                // Get clients with highest attendance
                $minPresentDays = request()->get('min_present_days', 10);

                $data = GymClient::selectRaw('gym_clients.first_name, gym_clients.middle_name, gym_clients.last_name, gym_clients.email, gym_clients.mobile, gym_clients.gender, COUNT(DISTINCT DATE(gym_client_attendances.check_in)) as present_days, MAX(gym_client_attendances.check_in) as last_attendance')
                    ->leftJoin('gym_client_attendances', 'gym_client_attendances.client_id', 'gym_clients.id')
                    ->leftJoin('business_customers', 'business_customers.customer_id', 'gym_clients.id')
                    ->where('gym_clients.is_client','yes')
                    ->where('business_customers.detail_id', $detail_id)
                    ->whereDate('gym_client_attendances.check_in', '>=', $start_date->format('Y-m-d'))
                    ->whereDate('gym_client_attendances.check_in', '<=', $end_date->format('Y-m-d'))
                    ->groupBy('gym_clients.id', 'gym_clients.first_name', 'gym_clients.middle_name', 'gym_clients.last_name', 'gym_clients.email', 'gym_clients.mobile', 'gym_clients.gender')
                    ->havingRaw('COUNT(DISTINCT DATE(gym_client_attendances.check_in)) >= ?', [$minPresentDays])
                    ->orderByDesc('present_days');
                break;
        }

        // Handle special DataTables formatting for new report types
        if ($id == 'regular_active_client' || $id == 'irregular_active_client' || $id == 'high_attendance') {
            return Datatables::of($data)
                ->editColumn('first_name', function ($row) {
                    return $row->first_name . ' ' . $row->middle_name . ' ' . $row->last_name;
                })
                ->editColumn('email', function ($row) {
                    return '<i class="fa fa-envelope"></i> ' . $row->email;
                })
                ->editColumn('mobile', function ($row) {
                    return '<i class="fa fa-mobile"></i> ' . $row->mobile;
                })
                ->editColumn('gender', function ($row) {
                    if ($row->gender == 'female') {
                        return '<i class="fa fa-female"></i> Female';
                    } else {
                        return '<i class="fa fa-male"></i> Male';
                    }
                })
                ->addColumn('present_days', function ($row) {
                    return '<span class="badge badge-success">' . ($row->present_days ?? 0) . ' days</span>';
                })
                ->addColumn('absent_days', function ($row) use ($id) {
                    if ($id == 'irregular_active_client') {
                        return '<span class="badge badge-danger">' . ($row->absent_days ?? 0) . ' days</span>';
                    }
                    return '---';
                })
                ->addColumn('last_attendance', function ($row) {
                    if($row->last_attendance != null){
                        return '<i class="fa fa-clock"></i> ' . date('M d, Y', strtotime($row->last_attendance));
                    }
                    return '---';
                })
                ->addColumn('status', function ($row) use ($start_date, $end_date, $id) {
                    if ($id == 'irregular_active_client') {
                        // For irregular report, calculate based on subscription period
                        $totalDays = $row->total_subscription_days ?? 1;
                        $attendanceRate = ($row->present_days / $totalDays) * 100;
                    } else {
                        // For other reports, use date range
                        $totalDays = $start_date->diffInDays($end_date) + 1;
                        $attendanceRate = (($row->present_days ?? 0) / $totalDays) * 100;
                    }

                    if ($attendanceRate >= 70) {
                        return '<span class="badge badge-success">Regular</span>';
                    } elseif ($attendanceRate >= 40) {
                        return '<span class="badge badge-warning">Moderate</span>';
                    } else {
                        return '<span class="badge badge-danger">Irregular</span>';
                    }
                })
                ->rawColumns(['email','mobile','gender','present_days','absent_days','last_attendance','status'])
                ->make();
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
            case 'regular_active_client':
                // Get regular active clients for PDF (≥40% attendance rate)
                $totalDaysInRange = $start_date->diffInDays($end_date) + 1;

                $activePurchases = GymPurchase::select('client_id', 'start_date', 'expires_on')
                    ->where('detail_id', $detail_id)
                    ->where(function($query) use ($start_date, $end_date) {
                        $query->where(function($q) use ($start_date, $end_date) {
                            $q->where('start_date', '<=', $end_date->format('Y-m-d'))
                              ->where('expires_on', '>=', $start_date->format('Y-m-d'));
                        });
                    })
                    ->groupBy('client_id')
                    ->get();

                $regularClients = [];
                foreach ($activePurchases as $purchase) {
                    $attendedDays = DB::table('gym_client_attendances')
                        ->where('client_id', $purchase->client_id)
                        ->whereDate('check_in', '>=', $start_date->format('Y-m-d'))
                        ->whereDate('check_in', '<=', $end_date->format('Y-m-d'))
                        ->selectRaw('COUNT(DISTINCT DATE(check_in)) as total')
                        ->value('total') ?? 0;

                    $attendanceRate = $totalDaysInRange > 0 ? ($attendedDays / $totalDaysInRange) * 100 : 0;

                    if ($attendanceRate >= 40) {
                        $client = GymClient::select('first_name', 'middle_name', 'last_name', 'email', 'mobile', 'gender')
                            ->where('id', $purchase->client_id)->first();

                        if ($client) {
                            $client->present_days = $attendedDays;
                            $client->total_days_in_range = $totalDaysInRange;
                            $client->last_attendance = GymClientAttendance::where('client_id', $purchase->client_id)
                                ->whereDate('check_in', '>=', $start_date->format('Y-m-d'))
                                ->whereDate('check_in', '<=', $end_date->format('Y-m-d'))
                                ->orderBy('check_in', 'desc')
                                ->value('check_in');
                            $regularClients[] = $client;
                        }
                    }
                }

                usort($regularClients, function($a, $b) {
                    return $b->present_days - $a->present_days;
                });

                $data = collect($regularClients);
                break;
            case 'irregular_active_client':
                // Get irregular active clients for PDF
                $activePurchases = GymPurchase::select('client_id', 'start_date', 'expires_on')
                    ->where('expires_on','>=',now())
                    ->where('detail_id', $detail_id)
                    ->groupBy('client_id')
                    ->get();

                $irregularClients = [];
                foreach ($activePurchases as $purchase) {
                    $totalSubDays = Carbon::parse($purchase->start_date)->diffInDays(Carbon::parse($purchase->expires_on)) + 1;
                    $attendedDays = DB::table('gym_client_attendances')
                        ->where('client_id', $purchase->client_id)
                        ->whereDate('check_in', '>=', $purchase->start_date)
                        ->whereDate('check_in', '<=', $purchase->expires_on)
                        ->selectRaw('COUNT(DISTINCT DATE(check_in)) as total')
                        ->value('total') ?? 0;

                    $absentDays = $totalSubDays - $attendedDays;
                    $attendanceRate = $totalSubDays > 0 ? ($attendedDays / $totalSubDays) * 100 : 0;

                    if ($attendanceRate < 40) {
                        $client = GymClient::select('first_name', 'middle_name', 'last_name', 'email', 'mobile', 'gender')
                            ->where('id', $purchase->client_id)->first();

                        if ($client) {
                            $client->present_days = $attendedDays;
                            $client->absent_days = $absentDays;
                            $client->total_subscription_days = $totalSubDays;
                            $client->last_attendance = GymClientAttendance::where('client_id', $purchase->client_id)
                                ->whereDate('check_in', '>=', $purchase->start_date)
                                ->orderBy('check_in', 'desc')
                                ->value('check_in');
                            $irregularClients[] = $client;
                        }
                    }
                }
                $data = collect($irregularClients);
                break;
            case 'high_attendance':
                // Get clients with highest attendance for PDF
                $data = GymClient::selectRaw('gym_clients.first_name, gym_clients.middle_name, gym_clients.last_name, gym_clients.email, gym_clients.mobile, gym_clients.gender, COUNT(DISTINCT DATE(gym_client_attendances.check_in)) as present_days, MAX(gym_client_attendances.check_in) as last_attendance')
                    ->leftJoin('gym_client_attendances', 'gym_client_attendances.client_id', 'gym_clients.id')
                    ->leftJoin('business_customers', 'business_customers.customer_id', 'gym_clients.id')
                    ->where('gym_clients.is_client','yes')
                    ->where('business_customers.detail_id', $detail_id)
                    ->whereDate('gym_client_attendances.check_in', '>=', $start_date->format('Y-m-d'))
                    ->whereDate('gym_client_attendances.check_in', '<=', $end_date->format('Y-m-d'))
                    ->groupBy('gym_clients.id', 'gym_clients.first_name', 'gym_clients.middle_name', 'gym_clients.last_name', 'gym_clients.email', 'gym_clients.mobile', 'gym_clients.gender')
                    ->orderByDesc('present_days')
                    ->get();
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
