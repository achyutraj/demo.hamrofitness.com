<?php

namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use App\Models\GymExpense;
use App\Models\GymMembershipPayment;
use App\Models\Income;
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

class ProfitLossReportController extends GymAdminBaseController
{
    public function index(Request $request)
    {
        if(!$this->data['user']->can("profit_loss_report"))
        {
            return App::abort(401);
        }

        $this->data['title'] = 'Profit Loss Statement';
        
        if($request->get('date') != null){
            $today = Carbon::createFromFormat('m/d/Y',$request->get('date'))->format('m/d/Y');
            $yesterday = Carbon::createFromFormat('m/d/Y',$request->get('date'))->subDay()->format('m/d/Y');
        }else{
            $today = Carbon::today()->format('m/d/Y');
            $yesterday = Carbon::yesterday()->format('m/d/Y');
        }
        $newData = $this->getCashBookStatementByDate($today,$this->data['user']->detail_id);
        $oldData = $this->getCashBookStatementByDate($yesterday,$this->data['user']->detail_id);

        //previous date
        $oldMembershipAmounts = $oldData['membershipAmounts'];
        $oldProductAmounts = $oldData['productAmounts'] ;
        $oldIncomes = $oldData['incomes'];
        $oldExpenses = $oldData['expenses'];

        $old_membership = $oldMembershipAmounts->sum('payment_amount');
        $old_product = $oldProductAmounts->sum('payment_amount') ;
        $old_incomes = $oldIncomes->sum('price');
        $old_expenses = $oldExpenses->sum('price');


        //current date 
        $this->data['membershipAmounts'] = $newData['membershipAmounts'];
        $this->data['productAmounts'] = $newData['productAmounts'] ;
        $this->data['incomes'] = $newData['incomes'];
        $this->data['expenses'] = $newData['expenses'];


        $current_membership = $this->data['membershipAmounts']->sum('payment_amount');
        $current_product = $this->data['productAmounts']->sum('payment_amount') ;
        $current_incomes = $this->data['incomes']->sum('price');
        $current_expenses = $this->data['expenses']->sum('price');


        $opening = ($old_membership + $old_product + $old_incomes) - $old_expenses;;
        $current = ($current_membership + $current_product + $current_incomes) - $current_expenses; 

        $this->data['opening_balance'] = $opening;
        $this->data['closing_balance'] = $opening + $current ;
        $this->data['date'] = $today;

        return View::make('gym-admin.reports.profit_loss.index',$this->data);
    }

    public function getCashBookStatementByDate($date,$businessId){
        $data = [];
        $date = Carbon::createFromFormat('m/d/Y',$date)->format('Y-m-d');
        $data['membershipAmounts'] = GymMembershipPayment::has('purchase')
        ->selectRaw('SUM(payment_amount) as payment_amount')
        ->select('payment_source','payment_amount','payment_date')
        ->where('detail_id', $businessId)
        ->whereDate('payment_date', $date)->groupBy('payment_source')->get();

        $data['productAmounts'] =  ProductPayment::where('branch_id',$businessId)
        ->selectRaw('SUM(payment_amount) as payment_amount')
        ->select('payment_source','payment_amount','payment_date')
        ->whereDate('payment_date', $date)->groupBy('payment_source')->get();

        $data['expenses'] =  GymExpense::where('detail_id',$businessId)
        ->selectRaw('SUM(price) as price')
        ->select('price','purchase_date','item_name','category_id')
        ->whereDate('purchase_date', $date)->get();

        $data['incomes'] =  Income::where('detail_id',$businessId)
        ->selectRaw('SUM(price) as price')
        ->select('price','purchase_date','supplier_id','category_id')
        ->whereDate('purchase_date', $date)->get();
        return $data;
    }

}
