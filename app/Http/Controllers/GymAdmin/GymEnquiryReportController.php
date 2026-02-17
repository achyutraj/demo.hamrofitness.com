<?php

namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use App\Exports\Reports\EnquiryReportExport;
use App\Models\GymEnquiries;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use PDF;
use Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use DataTables;

class GymEnquiryReportController extends GymAdminBaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->data['reportMenu'] = 'active';
        $this->data['enquiryreportMenu'] = 'active';
    }
    public function index()
    {
        if(!$this->data['user']->can("view_enquiry_report"))
        {
            return App::abort(401);
        }

        $this->data['title'] = 'Enquiry Report';
        return View::make('gym-admin.reports.enquiry.index',$this->data);
    }

    public function store()
    {
        $validator  = Validator::make(request()->all(),['date_range'=>'required']);

        if($validator->fails())
        {
            return Reply::formErrors($validator);
        }else{
            $date_range = explode('-',request()->get('date_range'));
            $come_to_know = request()->get('come_to_know');

            $date_range_start = Carbon::createFromFormat('M d, Y',trim($date_range[0]));
            $date_range_end   = Carbon::createFromFormat('M d, Y',trim($date_range[1]));
            if($come_to_know == "all"){
                $enquiry = GymEnquiries::whereBetween(DB::raw('DATE(created_at)'),[$date_range_start->format('Y-m-d'),$date_range_end->format('Y-m-d')])
                    ->where('detail_id','=',$this->data['user']->detail_id)
                    ->count();
            }else{
                $enquiry = GymEnquiries::whereBetween(DB::raw('DATE(created_at)'),[$date_range_start->format('Y-m-d'),$date_range_end->format('Y-m-d')])
                    ->where('come_to_know',$come_to_know)
                    ->where('detail_id','=',$this->data['user']->detail_id)
                    ->count();
            }

            $heading = 'Enquiries';
            $data = [
                'total'             => $enquiry,
                'start_date'        => $date_range_start->format('Y-m-d'),
                'end_date'          => $date_range_end->format('Y-m-d'),
                'source'            => $come_to_know,
                'report'            => $heading
            ];
            return Reply::successWithData('Reports Fetched',$data);
        }
    }

    public function ajax_create($start_date,$end_date,$source)
    {
        if($source == "all"){
            $enquiry = GymEnquiries::select('customer_name','customer_mname','customer_lname','email','mobile','sex','come_to_know','enquiry_date','next_follow_up')
                ->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])
                ->where('detail_id', '=', $this->data['user']->detail_id);
        }else{
            $enquiry = GymEnquiries::select('customer_name','customer_mname','customer_lname','email','mobile','sex','come_to_know','enquiry_date','next_follow_up')
                ->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])
                ->where('come_to_know',$source)
                ->where('detail_id', '=', $this->data['user']->detail_id);
        }

        return Datatables::of($enquiry)
            ->editColumn('customer_name',function($row){
                return $row->displayFullName();
            })->editColumn('email',function($row){
                return $row->email;
            })
            ->editColumn('next_follow_up',function($row){
                return $row->next_follow_up->format('d M, y');
            })
            ->editColumn('enquiry_date',function($row){
                return $row->enquiry_date->format('d M, y');
            })
            ->editColumn('mobile',function($row){
                return '<i class="fa fa-mobile"></i> '.$row->mobile;
            })
            ->editColumn('sex',function($row){
                if($row->sex == 'female'){
                    return '<i class="fa fa-female"></i> Female';
                }else{
                    return '<i class="fa fa-male"></i> Male';
                }
            })
            ->rawColumns(['next_follow_up','enquiry_date','mobile','sex'])
            ->make();

    }

    public function downloadEnquiryReport($sd,$ed,$source){
        $start_date = new Carbon($sd);
        $end_date = new Carbon($ed);
        if($source == "all"){
            $data = GymEnquiries::select('customer_name','customer_mname','customer_lname','email','mobile','sex','come_to_know','enquiry_date','next_follow_up','customer_goal')
                ->whereBetween(DB::raw('DATE(created_at)'), [$start_date->format('Y-m-d'), $end_date->format('Y-m-d')])
                ->where('detail_id', '=', $this->data['user']->detail_id)->get();
        }else{
            $data = GymEnquiries::select('customer_name','customer_mname','customer_lname','email','mobile','sex','come_to_know','enquiry_date','next_follow_up','customer_goal')
                ->whereBetween(DB::raw('DATE(created_at)'), [$start_date->format('Y-m-d'), $end_date->format('Y-m-d')])
                ->where('come_to_know',$source)->where('detail_id', '=', $this->data['user']->detail_id)
                ->get();
        }
        $pdf = PDF::loadView('pdf.enquiryReport',compact(['data','sd','ed','source']));
        return $pdf->download('enquiryReport.pdf');
    }

    public function downloadExcelEnquiryReport($sd,$ed,$source){
        $sDate = new Carbon($sd);
        $eDate = new Carbon($ed);
        $user = $this->data['user']->detail_id;
        return Excel::download(new EnquiryReportExport($sDate,$eDate,$user,$source),'enquiryReport.xls');

    }
}
