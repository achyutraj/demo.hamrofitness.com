<?php

namespace App\Exports\Reports;

use App\Models\GymExpense;
use App\Models\Income;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ProfitLossReportExport implements FromView
{
    public function __construct($sDate = null,$eDate = null,$type= null,$user = null,$category = null,$payment_source = null) {
        $this->sDate = $sDate;
        $this->eDate = $eDate;
        $this->type = $type;
        $this->user = $user;
        $this->category = $category;
        $this->payment_source = $payment_source;


    }

    public function view() : View
    {
        $start_date = $this->sDate->format('Y-m-d');
        $end_date = $this->eDate->format('Y-m-d');
        $category = $this->category;
        $payment_source = $this->payment_source;
        if($this->type == 'expense'){
            $query = GymExpense::with('category')
                ->whereBetween('purchase_date',[$start_date,$end_date])
                ->where('detail_id', $this->user);
        }else{
            $query = Income::with('category')
                ->whereBetween('purchase_date',[$start_date,$end_date])
                ->where('detail_id', $this->user);
        }
        if($category !== 'all'){
            $query = $query->where('category_id', $category);
        }

        if(!is_null($payment_source) && $payment_source !== 'all'){
            $query = $query->where('payment_source', $payment_source);
        }
        $data = $query->latest()->get();
        return view('gym-admin.reports.expenses.excel',['data'=>$data,'type'=>$this->type]);
    }
}
