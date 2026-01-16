<?php

namespace App\Exports\Reports;

use App\Models\BusinessRenewHistory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;

class BranchRenewReportExport implements FromView
{
    public function __construct($sDate = null, $eDate = null) {
        $this->sDate = $sDate;
        $this->eDate = $eDate;
    }

    public function view() : View
    {
        $branches = BusinessRenewHistory::select(
            'business_renew_history.package_offered',
            'business_renew_history.package_amount',
            'business_renew_history.renew_start_date',
            'business_renew_history.renew_end_date',
            'business_renew_history.created_at',
            'common_details.title',
            'common_details.owner_incharge_name',
            'common_details.email',
            'common_details.phone',
            'common_details.address',
            'common_details.start_date',
            'common_details.end_date',
            'common_details.has_device'
        )
        ->join('common_details', 'common_details.id', '=', 'business_renew_history.detail_id')
        ->whereBetween(DB::raw('DATE(business_renew_history.created_at)'), [$this->sDate, $this->eDate])
        ->get();

        return view('gym-admin.reports.branchRenew.excel', ['branches' => $branches]);
    }
}
