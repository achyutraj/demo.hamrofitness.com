<?php

namespace App\Exports\Backups;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class ProductExport implements FromView
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
        $products = Product::where('branch_id', '=', $this->user)
        ->get();

        return view('gym-admin.excel.product',['products'=>$products]);
    }
}
