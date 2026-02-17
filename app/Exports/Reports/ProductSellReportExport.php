<?php

namespace App\Exports\Reports;

use App\Models\ProductSales;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class ProductSellReportExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($sDate = null,$eDate = null,$user = null) {
        $this->sDate = $sDate;
        $this->eDate = $eDate;
        $this->user = $user;
    }

    public function collection()
    {
        //
    }

    public function view() : View
    {
        $data = ProductSales::whereBetween(DB::raw('DATE(created_at)'), [$this->sDate->format('Y-m-d'), $this->eDate->format('Y-m-d')])
            ->where('branch_id', '=', $this->user)->get();
        return view('gym-admin.reports.product_sell.excel',['data'=>$data]);
    }
}
