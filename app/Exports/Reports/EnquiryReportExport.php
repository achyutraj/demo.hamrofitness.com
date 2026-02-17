<?php

namespace App\Exports\Reports;

use App\Models\GymEnquiries;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class EnquiryReportExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($sDate = null,$eDate = null,$user = null,$source = null) {
        $this->sDate = $sDate;
        $this->eDate = $eDate;
        $this->user = $user;
        $this->source = $source;
    }
    public function collection()
    {
        //
    }
    public function view() : View
    {
        if($this->source == "all"){
            $enquiry = GymEnquiries::whereBetween(DB::raw('DATE(created_at)'), [$this->sDate->format('Y-m-d'), $this->eDate->format('Y-m-d')])
                ->where('detail_id', '=', $this->user)->get();
        }else{
            $enquiry = GymEnquiries::whereBetween(DB::raw('DATE(created_at)'), [$this->sDate->format('Y-m-d'), $this->eDate->format('Y-m-d')])
                ->where('come_to_know',$this->source)->where('detail_id', '=', $this->user)->get();
        }
        return view('gym-admin.reports.enquiry.excel',['enquiry'=>$enquiry]);
    }
}
