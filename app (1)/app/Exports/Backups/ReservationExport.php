<?php

namespace App\Exports\Backups;

use App\Models\LockerReservation;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class ReservationExport implements FromView
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
        $subscription = LockerReservation::leftJoin('gym_clients', 'gym_clients.id', '=', 'client_id')
            ->leftJoin('lockers', 'lockers.id', '=', 'locker_id')
            ->where('locker_reservations.detail_id', '=', $this->user)
            ->get();
        return view('gym-admin.excel.reservations',['subscription'=>$subscription]);
    }
}
