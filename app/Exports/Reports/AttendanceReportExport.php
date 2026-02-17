<?php

namespace App\Exports\Reports;

use App\Models\Employ;
use App\Models\GymClient;
use App\Models\CommonDetails;
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
        }
        return view('gym-admin.reports.attendance.excel',['data'=>$data]);
    }
}
