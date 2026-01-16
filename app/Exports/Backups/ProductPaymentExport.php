<?php

namespace App\Exports\Backups;

use App\Models\ProductPayment;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class ProductPaymentExport implements FromView
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
        $payments = ProductPayment::with('product_sale','client')
            ->where('product_payments.branch_id', '=',$this->user)
            ->get();
        return view('gym-admin.excel.product-payment',['payments'=>$payments]);
    }
}
