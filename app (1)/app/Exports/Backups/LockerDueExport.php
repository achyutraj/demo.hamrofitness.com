<?php

namespace App\Exports\Backups;

use App\Models\LockerReservation;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class LockerDueExport implements FromView
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
        $dues = LockerReservation::select('gym_clients.first_name','gym_clients.middle_name', 'gym_clients.last_name','locker_reservations.amount_to_be_paid as amount_to_be_paid','locker_reservations.purchase_date',
                'locker_reservations.paid_amount as paid','locker_reservations.discount as discount', 'locker_reservations.next_payment_date as due_date', 'lockers.locker_num as locker_num'
                , 'locker_reservations.id','locker_reservations.start_date as start_date')
                ->leftJoin('gym_clients', 'gym_clients.id', '=', 'client_id')
                ->leftJoin('lockers', 'lockers.id', '=', 'locker_id')
                ->where('locker_reservations.detail_id', $this->user)
                ->where('locker_reservations.status','!=','pending')
                ->where('locker_reservations.payment_required','yes')
                ->get();
        return view('gym-admin.excel.locker-dues',['dues'=>$dues]);
    }
}
