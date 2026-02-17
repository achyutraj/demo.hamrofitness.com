<?php

namespace App\Exports\Reports;

use App\Models\GymMembershipPayment;
use App\Models\GymPurchase;
use App\Models\LockerPayment;
use App\Models\LockerReservation;
use App\Models\ProductPayment;
use App\Models\ProductSales;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;

class FinanceReportExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($id = null,$sDate = null,$eDate = null,$user = null,$payment = null) {
        $this->id = $id;
        $this->sDate = $sDate;
        $this->eDate = $eDate;
        $this->user = $user;
        $this->payment = $payment;
    }

    public function collection()
    {
        //
    }

    public function view() : View
    {
        $payment = null;
        if ($this->id == 'all') {
            $query = GymMembershipPayment::select(
                'first_name',
                'middle_name',
                'last_name',
                'gym_membership_payments.payment_amount',
                'gym_membership_payments.payment_source',
                'gym_membership_payments.payment_date', 'gym_membership_payments.remarks')->leftJoin('gym_client_purchases', function ($join) {
                    $join->on('gym_membership_payments.purchase_id', '=', 'gym_client_purchases.id')
                        ->whereNotNull('gym_membership_payments.purchase_id');
                })
                ->leftJoin('gym_clients', 'gym_clients.id', '=', 'gym_membership_payments.user_id')
                ->where('gym_membership_payments.detail_id', $this->user);
                if($this->payment !== 'all'){
                    $query->where('gym_membership_payments.payment_source', $this->payment);
                }
               $payment = $query->whereBetween('payment_date', [$this->sDate, $this->eDate])->get();
        } else if ($this->id == 'allProduct') {
            $query = ProductPayment::select(
                'first_name',
                'middle_name',
                'last_name',
                'product_payments.payment_amount',
                'product_payments.payment_source',
                'product_payments.payment_date', 'product_payments.payment_date', 'product_payments.remarks')
                ->leftJoin('product_sales', function ($join) {
                    $join->on('product_payments.product_sale_id', '=', 'product_sales.id')
                        ->whereNotNull('product_payments.product_sale_id');
                })
                ->leftJoin('gym_clients', 'gym_clients.id', '=', 'product_payments.user_id')
                ->where('product_payments.branch_id',  $this->user);
                if($this->payment !== 'all'){
                    $query->where('product_payments.payment_source', $this->payment);
                }
                $payment = $query->whereBetween('payment_date', [$this->sDate, $this->eDate])->get();
        } else if ($this->id == 'dueProducts') {
            $payment = ProductSales::select(
                'product_sales.id',
                'first_name',
                'middle_name',
                'last_name',
                'product_sales.customer_type',
                'total_amount as amount_to_be_paid',
                'paid_amount',
                'product_sales.next_payment_date'
            )
                ->leftJoin('gym_clients', 'gym_clients.id', '=', 'product_sales.client_id')
                ->whereBetween('next_payment_date', [$this->sDate, $this->eDate])
                ->where('payment_required', '=', 'yes')
                ->where('product_sales.branch_id', '=', $this->user)->get();
        } else if($this->id == 'lockerPayments'){
            $query = LockerPayment::select(
                'first_name',
                'middle_name',
                'last_name',
                'locker_payments.payment_amount',
                'locker_payments.payment_source',
                'locker_payments.payment_date', 'locker_payments.remarks')
                ->leftJoin('locker_reservations', function ($join) {
                    $join->on('locker_payments.reservation_id', '=', 'locker_reservations.id')
                        ->whereNotNull('locker_payments.reservation_id');
                })
                ->leftJoin('gym_clients', 'gym_clients.id', '=', 'locker_payments.client_id')
                ->where('locker_payments.detail_id', $this->user);
                if($this->payment !== 'all'){
                    $query->where('locker_payments.payment_source', $this->payment);
                }
               $payment = $query->whereBetween('payment_date', [$this->sDate, $this->eDate])->get();
        } else if($this->id == 'lockerDues'){

            $payment = LockerReservation::select(
                'locker_reservations.id',
                'first_name',
                'middle_name',
                'last_name',
                'amount_to_be_paid',
                'paid_amount',
                'next_payment_date', 'locker_reservations.remarks'
            )->leftJoin('gym_clients', 'gym_clients.id', '=', 'locker_reservations.client_id')
                ->whereBetween('next_payment_date', [$this->sDate, $this->eDate])
                 ->where('payment_required', '=', 'yes')
                ->where('locker_reservations.detail_id', '=', $this->user)->get();

        } else {
            $payment = GymPurchase::select(
                'gym_client_purchases.id',
                'first_name',
                'middle_name',
                'last_name',
                'amount_to_be_paid',
                'paid_amount',
                'next_payment_date', 'gym_client_purchases.remarks'
            )->leftJoin('gym_clients', 'gym_clients.id', '=', 'gym_client_purchases.client_id')
                ->whereBetween('next_payment_date', [$this->sDate, $this->eDate])
                 ->where('payment_required', '=', 'yes')
                ->where('gym_client_purchases.detail_id', '=', $this->user)->get();

        }
        return view('gym-admin.reports.finance.excel',['payment'=>$payment,'id'=>$this->id]);
    }
}
