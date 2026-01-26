<?php

namespace App\Exports\Reports;

use App\Models\GymExpense;
use App\Models\GymMembershipPayment;
use App\Models\Income;
use App\Models\LockerPayment;
use App\Models\Payroll;
use App\Models\ProductPayment;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;

class BalanceReportExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($sDate = null,$eDate = null,$type= null,$user = null) {
        $this->sDate = $sDate;
        $this->eDate = $eDate;
        $this->type = $type;
        $this->user = $user;
    }

    public function collection()
    {
        //
    }

    public function view() : View
    {
        if($this->type == 'membership') {
            $data = GymMembershipPayment::leftJoin('gym_client_purchases', 'gym_client_purchases.id', '=', 'gym_membership_payments.purchase_id')
                ->leftJoin('gym_clients', 'gym_clients.id', '=', 'gym_membership_payments.user_id')
                ->leftJoin('gym_memberships', 'gym_memberships.id', '=', 'gym_client_purchases.membership_id')
                ->whereBetween(DB::raw('DATE(gym_membership_payments.payment_date)'), [$this->sDate->format('Y-m-d'), $this->eDate->format('Y-m-d')])
                ->where('gym_membership_payments.detail_id', '=', $this->user)->get();
        }elseif($this->type == 'product') {
            $data = ProductPayment::leftJoin('product_sales', 'product_sales.id', '=', 'product_payments.product_sale_id')
                ->leftJoin('gym_clients', 'gym_clients.id', '=', 'product_sales.client_id')
                ->whereBetween(DB::raw('DATE(product_payments.payment_date)'), [$this->sDate->format('Y-m-d'), $this->eDate->format('Y-m-d')])
                ->where('product_payments.branch_id', '=', $this->user)->get();
        }elseif($this->type == 'payroll') {
            $data = Payroll::whereHas('employes', function ($query) {
                        $query->where('branch_id', $this->user);
                    })
                    ->whereBetween(DB::raw('DATE(created_at)'),[$this->sDate->format('Y-m-d'), $this->eDate->format('Y-m-d')])
                    ->get();
        }elseif($this->type == 'locker'){
            $data = LockerPayment::select('locker_payments.id as pid', 'gym_clients.first_name','gym_clients.middle_name', 'gym_clients.last_name', 'payment_amount','payment_source', 'payment_date', 'payment_id','reservation_id','lockers.locker_num')
            ->leftJoin('locker_reservations', 'locker_reservations.id', '=', 'locker_payments.reservation_id')
            ->leftJoin('gym_clients', 'gym_clients.id', '=', 'locker_payments.client_id')
            ->leftJoin('lockers', 'lockers.id', '=', 'locker_reservations.locker_id')
            ->whereBetween(DB::raw('DATE(locker_payments.payment_date)'), [$this->sDate->format('Y-m-d'), $this->eDate->format('Y-m-d')])
                ->where('locker_payments.detail_id', '=', $this->user)->get();
        }elseif($this->type == 'income'){
            $data = Income::whereBetween(DB::raw('DATE(purchase_date)'), [$this->sDate->format('Y-m-d'), $this->eDate->format('Y-m-d')])
            ->where('detail_id', $this->user)->get();
        }else{
            $data = GymExpense::whereBetween(DB::raw('DATE(purchase_date)'), [$this->sDate->format('Y-m-d'), $this->eDate->format('Y-m-d')])
                ->where('detail_id', $this->user)->get();
        }
        return view('gym-admin.reports.balance.excel',['data'=>$data,'type'=>$this->type]);
    }
}
