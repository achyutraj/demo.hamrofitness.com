<?php

namespace App\Exports;

use App\Models\GymPurchase;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
class DueExport implements FromView
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
        $dues = GymPurchase::select('gym_clients.first_name','gym_clients.middle_name', 'gym_clients.last_name','gym_client_purchases.amount_to_be_paid as amount_to_be_paid','gym_client_purchases.purchase_date',
                'gym_client_purchases.paid_amount as paid','gym_client_purchases.discount as discount', 'gym_client_purchases.next_payment_date as due_date', 'gym_memberships.title as membership'
                , 'gym_client_purchases.id','gym_client_purchases.start_date as start_date')
                ->leftJoin('gym_clients', 'gym_clients.id', '=', 'client_id')
                ->leftJoin('gym_memberships', 'gym_memberships.id', '=', 'membership_id')
                ->where('gym_client_purchases.detail_id', $this->user)
                ->where('gym_client_purchases.status','!=','pending')
                ->where('gym_client_purchases.payment_required','yes')->get();
        return view('gym-admin.excel.dues',['dues'=>$dues]);
    }
}
