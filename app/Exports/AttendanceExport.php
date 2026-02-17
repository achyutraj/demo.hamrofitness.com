<?php

namespace App\Exports;

use App\Models\GymClientAttendance;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class AttendanceExport implements FromView
{
    public function __construct($user = null) {
        $this->user = $user;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
    }

    public function view() : View
    {
        $attendance = GymClientAttendance::select('gym_client_attendances.status','gym_clients.first_name','gym_clients.middle_name','gym_clients.last_name','gym_clients.gender','gym_clients.mobile', 'gym_clients.email')
            ->selectRaw("DATE_FORMAT(check_in, '%d-%M-%y %h:%i %a ') as check_in,DATE_FORMAT(check_out, '%d-%M-%y %h:%i %a ') as check_out ")
            ->join('gym_clients', 'gym_clients.id', '=', 'gym_client_attendances.client_id')
            ->join('business_customers', 'business_customers.customer_id', '=', 'gym_clients.id')
            ->where('business_customers.detail_id', $this->user)
            ->orderBy('check_in', 'desc')
            ->get();
        return view('gym-admin.excel.attendance',['attendance'=>$attendance]);
    }
}
