<?php

namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use App\Exports\Reports\BalanceReportExport;
use App\Models\GymExpense;
use App\Models\GymMembershipPayment;
use App\Models\Income;
use App\Models\LockerPayment;
use App\Models\Payroll;
use App\Models\Product;
use App\Models\ProductPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use DataTables;
use PDF;
use Excel;

class GymBalanceReportController extends GymAdminBaseController
{

    public function __construct() {
        parent::__construct();
        $this->data['reportMenu'] = 'active';
        $this->data['balancereportMenu'] = 'active';
    }

    public function index()
    {
        if(!$this->data['user']->can("balance_report"))
        {
            return App::abort(401);
        }

        $this->data['title'] = 'Balance Report';
        return View::make('gym-admin.reports.balance.index',$this->data);
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
            $memberships = GymMembershipPayment::whereBetween(DB::raw('DATE(payment_date)'),[$date_range_start->format('Y-m-d'),$date_range_end->format('Y-m-d')])
                ->where('detail_id','=',$this->data['user']->detail_id)
                ->sum('payment_amount');
            $lockers = LockerPayment::whereBetween(DB::raw('DATE(payment_date)'),[$date_range_start->format('Y-m-d'),$date_range_end->format('Y-m-d')])
                ->where('detail_id','=',$this->data['user']->detail_id)
                ->sum('payment_amount');
            $products = ProductPayment::whereBetween(DB::raw('DATE(payment_date)'),[$date_range_start->format('Y-m-d'),$date_range_end->format('Y-m-d')])
                ->where('branch_id','=',$this->data['user']->detail_id)
                ->sum('payment_amount');

            $incomes = Income::whereBetween(DB::raw('DATE(purchase_date)'),[$date_range_start->format('Y-m-d'),$date_range_end->format('Y-m-d')])
                ->where('detail_id','=',$this->data['user']->detail_id)
                ->sum('price');
            $total_income = $memberships + $products + $lockers + $incomes;

            $expense = GymExpense::whereBetween(DB::raw('DATE(purchase_date)'),[$date_range_start->format('Y-m-d'),$date_range_end->format('Y-m-d')])
                ->where('detail_id','=',$this->data['user']->detail_id)
                ->sum('price');

            $heading = 'Balance';
            $data = [
                'totalIncome'       => $total_income,
                'totalExpense'      => $expense,
                'start_date'        => $date_range_start->format('Y-m-d'),
                'end_date'          => $date_range_end->format('Y-m-d'),
                'report'            => $heading
            ];
            return Reply::successWithData('Reports Fetched',$data);
        }
    }

    public function ajax_create($start_date,$end_date)
    {
        $expenses = GymExpense::whereBetween(DB::raw('DATE(purchase_date)'),[$start_date,$end_date])
            ->where('detail_id',$this->data['user']->detail_id)->get();

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
                return $row->category->title ?? $row->item_name;
            })
            ->editColumn('payment_status', function ($row) {
                return ucfirst($row->payment_status);
            })
            ->editColumn('payment_source',function($row){
                return !is_null($row->payment_source) ? getPaymentType($row->payment_source) : '';
            })
            ->rawColumns(['supplier_id','price','category_id','payment_source'])
            ->make();

    }

    public function ajax_create_income($start_date,$end_date)
    {
        $incomes = Income::whereBetween(DB::raw('DATE(purchase_date)'),[$start_date,$end_date])
            ->where('detail_id',$this->data['user']->detail_id)->get();

        return Datatables::of($incomes)
            ->editColumn('purchase_date',function($row){
                return date('M d, Y',strtotime($row->purchase_date));
            })
            ->editColumn('supplier_id',function($row){
                return $row->supplier->name ?? '';
            })
            ->editColumn('price',function($row){
                return $this->data['gymSettings']->currency->acronym.' '. $row->price;
            })
            ->editColumn('payment_source',function($row){
                return !is_null($row->payment_source) ? getPaymentType($row->payment_source) : '';
            })
            ->editColumn('category_id',function($row){
                return $row->category->title ?? $row->item_name;
            })
            ->rawColumns(['supplier_id','price','category_id','payment_source'])
            ->make();

    }

    public function ajax_create_mem($start_date,$end_date)
    {
        $membershipIncome = GymMembershipPayment::select('gym_membership_payments.id as pid', 'gym_clients.first_name','gym_clients.middle_name', 'gym_clients.last_name', 'payment_amount','payment_source', 'payment_date', 'payment_id', 'gym_memberships.title as membership', 'purchase_id')
            ->leftJoin('gym_client_purchases', 'gym_client_purchases.id', '=', 'gym_membership_payments.purchase_id')
            ->leftJoin('gym_clients', 'gym_clients.id', '=', 'gym_membership_payments.user_id')
            ->leftJoin('gym_memberships', 'gym_memberships.id', '=', 'gym_client_purchases.membership_id')
            ->whereBetween(DB::raw('DATE(gym_membership_payments.payment_date)'), [$start_date, $end_date])
            ->where('gym_membership_payments.detail_id', '=', $this->data['user']->detail_id);

        return Datatables::of($membershipIncome)
            ->editColumn('gym_clients.first_name', function ($row) {
                    return $row->first_name.' '.$row->middle_name.' '.$row->last_name;
            })
            ->editColumn('payment_source', function ($row) {
                return getPaymentType($row->payment_source);
            })
            ->editColumn('payment_date', function ($row) {
                return Carbon::createFromFormat('Y-m-d H:i:s', $row->payment_date)->toFormattedDateString();
            })->editColumn('payment_amount', function ($row) {
                return $this->data['gymSettings']->currency->acronym . ' ' . $row->payment_amount;
            })->editColumn('payment_id', function ($row) {
                return '<b>' . $row->payment_id . '</b>';
            })->editColumn('membership', function ($row) {
                return '<b>' . $row->membership . '</b>';
            })
            ->rawColumns(['gym_clients.first_name','membership','payment_id','payment_amount','payment_source'])
            ->make();

    }

    public function ajax_create_locker($start_date,$end_date)
    {
        $lockerIncome = LockerPayment::select('locker_payments.id as pid', 'gym_clients.first_name','gym_clients.middle_name', 'gym_clients.last_name', 'payment_amount','payment_source', 'payment_date', 'payment_id','reservation_id','lockers.locker_num')
            ->leftJoin('locker_reservations', 'locker_reservations.id', '=', 'locker_payments.reservation_id')
            ->leftJoin('gym_clients', 'gym_clients.id', '=', 'locker_payments.client_id')
            ->leftJoin('lockers', 'lockers.id', '=', 'locker_reservations.locker_id')
            ->whereBetween(DB::raw('DATE(locker_payments.payment_date)'), [$start_date, $end_date])
            ->where('locker_payments.detail_id', '=', $this->data['user']->detail_id);
        return Datatables::of($lockerIncome)
            ->editColumn('gym_clients.first_name', function ($row) {
                    return $row->first_name.' '.$row->middle_name.' '.$row->last_name;
            })
            ->editColumn('payment_source', function ($row) {
                return getPaymentType($row->payment_source);
            })
            ->editColumn('payment_date', function ($row) {
                return Carbon::createFromFormat('Y-m-d H:i:s', $row->payment_date)->toFormattedDateString();
            })->editColumn('payment_amount', function ($row) {
                return $this->data['gymSettings']->currency->acronym . ' ' . $row->payment_amount;
            })->editColumn('payment_id', function ($row) {
                return '<b>' . $row->payment_id . '</b>';
            })->editColumn('locker_num', function ($row) {
                return '<b>' . $row->locker_num . '</b>';
            })
            ->rawColumns(['gym_clients.first_name','locker_num','payment_id','payment_amount','payment_source'])
            ->make();

    }

    public function ajax_create_product($start_date,$end_date)
    {
        $membershipIncome = ProductPayment::select('product_payments.id as pid', 'gym_clients.first_name','gym_clients.middle_name', 'gym_clients.last_name', 'payment_amount','payment_source', 'payment_date', 'payment_id', 'product_sales.product_name','product_sale_id')
            ->leftJoin('product_sales', 'product_sales.id', '=', 'product_payments.product_sale_id')
            ->leftJoin('gym_clients', 'gym_clients.id', '=', 'product_sales.client_id')
            ->whereBetween(DB::raw('DATE(product_payments.payment_date)'), [$start_date, $end_date])
            ->where('product_payments.branch_id', '=', $this->data['user']->detail_id);

        return Datatables::of($membershipIncome)
            ->editColumn('gym_clients.first_name', function ($row) {
                    return $row->first_name.' '.$row->middle_name.' '.$row->last_name;
            })
            ->editColumn('payment_source', function ($row) {
                return getPaymentType($row->payment_source);
            })
            ->editColumn('payment_date', function ($row) {
                return Carbon::createFromFormat('Y-m-d H:i:s', $row->payment_date)->toFormattedDateString();
            })->editColumn('payment_amount', function ($row) {
                return $this->data['gymSettings']->currency->acronym . ' ' . $row->payment_amount;
            })->editColumn('payment_id', function ($row) {
                return '<b>' . $row->payment_id . '</b>';
            })->editColumn('product_name', function ($row) {
                $data = '';
                $arr['product_name'] = json_decode($row->product_name,true);
                for($i=0; $i < count( $arr['product_name']) ;$i++){
                    $pro = Product::find($arr['product_name'][$i]);
                    if($pro != null){
                        if($i == 0){
                            $data = $pro->name;
                        }else{
                            $data = $data.', '.$pro->name;
                        }
                    }
                }
                return $data;
            })
            ->rawColumns(['gym_clients.first_name','payment_source','product_name','payment_amount','payment_id'])
            ->make();
    }

    public function ajax_create_payroll($start_date,$end_date)
    {
        $payrolls = Payroll::whereHas('employes', function ($query) {
                    $query->where('branch_id', $this->data['user']->detail_id);
                })
            ->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date]);

        return Datatables::of($payrolls)
            ->editColumn('employes.first_name', function ($row) {
                    return $row->employes->fullName;
            })
            ->editColumn('date', function ($row) {
                return Carbon::createFromFormat('Y-m-d H:i:s', $row->created_at)->toFormattedDateString();
            })
            ->editColumn('salary', function ($row) {
                return $this->data['gymSettings']->currency->acronym . ' ' .$row->salary;
            })->editColumn('allowance', function ($row) {
                return $this->data['gymSettings']->currency->acronym . ' ' .$row->allowance;
            })->editColumn('deduction', function ($row) {
                return $this->data['gymSettings']->currency->acronym . ' ' . $row->deduction;
            })->editColumn('total', function ($row) {
                return $this->data['gymSettings']->currency->acronym . ' ' .$row->total;
            })
            ->make();
    }

    public function downloadMembershipBalanceReport($sd,$ed){
        $start_date = new Carbon($sd);
        $end_date = new Carbon($ed);

        $data = GymMembershipPayment::select('gym_membership_payments.id as pid', 'gym_clients.first_name', 'gym_clients.middle_name', 'gym_clients.last_name', 'payment_amount','payment_source', 'payment_date', 'payment_id', 'gym_memberships.title as membership', 'purchase_id')
            ->leftJoin('gym_client_purchases', 'gym_client_purchases.id', '=', 'gym_membership_payments.purchase_id')
            ->leftJoin('gym_memberships', 'gym_memberships.id', '=', 'gym_client_purchases.membership_id')
            ->leftJoin('gym_clients', 'gym_clients.id', '=', 'gym_client_purchases.client_id')
            ->whereBetween(DB::raw('DATE(gym_membership_payments.payment_date)'), [$start_date->format('Y-m-d'), $end_date->format('Y-m-d')])
            ->where('gym_membership_payments.detail_id', '=', $this->data['user']->detail_id)->get();

        $pdf = PDF::loadView('pdf.membershipReport',compact(['data','sd','ed']));
        return $pdf->download('membershipReport.pdf');
    }

    public function downloadLockerBalanceReport($sd,$ed){
        $start_date = new Carbon($sd);
        $end_date = new Carbon($ed);

        $data = LockerPayment::select('locker_payments.id as pid', 'gym_clients.first_name','gym_clients.middle_name', 'gym_clients.last_name', 'payment_amount','payment_source', 'payment_date', 'payment_id','reservation_id','lockers.locker_num')
            ->leftJoin('locker_reservations', 'locker_reservations.id', '=', 'locker_payments.reservation_id')
            ->leftJoin('gym_clients', 'gym_clients.id', '=', 'locker_payments.client_id')
            ->leftJoin('lockers', 'lockers.id', '=', 'locker_reservations.locker_id')
            ->whereBetween(DB::raw('DATE(locker_payments.payment_date)'), [$start_date->format('Y-m-d'), $end_date->format('Y-m-d')])
            ->where('locker_payments.detail_id', '=', $this->data['user']->detail_id)->get();

        $pdf = PDF::loadView('pdf.lockerReport',compact(['data','sd','ed']));
        return $pdf->download('lockerReport.pdf');
    }

    public function downloadExcelMembershipBalanceReport($sd,$ed){
        $sDate = new Carbon($sd);
        $eDate = new Carbon($ed);
        $type = 'membership';
        $user = $this->data['user']->detail_id;
        return Excel::download(new BalanceReportExport($sDate,$eDate,$type,$user),'membershipReport.xls');
    }

    public function downloadExcelLockerBalanceReport($sd,$ed){
        $sDate = new Carbon($sd);
        $eDate = new Carbon($ed);
        $type = 'locker';
        $user = $this->data['user']->detail_id;
        return Excel::download(new BalanceReportExport($sDate,$eDate,$type,$user),'lockerReport.xls');
    }

    public function downloadProductBalanceReport($sd,$ed){
        $start_date = new Carbon($sd);
        $end_date = new Carbon($ed);

        $data = ProductPayment::select('product_payments.id as pid', 'gym_clients.first_name', 'gym_clients.middle_name','gym_clients.last_name', 'payment_amount','payment_source', 'payment_date', 'payment_id', 'product_sales.product_name','product_sale_id')
            ->leftJoin('product_sales', 'product_sales.id', '=', 'product_payments.product_sale_id')
            ->leftJoin('gym_clients', 'gym_clients.id', '=', 'product_sales.client_id')
            ->whereBetween(DB::raw('DATE(product_payments.payment_date)'), [$start_date->format('Y-m-d'), $end_date->format('Y-m-d')])
            ->where('product_payments.branch_id', '=', $this->data['user']->detail_id)->get();

        $pdf = PDF::loadView('pdf.productReport',compact(['data','sd','ed']));
        return $pdf->download('productReport.pdf');
    }

    public function downloadExcelProductBalanceReport($sd,$ed){
        $sDate = new Carbon($sd);
        $eDate = new Carbon($ed);
        $type = 'product';
        $user = $this->data['user']->detail_id;
        return Excel::download(new BalanceReportExport($sDate,$eDate,$type,$user),'productReport.xls');
    }

    public function downloadExpenseBalanceReport($sd,$ed){
        $start_date = new Carbon($sd);
        $end_date = new Carbon($ed);

        $data = GymExpense::whereBetween(DB::raw('DATE(created_at)'), [$start_date->format('Y-m-d'), $end_date->format('Y-m-d')])
            ->where('detail_id', $this->data['user']->detail_id)->get();

        $pdf = PDF::loadView('pdf.expenseReport',compact(['data','sd','ed']));
        return $pdf->download('expenseReport.pdf');
    }

    public function downloadExcelExpenseBalanceReport($sd,$ed){
        $sDate = new Carbon($sd);
        $eDate = new Carbon($ed);
        $type = 'expense';
        $user = $this->data['user']->detail_id;
        return Excel::download(new BalanceReportExport($sDate,$eDate,$type,$user),'expenseReport.xls');
    }

    public function downloadIncomeBalanceReport($sd,$ed){
        $start_date = new Carbon($sd);
        $end_date = new Carbon($ed);

        $data = Income::whereBetween(DB::raw('DATE(created_at)'), [$start_date->format('Y-m-d'), $end_date->format('Y-m-d')])
            ->where('detail_id', $this->data['user']->detail_id)->get();

        $pdf = PDF::loadView('pdf.incomeReport',compact(['data','sd','ed']));
        return $pdf->download('incomeCollectionReport.pdf');
    }

    public function downloadExcelIncomeBalanceReport($sd,$ed){
        $sDate = new Carbon($sd);
        $eDate = new Carbon($ed);
        $type = 'income';
        $user = $this->data['user']->detail_id;
        return Excel::download(new BalanceReportExport($sDate,$eDate,$type,$user),'incomeCollectionReport.xls');
    }

    public function downloadPayrollBalanceReport($sd,$ed){
        $start_date = new Carbon($sd);
        $end_date = new Carbon($ed);

        $data = Payroll::whereHas('employes', function ($query) {
                    $query->where('branch_id', $this->data['user']->detail_id);
                })
            ->whereBetween('created_at',[$start_date->format('Y-m-d'), $end_date->format('Y-m-d')])
            ->get();

        $pdf = PDF::loadView('pdf.payrollReport',compact(['data','sd','ed']));
        return $pdf->download('payrollReport.pdf');
    }

    public function downloadExcelPayrollBalanceReport($sd,$ed){
        $sDate = new Carbon($sd);
        $eDate = new Carbon($ed);
        $type = 'payroll';
        $user = $this->data['user']->detail_id;
        return Excel::download(new BalanceReportExport($sDate,$eDate,$type,$user),'payrollReport.xls');

    }
}
