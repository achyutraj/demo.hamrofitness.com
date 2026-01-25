<?php

namespace App\Exports\Reports;

use App\Models\LockerReservation;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class LockerReservationExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($sDate = null,$eDate = null,$user = null) {
        $this->sDate = $sDate;
        $this->eDate = $eDate;
        $this->user = $user;
    }

    public function view() : View
    {
        $booking = LockerReservation::has('locker')
            ->whereBetween('purchase_date',[$this->sDate,$this->eDate])
            ->where('detail_id',$this->user)->get();
        return view('gym-admin.reports.reservation.excel',['booking'=>$booking]);
    }
}
