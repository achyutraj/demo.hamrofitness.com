<?php

namespace App\Exports\Reports;

use App\Models\BankLedger;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;

class BankReportExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($sDate = null,$eDate = null,$user = null) {
        $this->sDate = $sDate;
        $this->eDate = $eDate;
        $this->user = $user;
    }
    public function collection()
    {
        //
    }

    public function view() : View
    {
        $data = BankLedger::leftJoin('bank_accounts', 'bank_accounts.id', '=', 'bank_account_id')
            ->leftJoin('banks', 'banks.id', '=', 'bank_accounts.bank_id')
            ->whereBetween(DB::raw('DATE(bank_ledgers.created_at)'), [$this->sDate->format('Y-m-d'), $this->eDate->format('Y-m-d')])
            ->where('bank_ledgers.branch_id', '=', $this->user)->get();

        return view('gym-admin.reports.bank.excel',['data'=>$data]);
    }

}
