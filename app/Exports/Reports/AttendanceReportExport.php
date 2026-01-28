<?php

namespace App\Exports\Reports;

use App\Models\Employ;
use App\Models\GymClient;
use App\Models\CommonDetails;
use App\Models\GymPurchase;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class AttendanceReportExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($id= null,$sDate = null,$eDate = null,$user = null) {
        $this->sDate = $sDate;
        $this->eDate = $eDate;
        $this->id = $id;
        $this->user = $user;
    }

    public function collection()
    {
        //
    }
    public function view() : View
    {
        // check device exists or not for employee
        $device = CommonDetails::where('id',$this->user)->first();
        switch ($this->id) {
            case 'employ':
                if($device->has_device == 1){
                    $data = GymClient::select('first_name', 'middle_name', 'last_name', 'email', 'mobile', 'gender', 'gym_client_attendances.check_in')
                    ->leftJoin('gym_client_attendances', 'gym_client_attendances.client_id', 'gym_clients.id')
                    ->leftJoin('business_customers', 'business_customers.customer_id', 'gym_clients.id')
                    ->where('gym_clients.is_client',  'no')
                    ->where('business_customers.detail_id',  $this->user)
                    ->whereDate('gym_client_attendances.check_in', '>=', $this->sDate->format('Y-m-d'))
                    ->whereDate('gym_client_attendances.check_in', '<=', $this->eDate->format('Y-m-d'))->get();
                }else{
                    $data = Employ::select('first_name', 'middle_name', 'last_name', 'email', 'mobile', 'gender', 'employ_attendances.check_in', 'branch_id', 'employ_attendances.client_id')
                    ->leftJoin('employ_attendances', 'employ_attendances.client_id', 'employes.id')
                    ->whereDate('employ_attendances.check_in', '>=', $this->sDate->format('Y-m-d'))
                    ->whereDate('employ_attendances.check_in', '<=', $this->eDate->format('Y-m-d'))
                    ->where('detail_id',  $this->user)->get();
                }

                break;
            case 'client':
                $data = GymClient::select('first_name', 'middle_name', 'last_name', 'email', 'mobile', 'gender', 'gym_client_attendances.check_in')
                    ->leftJoin('gym_client_attendances', 'gym_client_attendances.client_id', 'gym_clients.id')
                    ->leftJoin('business_customers', 'business_customers.customer_id', 'gym_clients.id')
                    ->where('gym_clients.is_client',  'yes')
                    ->where('business_customers.detail_id',  $this->user)
                    ->whereDate('gym_client_attendances.check_in', '>=', $this->sDate->format('Y-m-d'))
                    ->whereDate('gym_client_attendances.check_in', '<=', $this->eDate->format('Y-m-d'))->get();
                break;
            default:
                $result_explode = explode('|', $this->id);
                if ($result_explode[0] === 'customer') {
                    $data = GymClient::select('first_name', 'middle_name', 'last_name', 'email', 'mobile', 'gender', 'gym_client_attendances.check_in')
                        ->leftJoin('gym_client_attendances', 'gym_client_attendances.client_id', 'gym_clients.id')
                        ->leftJoin('business_customers', 'business_customers.customer_id', 'gym_clients.id')
                        ->where('gym_clients.is_client',  'yes')
                        ->where('business_customers.detail_id',  $this->user)
                        ->whereDate('gym_client_attendances.check_in', '>=', $this->sDate->format('Y-m-d'))
                        ->whereDate('gym_client_attendances.check_in', '<=', $this->eDate->format('Y-m-d'))
                        ->where('gym_client_attendances.client_id',$result_explode[1])->get();

                }elseif ($result_explode[0] === 'employee'){
                    if($device->has_device == 1){
                        $data = GymClient::select('first_name', 'middle_name', 'last_name', 'email', 'mobile', 'gender', 'gym_client_attendances.check_in')
                        ->leftJoin('gym_client_attendances', 'gym_client_attendances.client_id', 'gym_clients.id')
                        ->leftJoin('business_customers', 'business_customers.customer_id', 'gym_clients.id')
                        ->where('gym_clients.is_client',  'no')
                        ->where('business_customers.detail_id',  $this->user)
                        ->whereDate('gym_client_attendances.check_in', '>=', $this->sDate->format('Y-m-d'))
                        ->whereDate('gym_client_attendances.check_in', '<=', $this->eDate->format('Y-m-d'))
                        ->where('gym_client_attendances.client_id',$result_explode[1])->get();

                    }else{
                    $data =  Employ::select('first_name', 'middle_name', 'last_name', 'email', 'mobile', 'gender', 'employ_attendances.check_in', 'branch_id', 'employ_attendances.client_id')
                        ->leftJoin('employ_attendances', 'employ_attendances.client_id', 'employes.id')
                        ->whereDate('employ_attendances.check_in', '>=', $this->sDate->format('Y-m-d'))
                        ->whereDate('employ_attendances.check_in', '<=', $this->eDate->format('Y-m-d'))
                        ->where('detail_id',  $this->user)
                        ->where('employ_attendances.client_id',$result_explode[1])->get();
                    }
                }
                break;
            case 'regular_active_client':
                // Get regular active clients for Excel (≥40% attendance rate)
                $totalDaysInRange = $this->sDate->diffInDays($this->eDate) + 1;
                
                $activePurchases = GymPurchase::select('client_id', 'start_date', 'expires_on')
                    ->where('detail_id', $this->user)
                    ->where(function($query) {
                        $query->where(function($q) {
                            $q->where('start_date', '<=', $this->eDate->format('Y-m-d'))
                              ->where('expires_on', '>=', $this->sDate->format('Y-m-d'));
                        });
                    })
                    ->groupBy('client_id')
                    ->get();
                
                $regularClients = [];
                foreach ($activePurchases as $purchase) {
                    $attendedDays = \DB::table('gym_client_attendances')
                        ->where('client_id', $purchase->client_id)
                        ->whereDate('check_in', '>=', $this->sDate->format('Y-m-d'))
                        ->whereDate('check_in', '<=', $this->eDate->format('Y-m-d'))
                        ->selectRaw('COUNT(DISTINCT DATE(check_in)) as total')
                        ->value('total') ?? 0;
                    
                    $attendanceRate = $totalDaysInRange > 0 ? ($attendedDays / $totalDaysInRange) * 100 : 0;
                    
                    if ($attendanceRate >= 40) {
                        $client = GymClient::select('first_name', 'middle_name', 'last_name', 'email', 'mobile', 'gender')
                            ->where('id', $purchase->client_id)->first();
                        
                        if ($client) {
                            $client->present_days = $attendedDays;
                            $client->total_days_in_range = $totalDaysInRange;
                            $client->last_attendance = \App\Models\GymClientAttendance::where('client_id', $purchase->client_id)
                                ->whereDate('check_in', '>=', $this->sDate->format('Y-m-d'))
                                ->whereDate('check_in', '<=', $this->eDate->format('Y-m-d'))
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
                // Get irregular active clients for Excel
                $activePurchases = GymPurchase::select('client_id', 'start_date', 'expires_on')
                    ->where('expires_on','>=',now())
                    ->where('detail_id', $this->user)
                    ->groupBy('client_id')
                    ->get();
                
                $irregularClients = [];
                foreach ($activePurchases as $purchase) {
                    $totalSubDays = \Carbon\Carbon::parse($purchase->start_date)->diffInDays(\Carbon\Carbon::parse($purchase->expires_on)) + 1;
                    $attendedDays = \DB::table('gym_client_attendances')
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
                            $client->last_attendance = \App\Models\GymClientAttendance::where('client_id', $purchase->client_id)
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
                // Get clients with highest attendance for Excel
                $data = GymClient::selectRaw('gym_clients.first_name, gym_clients.middle_name, gym_clients.last_name, gym_clients.email, gym_clients.mobile, gym_clients.gender, COUNT(DISTINCT DATE(gym_client_attendances.check_in)) as present_days, MAX(gym_client_attendances.check_in) as last_attendance')
                    ->leftJoin('gym_client_attendances', 'gym_client_attendances.client_id', 'gym_clients.id')
                    ->leftJoin('business_customers', 'business_customers.customer_id', 'gym_clients.id')
                    ->where('gym_clients.is_client','yes')
                    ->where('business_customers.detail_id', $this->user)
                    ->whereDate('gym_client_attendances.check_in', '>=', $this->sDate->format('Y-m-d'))
                    ->whereDate('gym_client_attendances.check_in', '<=', $this->eDate->format('Y-m-d'))
                    ->groupBy('gym_clients.id', 'gym_clients.first_name', 'gym_clients.middle_name', 'gym_clients.last_name', 'gym_clients.email', 'gym_clients.mobile', 'gym_clients.gender')
                    ->orderByDesc('present_days')
                    ->get();
                break;
        }
        return view('gym-admin.reports.attendance.excel',['data'=>$data,'id'=>$this->id]);
    }
}
