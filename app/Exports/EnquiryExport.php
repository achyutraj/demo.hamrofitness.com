<?php

namespace App\Exports;

use App\Models\GymEnquiries;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class EnquiryExport implements FromView
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
        $enquiry = GymEnquiries::where('detail_id', '=', $this->user)->get();

        return view('gym-admin.excel.enquiry',['enquiry'=>$enquiry]);
    }
}
