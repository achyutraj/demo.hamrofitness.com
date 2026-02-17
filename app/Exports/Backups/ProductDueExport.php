<?php

namespace App\Exports\Backups;

use App\Models\ProductSales;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class ProductDueExport implements FromView
{

    public function __construct($user = null) {
        $this->user = $user;
    }

    public function collection()
    {
        //
    }

    public function view() : View
    {
        $dues =  ProductSales::leftJoin('gym_clients', 'gym_clients.id', '=', 'client_id')
                    ->where('product_sales.branch_id', $this->user)
                    ->where('product_sales.payment_required','yes')
                    ->get();
        return view('gym-admin.excel.product-dues',['dues'=>$dues]);
    }
}
