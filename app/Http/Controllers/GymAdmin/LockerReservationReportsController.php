<?php

namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use App\Exports\Reports\LockerReservationExport;
use App\Models\LockerReservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\DataTables;
use PDF;
use Excel;

class LockerReservationReportsController extends GymAdminBaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->data['reportMenu'] = 'active';
        $this->data['reservationReportMenu'] = 'active';
    }

    public function index()
    {
        if(!$this->data['user']->can("view_booking_report"))
        {
            return App::abort(401);
        }

        $this->data['title'] = 'Reservation Report';
        return View::make('gym-admin.reports.reservation.index',$this->data);
    }

    public function store()
    {
        $validator  = Validator::make(request()->all(),['date_range'=>'required']);

        if($validator->fails())
        {
            return Reply::formErrors($validator);
        }else{
            $date_range = explode('-',request()->get('date_range'));
            $date_range_start = Carbon::createFromFormat('M d, Y',trim($date_range[0]));
            $date_range_end   = Carbon::createFromFormat('M d, Y',trim($date_range[1]));
            $sum = LockerReservation::has('locker')->where('status','active')->whereBetween('purchase_date',[$date_range_start->format('Y-m-d'),$date_range_end->format('Y-m-d')])
                ->where('detail_id',$this->data['user']->detail_id)
                ->sum('paid_amount');
                
            $heading = 'Locker Reservations';
            $data = [
                'total'             => $sum,
                'start_date'        => $date_range_start->format('Y-m-d'),
                'end_date'          => $date_range_end->format('Y-m-d'),
                'report'            => $heading
            ];
            return Reply::successWithData('Reports Fetched',$data);
        }
    }

    public function ajax_create($start_date,$end_date)
    {
        $booking = LockerReservation::has('locker')->where('status','active')
            ->whereBetween('purchase_date',[$start_date,$end_date])
            ->where('detail_id',$this->data['user']->detail_id);

        return Datatables::of($booking)
            ->editColumn('client_id',function($row){
               return $row->client->fullName ?? '';
            })->editColumn('locker_id',function($row) {
                return $row->locker->locker_num ?? '';
            })->editColumn('start_date',function($row){
                return $row->start_date->toFormattedDateString();
            })->editColumn('end_date',function($row){
                return $row->end_date->toFormattedDateString();
            })->editColumn('paid_amount',function($row){
                return $row->paid_amount;
            })
            ->rawColumns(['locker_id','client_id'])
            ->make();
    }

    public function downloadReservationReport($sd, $ed)
    {
        $start_date = new Carbon($sd);
        $end_date = new Carbon($ed);

        $booking = LockerReservation::has('locker')->where('status','active')
            ->whereBetween('purchase_date',[$start_date,$end_date])
            ->where('detail_id',$this->data['user']->detail_id)->get();

        $pdf = PDF::loadView('pdf.reservationReport',compact(['booking','sd','ed']));
        return $pdf->download('reservation.pdf');
    }

    public function downloadExcelReservationReport($sd, $ed)
    {
        $sDate = new Carbon($sd);
        $eDate = new Carbon($ed);
        $user = $this->data['user']->detail_id;
        return Excel::download(new LockerReservationExport($sDate,$eDate,$user),'reservations.xls');
    }
}
