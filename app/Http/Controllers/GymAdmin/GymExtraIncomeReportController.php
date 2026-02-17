<?php

namespace App\Http\Controllers\GymAdmin;

use App\Http\Controllers\Controller;
use App\Exports\Reports\ProfitLossReportExport;
use App\Models\IncomeCategory;
use App\Models\Income;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use App\Classes\Reply;
use Carbon\Carbon;
use DataTables;
use Excel;
use PDF;

class GymExtraIncomeReportController extends GymAdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['reportMenu'] = 'active';
        $this->data['collectionReportMenu'] = 'active';
    }

    public function index()
    {
        if (!$this->data['user']->can("view_target_report")) {
            return App::abort(401);
        }
        $this->data['title'] = "Extra Collection Reports";
        $this->data['categories'] = IncomeCategory::whereNull('detail_id')
                ->orWhere(function($query){
                    $query->where('detail_id',$this->data['user']->detail_id);
                })->latest()->get();
        $this->data['paymentSources'] = listPaymentType();
        return view('gym-admin.reports.expenses.income', $this->data);
    }

    public function store()
    {
        $validator  = Validator::make(request()->all(),['payment_source' => 'required','date_range'=>'required']);

        if($validator->fails())
        {
            return Reply::formErrors($validator);
        }else{
            $payment_source = request()->get('payment_source') ?? null;
            $date_range = explode('-',request()->get('date_range'));
            $category = request()->get('category') ?? null;
            $date_range_start = Carbon::createFromFormat('M d, Y',trim($date_range[0]));
            $date_range_end   = Carbon::createFromFormat('M d, Y',trim($date_range[1]));

            $start = $date_range_start->format('Y-m-d');
            $end = $date_range_end->format('Y-m-d');

            $query = Income::with('category')
            ->whereBetween('purchase_date',[$start,$end])
            ->where('detail_id', $this->data['user']->detail_id);

            if($category !== 'all'){
                $query->where('category_id', $category);
            }
            if ($payment_source !== 'all') {
                $query->where('payment_source', $payment_source);
            }
            $income = $query->sum('price');

            $heading = 'Extra Income Report';
            $data = [
                'total'            => $income,
                'start_date'       => $start,
                'end_date'         => $end,
                'report'           => $heading,
                'category'         => $category,
                'payment_source' => $payment_source,
            ];
            return Reply::successWithData('Reports Fetched',$data);
        }
    }

    public function ajax_create_income($start_date,$end_date,$category,$payment_source)
    {
        $query = Income::with('category')
        ->whereBetween('purchase_date',[$start_date,$end_date])
        ->where('detail_id', $this->data['user']->detail_id);

        if($category !== 'all'){
            $query->where('category_id', $category);
        }
        if ($payment_source !== 'all') {
            $query->where('payment_source', $payment_source);
        }
        $incomes = $query->get();

        return Datatables::of($incomes)
            ->editColumn('purchase_date',function($row){
                return $row->purchase_date->toFormattedDateString();
            })
            ->editColumn('price',function($row){
                return $this->data['gymSettings']->currency->acronym.' '. $row->price;
            })
            ->editColumn('category_id',function($row){
                return $row->category->title ?? '';
            })
            ->editColumn('payment_source',function($row){
                return !is_null($row->payment_source) ? getPaymentType($row->payment_source) : '';
            })
            ->editColumn('supplier_id', function ($row) {
                return $row->supplier->name ?? '---';
            })
            ->rawColumns(['price','category_id','supplier_id','payment_source'])
            ->make();
    }

    public function downloadReport($sd,$ed,$category,$payment_source){
        $start_date = new Carbon($sd);
        $end_date = new Carbon($ed);

        $query = Income::with('category')
        ->whereBetween('purchase_date',[$start_date,$end_date])
        ->where('detail_id', $this->data['user']->detail_id);

        if($category !== 'all'){
            $query->where('category_id', $category);
        }
        if($payment_source !== 'all'){
            $query->where('payment_source', $payment_source);
        }
        $data = $query->latest()->get();
        $type = 'extra collection';
        $pdf = PDF::loadView('gym-admin.reports.expenses.pdf',compact(['data','sd','ed','type']));
        return $pdf->download('collectionReport.pdf');
    }

    public function downloadExcelReport($sd,$ed,$category,$payment_source){
        $sDate = new Carbon($sd);
        $eDate = new Carbon($ed);
        $user = $this->data['user']->detail_id;
        return Excel::download(new ProfitLossReportExport($sDate,$eDate,'income',$user,$category,$payment_source),'collectionReport.xls');

    }
}
