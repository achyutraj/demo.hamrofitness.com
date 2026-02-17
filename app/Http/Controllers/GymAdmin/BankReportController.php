<?php

namespace App\Http\Controllers\GymAdmin;

use App\Exports\Reports\BankReportExport;
use App\Models\BankLedger;
use App\Classes\Reply;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use DataTables;
use PDF;
use Excel;

class BankReportController extends GymAdminBaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->data['reportMenu'] = 'active';
        $this->data['bankreportMenu'] = 'active';
    }
    public function index()
    {
        if(!$this->data['user']->can("view_enquiry_report"))
        {
            return App::abort(401);
        }

        $this->data['title'] = 'Bank Ledgers Report';
        return View::make('gym-admin.reports.bank.index',$this->data);
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

            $banks = BankLedger::whereBetween(DB::raw('DATE(created_at)'),[$date_range_start->format('Y-m-d'),$date_range_end->format('Y-m-d')])
                ->where('branch_id','=',$this->data['user']->detail_id)
                ->count();
            $heading = 'Bank Ledgers';
            $data = [
                'total'             => $banks,
                'start_date'        => $date_range_start->format('Y-m-d'),
                'end_date'          => $date_range_end->format('Y-m-d'),
                'report'            => $heading
            ];
            return Reply::successWithData('Reports Fetched',$data);
        }
    }

    public function ajax_create($start_date,$end_date)
    {
        $banks = BankLedger::select('bank_accounts.account_number','banks.name as name','transaction_type','transaction_method','date','amount','remarks')
            ->leftJoin('bank_accounts', 'bank_accounts.id', '=', 'bank_account_id')
            ->leftJoin('banks', 'banks.id', '=', 'bank_accounts.bank_id')
            ->whereBetween(DB::raw('DATE(bank_ledgers.created_at)'), [$start_date, $end_date])
            ->where('bank_ledgers.branch_id', '=', $this->data['user']->detail_id);

        return Datatables::of($banks)
            ->editColumn('bank_accounts.account_number',function($row){
                return $row->name .'<br>'. $row->account_number;
            })
            ->editColumn('transaction_type',function($row){
                return $row->transaction_type;
            })
            ->editColumn('amount',function($row){
                return $this->data['gymSettings']->currency->acronym.' '.$row->amount;
            })
            ->editColumn('date',function($row){
                return (date('M d, Y',strtotime($row->date)));
            })
            ->editColumn('transaction_method',function($row){
                return '<i class="fa fa-cash"></i> '.$row->transaction_method;
            })
            ->rawColumns(['bank_accounts.account_number','transaction_method'])
            ->make();

    }
    public function downloadBankReport($sd,$ed){
        $start_date = new Carbon($sd);
        $end_date = new Carbon($ed);
        $data = BankLedger::select('bank_accounts.account_number','banks.name','transaction_type','transaction_method','date','amount','remarks')
            ->leftJoin('bank_accounts', 'bank_accounts.id', '=', 'bank_account_id')
            ->leftJoin('banks', 'banks.id', '=', 'bank_accounts.bank_id')
            ->whereBetween(DB::raw('DATE(bank_ledgers.created_at)'), [$start_date->format('Y-m-d'), $end_date->format('Y-m-d')])
            ->where('bank_ledgers.branch_id', '=', $this->data['user']->detail_id)->get();

        $pdf = PDF::loadView('pdf.bankReport',compact(['data','sd','ed']));
        return $pdf->download('bankReport.pdf');
    }

    public function downloadExcelBankReport($sd,$ed){
        $sDate = new Carbon($sd);
        $eDate = new Carbon($ed);
        $user = $this->data['user']->detail_id;
        return Excel::download(new BankReportExport($sDate,$eDate,$user),'bankReport.xls');
    }
}
