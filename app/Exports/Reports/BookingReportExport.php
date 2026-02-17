<?php

namespace App\Exports\Reports;

use App\Models\GymMembershipExtend;
use App\Models\GymMembershipFreeze;
use App\Models\GymPurchase;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class BookingReportExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($id = null,$sDate = null,$eDate = null,$user = null, $membership = null) {
        $this->id = $id;
        $this->sDate = $sDate;
        $this->eDate = $eDate;
        $this->user = $user;
        $this->membership = $membership;

    }
    public function collection()
    {
        //
    }

    public function view() : View
    {
        $booking = null;
        $membership = $this->membership;
        switch ($this->id){
            case 'expire':
                $booking = GymPurchase::select(
                    'gym_clients.first_name','gym_clients.middle_name',
                    'gym_clients.last_name',
                    'amount_to_be_paid',
                    'gym_memberships.title as membership',
                    'gym_client_purchases.start_date','gym_client_purchases.expires_on')
                    ->leftJoin('gym_clients','gym_clients.id','=','gym_client_purchases.client_id')
                    ->leftJoin('gym_memberships','gym_memberships.id','=','gym_client_purchases.membership_id')
                    ->whereBetween('expires_on',[$this->sDate,$this->eDate])
                    ->where('gym_client_purchases.detail_id','=',$this->user);
               
                break;
            case 'renew':
                $booking = GymPurchase::select(
                    'gym_clients.first_name','gym_clients.middle_name',
                    'gym_clients.last_name',
                    'amount_to_be_paid',
                    'gym_memberships.title as membership',
                    'gym_client_purchases.start_date','gym_client_purchases.expires_on')
                    ->leftJoin('gym_clients','gym_clients.id','=','gym_client_purchases.client_id')
                    ->leftJoin('gym_memberships','gym_memberships.id','=','gym_client_purchases.membership_id')
                    ->where('gym_client_purchases.is_renew',1)
                    ->whereBetween('purchase_date',[$this->sDate,$this->eDate])
                    ->where('gym_client_purchases.detail_id',$this->user);
            
                break;
            case 'freeze':
                $booking = GymMembershipFreeze::select(
                    'gym_clients.first_name','gym_clients.middle_name',
                    'gym_clients.last_name',
                    'amount_to_be_paid',
                    'gym_memberships.title as membership',
                    'gym_client_purchases.start_date','gym_client_purchases.expires_on')
                    ->leftJoin('gym_client_purchases','gym_client_purchases.id','=','gym_membership_freeze.purchase_id')
                    ->leftJoin('gym_clients','gym_clients.id','=','gym_client_purchases.client_id')
                    ->leftJoin('gym_memberships','gym_memberships.id','=','gym_client_purchases.membership_id')
                    ->whereBetween('gym_membership_freeze.start_date',[$this->sDate,$this->eDate])
                    ->where('gym_membership_freeze.detail_id', $this->user);
                break;
            case 'extend':
                $booking = GymMembershipExtend::select(
                    'gym_clients.first_name','gym_clients.middle_name',
                    'gym_clients.last_name',
                    'amount_to_be_paid',
                    'gym_memberships.title as membership',
                    'gym_client_purchases.start_date','gym_client_purchases.expires_on')
                    ->leftJoin('gym_client_purchases','gym_client_purchases.id','=','gym_membership_extends.purchase_id')
                    ->leftJoin('gym_clients','gym_clients.id','=','gym_client_purchases.client_id')
                    ->leftJoin('gym_memberships','gym_memberships.id','=','gym_client_purchases.membership_id')
                    ->whereBetween('extend_from',[$this->sDate,$this->eDate])
                    ->where('gym_membership_extends.detail_id', $this->user);
                break;
            case 'pending':
                $booking = GymPurchase::select(
                    'gym_clients.first_name','gym_clients.middle_name',
                    'gym_clients.last_name',
                    'amount_to_be_paid',
                    'gym_memberships.title as membership',
                    'gym_client_purchases.start_date','gym_client_purchases.expires_on')
                    ->leftJoin('gym_clients','gym_clients.id','=','gym_client_purchases.client_id')
                    ->leftJoin('gym_memberships','gym_memberships.id','=','gym_client_purchases.membership_id')
                    ->whereBetween('purchase_date',[$this->sDate,$this->eDate])
                    ->where('gym_client_purchases.status','=','pending')
                    ->where('gym_client_purchases.detail_id','=',$this->user);
                break;
            case 'all':
                $booking = GymPurchase::select(
                    'gym_clients.first_name','gym_clients.middle_name',
                    'gym_clients.last_name',
                    'amount_to_be_paid',
                    'gym_memberships.title as membership',
                    'gym_client_purchases.start_date','gym_client_purchases.expires_on')
                    ->leftJoin('gym_clients','gym_clients.id','=','gym_client_purchases.client_id')
                    ->leftJoin('gym_memberships','gym_memberships.id','=','gym_client_purchases.membership_id')
                    ->whereBetween('purchase_date',[$this->sDate,$this->eDate])
                    ->where('gym_client_purchases.detail_id','=',$this->user);
                break;
        }
        if($membership !== 'all'){
            $booking = $booking->where('membership_id', $membership);
        }
        $booking = $booking->get();
        return view('gym-admin.reports.booking.excel',['booking'=>$booking]);
    }
}
