<?php

namespace App\Exports;

use App\Models\GymMembershipPayment;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class PaymentExport implements FromView
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
        $payments = GymMembershipPayment::with('purchase','client')
            ->where('gym_membership_payments.detail_id', '=',$this->user)
            ->get();
        return view('gym-admin.excel.payment',['payments'=>$payments]);
    }
}
