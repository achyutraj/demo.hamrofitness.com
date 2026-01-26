<?php

namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use App\Exports\Reports\BookingReportExport;
use App\Models\GymMembership;
use App\Models\GymMembershipExtend;
use App\Models\GymMembershipFreeze;
use App\Models\GymPurchase;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use DataTables;
use Excel;
use PDF;

class GymBookingReportsController extends GymAdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['reportMenu'] = 'active';
        $this->data['bookingreportMenu'] = 'active';
    }

    public function index()
    {
        if(!$this->data['user']->can("view_booking_report"))
        {
            return App::abort(401);
        }
        $this->data['title'] = 'Subscription Report';
        $this->data['memberships'] = GymMembership::membershipByBusiness($this->data['user']->detail_id);
        return View::make('gym-admin.reports.booking.index',$this->data);
    }

    public function store()
    {
        $validator  = Validator::make(request()->all(),['booking_type'=>'required','date_range'=>'required']);

        if($validator->fails())
        {
            return Reply::formErrors($validator);
        }else{
            $choice = request()->get('booking_type');
            $date_range = explode('-',request()->get('date_range'));
            $membership = request()->get('membership') ?? null;
            $date_range_start = Carbon::createFromFormat('M d, Y',trim($date_range[0]));
            $date_range_end   = Carbon::createFromFormat('M d, Y',trim($date_range[1]));
            $sum = 0;
            $heading = '';

            switch($choice){
                case 'expire':
                    $query = GymPurchase::whereBetween('expires_on',[$date_range_start->format('Y-m-d'),$date_range_end->format('Y-m-d')])
                                        ->where('detail_id',$this->data['user']->detail_id);
                    $heading = 'Expire';
                    break;
                case 'renew':
                    $query = GymPurchase::whereBetween('purchase_date',[$date_range_start->format('Y-m-d'),$date_range_end->format('Y-m-d')])
                                        ->where('detail_id',$this->data['user']->detail_id)
                                        ->where('is_renew', 1);
                    $heading = 'Renew';
                    break;
                case 'freeze':
                    $query = GymMembershipFreeze::with('purchase')->whereBetween('start_date',[$date_range_start->format('Y-m-d'),$date_range_end->format('Y-m-d')])
                                                ->where('detail_id',$this->data['user']->detail_id);
                    $heading = 'Freeze';
                    break;
                case 'extend':
                    $query = GymMembershipExtend::with('purchase')->whereBetween('extend_from',[$date_range_start->format('Y-m-d'),$date_range_end->format('Y-m-d')])
                                                ->where('detail_id',$this->data['user']->detail_id);
                    $heading = 'Extend';
                    break;
                case 'pending':
                    $query = GymPurchase::whereBetween('purchase_date',[$date_range_start->format('Y-m-d'),$date_range_end->format('Y-m-d')])
                                        ->where('detail_id',$this->data['user']->detail_id)
                                        ->where('status', 'pending');
                    $heading = 'Pending';
                    break;
                case 'all':
                    $query = GymPurchase::whereBetween('purchase_date',[$date_range_start->format('Y-m-d'),$date_range_end->format('Y-m-d')])
                                        ->where('detail_id',$this->data['user']->detail_id);
                    $heading = 'All';
                    break;
            }
            
            if ($membership !== 'all') {
                if ($choice == 'extend' || $choice == 'freeze') {
                    $query->whereHas('purchase', function ($q) use ($membership) {
                        $q->where('membership_id', $membership);
                    });
                } else {
                    $query->where('membership_id', $membership);
                }
            }
            
            if ($choice == 'extend' || $choice == 'freeze') {
                $sum = $query->get()->sum(function($record) {
                    return $record->purchase->amount_to_be_paid ?? 0;
                });
            } else {
                $sum = $query->sum('amount_to_be_paid');
            }
            
            $data = [
                'total'             => $sum,
                'start_date'        => $date_range_start->format('Y-m-d'),
                'end_date'          => $date_range_end->format('Y-m-d'),
                'type'              => $choice,
                'report'            => $heading,
                'membership'         => $membership,
            ];

            return Reply::successWithData('Reports Fetched',$data);
        }
    }

    public function ajax_create($type,$start_date,$end_date,$membership)
    {
        switch($type){
            case 'expire':
                $booking = GymPurchase::select(
                    'gym_clients.first_name','gym_clients.middle_name',
                    'gym_clients.last_name',
                    'amount_to_be_paid',
                    'gym_memberships.title as membership','gym_client_purchases.membership_id',
                    'gym_client_purchases.start_date','gym_client_purchases.expires_on')
                    ->leftJoin('gym_clients','gym_clients.id','=','gym_client_purchases.client_id')
                    ->leftJoin('gym_memberships','gym_memberships.id','=','gym_client_purchases.membership_id')
                    ->whereBetween('expires_on',[$start_date,$end_date])
                    ->where('gym_client_purchases.detail_id','=',$this->data['user']->detail_id);
               
                break;
            case 'renew':
                $booking = GymPurchase::select(
                    'gym_clients.first_name','gym_clients.middle_name',
                    'gym_clients.last_name',
                    'amount_to_be_paid',
                    'gym_memberships.title as membership','gym_client_purchases.membership_id',
                    'gym_client_purchases.start_date','gym_client_purchases.expires_on')
                    ->leftJoin('gym_clients','gym_clients.id','=','gym_client_purchases.client_id')
                    ->leftJoin('gym_memberships','gym_memberships.id','=','gym_client_purchases.membership_id')
                    ->whereBetween('purchase_date',[$start_date,$end_date])
                    ->where('gym_client_purchases.is_renew',1)
                    ->where('gym_client_purchases.detail_id',$this->data['user']->detail_id);
            
                break;
            case 'freeze':
                $booking = GymMembershipFreeze::select(
                    'gym_clients.first_name','gym_clients.middle_name',
                    'gym_clients.last_name',
                    'amount_to_be_paid',
                    'gym_memberships.title as membership','gym_client_purchases.membership_id',
                    'gym_client_purchases.start_date','gym_client_purchases.expires_on')
                    ->leftJoin('gym_client_purchases','gym_client_purchases.id','=','gym_membership_freeze.purchase_id')
                    ->leftJoin('gym_clients','gym_clients.id','=','gym_client_purchases.client_id')
                    ->leftJoin('gym_memberships','gym_memberships.id','=','gym_client_purchases.membership_id')
                    ->whereBetween('gym_membership_freeze.start_date',[$start_date,$end_date])
                    ->where('gym_membership_freeze.detail_id', $this->data['user']->detail_id);
                break;
            case 'extend':
                $booking = GymMembershipExtend::select(
                    'gym_clients.first_name','gym_clients.middle_name',
                    'gym_clients.last_name',
                    'amount_to_be_paid',
                    'gym_memberships.title as membership','gym_client_purchases.membership_id',
                    'gym_client_purchases.start_date','gym_client_purchases.expires_on')
                    ->leftJoin('gym_client_purchases','gym_client_purchases.id','=','gym_membership_extends.purchase_id')
                    ->leftJoin('gym_clients','gym_clients.id','=','gym_client_purchases.client_id')
                    ->leftJoin('gym_memberships','gym_memberships.id','=','gym_client_purchases.membership_id')
                    ->whereBetween('extend_from',[$start_date,$end_date])
                    ->where('gym_membership_extends.detail_id', $this->data['user']->detail_id);
                break;
            case 'pending':
                $booking = GymPurchase::select(
                    'gym_clients.first_name','gym_clients.middle_name',
                    'gym_clients.last_name',
                    'amount_to_be_paid',
                    'gym_memberships.title as membership','gym_client_purchases.membership_id',
                    'gym_client_purchases.start_date','gym_client_purchases.expires_on')
                    ->leftJoin('gym_clients','gym_clients.id','=','gym_client_purchases.client_id')
                    ->leftJoin('gym_memberships','gym_memberships.id','=','gym_client_purchases.membership_id')
                    ->whereBetween('purchase_date',[$start_date,$end_date])
                    ->where('gym_client_purchases.status','=','pending')
                    ->where('gym_client_purchases.detail_id','=',$this->data['user']->detail_id);
                break;
            case 'all':
                $booking = GymPurchase::select(
                    'gym_clients.first_name','gym_clients.middle_name',
                    'gym_clients.last_name',
                    'amount_to_be_paid',
                    'gym_memberships.title as membership','gym_client_purchases.membership_id',
                    'gym_client_purchases.start_date','gym_client_purchases.expires_on')
                    ->leftJoin('gym_clients','gym_clients.id','=','gym_client_purchases.client_id')
                    ->leftJoin('gym_memberships','gym_memberships.id','=','gym_client_purchases.membership_id')
                    ->whereBetween('purchase_date',[$start_date,$end_date])
                    ->where('gym_client_purchases.detail_id','=',$this->data['user']->detail_id);
                break;
        }
        if($membership !== 'all'){
            $booking->where('membership_id', $membership);
        }
        $booking = $booking->get();

        return Datatables::of($booking)
            ->editColumn('gym_clients.first_name',function($row){
                $name = ucfirst($row->first_name).' ';
                if(!is_null($row->middle_name)) {
                    $name .= ucfirst($row->middle_name).' ';
                }
                $name .= ucfirst($row->last_name);
                return $name;
            })->addColumn('membership',function($row) {
                return $row->membership;
            })->editColumn('start_date',function($row){
                return date('M d, Y',strtotime($row->start_date));
            })->editColumn('expires_on',function($row){
                return !is_null($row->expires_on) ? date('M d, Y',strtotime($row->expires_on)) : '';
            })->editColumn('amount_to_be_paid',function($row){
                return $row->amount_to_be_paid;
            })
            ->rawColumns(['membership'])
            ->make();
    }

    public function downloadBookingReport($id, $sd, $ed, $membership)
    {
        $start_date = new Carbon($sd);
        $end_date = new Carbon($ed);
        $type = $id;
        switch($id){
            case 'expire':
                $booking = GymPurchase::select(
                    'gym_clients.first_name','gym_clients.middle_name',
                    'gym_clients.last_name',
                    'amount_to_be_paid',
                    'gym_memberships.title as membership',
                    'gym_client_purchases.start_date','gym_client_purchases.expires_on')
                    ->leftJoin('gym_clients','gym_clients.id','=','gym_client_purchases.client_id')
                    ->leftJoin('gym_memberships','gym_memberships.id','=','gym_client_purchases.membership_id')
                    ->whereBetween('expires_on',[$start_date,$end_date])
                    ->where('gym_client_purchases.detail_id','=',$this->data['user']->detail_id);
               
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
                    ->whereBetween('purchase_date',[$start_date,$end_date])
                    ->where('gym_client_purchases.is_renew',1)
                    ->where('gym_client_purchases.detail_id',$this->data['user']->detail_id);
            
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
                    ->whereBetween('gym_membership_freeze.start_date',[$start_date,$end_date])
                    ->where('gym_membership_freeze.detail_id', $this->data['user']->detail_id);
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
                    ->whereBetween('extend_from',[$start_date,$end_date])
                    ->where('gym_membership_extends.detail_id', $this->data['user']->detail_id);
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
                    ->whereBetween('purchase_date',[$start_date,$end_date])
                    ->where('gym_client_purchases.status','=','pending')
                    ->where('gym_client_purchases.detail_id','=',$this->data['user']->detail_id);
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
                    ->whereBetween('purchase_date',[$start_date,$end_date])
                    ->where('gym_client_purchases.detail_id','=',$this->data['user']->detail_id);
                break;
        }

        if($membership !== 'all'){
            $booking->where('membership_id', $membership);
        }
        $booking = $booking->get();

        $pdf = PDF::loadView('pdf.bookingReport',compact(['booking','sd','ed','type']));
        $filename = $type.'-subscription.pdf';
        return $pdf->download($filename);
    }

    public function downloadExcelBookingReport($id, $sd, $ed, $membership)
    {
        $sDate = new Carbon($sd);
        $eDate = new Carbon($ed);
        $user = $this->data['user']->detail_id;
        $filename = $id.'-subscription.xls';
        return Excel::download(new BookingReportExport($id,$sDate,$eDate,$user,$membership),$filename);
    }
}
