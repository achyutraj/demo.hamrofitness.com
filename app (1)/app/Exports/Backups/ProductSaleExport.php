<?php

namespace App\Exports\Backups;

use App\Models\ProductSales;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class ProductSaleExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($user = null) {
        $this->user = $user;
    }

    public function collection()
    {
        //
    }

    public function view() : View
    {
        $subscription =  ProductSales::join('gym_clients', 'gym_clients.id', '=', 'client_id')
            ->where('product_sales.branch_id', '=', $this->user)
            ->get();
        return view('gym-admin.excel.sales',['subscription'=>$subscription]);
    }
}
