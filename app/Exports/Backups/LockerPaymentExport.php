<?php

namespace App\Exports\Backups;

use App\Models\LockerPayment;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class LockerPaymentExport implements FromView
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
        $payments = LockerPayment::with('reservation','client')
            ->where('locker_payments.detail_id', '=',$this->user)
            ->get();
        return view('gym-admin.excel.locker-payment',['payments'=>$payments]);
    }
}
