<?php

namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use App\Exports\Reports\BranchRenewReportExport;
use App\Models\BusinessRenewHistory;
use Carbon\Carbon;
use PDF;
use Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use DataTables;

class BranchRenewReportController extends GymAdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['reportMenu'] = 'active';
        $this->data['branchreportMenu'] = 'active';
    }

    public function index()
    {
        $this->data['title'] = 'Branch Renew Report';
        return view('gym-admin.reports.branchRenew.index', $this->data);
    }

    public function store()
    {
        $validator = Validator::make(request()->all(), ['date_range' => 'required']);

        if($validator->fails())
        {
            return Reply::formErrors($validator);
        } else {
            $date_range = explode('-', request()->get('date_range'));
            $date_range_start = Carbon::createFromFormat('M d, Y', trim($date_range[0]));
            $date_range_end = Carbon::createFromFormat('M d, Y', trim($date_range[1]));

            $branches = BusinessRenewHistory::whereBetween(DB::raw('DATE(created_at)'), [$date_range_start->format('Y-m-d'), $date_range_end->format('Y-m-d')])
            ->count();

            $heading = 'Branches Renew';
            $data = [
                'total' => $branches,
                'start_date' => $date_range_start->format('Y-m-d'),
                'end_date' => $date_range_end->format('Y-m-d'),
                'report' => $heading
            ];
            return Reply::successWithData('Reports Fetched', $data);
        }
    }

    public function ajax_create($start_date, $end_date)
    {
        $branches = BusinessRenewHistory::select(
                'business_renew_history.id',
                'business_renew_history.package_offered',
                'business_renew_history.package_amount',
                'business_renew_history.renew_start_date',
                'business_renew_history.renew_end_date',
                'business_renew_history.created_at',
                'common_details.title',
                'common_details.owner_incharge_name',
                'common_details.email',
                'common_details.phone',
                'common_details.address',
                'common_details.start_date',
                'common_details.end_date',
                'common_details.has_device'
            )
            ->join('common_details', 'common_details.id', '=', 'business_renew_history.detail_id')
            ->whereBetween(DB::raw('DATE(business_renew_history.created_at)'), [$start_date, $end_date]);

        return Datatables::of($branches)
            ->editColumn('title', function($row) {
                return $row->title;
            })
            ->editColumn('owner_incharge_name', function($row) {
                return $row->owner_incharge_name;
            })
            ->editColumn('email', function($row) {
                return $row->email;
            })
            ->editColumn('phone', function($row) {
                return $row->phone;
            })
            ->editColumn('address', function($row) {
                return $row->address;
            })
            ->editColumn('start_date', function($row) {
                return date('M d,Y',strtotime($row->start_date));
            })
            ->editColumn('has_device', function($row) {
                return $row->has_device ? 'Yes' : 'No';
            })
            ->editColumn('package_offered', function($row) {
                return $row->package_offered . 'Months';
            })
            ->editColumn('package_amount', function($row) {
                return $row->package_amount;
            })
            ->editColumn('created_at', function($row) {
                return $row->created_at->format('d M, y');
            })
            ->editColumn('renew_start_date', function($row) {
                return $row->renew_start_date->format('d M, y');
            })
            ->editColumn('renew_end_date', function($row) {
                return $row->renew_end_date->format('d M, y');
            })
            ->make(true);
    }

    public function downloadBranchReport($sd, $ed)
    {
        $start_date = new Carbon($sd);
        $end_date = new Carbon($ed);

        $data = BusinessRenewHistory::select(
                'business_renew_history.package_offered',
                'business_renew_history.package_amount',
                'business_renew_history.renew_start_date',
                'business_renew_history.renew_end_date',
                'business_renew_history.created_at',
                'common_details.title',
                'common_details.owner_incharge_name',
                'common_details.email',
                'common_details.phone',
                'common_details.address',
                'common_details.start_date',
                'common_details.end_date',
                'common_details.has_device'
            )
            ->join('common_details', 'common_details.id', '=', 'business_renew_history.detail_id')
            ->whereBetween(DB::raw('DATE(business_renew_history.created_at)'), [$start_date->format('Y-m-d'), $end_date->format('Y-m-d')])
            ->get();

        $pdf = PDF::loadView('pdf.branchRenewReport', compact(['data', 'sd', 'ed']));
        return $pdf->download('branchRenewReport.pdf');
    }

    public function downloadExcelBranchReport($sd, $ed)
    {
        $sDate = new Carbon($sd);
        $eDate = new Carbon($ed);
        return Excel::download(new BranchRenewReportExport($sDate, $eDate), 'branchReport.xls');
    }
}
