<?php

namespace App\Http\Controllers\GymAdmin;

use App\Exports\Reports\ProfitLossReportExport;
use App\Models\ExpenseCategory;
use App\Models\GymExpense;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use App\Classes\Reply;
use Carbon\Carbon;
use DataTables;
use Excel;
use PDF;

class GymExpenseReportController extends GymAdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['reportMenu'] = 'active';
        $this->data['expenseReportMenu'] = 'active';
    }

    public function index()
    {
        if (!$this->data['user']->can("view_target_report")) {
            return App::abort(401);
        }
        $this->data['title'] = "Expense Reports";
        $this->data['categories'] = ExpenseCategory::whereNull('detail_id')
                                    ->orWhere(function($query){
                                        $query->where('detail_id',$this->data['user']->detail_id);
                                    })->latest()->get();
        return view('gym-admin.reports.expenses.index', $this->data);
    }

    public function store()
    {
        $validator  = Validator::make(request()->all(),['date_range'=>'required']);

        if($validator->fails())
        {
            return Reply::formErrors($validator);
        }else{
            $date_range = explode('-',request()->get('date_range'));
            $category = request()->get('category') ?? null;
            $date_range_start = Carbon::createFromFormat('M d, Y',trim($date_range[0]));
            $date_range_end   = Carbon::createFromFormat('M d, Y',trim($date_range[1]));

            $start = $date_range_start->format('Y-m-d');
            $end = $date_range_end->format('Y-m-d');

            $query = GymExpense::with('category')
                ->whereBetween('purchase_date',[$start,$end])
                ->where('detail_id', $this->data['user']->detail_id);

            if($category !== 'all'){
                $query->where('category_id', $category);
            }
            $expense = $query->sum('price');

            $heading = 'Expense Report';
            $data = [
                'total'            => $expense,
                'start_date'       => $start,
                'end_date'         => $end,
                'report'           => $heading,
                'category'         => $category,
            ];
            return Reply::successWithData('Reports Fetched',$data);
        }
    }

    public function ajax_create_expense($start_date,$end_date,$category)
    {
        $query = GymExpense::with('category')
        ->whereBetween('purchase_date',[$start_date,$end_date])
        ->where('detail_id', $this->data['user']->detail_id);

        if($category !== 'all'){
            $query->where('category_id', $category);
        }
        $expenses = $query->get();

        return Datatables::of($expenses)
            ->editColumn('purchase_date',function($row){
                return Carbon::createFromFormat('Y-m-d', $row->purchase_date)->toFormattedDateString();
            })
            ->editColumn('supplier_id',function($row){
                return $row->supplier->name ?? '';
            })
            ->editColumn('price',function($row){
                return $this->data['gymSettings']->currency->acronym.' '. $row->price;
            })
            ->editColumn('category_id',function($row){
                return $row->category->title ?? null;
            })
            ->editColumn('payment_source',function($row){
                return !is_null($row->payment_source) ? getPaymentType($row->payment_source) : '';
            })
            ->editColumn('payment_status', function ($row) {
                return $row->payment_status == 'paid' ? '<span class="label label-success">Paid</span>' : '<span class="label label-danger">Unpaid</span>';
            })
            ->rawColumns(['supplier_id','price','category_id','payment_source','payment_status'])
            ->make();

    }
    public function downloadReport($sd,$ed,$category){
        $start_date = new Carbon($sd);
        $end_date = new Carbon($ed);

        $query = GymExpense::with('category')
        ->whereBetween('purchase_date',[$start_date,$end_date])
        ->where('detail_id', $this->data['user']->detail_id);

        if($category !== 'all'){
            $query->where('category_id', $category);
        }
        $data = $query->latest()->get();
        $type = 'expense';
        $pdf = PDF::loadView('gym-admin.reports.expenses.pdf',compact(['data','sd','ed','type']));
        return $pdf->download('expenseReport.pdf');
    }

    public function downloadExcelReport($sd,$ed,$category){
        $sDate = new Carbon($sd);
        $eDate = new Carbon($ed);
        $user = $this->data['user']->detail_id;
        return Excel::download(new ProfitLossReportExport($sDate,$eDate,'expense',$user,$category),'expenseReport.xls');

    }
}
