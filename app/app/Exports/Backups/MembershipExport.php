<?php

namespace App\Exports\Backups;

use App\Models\GymMembership;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class MembershipExport implements FromView
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
        $membership = GymMembership::where('detail_id', '=', $this->user)
            ->get();
        return view('gym-admin.excel.membership',['membership'=>$membership]);
    }
}
